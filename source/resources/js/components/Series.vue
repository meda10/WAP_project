<template>
<div>
    <h1>{{ genre.name }}</h1>
</div>
</template>
<script>
export default {
    title: 'Seriály',
    data() {
        return {
            genre: {
                name: '',
                url: this.$route.params.serialGenre
            }
        }
    },
    watch: {
        $route (to, from) {
            this.getGenreByUrl();
        },
    },
    mounted() {
        this.getGenreByUrl();

        if (this.$route.params.genre != null)
            this.genre.url = this.$route.params.genre;
    },
    methods: {
        getGenreByUrl() {
            this.$emit('emitHandler', {isLoading: true});

            axios.post('/api/genre_info_from_url', {'url' : this.$route.params.serialGenre}).then((res) => {
                this.genre.name = res.data.name;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler', {isLoading: false});
            });
        }
    }
}
</script>
