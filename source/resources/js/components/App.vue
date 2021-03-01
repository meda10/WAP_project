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

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <router-link :to="{ name: 'home' }" class="navbar-brand">Wap-projekt</router-link>
<!--                <a class="navbar-brand" href="#">Navbar</a>-->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav col">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Filmy</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" v-for="genre in seriesGenres" href="#" v-bind:key="genre.text"> {{ genre.text }} </a>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'movies' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Seriály</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" v-for="genre in seriesGenres" href="#" v-bind:key="genre.text"> {{ genre.text }} </a>
                                <div class="dropdown-divider"></div>
                                <router-link :to="{ name: 'series' }" class="dropdown-item">Jiné</router-link>
                            </div>
                        </li>
                    </ul>
                    <form class="form-inline col-auto">
                        <input class="form-control " type="text" placeholder="Search">
                        <button class="btn btn-secondary" type="submit">Search</button>
                    </form>
<!--                    <div class="input-group col-auto">-->
<!--                        <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">-->
<!--                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">Button</button>-->
<!--                    </div>-->

                    <ul id="login-nav" class="navbar-nav col col-sm-2" v-if="!user">
                        <li class="nav-item">
                            <router-link :to="{ name: 'login' }" class="dropdown-item">Přihlásit se</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'register' }" class="dropdown-item">Registrace</router-link>
                        </li>
                    </ul>

                    <ul id="logout-nav" class="navbar-nav col col-sm-4" v-if="user">
                        <li class="nav-item">
                            <span class="dropdown-item">{{ user.name }} {{ user.surname }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" @click.prevent="logout" href="#">Odhlásit se</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div style="margin-top: 100px;">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: null,
                seriesGenres: [
                    {text: 'Akční'},
                    {text: 'Horror'},
                    {text: 'Komedie'},
                    {text: 'Romantické'}
                ]
            }
        },
        watch:{
            $route (to, from){
                this.getUser();
            }
        },
        mounted() {
            this.getUser();
        },
        methods: {
            logout() {
                axios.post('/api/logout').then(() => {
                    this.$router.push({ name: 'login' });
                    this.user = null;
                });
            },
            getUser() {
                axios.get('/api/user').then((res) => {
                    this.user = res.data;
                });
            }
        }   
    }

    // module.exports ={
    //     el: '#navbar',
    //     data: {
    //         seriesGenres: [
    //             {text: 'Akční'},
    //             {text: 'Horror'},
    //             {text: 'Komedie'},
    //             {text: 'Romantické'}
    //         ]
    //     }
    // }
</script>
