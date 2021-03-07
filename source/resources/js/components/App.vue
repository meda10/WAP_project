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
                                <router-link class="dropdown-item" v-for="genre in hotMoviesGenres" v-bind:key="genre.name" :to="'/filmy/' + genre.url">{{ genre.name }}</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'movies' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Seriály</a>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-for="genre in hotSeriesGenres" v-bind:key="genre.name" :to="'/serialy/' + genre.url">{{ genre.name }}</router-link>
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

                    <ul id="login-nav" class="navbar-nav col col-sm-4">
                        <li class="nav-item" v-if="!user">
                            <router-link :to="{ name: 'login' }" class="nav-link">Přihlásit se</router-link>
                        </li>
                        <li class="nav-item" v-if="!user">
                            <router-link :to="{ name: 'register' }" class="nav-link">Registrace</router-link>
                        </li>
                        <li class="nav-item dropdown" v-if="user">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                {{ user.name }} {{ user.surname }}
                            </a>
                            <div class="dropdown-menu">
                                <router-link :to="{ name: 'settings' }" class="dropdown-item">Nastavení</router-link>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" @click.prevent="logout" href="#">Odhlásit se</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown nav-item-icon" id="navCartIcon">
                            <router-link :to="{ name: 'cart' }" class="nav-link">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span v-if="cartItemsNumber != 0">
                                    <span class='badge badge-warning' id='lblCartCount'>{{cartItemsNumber}}</span>
                                    <span class='badge' id='infoCart'>{{cartItemsPrice}} Kč</span>
                                </span>
                                <span v-if="cartItemsNumber == 0">
                                    <span class='badge' id='infoCart'>Košík</span>
                                </span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="alert alert-danger" role="alert" v-if="user && !user.confirmed">
            <strong>Váš účet není ověřený.</strong> Ověřte své údaje na některé z našich prodejen.
        </div>

        <div class="container" style="margin-top: 100px;">
            <router-view v-on:emitHandler="emitHandler" :user="user" 
            :cartCookiesProps="cartCookies" :cartItemsPriceProps="cartItemsPrice"></router-view>
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
            hotSeriesGenres: [],
            hotMoviesGenres: [],
            cartCookies: [],
            cartItemsNumber: 0,
            cartItemsPrice: 0
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
        },
        cartCookies: {
            handler: function (cartCookies) {
                this.cookiesItemsCount();
                this.$forceUpdate();
            },
            immediate: true
        },
        cartItemsNumber: {
            handler: function (cartItemsNumber) {
                this.$forceUpdate();
            },
            immediate: true
        }
    },
    mounted() {
        this.loadCookies();
        this.getGenres();
        this.getUser();


        // TODO DELETE THIS AFTER UCHYLE_Z_VOKUREK

        // USAGE
        // type : ['type', 'serial']
        // number_of_titles - number of titles on one page
        // page_number - actual page number
        // order : ['asc', 'desc']

        // var request = {type: 'movie', genre_url: 'krimi', number_of_titles: 2, page_number: 1, order: 'asc'};
        // axios.post('/api/get_titles', request).then((res) => {
        //     console.log(res.data);
        // }).catch((error) => {
        //     console.log(error);
        // });
        //
        // var request = {type: 'movie', genre_url: 'krimi', number_of_titles: 2, page_number: 2, order: 'asc'};
        // axios.post('/api/get_titles', request).then((res) => {
        //     console.log(res.data);
        // }).catch((error) => {
        //     console.log(error);
        // });
    },
    methods: {
        logout() {
            this.isLoading = true;

            axios.post('/logout').then(() => {
                this.user = null;
                this.$router.push({ name: 'login' });
            });
        },
        getUser() {
            axios.get('/api/user').then((res) => {
                this.user = res.data;
            });
        },
        getGenres() {
            axios.get('/api/genres_menu').then((res) => {
                this.hotMoviesGenres = res.data.movies;
                this.hotSeriesGenres = res.data.series;
            });
        },
        loadCookies(){
            this.cartCookies = JSON.parse($cookies.get('wap-cart')) || [];
            this.cookiesItemsCount();
        },
        cookiesItemsCount() {
            this.cartItemsNumber = 0;
            this.cartItemsPrice = 0;
            this.cartCookies.forEach(element => {
                this.cartItemsNumber += Number(element.quantity);
                this.cartItemsPrice += Number(element.quantity) * Number(element.price);
            });  
        },
        emitHandler(values) {
            if (values.isLoading != undefined) this.isLoading = values.isLoading;

            if (values.cartCookies != undefined) {
                this.cartCookies = values.cartCookies;
                this.$cookies.set('wap-cart', JSON.stringify(this.cartCookies));
                this.isLoading = false;
                this.cookiesItemsCount();
            }
        }
    }
}
</script>
