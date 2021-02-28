<template>

    <div>
        <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <router-link :to="{ name: 'home' }" class="navbar-brand">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'titles' }" class="navbar-brand">Titles</router-link>
                </li>
            </ul>
        </div>

        <div  style="margin-top: 50px;">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>
