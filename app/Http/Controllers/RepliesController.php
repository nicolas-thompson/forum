<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;
use Illuminate\Http\Request;

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
     * @param [type] $channelId
     * @param Thread $thread
     * @return void
     */
    public function store($channelId, Thread $thread)
    {
        try {
            
            $this->validateReply();

            $reply = $thread->addReply([
                'user_id'   => auth()->id(),
                'body'      => request('body')
            ]);

        } catch(\Exception $e) {

            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    
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

        $this->validateReply();

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

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
