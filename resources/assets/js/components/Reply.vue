<template>

    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name"
                        v-text="reply.owner.name">
                    </a> said <span  v-text="ago"></span>...
                </h5>
                    <div v-if="signedIn">
                        <favourite :reply="reply"></favourite>
                    </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-link">Update</button>
                    <button class="btn btn-xs btn-primary" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <div class="panel-footer level" v-if="authorize('owns', reply) || authorize('owns', thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
            </div>
            
            <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best reply?</button>
        </div>
    </div>

</template>


<script>

    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {

        props: ['reply'],

        components: { Favourite },

        data() {

            return {
                editing: false,
                id: this.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        computed: {

            ago() {
                return moment.utc(this.reply.created_at).fromNow();
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id)
            });
        },

        methods: {
        
            update() {
        
                axios.patch(
                    '/replies/' + this.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;
                flash('Updated!');
            },
        
            destroy() {
                axios.delete('/replies/' + this.id);
                this.$emit('deleted', this.id);
                flash('Reply was deleted.');
            },

            markBestReply() {

                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }

</script>