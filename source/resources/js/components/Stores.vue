<template>
    <div>
        <h2>Obchody</h2>
        <b-button variant="success" size="sm" @click="add_store()" class="mr-2" >Přidat obchod</b-button>
        <b-table :items="stores" :fields="fields" striped responsive="sm">
            <template #cell(actions)="row">
                <b-button size="sm" :to="{ name: 'storeEdit', params: {id: row.item.id}}" class="mr-2">
                    Upravit
                </b-button>
                <b-button variant="danger" size="sm" @click="remove_store(row.item.id)" class="mr-2">
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
            fields: [{ key: 'adresa', label: 'Adresa' },
                { key: 'psc', label: 'PSČ' },
                { key: 'mesto', label: 'Město' },
                { key: 'popis', label: 'Popis' },
                { key: 'actions', label: 'Akce' }],
            stores: [],
        }
    },
    mounted() {
        this.get_stores();
    },
    methods: {
        get_stores() {
            this.$emit('emitHandler',  {isLoading: true});
            axios.get('/api/get_all_stores').then((res) => {
                this.stores = res.data.data;
                // console.log(this.stores);
                this.$emit('emitHandler',  {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler',  {isLoading: false});
            });
        },
        async remove_store($id){
            const response = await axios.delete("/api/delete_store/" + $id).catch(error => {
                console.log(error.response)
            });
            if (JSON.parse(response.status) == '200') {
                this.get_stores();
            }
        },
        add_store(){
            this.$router.push({name: 'storeAdd'});
        }
    }
}
</script>
