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
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#">&laquo;</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">5</a>
            </li>
            <li class="page-item">
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
            moviesGenres: []
        }
    },
    watch: {
        $route (to, from) {
            this.getGenreByUrl();
        },
    },
    mounted() {
        this.getGenreByUrl();
        this.getGenres();

        if (this.$route.params.genre != null)
            this.genre.url = this.$route.params.genre;
    },
    methods: {
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
