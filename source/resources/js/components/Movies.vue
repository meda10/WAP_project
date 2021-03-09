<template>
<div>
    <h1>{{ genre.name }}</h1>
    <div class="row">
        <div class="col-10">
            <div class="form-group">
                <label for="genresSelect">Výběr žánru:</label>
                <select class="form-control" id="genresSelect" @change="changeGenre">
                    <option v-for="(ge, key) in moviesGenres"
                            :selected="(genre.name === ge.name)"
                            :key="key" v-bind:value="ge.url">
                        {{ ge.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="col-2 align-self-end mb-3">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary mr-2"
                        @click="toggleView('list')"
                        v-bind:class="renderView.listView ? 'active' : ''">
                    <i class="fas fa-list fa-lg"></i>
                </button>
                <button type="button" class="btn btn-primary"
                        @click="toggleView('grid')"
                        v-bind:class="renderView.gridView ? 'active' : ''">
                    <i class="fas fa-th fa-lg"></i>
                </button>
            </div>
        </div>
    </div>
    <!--  GRID VIEW  -->
    <div v-if="renderView.gridView && !renderView.listView">
        <div class="row">
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Tady by mohl být název filmu velky</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Tady by mohl být název filmu velky</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  END GRID VIEW  -->
    <!--  LIST VIEW  -->
    <table class="table table-hover" v-if="!renderView.gridView && renderView.listView" id="titlesTable">
        <thead>
        <tr>
            <th scope="col">No.</th>
            <th scope="col">Název</th>
            <th scope="col">Rok vydání</th>
            <th scope="col">Popisek</th>
        </tr>
        </thead>
        <tbody>
        <router-link  v-for="(title, key) in titles.list" class="titleLink"
            v-bind:class="!(key%2) ? 'table-dark' : ''" :key="key" tag="tr" :to="'/film/' + title.url">
                <td>{{ key+1 }}</td>
                <th>{{ title.title_name }}</th>
                <td>{{ title.year }}</td>
                <td>{{ title.description.substring(0, 300)+"..." }}</td>
        </router-link >
        </tbody>
    </table>
    <!--  ENDLIST VIEW  -->

    <div class="row justify-content-center mt-4">
        <ul class="pagination" v-if="titles.pageNumbers < 8">
            <li class="page-item"
                @click="changePageNum(titles.pageNumber-1)"
                v-bind:class="(titles.pageNumber <= 1) ? 'disabled' : ''">
                <a class="page-link" href="#">&laquo;</a>
            </li>
            <!--      When number of pages <= 7      -->
            <li class="page-item"
                v-for="num in titles.pageNumbers"
                @click="changePageNum(num)"
                :key="num"
                v-bind:class="(titles.pageNumber === num) ? 'active' : ''">
                <a class="page-link" href="#">{{ num }}</a>
            </li>
            <li class="page-item"
                @click="changePageNum(titles.pageNumber+1)"
                v-bind:class="(titles.pageNumber >= titles.pageNumbers) ? 'disabled' : ''">
                <a class="page-link" href="#">&raquo;</a>
            </li>
        </ul>
        <ul class="pagination" v-if="titles.pageNumbers >= 8">
            <li class="page-item"
                @click="changePageNum(titles.pageNumber-1)"
                v-bind:class="(titles.pageNumber <= 1) ? 'disabled' : ''">
                <a class="page-link" href="#">&laquo;</a>
            </li>

            <!--      When number of pages       -->
            <li class="page-item"
                v-for="num in 2"
                @click="changePageNum(num)"
                v-bind:key="num"
                v-bind:class="(titles.pageNumber === num) ? 'active' : ''">
                <a class="page-link" href="#">{{ num }}</a>
            </li>

            <li class="page-item disabled"
                v-if="titles.pageNumber > 4">
                <a class="page-link">...</a>
            </li>
            <li class="page-item"
                v-if="(titles.pageNumber <= 4)"
                v-for="num in 3"
                :key="num+2"
                @click="changePageNum(num+2)"
                v-bind:class="(titles.pageNumber === (num+2)) ? 'active' : ''">
                <a class="page-link" href="#">{{ num+2 }}</a>
            </li>

            <li class="page-item"
                v-if="(titles.pageNumber > 4) && (titles.pageNumber < (titles.pageNumbers-3))"
                v-for="num in 3"
                :key="titles.pageNumber-2+num"
                @click="changePageNum(titles.pageNumber-2+num)"
                v-bind:class="(titles.pageNumber === (titles.pageNumber-2+num)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumber-2+num) }}</a>
            </li>

            <li class="page-item disabled"
                v-if="titles.pageNumber < (titles.pageNumbers-3)">
                <a class="page-link">...</a>
            </li>
            <li class="page-item"
                v-if="(titles.pageNumber >= (titles.pageNumbers-3))"
                v-for="num in 3"
                :key="titles.pageNumbers-5+num"
                @click="changePageNum(titles.pageNumbers-5+num)"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers-5+num)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers-5+num) }}</a>
            </li>

            <li class="page-item"
                @click="changePageNum(titles.pageNumbers-1)"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers-1)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers-1) }}</a>
            </li>
            <li class="page-item"
                @click="changePageNum(titles.pageNumbers)"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers) }}</a>
            </li>

            <li class="page-item"
                @click="changePageNum(titles.pageNumber + 1)"
                v-bind:class="(titles.pageNumber >= titles.pageNumbers) ? 'disabled' : ''">
                <a class="page-link" href="#">&raquo;</a>
            </li>
        </ul>
    </div>
