<?php

namespace App\Exceptions;

use Sentry\Tracing\SamplingContext;

class Sentry
{
    public static function tracesSampler(SamplingContext $context): float
    {
        if ($context->getParentSampled()) {
            // If the parent transaction (for example a JavaScript front-end)
            // is sampled, also sample the current transaction
            return 1.0;
        }

        // Default sample rate for all other transactions (replaces `traces_sample_rate`)
        return config('sentry.traces_sample_rate', null) === null ? 0 : config('sentry.traces_sample_rate');
    }
}
