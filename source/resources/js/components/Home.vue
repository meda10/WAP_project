<template>
    <div class="container">
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
                    <img class="d-block w-100" :src="'/img/slide1.svg'" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First Slide</h5>
                        <p>Tady bude super text, který tady bude něco ukazovat.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/img/slide2.svg'" alt="Second slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Druhý slide</h5>
                        <p>Tady bude super text, který tady bude něco ukazovat.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/img/slide3.svg'" alt="Third slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Třetí slide</h5>
                        <p>Tady bude super text, který tady bude něco ukazovat.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/img/slide4.svg'" alt="Fourth slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Čtvrtý slide</h5>
                        <p>Tady bude super text, který tady bude něco ukazovat.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" :src="'/img/slide5.svg'" alt="Fifth slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Páty slide</h5>
                        <p>Tady bude super text, který tady bude něco ukazovat.</p>
                    </div>
                </div>
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
        
        <router-link :to="{ name: 'checkout' }" class="nav-link">Platba</router-link>
        
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
        title: 'Home Page'
    }
</script>
