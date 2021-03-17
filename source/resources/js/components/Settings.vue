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
                <span v-if="user">{{ user.email }}</span>
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
                        <input type="text" class="form-control" id="nameEditForm" v-model="updateForm.name">
                        <button class="btn btn-success" @click="updateName">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.name = false;
                                updateForm.name = ''">Zrušit</button>
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
                        <input type="text" class="form-control" id="surnameEditForm" v-model="updateForm.surname">
                        <button class="btn btn-success" @click="updateSurname">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.surname = false;
                                updateForm.surname = ''">Zrušit</button>
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
                        <input type="password" class="form-control  mb-2" id="passwordEditForm" v-model="updateForm.passwordCurrent" autocomplete="new-password">

                        <label for="newPasswordEditForm">Nové heslo:</label>
                        <input type="password" class="form-control  mb-2" id="newPasswordEditForm" v-model="updateForm.password" autocomplete="new-password">

                        <label for="newPasswordAgainEditForm">Nové heslo znovu:</label>
                        <input type="password" class="form-control mb-2" id="newPasswordAgainEditForm" v-model="updateForm.passwordAgain" autocomplete="new-password">

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
                                                   updateForm.city = user.city; updateForm.zipCode = user.zip_code;"></i>
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
                    <input type="text" class="form-control" id="addressAddr" v-model="updateForm.address">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label" for="addressCity">Město:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="addressCity" v-model="updateForm.city">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label" for="addressZipCode">PSČ:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="addressZipCode" v-model="updateForm.zipCode">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1">
                </div>
                <div class="col-sm-5">
                    <button class="btn btn-success" @click="updateAddress">Uložit</button>
                    <button class="btn btn-danger" @click="whatChange.address = false;
                                updateForm.address = ''; updateForm.city = ''; updateForm.zipCode = '';">Zrušit</button>
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
                zipCode: ''
            },
            whatChange: {
                name: false,
                surname: false,
                password: false,
                address: false
            }
        }
    },
    methods: {
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
        updateName() {
            // TODO: values validation
            console.log("menim NAME");
            this.$emit('emitHandler',  {isLoading: true});

            axios.post('/api/update_user_name', {'userId': this.user.id, 'name': this.updateForm.name}).then((res) => {
                // console.log(res);
                this.updateForm.name = '';
                this.whatChange.name = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        updateSurname() {
            // TODO: values validation
            console.log("menim SURNAME");
            this.$emit('emitHandler',  {isLoading: true});

            axios.post('/api/update_user_surname', {'userId': this.user.id, 'surname': this.updateForm.surname}).then((res) => {
                // console.log(res);
                this.updateForm.surname = '';
                this.whatChange.surname = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        updatePassword() {
            // TODO: values validation
            console.log("menim PASSWORD");
            this.$emit('emitHandler',  {isLoading: true});

            axios.post('/api/update_user_password',
                {'current_password': this.updateForm.passwordCurrent, 'newPassword': this.updateForm.password}).then((res) => {
                // console.log(res);
                this.updateForm.password = '';
                this.updateForm.passwordCurrent = '';
                this.updateForm.passwordAgain = '';
                this.whatChange.password = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        },
        updateAddress() {
            // TODO: values validation
            console.log("menim ADDRESS");
            this.$emit('emitHandler',  {isLoading: true});
            
            axios.post('/api/update_user_address',
                {'address': this.updateForm.address, 'city': this.updateForm.city, 'zip_code': this.updateForm.zipCode}).then((res) => {
                this.updateForm.address = '';
                this.updateForm.city = '';
                this.updateForm.zipCode = '';
                this.whatChange.address = false;
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                console.log(error);
            });
        }
    }
}
</script>
