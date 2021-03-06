<template>
    <div>
<!--        <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">-->
<!--            <ul class="navbar-nav me-auto mb-2 mb-lg-0">-->
<!--                <li class="nav-item">-->
<!--                    <router-link :to="{ name: 'home' }" class="navbar-brand">Home</router-link>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <router-link :to="{ name: 'titles' }" class="navbar-brand">Titles</router-link>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->

        <loading :active='isLoading' :is-full-page="true" />

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <router-link :to="{ name: 'home' }" class="navbar-brand">Wap-projekt</router-link>
<!--                <a class="navbar-brand" href="#">Navbar</a>-->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav col">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filmy</a>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-for="genre in moviesGenres" v-bind:key="genre.name" :to="'/filmy/' + genre.url">{{ genre.name }}</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'movies' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Seriály</a>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-for="genre in seriesGenres" v-bind:key="genre.name" :to="'/serialy/' + genre.url">{{ genre.name }}</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'series' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>
                    </ul>
                    <form class="form-inline col-auto">
                        <input class="form-control " type="text" placeholder="Search">
                        <button class="btn btn-secondary">Search</button>
                    </form>
<!--                    <div class="input-group col-auto">-->
<!--                        <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">-->
<!--                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">Button</button>-->
<!--                    </div>-->

                    <ul id="login-nav" class="navbar-nav col col-sm-3" v-if="!user">
                        <li class="nav-item">
                            <router-link :to="{ name: 'login' }" class="nav-link">Přihlásit se</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'register' }" class="nav-link">Registrace</router-link>
                        </li>
                    </ul>

                    <ul id="logout-nav" class="navbar-nav col col-sm-2" v-if="user">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                {{ user.name }} {{ user.surname }}
                            </a>
                            <div class="dropdown-menu">
                                <router-link :to="{ name: 'settings' }" class="dropdown-item">Nastevení</router-link>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" @click.prevent="logout" href="#">Odhlásit se</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container" style="margin-top: 100px;">
            <router-view v-on:emitIsLoading="emitIsLoadingHandler" :user="user"></router-view>
        </div>

    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        Loading
    },
    data() {
        return {
            user: null,
            isLoading: false,
            seriesGenres: [],
            moviesGenres: []
        }
    },
    watch: {
        $route (to, from) {
            this.getUser();
        },
        user: {
            handler: function (user) {
                this.isLoading = false;
            },
            immediate: true
        },
        isLoading: {
            handler: function (isLoading) {
                this.$forceUpdate();
            },
            immediate: true
        }
    },
    mounted() {
        this.getGenres();
        this.getUser();
    },
    methods: {
        logout() {
            this.isLoading = true;

            axios.post('/api/logout').then(() => {
                this.$router.push({ name: 'login' });
                this.user = null;
            });
        },
        getUser() {
            axios.get('/api/user').then((res) => {
                this.user = res.data;
            });
        },
        getGenres() {
            axios.get('/api/genres_menu').then((res) => {
                this.moviesGenres = res.data.movies;
                this.seriesGenres = res.data.series;
            });
        },
        emitIsLoadingHandler(isLoading) {
            this.isLoading = isLoading;
        }
    }
}
</script>
