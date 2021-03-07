<template>
    <div>
        <h1>Title</h1>
    </div>
</template>

<script>
    export default {
        title: '',
        data() {
            return {
                titleName: '',
                type: ''
            }
        },
        watch: {
            titleName: {
                handler: function (user) {
                    this.$forceUpdate();
                },
                immediate: true
            },
            type: {
                handler: function (isLoading) {
                    this.$forceUpdate();
                },
                immediate: true
            }
        },
        mounted() {
            if (this.$route.params.titleName != null)
                this.titleName = this.$route.params.titleName;
            if (this.$route.name != null)
                this.type = this.$route.name;

            this.getTitleInfo();
        },
        methods: {
            getTitleInfo() {
                axios.post('/api/get_title', {'url' : this.$route.params.movieGenre}).then((res) => {
                    this.genre.name = res.data.name;
                    this.$emit('emitIsLoading', false);
                }).catch((error) => {
                    // TODO handle this error
                    console.log(error);
                    this.$emit('emitIsLoading', false);
                });
            }
        }
    }
</script>
