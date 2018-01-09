@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#"> {{ $thread->creator->name }}</a> posted:
                    {{ $thread->title }}
                </div>
                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
            @foreach ($thread->replies as $reply)
                @include ('threads.reply')
            @endforeach
            @if(auth()->check())
            <form method="POST" action="{{ $thread->path() . '/replies' }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body" id="" class="form-control" placeholder="Have something to say?" rows="5"></textarea>
                </div>
                <button type="submit" class='btn btn-default'>Post</button>
            </form>
            @else
                <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
            @endif
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                <p>This thread was created {{$thread->created_at->diffForHumans()}} by <a href="#">{{$thread->creator->name}}</a> and currently has {{$thread->replies()->count()}} comments.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
