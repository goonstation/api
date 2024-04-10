<?php

namespace App\Jobs;

use App\Helpers\ModelHelper;
use App\Models\LatestEventSearchIndex;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Scout\Searchable;

class IndexSearchableEvents implements ShouldQueue
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
        $eventModels = ModelHelper::getModels('App\Models\Events');
        foreach ($eventModels as $modelName) {
            $isSearchable = in_array(Searchable::class, class_uses_recursive($modelName));
            if ($isSearchable) {
                $model = new $modelName;
                if (!$model->count()) continue;

                $latestIndex = 0;
                $latestIndexRow = LatestEventSearchIndex::where('event_type', $modelName)->first();
                if ($latestIndexRow) $latestIndex = $latestIndexRow->latest_indexed;

                $maxEventId = $model->latest()->first()->id;
                $model
                    ->where('id', '>', $latestIndex)
                    ->where('id', '<=', $maxEventId)
                    ->searchable();

                LatestEventSearchIndex::updateOrCreate(
                    ['event_type' => $modelName],
                    ['latest_indexed' => $maxEventId]
                );
            }
        }
    }
}
