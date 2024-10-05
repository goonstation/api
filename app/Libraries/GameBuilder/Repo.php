<?php

namespace App\Libraries\GameBuilder;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class Repo
{
    private $remoteUrl = 'https://robuddybot@github.com/goonstation/goonstation';

    public $repoDir;
    private $repoSecretDir;

    public function __construct(string $serverDir)
    {
        $this->repoDir = "$serverDir/repo";
        $this->repoSecretDir = "{$this->repoDir}/+secret";
    }

    private function run(array $cmd, string $cwd = '')
    {
        if (!$cwd) $cwd = $this->repoDir;
        $process = new Process($cmd, $cwd);
        $process->mustRun();
        return trim($process->getOutput(), " \n\r\t\v\0\"");
    }

    public function init(string $branch = 'master')
    {
        if (File::exists($this->repoDir)) {
            return;
        }

        File::makeDirectory($this->repoDir, recursive: true);

        $process = new Process([
            'git', 'clone', '--recurse-submodules',
            '-b', $branch,
            $this->remoteUrl, $this->repoDir
        ], timeout: 300);
        $process->mustRun();

        $process = new Process(['git', 'config', 'user.name', 'robuddybot'], $this->repoDir);
        $process->mustRun();
        $process = new Process(['git', 'config', 'user.email', 'robuddybot@goonhub.com'], $this->repoDir);
        $process->mustRun();
    }

    public function fetch()
    {
        return $this->run(['git', 'fetch', '--recurse-submodules']);
    }

    public function update()
    {
        return $this->run(['git', 'reset', '--recurse-submodules', '--hard', '@{u}']);
    }

    public function getBranch(bool $secret = false): string
    {
        return $this->run(
            ['git', 'rev-parse', '--abbrev-ref', 'HEAD'],
            $secret ? $this->repoSecretDir : $this->repoDir
        );
    }

    public function getHash(string $branch = null): string
    {
        return $this->run(['git', 'rev-parse', $branch ? $branch : '@']);
    }

    public function getAuthor(string $commit)
    {
        return $this->run(['git', 'log', '--format="%an"', '-n', '1', $commit]);
    }

    public function checkout(string $branch)
    {
        return $this->run(['git', 'checkout', '--recurse-submodules', $branch]);
    }

    public function createAndCheckoutBranch(string $branch)
    {
        return $this->run(['git', 'checkout', '--recurse-submodules', '-b', $branch]);
    }

    public function fetchPr(int $prId)
    {
        return $this->run(['git', 'fetch', 'origin', "pull/{$prId}/head:pr-{$prId}"]);
    }

    public function resetBranchToCommit(string $commit)
    {
        return $this->run(['git', 'reset', '--recurse-submodules', '--hard', $commit]);
    }

    public function merge(string $branch)
    {
        return $this->run(['git', 'merge', '--no-commit', '--no-ff', $branch]);
    }

    public function getConflictedFiles()
    {
        return $this->run(['git', 'diff', '--name-only', '--diff-filter=U', '--relative']);
    }

    public function abortMerge()
    {
        return $this->run(['git', 'merge', '--abort']);
    }

    public function commit(string $message)
    {
        return $this->run(['git', 'commit', '-m', $message]);
    }

    public function getMessage(string $commit)
    {
        return $this->run(['git', 'log', '--format="%B"', '-n', 1, $commit]);
    }
}
