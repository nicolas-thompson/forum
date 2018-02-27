<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use App\Http\Forms\CreatePostForm;
use Illuminate\Support\Facades\Gate;

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
     * @param CreatePostForm $form
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        // if(Gate::denies('create', new Reply)) {
            
        //     return response(
        //       'You are posting too frequently. Please take a break. :)', 422
        //     );    
        // }

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

        try {

            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(['body' => request('body')]);

        } catch(\Exception $e) {

            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );            
        }
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