</div>
</template>
<script>
export default {
    title: 'Filmy',
    data() {
        return {
            genre: {
                name: '',
                url: this.$route.params.movieGenre
            },
            renderView: {
                gridView: false,
                listView: true
            },
            moviesGenres: [],
            titles: {
                /** Example: [{title_name: 'Kmotr',
                 *  description: 'Text...',
                 *  type: 'movie',
                 *  url: 'kmotr',
                 *  year: 1972}] **/
                list: [],
                ordering: 'asc',
                pageNumber: 1,
                titlesToPage : 2,
                numberOfGenreTitles: 0,
                pageNumbers: 0,
                numRows: 0
            }
        }
    },
    watch: {
        $route (to, from) {
            this.getGenreByUrl();
            this.getTitles(to.params.movieGenre);
        }
    },
    mounted() {
        this.getGenreByUrl();
        this.getGenres();
        this.getTitles(this.genre.url);

        if (this.$route.params.genre != null)
            this.genre.url = this.$route.params.genre;
    },
    methods: {
        changePageNum(val) {
            if (val >= 1 && val <= this.titles.pageNumbers && this.titles.pageNumber !== val) {
                this.titles.pageNumber = val;
                this.getTitles(this.genre.url);
            }
        },
        changeGenre(val) {
            this.titles.pageNumber = 1;
            this.$router.push({path: '/filmy/' + val.target.value});
        },
        countNumOfPages() {
            this.titles.pageNumbers = Math.ceil(this.titles.numberOfGenreTitles / this.titles.titlesToPage);
        },
        getTitles(url) {
            this.$emit('emitHandler', {isLoading: true});

            let request = {type: 'movie', genre_url: url,
                number_of_titles: this.titles.titlesToPage, page_number: this.titles.pageNumber, order: this.titles.ordering};
            axios.post('/api/get_titles', request).then((res) => {
                this.titles.list = res.data.titles;
                this.titles.numberOfGenreTitles = res.data.titles_count;
                this.countNumOfPages();
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        toggleView(val) {
            if (val === 'list') {
                this.renderView.gridView = false;
                this.renderView.listView = true;
            }
            else if (val === 'grid') {
                this.renderView.gridView = true;
                this.renderView.listView = false;
            }
        },
        getGenreByUrl() {
            this.$emit('emitHandler', {isLoading: true});

            axios.post('/api/genre_info_from_url', {'url' : this.$route.params.movieGenre}).then((res) => {
                this.genre.name = res.data.name;
                this.genre.url = this.$route.params.movieGenre
                console.log(this.genre.name);
                console.log(this.genre.url);
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        getGenres() {
            // TODO - same you can use '/api/get_genres_series'
            axios.get('/api/get_genres_movies').then((res) => {
                this.moviesGenres = res.data;
            });
        }
    }
}
</script>
