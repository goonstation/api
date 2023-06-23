<?php

namespace App\Helpers;

use App\Models\Changelog;

class ChangelogHelper
{
    public static function get($amount = 0)
    {
        $changelog = Changelog::first();
        if (! $changelog) {
            return [];
        }
        $changelogEntries = array_reverse(json_decode($changelog->entries, true));
        if ($amount) {
            $changelogEntries = array_slice($changelogEntries, 0, $amount, true);
        }

        return $changelogEntries;
    }
}
