<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Build a meta data object with defaults
     *
     * @param Request $request
     * @return array
     */
    private function getMetaData(Request $request): array
    {
        return [
            'title' => function () use ($request) {
                $title = $request->session()->get('meta_title');
                if ($title) $title .= ' - ' . config('app.name');
                else $title = config('app.name');
                return $title;
            },
            'description' => function () use ($request) {
                $description = $request->session()->get('meta_description');
                if (!$description) $description = 'Dive into the world of Space Station 13\'s Goonstation branch with Goonhub. Get access to comprehensive statistics and stay up-to-date with the latest developments.';
                return $description;
            },
            'image' => function () use ($request) {
                $image = $request->session()->get('meta_image');
                if (!$image) $image = asset('/storage/img/og.png');
                return $image;
            },
            'url' => url()->current(),
        ];
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'breadcrumbs' => $request->route()->breadcrumbs()->jsonSerialize(),
            'env' => [
                'GAME_BRIDGE_URL' => config('goonhub.game_bridge_url'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
            'meta' => $this->getMetaData($request),
        ]);
    }
}
