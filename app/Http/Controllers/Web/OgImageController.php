<?php

namespace App\Http\Controllers\Web;

use App\Facades\OpenGraphImage;
use App\Http\Controllers\Controller;
use App\Models\Events\EventAntag;
use App\Models\Events\EventDeath;
use App\Models\Events\EventFine;
use App\Models\Events\EventTicket;
use App\Models\GameRound;
use App\Models\Map;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class OgImageController extends Controller
{
    private $validTypes = [
        'round' => GameRound::class,
        'ticket' => EventTicket::class,
        'fine' => EventFine::class,
        'death' => EventDeath::class,
        'antag' => EventAntag::class,
        'player' => Player::class,
        'map' => Map::class,
    ];

    private function defaultImage()
    {
        return response(Storage::get('/public/img/og.png'))
            ->withHeaders([
                'Content-Type' => 'image/png',
            ]);
    }

    public function index(Request $request, string $type, int $id)
    {
        if (! View::exists("open-graph.$type")) {
            return $this->defaultImage();
        }

        $imageKey = null;
        $imageData = null;
        $image = null;
        if (array_key_exists($type, $this->validTypes)) {
            $imageKey = $id;
            $image = OpenGraphImage::getFile($type, $id);
            if (! $image) {
                $imageData = $this->validTypes[$type]::getOpenGraphData($id);
            }
        } else {
            return $this->defaultImage();
        }

        $image = $image ? $image : OpenGraphImage::getImage($type, $imageKey, $imageData);

        if (!$image) {
            return $this->defaultImage();
        }

        return response($image['file'])
            ->withHeaders([
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public'.
                    ', max-age='.OpenGraphImage::getCacheLength().
                    ', must-revalidate',
                'Age' => $image['age'],
                'ETag' => $image['etag'],
            ]);
    }

    public function preview(Request $request, string $type, int $id)
    {
        if (! array_key_exists($type, $this->validTypes)) {
            return abort(404);
        }

        return view(
            "open-graph.$type",
            [
                'data' => $this->validTypes[$type]::getOpenGraphData($id),
            ]
        );
    }
}
