<?php

namespace App\Console\Commands;

use League\Csv\Reader;

trait HasGoonTeamHandling
{
    public $teamSheet = null;

    public function getAdminTeamSheet()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://docs.google.com/spreadsheets/d/1JBUyieDGCyCCBOhn5OG6gcgb3IgvI3PTVvjsqE3PNNE/export?exportFormat=csv');
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.example.org/yay.htm');
        curl_setopt($ch, CURLOPT_USERAGENT, 'MozillaXYZ/1.0');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:'.curl_error($ch);
        }
        curl_close($ch);

        $reader = Reader::createFromString($result);
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        $this->teamSheet = $records;
    }

    public function getTeamAdminsByRank()
    {
        $admins = [];
        foreach ($this->teamSheet as $record) {
            $admins[$record['Byond Ckey']] = [
                'alias' => $record['Alias'],
                'rank' => $record['Rank'],
                'discord_id' => $record['Discord ID'],
            ];
        }

        return $admins;
    }

    public function getTeamAdminsByDiscordName()
    {
        $admins = [];
        foreach ($this->teamSheet as $record) {
            $discordCkey = preg_replace('/#[\d]+/i', '', $record['Discord Username']);
            $admins[ckey($discordCkey)] = $record['Byond Ckey'];
        }

        return $admins;
    }
}
