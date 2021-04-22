<template>
    <div class="page-wrap d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <span class="display-1 d-block">404</span>
                    <div class="mb-4 lead">Stránka nebyla nalezena</div>
                    <router-link :to="{ name: 'home' }">Zpět na hlavní stránku</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>
