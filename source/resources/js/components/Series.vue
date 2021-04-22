<template>
<div>
    <h1>{{ genre.name }}</h1>
</div>
</template>
<script>
export default {
    title: 'Seriály',
    props: [
        'chosenStoreChanged'
    ],
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
        chosenStoreChanged: {
            handler: function (chosenStoreChanged) {
                this.getTitles(this.genre.url);
            },
            immediate: true
        }
    },
    mounted() {
        this.getGenreByUrl();

        if (this.$route.params.genre != null)
            this.genre.url = this.$route.params.genre;
    },
    methods: {
        getGenreByUrl() {
            if (typeof this.$route.params.serialGenre === 'undefined') {
                return; // todo this.$route.params.serialGenre is undefined -> route fail with 404
            }
            this.$emit('emitHandler', {isLoading: true});
            axios.post('/api/genre_info_from_url', {'url' : this.$route.params.serialGenre}).then((res) => {
                this.genre.name = res.data.name;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        getTitles(url) {
            // TODO tady Tome potom kontroluj, jestli to vraci dobre ... to chosenStore tam byt musi, aby to filtrovalo i podle vybranyho storu
            this.$emit('emitHandler', {isLoading: true});
            var chosenStore = this.$session.get('wap-store') || 1;

            let request = {type: 'serial', genre_url: url,
                number_of_titles: this.titles.titlesToPage, page_number: this.titles.pageNumber, order: this.titles.ordering, 
                store_id: chosenStore};
            axios.post('/api/get_titles', request).then((res) => {
                this.titles.list = res.data.titles;
                this.titles.numberOfGenreTitles = res.data.titles_count;
                this.titles.gridList = [];
                this.countNumOfPages();
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
                this.$emit('emitHandler', {isLoading: false});
            });
        },
    }
}
</script>
