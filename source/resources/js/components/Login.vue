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
                    <form v-on:submit.prevent="loginUser" method="post">
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control"
                                       v-bind:class="formValid.email ? 'is-valid' : 'is-invalid'"
                                       name="email" required autocomplete="email" autofocus v-model="form.email">
                                <span class="invalid-feedback" role="alert" v-if="errors.email">
                                    <strong>{{ errors.email[0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Heslo</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control"
                                       v-bind:class="formValid.password ? 'is-valid' : 'is-invalid'"
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
                    </form>
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
            key: 0,
            form: {
                email: '',
                password: ''
            },
            formValid: {
                email: false,
                password: false
            },
            errors: [],
            registered: false
        }
    },
    mounted() {
        this.registered = this.$route.params.registration;
    },
    watch: {
        immediate: true,
        'form.email': {
            handler: function (val) {
                this.form.email = val;
                this.formValid.email = this.validateEmail() && this.errors.email === undefined;
            }
        },
        'form.password': {
            immediate: true,
            handler: function (val) {
                this.form.password = val;
                this.formValid.password = this.form.password !== '';
            }
        },
        user: {
            handler: function (user) {
                if (user != null) this.$router.push({ name: 'home' });
            },
            immediate: true
        },
        errors: {
            handler: function (errors) {
                if (errors != null) this.$emit('emitIsLoading', false);
            },
            immediate: true
        }
    },
    methods: {
        validateEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.form.email);
        },
        loginUser() {
            // if (this.formValid.email && this.formValid.password) {
                this.$emit('emitIsLoading', true);

                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/login', this.form).then((res) => {
                        this.$router.push({name: 'home'});
                    }).catch((error) => {
                        this.errors = error.response.data.errors;
                        for (const [key, value] of Object.entries(this.errors)) {
                            if (Object.keys(this.errors).length !== 0) {
                                this.formValid.email = false;
                                this.formValid.password = false;
                                this.form.password = '';
                            }
                        }
                    });
                });
            // }
        }
    }
}
</script>
