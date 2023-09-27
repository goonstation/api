<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexQueryRequest;
use App\Http\Resources\RedirectResource;
use App\Models\Redirect;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class RedirectsController extends Controller
{
    use IndexableQuery;

    /**
     * List
     *
     * List filtered and paginated redirects
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<RedirectResource>>
     */
    public function index(IndexQueryRequest $request)
    {
        return RedirectResource::collection(
            $this->indexQuery(Redirect::class)
        );
    }

    /**
     * Add
     *
     * Add a new redirect
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            /**
             * The path to redirect from. Appends to the root redirect URL, for example as `https://goonhub.com/r/foo`
             * @example foo
             */
            'from' => 'required|string',
            /**
             * The URL to redirect to
             * @example https://example.com
             */
            'to' => 'required|url',
        ]);

        $redirect = new Redirect();
        $redirect->from = $data['from'];
        $redirect->to = $data['to'];
        $redirect->save();

        return new RedirectResource($redirect);
    }

    /**
     * Edit
     *
     * Edit an existing redirect
     */
    public function update(Request $request, Redirect $redirect)
    {
        $data = $request->validate([
            /**
             * The path to redirect from. Appends to the root redirect URL, for example as `https://goonhub.com/r/foo`
             * @example foo
             */
            'from' => 'required|string',
            /**
             * The URL to redirect to
             * @example https://example.com
             */
            'to' => 'required|url',
        ]);

        $redirect->from = $data['from'];
        $redirect->to = $data['to'];
        $redirect->save();

        return new RedirectResource($redirect);
    }

    /**
     * Delete
     *
     * Delete an existing redirect
     */
    public function destroy(Redirect $redirect)
    {
        $redirect->delete();

        return ['message' => 'Redirect removed'];
    }
}
