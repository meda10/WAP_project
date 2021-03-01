<template>
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registrace</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Jméno</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                            name="name" required autocomplete="name" autofocus v-model="form.name">
                            <span class="invalid-feedback" role="alert" v-if="errors.name">
                                <strong>{{ errors.name[0] }}</strong>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="surname" class="col-md-4 col-form-label text-md-right">Příjmení</label>
                        <div class="col-md-6">
                            <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" 
                            name="surname" required autocomplete="surname" autofocus v-model="form.surname">
                            <span class="invalid-feedback" role="alert" v-if="errors.surname">
                                <strong>{{ errors.surname[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" required autocomplete="email" v-model="form.email">
                            <span class="invalid-feedback" role="alert" v-if="errors.email">
                                <strong>{{ errors.email[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Heslo</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                            name="password" required autocomplete="new-password" v-model="form.password">
                            <span class="invalid-feedback" role="alert" v-if="errors.email">
                                <strong>{{ errors.password[0] }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirm" class="col-md-4 col-form-label text-md-right">Heslo znovu</label>
                        <div class="col-md-6">
                            <input id="password_confirm" type="password" class="form-control"
                            name="password_confirmation" required autocomplete="new-password" v-model="form.password_confirmation">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" @click.prevent="registerUser">
                                Registrace
                            </button>
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
    title: 'Register',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: []
        }
    },
    methods: {
        registerUser() {
            axios.post('/api/register', this.form).then(() => {
                this.$router.push({ name: 'login', params: { registration: true } });
            }).catch((error) => {
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>
