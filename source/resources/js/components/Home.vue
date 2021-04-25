<template>
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="reservation">
            <strong>Rezervace byla úspěšná!</strong> Faktura byla zaslána na Váš email.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div id="carouselNews" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselNews" data-slide-to="0" class="active"></li>
                <li data-target="#carouselNews" data-slide-to="1"></li>
                <li data-target="#carouselNews" data-slide-to="2"></li>
                <li data-target="#carouselNews" data-slide-to="3"></li>
<!--                <li data-target="#carouselNews" data-slide-to="4"></li>-->
            </ol>
            <div class="carousel-inner">
                <router-link class="carousel-item active" tag="div" :to="'/film/mlceni-jehatek'">
                    <img class="d-block w-100" :src="'/storage/img/slide1.jpg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            Mlčení jehňátek
                        </h3>
                    </div>
                </router-link>
                <router-link class="carousel-item" tag="div" :to="'/film/kmotr'">
                        <img class="d-block w-100" :src="'/storage/img/slide2.jpg'" alt="First slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h3 style="font-weight: bold;">
                                Kmotr
                            </h3>
                        </div>
                </router-link>
                <router-link class="carousel-item" tag="div" :to="'/film/terminator-2-den-zuctovani'">
                    <img class="d-block w-100" :src="'/storage/img/slide3.webp'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            Terminátor 2 den zůčtování
                        </h3>
                    </div>
                </router-link>
                <router-link class="carousel-item" tag="div" :to="'/film/temny-rytir'">
                    <img class="d-block w-100" :src="'/storage/img/slide4.jpg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            Temný rytíř
                        </h3>
                    </div>
                </router-link>
            </div>
            <a class="carousel-control-prev" href="#carouselNews" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselNews" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
<!--        <div class="row">-->
<!--            <div class="col-lg-4">jo</div>-->
<!--            <div class="col-lg-4">ne</div>-->
<!--            <div class="col-lg-4">sdf</div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-lg-4">-->
<!--                <div class="bs-component">jo</div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">ne</div>-->
<!--            <div class="col-lg-4">sdf</div>-->
<!--        </div>-->
    </div>
</template>

<script>
    export default {
        title: 'Home Page',
        data() {
            return {
                reservation: false,
            }
        },
        mounted() {
            this.reservation = this.$route.params.reservation;
        },
    }
</script>
