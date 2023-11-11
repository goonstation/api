<?php

namespace App\Jobs;

use App\Models\Medal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

class ImportMedalsFromByond implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::createChromeClient('/usr/local/bin/chromedriver', [
            '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36',
            '--window-size=1200,1100',
            '--disable-gpu',
            '--disable-web-security',
            '--disable-blink-features=AutomationControlled',
            '--headless',
            '--disable-dev-shm-usage',
            '--no-sandbox',
        ]);

        $client->request('GET', 'https://secure.byond.com/login.cgi');
        $client->submitForm('Login', [
            'key' => config('goonhub.byond_user'),
            'password' => config('goonhub.byond_pass'),
        ]);

        $client->request('GET', 'https://secure.byond.com/members/?command=edit_hub_entry&hub=77041#tab=medals');
        $crawler = $client->waitFor('#all_medals');

        $medals = [];
        $elements = $crawler->filter('#all_medals > div');
        $elements->each(function (Crawler $element) use (&$medals) {
            $medalId = $element->attr('id');
            if ($medalId === 'new_medal') {
                return true;
            }
            $medalId = explode('_', $medalId);
            $medalId = $medalId[1];

            $title = $element->filter("#medal_name_element_$medalId")->attr('value');
            $description = $element->filter("#medal_desc_element_$medalId")->attr('value');
            $description = $description ? $description : null;
            $image = $element->filter("img[alt=\"$title\"]");
            $image = $image->count() ? $image->attr('src') : null;
            $hidden = $element->filter("#medal_spoiler_$medalId")->attr('checked');
            $hidden = $hidden === 'true';

            $medals[] = [
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'hidden' => $hidden,

            ];
        });

        foreach ($medals as $medalData) {
            // if (Medal::where('title', $medalData['title'])->exists()) {
            //     continue;
            // }

            // $medal = new Medal();
            // $medal->title = $medalData['title'];
            // $medal->description = $medalData['description'];
            // $medal->hidden = $medalData['hidden'];
            // $medal->save();

            $medal = Medal::updateOrCreate(
                    ['title' => $medalData['title']],
                    [
                        'description' => $medalData['description'],
                        'hidden' => $medalData['hidden'],
                    ]
                );

            if ($medalData['image']) {
                $imageName = $medal->id;
                $imagePath = "medals/$imageName.png";
                $response = Http::get($medalData['image']);
                Storage::disk('public')->put($imagePath, $response->body());
            }
        }
    }
}
