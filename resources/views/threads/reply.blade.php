<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="#">
                    {{ $reply->owner->name }}
                </a> said {{$reply->created_at->diffForHumans()}}...
            </h5>
            <div>
                <form action="">
                    <button type="submit" class="btn btn-default">Favourite</button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>