<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWhereMentioned;

class RepliesController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth')->except(['index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Store.
     *
     * @param integer $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if($thread->locked) {
            return response('Thread is locked.', 422);
        }

        return $thread->addReply([
            'user_id'   => auth()->id(),
            'body'      => request('body')
        ])->load('owner');
    }

    /**
     * Update and existing reply
     * 
     * @param Reply $reply
     * @param Spam $spam
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required|spamfree']);

        $reply->update(['body' => request('body')]);
    }
    
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Your reply has been deleted.']);
        }

        return back();
    }
}
