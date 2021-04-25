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
                <li data-target="#carouselNews" data-slide-to="4"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" :src="'/storage/img/slide1.jpg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            <router-link style="text-decoration: none;" :to="{ path: '/film/mlceni-jehatek' }">Mlčení jehňátek</router-link>
                        </h3>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/storage/img/slide2.jpg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            <router-link style="text-decoration: none;" :to="{ path: '/film/kmotr' }">Kmotr</router-link>
                        </h3>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/storage/img/slide3.webp'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            <router-link style="text-decoration: none;" :to="{ path: '/film/terminator-2-den-zuctovani' }">Terminátor 2 den zůčtování</router-link>
                        </h3>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/storage/img/slide4.jpg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 style="font-weight: bold;">
                            <router-link style="text-decoration: none;" :to="{ path: '/film/temny-rytir' }">Temný rytíř</router-link>
                        </h3>
                    </div>
                </div>
<!--                <div class="carousel-item">-->
<!--                    <img class="d-block w-100" :src="'/storage/img/slide5.svg'" alt="Fifth slide">-->
<!--                    <div class="carousel-caption d-none d-md-block">-->
<!--                        <h5>Páty slide</h5>-->
<!--                        <p>Tady bude super text, který tady bude něco ukazovat.</p>-->
<!--                    </div>-->
<!--                </div>-->
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
                reservation: false
            }
        },
        mounted() {
            this.reservation = this.$route.params.reservation;
        }
    }
</script>
