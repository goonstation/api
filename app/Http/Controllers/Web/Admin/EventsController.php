<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\ModelHelper;
use App\Http\Controllers\Controller;
use App\Traits\IndexableQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventsController extends Controller
{
    use IndexableQuery;

    public function index(Request $request)
    {
        $eventTypes = [];
        $filteringEvent = null;
        $eventModels = ModelHelper::getModels('App\Models\Events');
        foreach ($eventModels as $modelName) {
            $model = new $modelName;
            $tableName = $model->getTable();
            $eventTypes[] = $tableName;
            if (!$request->input('type') && !$filteringEvent) {
                $filteringEvent = $model;
            } else if ($tableName === $request->input('type')) {
                $filteringEvent = $model;
            }
        }

        $events = [];
        if ($filteringEvent) {
            $events = $this->indexQuery($filteringEvent, perPage: 30);
        }

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Events/Index', [
                'eventTypes' => $eventTypes,
                'events' => $events,
            ]);
        } else {
            return $events;
        }
    }
}
