<?php

namespace App\Http\Middleware;

use Glhd\Gretel\Routing\RequestBreadcrumbs;
use Glhd\Gretel\View\Breadcrumb;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\SchemaOrg\Schema;

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
     */
    private function getMetaData(Request $request): array
    {
        return [
            'title' => function () use ($request) {
                $title = $request->session()->get('meta_title');
                if ($title) {
                    $title .= ' - '.config('app.name');
                } else {
                    $title = config('app.name');
                }

                return $title;
            },
            'description' => function () use ($request) {
                return $request->session()->get('meta_description');
            },
            'image' => function () use ($request) {
                $image = $request->session()->get('meta_image');
                // if (! $image) {
                //     $image = asset('/storage/img/og.png');
                // }

                return $image;
            },
            'url' => url()->current(),
        ];
    }

    private function getBreadcrumbSchema(RequestBreadcrumbs $breadcrumbs): string
    {
        if ($breadcrumbs->count() <= 1) {
            return '';
        }

        $schema = Schema::breadcrumbList();
        $items = [];
        /** @var Breadcrumb $breadcrumb */
        foreach ($breadcrumbs->all() as $key => $breadcrumb) {
            if ($breadcrumb->is_current_page) {
                continue;
            }
            $items[] = Schema::listItem()
                ->name($breadcrumb->title)
                ->item(Schema::thing()->url($breadcrumb->url))
                ->position($key + 1);
        }

        $schema->itemListElement($items);

        return $schema->toScript();
    }

    private function getSchema(Request $request, RequestBreadcrumbs $breadcrumbs): string
    {
        $ldJson = $request->session()->get('schema', '');
        $ldJson .= $this->getBreadcrumbSchema($breadcrumbs);

        return $ldJson;
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        $breadcrumbs = $request->route()->breadcrumbs();

        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'breadcrumbs' => fn () => $breadcrumbs->jsonSerialize(),
            'env' => [],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
            'meta' => fn () => $this->getMetaData($request),
            'schema' => fn () => $this->getSchema($request, $breadcrumbs),
        ]);
    }
}
