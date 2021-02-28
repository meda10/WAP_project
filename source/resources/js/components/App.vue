<template>

    <div>
        <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
            <div class="container">
                <router-link :to="{ name: 'home' }" class="navbar-brand">Home</router-link>
                <router-link :to="{ name: 'titles' }">Titles</router-link>
                {{ $name }}
            </div>
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
