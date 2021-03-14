<template>
    <div>
        <loading :active='isLoading' :is-full-page="true" />

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <router-link :to="{ name: 'home' }" class="navbar-brand">Blockbuster 2.0</router-link>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav col-2">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filmy</a>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-for="genre in hotMoviesGenres" :key="genre.name" :to="'/filmy/' + genre.url">{{ genre.name }}</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'movies' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Seriály</a>
                            <div class="dropdown-menu">
                                <router-link class="dropdown-item" v-for="genre in hotSeriesGenres" :key="genre.name" :to="'/serialy/' + genre.url">{{ genre.name }}</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'series' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>
                    </ul>

                    <div class="search-dropdown ml-auto" v-click-outside="lostFocus" style="padding-left:20px; padding-right:20px;">
                        <input v-model.trim="searchForm.inputValue" class="form-control search-dropdown-input" type="text" placeholder="Search" @click="searchForm.searchFocus = true">
                        <div class="search-dropdown-list" v-show="searchForm.searchFocus">
                            <div v-for="title in searchForm.itemList" :key="title.title_name" class="search-dropdown-item"
                                 v-show="searchItemVisible(title)" @click="routeToTitle(title)">
                                {{ title.title_name }} - {{ title.type }}
                            </div>
                        </div>
                    </div>

                    <ul id="login-nav" class="navbar-nav col-5">
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
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span v-if="cartItemsNumber !== 0">
                                    <span class='badge badge-warning' id='lblCartCount'>{{cartItemsNumber}}</span>
                                    <span class='badge' id='infoCart'>{{cartItemsPrice}} Kč</span>
                                </span>
                                <span v-if="cartItemsNumber === 0">
                                    <span class='badge' id='infoCart'>Košík</span>
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <router-link :to="{ name: 'cart' }" class="dropdown-item">Do košíku</router-link>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" @click="changeStore">Změnit prodejnu</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="alert alert-danger" role="alert" v-if="user && !user.confirmed">
            <strong>Váš účet není ověřený.</strong> Ověřte své údaje na některé z našich prodejen.
        </div>

        <div class="container" style="margin-top: 100px;">
            <router-view v-on:emitHandler="emitHandler" :user="user" :key="$route.path"
            :cartCookiesProps="cartCookies" :cartItemsPriceProps="cartItemsPrice" 
            :chosenStoreProps="chosenStore" :storesProps="stores"></router-view>
        </div>

        <b-modal ref="modal-choose-store" :retain-focus="false" title="Vyberte si prodejnu"
                :ok-only="!changeStoreMessage" :no-close-on-backdrop="!changeStoreMessage" 
                :hide-header-close="!changeStoreMessage" @ok="handleSaveStore">
            <div v-if="changeStoreMessage">
                Při zmeně prodejny dojde ke smazání Vašeho košíku!
            </div>
            <select autocomplete="off" class="form-select" v-model="chosenStore" style="width:400px;">
                <option v-for="store in stores" v-bind:value="store.id"
                        v-bind:key="store.id">{{store.address}}</option>
            </select>
        </b-modal>
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
            cartItemsPrice: 0,
            stores: [],
            chosenStore: 1,
            changeStoreMessage: false,
            searchForm: {
                inputValue: '',
                searchFocus: false,
                itemList: []
            }
        }
    },
    directives: {
        'click-outside': {
            bind: function (el, binding, vnode) {
                el.clickOutsideEvent = function (event) {
                    // here I check that click was outside the el and his children
                    if (!(el === event.target || el.contains(event.target))) {
                        // and if it did, call method provided in attribute value
                        vnode.context[binding.expression](event);
                    }
                };
                document.body.addEventListener('click', el.clickOutsideEvent)
            },
            unbind: function (el) {
                document.body.removeEventListener('click', el.clickOutsideEvent)
            },
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
        this.getStores();
        this.loadCookies();
        this.getGenres();
        this.getSeachTitles();
        this.getUser();
        this.chooseStore();
    },
    methods: {
        getSeachTitles() {
            axios.get('/api/get_all_titles_search').then((res) => {
                this.searchForm.itemList = res.data;

                this.searchForm.itemList.forEach(item => {
                    if (item.typeUrl === 'movie') {
                        item.typeUrl = 'film';
                        item.type = 'Film';
                        return;
                    }
                    item.typeUrl = 'serial';
                    item.type = 'Seriál';
                });
            });
        },
        handleSaveStore() {
            this.setStore();
        },
        chooseStore() {
            if (!this.$session.exists('wap-store')) {
                this.isLoading = false;
                this.showModalChooseStore();
                this.setStore();
            }
        },
        setStore() {
            if (this.$session.exists('wap-store') && this.$session.get('wap-store') !== this.chosenStore) 
                this.clearCookies();

            this.$session.set('wap-store', this.chosenStore);
        },
        changeStore() {
            this.changeStoreMessage = true;
            this.showModalChooseStore();
        },
        showModalChooseStore() {
            this.$refs['modal-choose-store'].show();
        },
        getStores() {
            axios.get('/api/get_stores').then((res) => {
                this.stores = res.data;
                this.chosenStore = this.stores[0].id;
            });
        },
        clearCookies() {
            this.cartCookies = [];
            this.$cookies.set('wap-cart', JSON.stringify(this.cartCookies));
        },
        lostFocus() {
            this.searchForm.searchFocus = false;
        },
        routeToTitle(title) {
            this.searchForm.searchFocus = false;
            this.searchForm.inputValue = '';
            this.$router.push({path: '/' + title.typeUrl + '/' + title.url});
        },
        searchItemVisible(title) {
            let currentName = title.title_name.toLowerCase();
            let currentInput = this.searchForm.inputValue.toLowerCase();
            return currentName.includes(currentInput);
        },
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
            this.cartCookies = JSON.parse(this.$cookies.get('wap-cart')) || [];
            this.cookiesItemsCount();
        },
        cookiesItemsCount() {
            this.cartItemsNumber = 0;
            this.cartItemsPrice = 0;
            this.cartCookies.forEach(element => {
                this.cartItemsNumber += Number(element.quantity);
                this.cartItemsPrice += Number(element.quantity) * Number(element.price) * Number(element.reservationNumberOfDays);
            });
        },
        emitHandler(values) {
            if (values.isLoading !== undefined) this.isLoading = values.isLoading;

            if (values.cartCookies !== undefined) {
                this.cartCookies = values.cartCookies;
                this.$cookies.set('wap-cart', JSON.stringify(this.cartCookies));
                this.isLoading = false;
                this.cookiesItemsCount();
            }
        }
    }
}
</script>
