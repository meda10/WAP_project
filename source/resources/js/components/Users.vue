<template>
    <div>
        <h2>Uživatelé</h2>
        <b-button variant="success" size="sm" @click="add_user()" class="mr-2" >Přidat uživatele</b-button>
        <b-table :items="users" :fields="fields" striped responsive="sm">
            <template #cell(confirmed)="row">
                <b-badge v-if="row.item.confirmed" variant="success">Potvrzen</b-badge>
                <b-button v-else size="sm" @click="confirm_user(row.item.id)" class="mr-2" >Potvrdit</b-button>
            </template>
            <template #cell(actions)="row">
                <b-button size="sm" :to="{ name: 'userEdit', params: {id: row.item.id}}" class="mr-2">
                    Upravit
                </b-button>
                <b-button variant="danger" size="sm" @click="remove_user(row.item.id)" class="mr-2">
                    Odstranit
                </b-button>
            </template>
        </b-table>
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
        get_users() {
            axios.get('/api/get_users').then((res) => {
                this.users = res.data.data;
                console.log(this.users);
            });
        },
        async remove_user($id){
            await axios.delete("/api/delete_user/" + $id).catch(error => {
                console.log(error.response)
            });
            this.get_users();
        },
        async confirm_user($id){
            await axios.post("/api/confirm_user/" + $id).catch(error => {
                console.log(error.response)
            });
            this.get_users();
        },
        async add_user(){
            await this.$router.push({name: 'userAdd'});
        }
    }
}
</script>
