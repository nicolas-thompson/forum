<?php

namespace App;

use App\Filters\ThreadFilters;

trait RecordsActivity
{
    protected static function bootRecordActivity()
    {
        static::created(function($thread) {
            $thread->recordActivity('created');
        });
    }

    protected function recordActivity($event)
    {
        Activity::create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
    }

    protected function getActivityType($event)
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}