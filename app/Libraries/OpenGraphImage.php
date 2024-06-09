<?php

namespace App\Libraries;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class OpenGraphImage
{
    private $chromeUserData = '/tmp/chrome-user-data';

    private $storagePath = '';

    private $pathSuffix = 'img/og';

    public function __construct()
    {
        if (! File::exists($this->chromeUserData)) {
            File::makeDirectory($this->chromeUserData, 0755, true);
        }

        $this->storagePath = storage_path('app/public/'.$this->pathSuffix);
        if (! File::exists($this->storagePath)) {
            File::makeDirectory($this->storagePath, 0755, true);
        }
    }

    public function getCacheLength(): int
    {
        return 1 * 24 * 60 * 60; // 1 day
    }

    protected function makeImage(string $type, $data, string $path): void
    {
        Browsershot::html(
            view("open-graph.$type", ['data' => $data])->render()
        )
            ->noSandbox()
            ->userDataDir($this->chromeUserData)
            ->windowSize(1200, 630)
            // ->deviceScaleFactor(2)
            ->setContentUrl(url('/'))
            ->save($path);
    }

    protected function getFullPath(string $type, string|int $key): string
    {
        return $this->storagePath.'/'.$type.'/'.$key.'.png';
    }

    protected function getFileAge(string $path): int
    {
        return time() - File::lastModified($path);
    }

    protected function getEtag(string $path): string
    {
        return md5(File::lastModified($path));
    }

    protected function isFileExpired(string $path): bool
    {
        return $this->getFileAge($path) > $this->getCacheLength();
    }

    public function getFile(string $type, string|int $key): array|bool
    {
        $fullPath = $this->getFullPath($type, $key);
        if (! File::exists($fullPath)) return false;
        if ($this->isFileExpired($fullPath)) return false;
        return [
            'age' => $this->getFileAge($fullPath),
            'etag' => $this->getEtag($fullPath),
            'file' => file_get_contents($fullPath)
        ];
    }

    public function getImage(string $type, string|int $key, $data): array
    {
        $path = $this->storagePath.'/'.$type;
        $fullPath = $this->getFullPath($type, $key);

        if (! File::exists($fullPath) || $this->isFileExpired($fullPath)) {
            if (! File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            $this->makeImage($type, $data, $fullPath);
        }

        return [
            'age' => $this->getFileAge($fullPath),
            'etag' => $this->getEtag($fullPath),
            'file' => file_get_contents($fullPath)
        ];
    }
}
