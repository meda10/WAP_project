<template>
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registrace</div>

                <div class="card-body">
                    <form v-on:submit.prevent="registerUser">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Jméno</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control"
                                       v-bind:class="formValid.name ? 'is-valid' : 'is-invalid'"
                                       name="name" required autocomplete="name" autofocus v-model="form.name">
                                <span class="invalid-feedback" role="alert" v-if="errors.name">
                                    <strong>{{ errors.name[0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">Příjmení</label>
                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control"
                                       v-bind:class="formValid.surname ? 'is-valid' : 'is-invalid'"
                                       name="surname" required autocomplete="surname" autofocus v-model="form.surname">
                                <span class="invalid-feedback" role="alert" v-if="errors.surname">
                                    <strong>{{ errors.surname[0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control"
                                       v-bind:class="formValid.email ? 'is-valid' : 'is-invalid'"
                                       name="email" autocomplete="email" required v-model="form.email">
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
                                       name="password" autocomplete="new-password" required v-model="form.password">
                                <span class="invalid-feedback" role="alert" v-if="errors.password">
                                    <strong>{{ errors.password[0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirm" class="col-md-4 col-form-label text-md-right">Heslo znovu</label>
                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control"
                                       v-bind:class="formValid.password_confirmation ? 'is-valid' : 'is-invalid'"
                                       name="password_confirmation" required autocomplete="new-password" v-model="form.password_confirmation">
                                <span class="invalid-feedback" role="alert" v-if="!formValid.password_confirmation">
                                    <strong>Hesla se neshodují</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" @click.prevent="registerUser">
                                    Registrace
                                </button>
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
    title: 'Register',
    props: ['user'],
    data() {
        return {
            form: {
                name: '',
                surname: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            formValid: {
                name: false,
                surname: false,
                email: false,
                password: false,
                password_confirmation: false
            },
            errors: []
        }
    },
    watch: {
        'form.name': {
            handler: function () {
                this.formValid.name = this.form.name !== '';
            }
        },
        'form.surname': {
            handler: function () {
                this.formValid.surname = this.form.surname !== '';

            }
        },
        'form.email': {
            handler: function () {
                this.formValid.email = this.validateEmail();
            }
        },
        'form.password': {
            handler: function () {
                this.formValid.password = this.form.password !== '';
            }
        },
        'form.password_confirmation': {
            handler: function () {
                this.formValid.password_confirmation = this.form.password === this.form.password_confirmation;
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
        registerUser() {
            if (this.formValid.name && this.formValid.surname && this.formValid.email &&
                this.formValid.password && this.formValid.password_confirmation) {
                this.$emit('emitIsLoading', true);

                axios.post('/register', this.form).then(() => {
                    this.$router.push({name: 'login', params: {registration: true}});
                }).catch((error) => {
                    this.errors = error.response.data.errors;
                });
            }
        }
    }
}
</script>
