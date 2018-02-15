<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this, $reply));
    }
}
