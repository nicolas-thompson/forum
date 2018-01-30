<template>
    <div>
        <!--<@if(auth()->check())
            form method="POST" action="{{ $thread->path() . '/replies' }}">-->
                <div class="form-group">
                    <textarea name="body" 
                                id="" 
                                class="form-control" 
                                placeholder="Have something to say?" 
                                rows="5"
                                required 
                                v-model="body"></textarea>
                </div>
                <button type="submit" 
                        class='btn btn-default'
                        @click="addReply">Post</button>
            <!--</form>
            @else-->
                <!--<p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>-->
        <!--@endif-->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: '',
                endpoint: '/threads/cupiditate/1/replies'
            };
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, {body: this.body})
                .then(({data}) => {
                    this.body = '';
                    flash('Your reply has been posted.');
                    this.$emit('created', data);
                });
            }
        }
    }
</script>