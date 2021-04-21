<template>
    <div>
        <h2>Uživatelé</h2>
        <div v-if="can('Add all users')">
            <b-button style="margin-top: 20px; margin-bottom: 20px;" variant="success" size="sm" @click="add_user()" class="mr-2" >Přidat uživatele</b-button>
        </div>
        <div class="table-responsive">
            <b-table :items="users" :fields="fields" striped responsive="sm">
                <template #cell(confirmed)="row">
                    <div v-if="can('Confirm users')">
                        <b-badge v-if="row.item.confirmed" variant="success">Potvrzen</b-badge>
                        <b-button v-else size="sm" @click="confirm_user(row.item.id)" class="mr-2" >Potvrdit</b-button>
                    </div>
                </template>
                <template #cell(actions)="row">
                    <div style="display: flex;">
                        <div v-if="can('View all reservations')">
                            <b-button size="sm" @click="user_reservations(row.item.id)" class="mr-2">
                                Rezervace
                            </b-button>
                        </div>
                        <div v-if="can('Edit all users')">
                            <b-button size="sm" :to="{ name: 'userEdit', params: {id: row.item.id}}" class="mr-2">
                                Upravit
                            </b-button>
                        </div>
                        <div v-if="can('Edit all users')">
                            <b-button variant="danger" size="sm" @click="remove_user(row.item.id)" class="mr-2">
                                Odstranit
                            </b-button>
                        </div>
                    </div>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: [{ key: 'name', label: 'Jméno' },
                { key: 'surname', label: 'Přijmení' },
                { key: 'email', label: 'E-mail' },
                { key: 'role', label: 'Role' },
                { key: 'address', label: 'Adresa' },
                { key: 'zip_code', label: 'PSČ' },
                { key: 'city', label: 'Město' },
                { key: 'confirmed', label: 'Potvrzen'},
                { key: 'actions', label: 'Akce' }],
            users: [],
        }
    },
    mounted() {
        this.get_users();
    },
    methods: {
        async get_users() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_all_users').then((res) => {
                this.users = res.data.data;
                // console.log(this.users);
            }).catch(error => {
                console.log(error.response)
            });
            this.$emit('emitHandler',  {isLoading: false});
        },
        user_reservations($id){
            this.$router.push({name: 'userReservationsId', params: {id: $id}});
        },
        async reset_password($id){
            this.$emit('emitHandler',  {isLoading: true});
            await axios.delete("/api/reset_password/" + $id).catch(error => {
                console.log(error.response)
            });
            this.$emit('emitHandler',  {isLoading: false});
            this.get_users();
        },
        async remove_user($id){
            this.$emit('emitHandler',  {isLoading: true});
            await axios.delete("/api/delete_user/" + $id)
                .then(res => {
                    this.get_users();
                    this.$emit('emitHandler',  {isLoading: false});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        async confirm_user($id){
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post("/api/confirm_user/" + $id)
                .then(res => {
                    this.get_users();
                    this.$emit('emitHandler',  {isLoading: false});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        add_user(){
            this.$router.push({name: 'userAdd'});
        }
    }
}
</script>
