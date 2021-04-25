<template>
    <div>
        <div class="row mb-2">
            <div class="col">
                <h1>Nastavení</h1>
                <hr style="background-color: white">
            </div>
        </div>
        <div class="row my-4">
            <div class="col-sm-1">
                Email:
            </div>
            <div class="col-sm-5">
                <span v-if="user && !whatChange.email">
                    {{ user.email }} <i class="fas fa-pencil-alt edit-icon" @click="whatChange.email = true; updateForm.email = user.email"></i>
                </span>

                <div class="form-row align-items-center" v-if="whatChange.email">
                    <div class="input-group">
                        <input type="text" class="form-control" id="emailEditForm"
                               v-bind:class="validUpdateForm.email ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.email">
                        <button class="btn btn-success" @click="updateEmail">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.email = false; updateForm.email = ''">Zrušit</button>
                    </div>
                    <div v-if="!validUpdateForm.email">
                        <span>{{ errorTextList['email'] }}</span><br>

                    </div>
                </div>
            </div>

            <div class="col-sm-1">
                Role:
            </div>
            <div class="col-sm-5">
                <span v-if="user">{{ showRole(user.role) }}</span>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-sm-1 justify-content-center">
                <span>Jméno:</span>
            </div>
            <div class="col-sm-5">
                <span v-if="user && !whatChange.name">
                    {{ user.name }} <i class="fas fa-pencil-alt edit-icon" @click="whatChange.name = true; updateForm.name = user.name"></i>
                </span>

                <div class="form-row align-items-center" v-if="whatChange.name">
                    <div class="input-group">
                        <input type="text" class="form-control" id="nameEditForm"
                               v-bind:class="validUpdateForm.name ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.name">
                        <button class="btn btn-success" @click="updateName">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.name = false;
                                updateForm.name = ''">Zrušit</button>
                    </div>
                    <div v-if="!validUpdateForm.name">
                        <span>{{ errorTextList['name'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-1">
                Příjmení:
            </div>
            <div class="col-sm-5">
                <span v-if="user && !whatChange.surname">
                    {{ user.surname }} <i class="fas fa-pencil-alt edit-icon" @click="whatChange.surname = true; updateForm.surname = user.surname"></i>
                </span>

                <div class="form-row align-items-center" v-if="whatChange.surname">
                    <div class="input-group">
                        <input type="text" class="form-control" id="surnameEditForm"
                               v-bind:class="validUpdateForm.surname ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.surname">
                        <button class="btn btn-success" @click="updateSurname">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.surname = false;
                                updateForm.surname = ''">Zrušit</button>
                    </div>
                    <div v-if="!validUpdateForm.surname">
                        <span>{{ errorTextList['surname'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-sm-1">
                Změna hesla <i class="fas fa-pencil-alt edit-icon" @click="whatChange.password = true"></i>
            </div>
            <div class="col-sm-5">
                <div class="form-row align-items-center" v-if="whatChange.password">
                    <div class="form-group">
                        <label for="passwordEditForm">Aktuální heslo:</label>
                        <input type="password" class="form-control  mb-2" id="passwordEditForm"
                               v-bind:class="validUpdateForm.passwordCurrent ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.passwordCurrent" autocomplete="new-password">
                        <div v-if="!validUpdateForm.passwordCurrent">
                            <span>{{ errorTextList['passwordCurrent'] }}</span>
                        </div>

                        <label for="newPasswordEditForm">Nové heslo:</label>
                        <input type="password" class="form-control  mb-2" id="newPasswordEditForm"
                               v-bind:class="validUpdateForm.password ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.password" autocomplete="new-password">
                        <div v-if="!validUpdateForm.password">
                            <span>{{ errorTextList['password'] }}</span>
                        </div>

                        <label for="newPasswordAgainEditForm">Nové heslo znovu:</label>
                        <input type="password" class="form-control mb-2" id="newPasswordAgainEditForm"
                               v-bind:class="validUpdateForm.passwordAgain ? 'is-valid' : 'is-invalid'"
                               v-model="updateForm.passwordAgain" autocomplete="new-password">
                        <div v-if="!validUpdateForm.passwordAgain">
                            <span>{{ errorTextList['passwordAgain'] }}</span><br>
                            <span>{{ errorTextList['passwordSame'] }}</span>
                        </div>

                        <button class="btn btn-success" @click="updatePassword">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.password = false;
                                updateForm.password = ''; updateForm.passwordAgain = ''; updateForm.passwordCurrent = ''">Zrušit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 mb-3">
            <div class="col">
                <span class="h3">Adresa:</span> <i class="fas fa-pencil-alt edit-icon"
                                                   @click="whatChange.address = true; updateForm.address = user.address;
                                                   updateForm.city = user.city; updateForm.zip_code = user.zip_code;"></i>
            </div>
        </div>
        <!--   Address show     -->
        <div v-if="user && !whatChange.address">
            <div class="row mb-4">
                <div class="col-sm-1">
                    Ulice:
                </div>
                <div class="col-sm-5">
                    {{ user.address }}
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-1">
                    Město:
                </div>
                <div class="col-sm-5">
                    {{ user.city }}
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-1">
                    PSČ:
                </div>
                <div class="col-sm-5">
                    {{ user.zip_code }}
                </div>
            </div>
        </div>
        <!--   Address form     -->
        <div v-if="user && whatChange.address">
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label" for="addressAddr">Ulice:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="addressAddr"
                           v-bind:class="validUpdateForm.address ? 'is-valid' : 'is-invalid'"
                           v-model="updateForm.address">
                    <div v-if="!validUpdateForm.address">
                        <span>{{ errorTextList['address'] }}</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label" for="addressCity">Město:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="addressCity"
                           v-bind:class="validUpdateForm.city ? 'is-valid' : 'is-invalid'"
                           v-model="updateForm.city">
                    <div v-if="!validUpdateForm.city">
                        <span>{{ errorTextList['city'] }}</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label" for="addressZipCode">PSČ:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="addressZipCode"
                           v-bind:class="validUpdateForm.zip_code ? 'is-valid' : 'is-invalid'"
                           v-model="updateForm.zip_code">
                    <div v-if="!validUpdateForm.zip_code">
                        <span>{{ errorTextList['zip_code'] }}</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1">
                </div>
                <div class="col-sm-5">
                    <button class="btn btn-success" @click="updateAddress">Uložit</button>
                    <button class="btn btn-danger" @click="whatChange.address = false;
                                updateForm.address = ''; updateForm.city = ''; updateForm.zip_code = '';">Zrušit</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    title: 'Nastavení',
    props: ['user'],
    data() {
        return {
            updateForm: {
                name: '',
                surname: '',
                passwordCurrent: '',
                password: '',
                passwordAgain: '',
                address: '',
                city: '',
                zip_code: '',
                email: ''
            },
            validUpdateForm: {
                name: false,
                surname: false,
                passwordCurrent: false,
                password: false,
                passwordAgain: false,
                address: false,
                city: false,
                zip_code: false,
                email: false
            },
            whatChange: {
                name: false,
                surname: false,
                password: false,
                address: false,
                email: false
            },
            errorTextList: {
                'email': "Email má špatný formát.",
                'emailEmpty': "Email musí být vyplněn.",
                'name': "Jméno musí být vyplněno.",
                'surname': "Příjmení musí být vyplněno.",
                'passwordCurrent': "Stávající heslo musí být vyplněno.",
                'password': "Nové heslo musí být vyplněno.",
                'passwordAgain': "Heslo musí být vyplněno znovu.",
                'passwordSame': "Hesla se neshodují.",
                'address': "Adresa musí být vyplněna",
                'city': "Město musí být vyplněno.",
                'zip_code': "PSČ musí být vyplněno."
            }
        }
    },
    watch: {
        'updateForm.email': {
            handler: function () {
                if(this.updateForm.email === '') {
                    this.validUpdateForm.email = false;
                    this.errorTextList['emailEmpty'] = "Email musí být vyplněn.";
                } else {
                    this.errorTextList['emailEmpty'] = '';
                }

                if(!this.validateEmail()) {
                    this.validUpdateForm.email = false;
                    this.errorTextList['email'] = "Email má špatný formát.";
                } else {
                    this.errorTextList['email'] = '';
                }

                if(this.updateForm.email !== '' && this.validateEmail()) {
                    this.validUpdateForm.email = true;
                }
            }
        },
        'updateForm.name': {
            handler: function () {
                if(this.updateForm.name === '') {
                    this.validUpdateForm.name = false;
                    this.errorTextList['name'] = "Jméno musí být vyplněno.";
                }
                else {
                    this.validUpdateForm.name = true;
                    this.errorTextList['name'] = '';
                }
            }
        },
        'updateForm.surname': {
            handler: function () {
                if(this.updateForm.surname === '') {
                    this.validUpdateForm.surname = false;
                    this.errorTextList['surname'] = "Příjmení musí být vyplněno.";
                }
                else {
                    this.validUpdateForm.surname = true;
                    this.errorTextList['surname'] = '';
                }
            }
        },
        'updateForm.passwordCurrent': {
            handler: function () {
                if(this.updateForm.passwordCurrent === '') {
                    this.validUpdateForm.passwordCurrent = false;
                    this.errorTextList['passwordCurrent'] = "Stávající heslo musí být vyplněno.";
                }
                else {
                    this.validUpdateForm.passwordCurrent = true;
                    this.errorTextList['passwordCurrent'] = '';
                }
            }
        },
        'updateForm.password': {
            handler: function () {
                if(this.updateForm.password === '') {
                    this.validUpdateForm.password = false;
                    this.errorTextList['password'] = "Nové heslo musí být vyplněno.";
                } else {
                    if(this.updateForm.password !== this.updateForm.passwordAgain) {
                        this.validUpdateForm.password = false;
                        this.validUpdateForm.passwordAgain = false;
                        this.errorTextList['passwordSame'] = "Hesla se neshodují";
                    } else {
                        this.errorTextList['passwordSame'] = '';
                    }
                    this.errorTextList['password'] = "";
                }

                if(this.updateForm.password !== '' && this.updateForm.password === this.updateForm.passwordAgain) {
                    this.validUpdateForm.password = true;
                    this.validUpdateForm.passwordAgain = true;
                }
            }
        },
        'updateForm.passwordAgain': {
            handler: function () {
                if(this.updateForm.passwordAgain === '') {
                    this.validUpdateForm.passwordAgain = false;
                    this.errorTextList['passwordAgain'] = "Heslo musí být vyplněno znovu.";
                } else {
                    if(this.updateForm.password !== this.updateForm.passwordAgain) {
                        this.validUpdateForm.password = false;
                        this.validUpdateForm.passwordAgain = false;
                        this.errorTextList['passwordSame'] = "Hesla se neshodují";
                    } else {
                        this.errorTextList['passwordSame'] = '';
                    }
                    this.errorTextList['passwordAgain'] = '';
                }

                if(this.updateForm.passwordAgain !== '' && this.updateForm.password === this.updateForm.passwordAgain) {
                    this.validUpdateForm.password = true;
                    this.validUpdateForm.passwordAgain = true;
                }
            }
        },
        'updateForm.address': {
            handler: function () {
                if(this.updateForm.address === '') {
                    this.validUpdateForm.address = false;
                    this.errorTextList['address'] = "Adresa musí být vyplněna";
                }
                else {
                    this.validUpdateForm.address = true;
                    this.errorTextList['address'] = '';
                }
            }
        },
        'updateForm.city': {
            handler: function () {
                if(this.updateForm.city === '') {
                    this.validUpdateForm.city = false;
                    this.errorTextList['city'] = "Město musí být vyplněno.";
                }
                else {
                    this.validUpdateForm.city = true;
                    this.errorTextList['city'] = '';
                }
            }
        },
        'updateForm.zip_code': {
            handler: function () {
                if(this.updateForm.zip_code === '') {
                    this.validUpdateForm.zip_code = false;
                    this.errorTextList['zip_code'] = "PSČ musí být vyplněno.";
                }
                else {
                    this.validUpdateForm.zip_code = true;
                    this.errorTextList['zip_code'] = '';
                }
            }
        },
    },
    methods: {
        validateEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.updateForm.email);
        },
        showRole(role) {
            if (role === 'customer') {
                return 'Zákazník';
            }
            else if (role === 'employee') {
                return 'Zaměstnanec';
            }
            else if (role === 'manager') {
                return 'Manažer';
            }
            else if (role === 'director') {
                return 'Ředitel';
            }
            else {
                return 'Neznámé';
            }
        },
        async updateName() {
            if(!this.validUpdateForm.name) {
                return;
            }
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/update_user_name', {'userId': this.user.id, 'name': this.updateForm.name}).then((res) => {
                this.user.name  = res.data.name ;
                this.updateForm.name = res.data.name;
                this.whatChange.name = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        async updateSurname() {
            if(!this.validUpdateForm.surname) {
                return;
            }
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/update_user_surname', {'userId': this.user.id, 'surname': this.updateForm.surname}).then((res) => {
                this.user.surname = res.data.surname;
                this.updateForm.surname = '';
                this.whatChange.surname = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        async updatePassword() {
            if(!this.validUpdateForm.passwordCurrent || !this.validUpdateForm.password || this.validUpdateForm.passwordAgain) {
                return;
            }
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/update_user_password',
                {'current_password': this.updateForm.passwordCurrent,
                    'password': this.updateForm.password, 'password_confirmation': this.updateForm.passwordAgain   }).then((res) => {
                this.updateForm.password = '';
                this.updateForm.passwordCurrent = '';
                this.updateForm.passwordAgain = '';
                this.whatChange.password = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        async updateAddress() {
            if(!this.validUpdateForm.address || !this.validUpdateForm.city || !this.validUpdateForm.zip_code) {
                return;
            }
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/update_user_address',
                {'address': this.updateForm.address, 'city': this.updateForm.city, 'zip_code': this.updateForm.zip_code}).then((res) => {
                this.user.address = res.data.address;
                this.user.city = res.data.city;
                this.user.zip_code = res.data.zip_code;
                this.updateForm.address = '';
                this.updateForm.city = '';
                this.updateForm.zip_code = '';
                this.whatChange.address = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        async updateEmail() {
            if(!this.validUpdateForm.email) {
                return;
            }
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/update_user_email', {'email': this.updateForm.email}).then((res) => {
                this.$emit('emitHandler', {isLoading: false});
                this.user.email = res.data.email;
                this.updateForm.email = '';
                this.whatChange.email = false;
            }).catch((error) => {
                console.log(error);
            });
        }
    }
}
</script>
