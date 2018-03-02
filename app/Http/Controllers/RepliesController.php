<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;
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
     * @param CreatePostRequest $form
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        $reply = $thread->addReply([
            'user_id'   => auth()->id(),
            'body'      => request('body')
        ]);
        
        // Inspect the body of the reply for usernanme mentions.
        preg_match_all('/\@([^\s\.]+)/', $reply->body, $usernames);

        foreach($usernames[1] as $name) {
            $user = User::whereName($name)->first();
            if($name){
                $user->notify(new YouWhereMentioned());
            }
        }

        // And then for each mentioned user, notify them.
        return $reply->load('owner');
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
