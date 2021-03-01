<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="registered">
                <strong>Registrace byla úspěšná!</strong> Nyní se můžete přihlásit.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="card">
                <div class="card-header">Přihlášení</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" required autocomplete="email" autofocus v-model="form.email">
                            <span class="invalid-feedback" role="alert" v-if="errors.email">
                                <strong>{{ errors.email[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Heslo</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password" v-model="form.password">
                            <span class="invalid-feedback" role="alert" v-if="errors.password">
                                <strong>{{ errors.password[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <!-- TODO -->
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                <label class="form-check-label" for="remember">
                                    Zapamatuj si mě
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" @click.prevent="loginUser">
                                Přihlásit se
                            </button>

                            <router-link :to="{ name: 'password' }" class="btn btn-link">Zapomněl(a) jsem heslo</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    title: 'Login',
    props: ['user'],
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            errors: [],
            registered: false
        }
    },
    mounted() {
        this.registered = this.$route.params.registration;
    },
    watch: {
        user: {
            handler: function (user) {
                if (user != null) this.$router.push({ name: 'home' });
            },
            immediate: true
        }
    },
    methods: {
        loginUser() {
            axios.post('/api/login', this.form).then(() => {
                this.$router.push({ name: 'home' });
            }).catch((error) => {
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>
