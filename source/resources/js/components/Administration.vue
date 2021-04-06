<template>
<div>
    <div class="row mb-5">
        <div class="col">
            <h1>Administrace</h1>
        </div>
    </div>

    <!--UZIVATELE-->
    <div class="row">
        <div class="col">
            <h2>Uživatelé</h2>
            <hr class="white-hr">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/uzivatele'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <p class="card-text text-center">Seznam uživatelů</p>
            </router-link>
        </div>
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/uzivatele/pridat_uzivatele'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-plus"></i>
                </div>
                <p class="card-text text-center">Přidat uživatele</p>
            </router-link>
        </div>
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/uzivatele/rezervace'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <p class="card-text text-center">Rezervace uživatelů</p>
            </router-link>
        </div>
        <div class="col-2">
        </div>
    </div>


    <!--TITULY-->
    <div class="row">
        <div class="col">
            <h2>Tituly</h2>
            <hr class="white-hr">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/pridat_titul'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-film"></i>
                    <i class="fas fa-plus ml-1" ></i>
                </div>
                <p class="card-text text-center">Přidat titul</p>
            </router-link>
        </div>
        <div class="col-2">
        </div>
        <div class="col-2">
        </div>
        <div class="col-2">
        </div>
    </div>


    <!--HERCI-->
    <div class="row">
        <div class="col">
            <h2>Herci</h2>
            <hr class="white-hr">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/herci'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <p class="card-text text-center">Seznam herců</p>
            </router-link>
        </div>
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/herci/pridat_herce'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-plus fa-3x" ></i>
                </div>
                <p class="card-text text-center">Přidat herce</p>
            </router-link>
        </div>
        <div class="col-2">
        </div>
        <div class="col-2">
        </div>
    </div>


    <!--OBCHODY-->
    <div class="row">
        <div class="col">
            <h2>Obchody</h2>
            <hr class="white-hr">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/obchody'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-store-alt"></i>
                </div>
                <p class="card-text text-center">Seznam obchodů</p>
            </router-link>
        </div>
        <div class="col-2">
            <router-link class="card border-0 admin-link" tag="div" :to="'/admin/obchody/pridat_obchod'">
                <div class="card-img d-flex align-items-center justify-content-center">
                    <i class="fas fa-store-alt"></i>
                    <i class="fas fa-plus"></i>
                </div>
                <p class="card-text text-center">Přidat obchod</p>
            </router-link>
        </div>
        <div class="col-2">
        </div>
        <div class="col-2">
        </div>
    </div>
</div>
</template>
<script>
export default {

}
</script>
