<template>
<div>
    <h1>{{ genre.name }}</h1>
    <div class="row">
        <div class="col-10">
            <div class="form-group">
                <label for="exampleSelect1">Výběr žánru:</label>
                <select class="form-control" id="exampleSelect1">
                    <option v-for="genre in moviesGenres">{{ genre.name }}</option>
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
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Tady by mohl být název filmu velky</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <hr>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Tady by mohl být název filmu velky</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <hr>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  END GRID VIEW  -->
    <!--  LIST VIEW  -->
    <table class="table table-hover" v-if="!renderView.gridView && renderView.listView">
        <thead>
        <tr>
            <th scope="col">Type</th>
            <th scope="col">Column heading</th>
            <th scope="col">Column heading</th>
            <th scope="col">Column heading</th>
        </tr>
        </thead>
        <tbody>
        <tr class="table-dark">
            <th>Default</th>
            <td>Column content</td>
            <td>Column content</td>
            <td>Column content</td>
        </tr>
        <tr>
            <th>Default</th>
            <td>Column content</td>
            <td>Column content</td>
            <td>Column content</td>
        </tr>
        <tr class="table-dark">
            <th>Dark</th>
            <td>Column content</td>
            <td>Column content</td>
            <td>Column content</td>
        </tr>
        <tr>
            <th>Default</th>
            <td>Column content</td>
            <td>Column content</td>
            <td>Column content</td>
        </tr>
        <tr class="table-dark">
            <th>Dark</th>
            <td>Column content</td>
            <td>Column content</td>
            <td>Column content</td>
        </tr>
        </tbody>
    </table>
    <!--  ENDLIST VIEW  -->

    <div class="row justify-content-center mt-4">
        <ul class="pagination" v-if="titles.pageNumbers < 8">
            <li class="page-item"
                @click="titles.pageNumber--"
                v-bind:class="(titles.pageNumber <= 1) ? 'disabled' : ''">
                <a class="page-link" href="#">&laquo;</a>
            </li>
            <!--      When number of pages <= 7      -->
            <li class="page-item"
                v-for="num in titles.pageNumbers"
                @click="titles.pageNumber = num"
                v-bind:class="(titles.pageNumber === num) ? 'active' : ''">
                <a class="page-link" href="#">{{ num }}</a>
            </li>
            <li class="page-item"
                @click="titles.pageNumber++"
                v-bind:class="(titles.pageNumber >= titles.pageNumbers) ? 'disabled' : ''">
                <a class="page-link" href="#">&raquo;</a>
            </li>
        </ul>
        <ul class="pagination" v-if="titles.pageNumbers >= 8">
            <li class="page-item"
                @click="titles.pageNumber--"
                v-bind:class="(titles.pageNumber <= 1) ? 'disabled' : ''">
                <a class="page-link" href="#">&laquo;</a>
            </li>

            <!--      When number of pages       -->
            <li class="page-item"
                v-for="num in 2"
                @click="titles.pageNumber = num"
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
                @click="titles.pageNumber = num+2"
                v-bind:class="(titles.pageNumber === (num+2)) ? 'active' : ''">
                <a class="page-link" href="#">{{ num+2 }}</a>
            </li>

            <li class="page-item"
                v-if="(titles.pageNumber > 4) && (titles.pageNumber < (titles.pageNumbers-3))"
                v-for="num in 3"
                @click="titles.pageNumber = titles.pageNumber-2+num"
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
                @click="titles.pageNumber = titles.pageNumbers-5+num"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers-5+num)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers-5+num) }}</a>
            </li>

            <li class="page-item"
                @click="titles.pageNumber = titles.pageNumbers-1"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers-1)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers-1) }}</a>
            </li>
            <li class="page-item"
                @click="titles.pageNumber = titles.pageNumbers"
                v-bind:class="(titles.pageNumber === (titles.pageNumbers)) ? 'active' : ''">
                <a class="page-link" href="#">{{ (titles.pageNumbers) }}</a>
            </li>

            <li class="page-item"
                @click="titles.pageNumber++"
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
                numberOfGenreTitles: 20,
                pageNumbers: 0
            }
        }
    },
    watch: {
        $route (to, from) {
            this.getGenreByUrl();
            this.getTitles();
            this.countNumOfPages();
        },
        'titles.pageNumber': {
            handler: function (newVal, oldVal) {
                if (this.titles.pageNumber < 1 || this.titles.pageNumber > this.titles.pageNumbers) {
                    this.titles.pageNumber = oldVal;
                }
            }
        },
    },
    mounted() {
        this.getGenreByUrl();
        this.getGenres();
        this.getTitles();
        this.countNumOfPages();

        if (this.$route.params.genre != null)
            this.genre.url = this.$route.params.genre;
    },
    methods: {
        countNumOfPages() {
            this.titles.pageNumbers = Math.ceil(this.titles.numberOfGenreTitles / this.titles.titlesToPage);
        },
        getTitles() {
            let request = {type: this.genre.name, genre_url: this.genre.url,
                number_of_titles: this.titles.titlesToPage, page_number: this.titles.pageNumber, order: this.titles.ordering};
            axios.post('/api/get_titles', request).then((res) => {
                console.log(res.data);
            }).catch((error) => {
                console.log(error);
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
            this.$emit('emitIsLoading', true);

            axios.post('/api/genre_info_from_url', {'url' : this.$route.params.movieGenre}).then((res) => {
                this.genre.name = res.data.name;
                // this.genre.url = res.data.url
                this.$emit('emitIsLoading', false);
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitIsLoading', false);
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
