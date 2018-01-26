<template>
    <button type="button" :classs="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                count: this.reply.favouritesCount,
                active: this.reply.isFavourited
            }
        },
        computed: {
            classes() {
                return [
                    'btn',  
                    this.active ? 'btn-primary' : 'btn-default'
                ];
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favourites';
            }
        },
        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },
            destroy() {
                    axios.delete(this.endpoint);
                    this.active = false;
                    this.count--;
            },
            create() {
                     axios.post(this.endpoint);
                    this.active = true;
                    this.count++;               
            }
        }
    }
</script>