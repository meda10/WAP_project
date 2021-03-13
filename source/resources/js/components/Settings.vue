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
                <span v-if="user && !whatChange.name">{{ user.name }} <i class="fas fa-pencil-alt edit-icon" @click="whatChange.name = true"></i></span>

                <div class="form-row align-items-center" v-if="whatChange.name">
                    <div class="input-group">
                        <input type="text" class="form-control" id="nameEditForm" :value="user.name">
                        <button class="btn btn-success">Uložit</button>
                        <button class="btn btn-danger" @click="whatChange.name = false">Zrušit</button>
                    </div>
                </div>
            </div>

            <div class="col-sm-1">
                Příjmení:
            </div>
            <div class="col-sm-5">
                <span v-if="user">{{ user.surname }}</span>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-sm-1">
                Změna hesla:
            </div>
            <div class="col-sm-5">
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
        }
    }
}
</script>
