<?php

namespace App\Observers;

use App\Models\Map;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\File;
use Str;

class MapObserver implements ShouldHandleEventsAfterCommit
{
    public function updated(Map $map): void
    {
        $dirName = Str::lower($map->map_id);
        $publicPath = storage_path(Map::PUBLIC_ROOT."/$dirName");
        $privatePath = storage_path(Map::PRIVATE_ROOT."/$dirName");

        if ($map->admin_only && File::exists($publicPath)) {
            File::moveDirectory($publicPath, $privatePath);
        } elseif (! $map->admin_only && File::exists($privatePath)) {
            File::moveDirectory($privatePath, $publicPath);
        }
    }

    public function deleting(Map $map): void
    {
        $dirName = Str::lower($map->map_id);
        $publicPath = storage_path(Map::PUBLIC_ROOT."/$dirName");
        $privatePath = storage_path(Map::PRIVATE_ROOT."/$dirName");

        if (File::exists($publicPath)) {
            File::deleteDirectory($publicPath);
        }

        if (File::exists($privatePath)) {
            File::deleteDirectory($privatePath);
        }
    }
}
