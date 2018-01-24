<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner->name) }}">
                        {{ $reply->owner->name }}
                    </a> said {{$reply->created_at->diffForHumans()}}...
                </h5>
                <div>
                    <form method="POST" action="/replies/{{ $reply->id }}/favourites">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : ''}}>
                            {{ $reply->favourites_count }} {{ str_plural('Favorite', $reply->favourites_count) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="editing = false">Cancel</button>
                <button class="btn btn-xs btn-link" @click="update">Update</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can ('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>
