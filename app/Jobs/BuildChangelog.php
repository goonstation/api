<?php

namespace App\Jobs;

use App\Models\Changelog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class BuildChangelog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $entries = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function getChangelogFromRepo()
    {
        if (! app()->environment('local')) {
            $res = Http::withHeaders([
                'Accept: application/vnd.github+json',
                'Authorization: Bearer '.config('github.user_token'),
                'X-Github-Api-Version: 2022-11-28',
                'User-Agent: SomethingOrOther',
            ])
            ->get('https://api.github.com/repos/goonstation/goonstation/contents/strings/changelog.txt');

            return base64_decode($res['content']);
        } else {
            return Storage::get('changelog.txt');
        }
    }

    private function flushEntry($date, $entry)
    {
        if (! count($entry)) {
            return false;
        }
        $this->entries[$date][] = $entry;

        return true;
    }

    private function parseDate($dumbDate)
    {
        $dateParts = explode(' ', $dumbDate);
        $dayOfWeek = $dateParts[0]; // yeah let's just...let the view layer handle that
        $month = $dateParts[1];
        $day = $dateParts[2];
        $year = $dateParts[3];

        return date('Y-m-d H:i:s', strtotime("$month $day $year"));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $changelog = $this->getChangelogFromRepo();
        $lines = preg_split('/\r\n|\r|\n/', $changelog);

        $entry = [];
        $date = null;
        foreach ($lines as $line) {
            if (! $line) {
                continue;
            }
            if (str_starts_with($line, '#')) {
                continue;
            }

            if (str_starts_with($line, '(t)')) {
                if ($this->flushEntry($date, $entry)) {
                    $entry = [];
                }
                $date = $this->parseDate(trim(explode('(t)', $line)[1]));
            } elseif (str_starts_with($line, '(u)')) {
                if ($this->flushEntry($date, $entry)) {
                    $entry = [];
                }
                $entry['user'] = trim(explode('(u)', $line)[1]);
            } elseif (str_starts_with($line, '(p)')) {
                $entry['pr'] = trim(explode('(p)', $line)[1]);
            } elseif (str_starts_with($line, '(e)')) {
                $emojiLabels = trim(explode('(e)', $line)[1]);
                $parts = explode('|', $emojiLabels);
                $emojis = $parts[0];
                $labels = null;
                if (count($parts) > 1) {
                    $labels = $parts[1];
                }
                $entry['emojiLabels'] = [
                    'emojis' => $emojis,
                    'labels' => $labels,
                ];
            } elseif (str_starts_with($line, '(*)')) {
                $entry['major'][] = trim(explode('(*)', $line)[1]);
            } elseif (str_starts_with($line, '(+)')) {
                $entry['minor'][] = trim(explode('(+)', $line)[1]);
            }
        }
        $this->flushEntry($date, $entry);

        $entries = json_encode($this->entries);
        $item = Changelog::first();
        if ($item) {
            $item->update(['entries' => $entries]);
        } else {
            $item = new Changelog();
            $item->entries = $entries;
            $item->save();
        }
    }
}
