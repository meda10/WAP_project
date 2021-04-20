<template>
    <div>
        <h2>Obchody</h2>
        <div v-if="can('Edit all stores')">
            <b-button variant="success" size="sm" @click="add_store()" class="mr-2" >Přidat obchod</b-button>
        </div>
        <b-table :items="stores" :fields="fields" striped responsive="sm">
            <template #cell(actions)="row">
                <div v-if="can('Edit all stores')">
                    <b-button size="sm" :to="{ name: 'storeEdit', params: {id: row.item.id}}" class="mr-2">
                        Upravit
                    </b-button>
                    <b-button variant="danger" size="sm" @click="remove_store(row.item.id)" class="mr-2">
                        Odstranit
                    </b-button>
                </div>
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
        async get_stores() {
            this.$emit('emitHandler',{isLoading: true});
            await axios.get('/api/get_all_stores').then((res) => {
                this.stores = res.data.data;
                // console.log(this.stores);
                this.$emit('emitHandler',{isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler',{isLoading: false});
            });
        },
        async remove_store($id){
            this.$emit('emitHandler',{isLoading: true});
            await axios.delete("/api/delete_store/" + $id)
                .then(res => {
                    this.get_stores();
                    this.$emit('emitHandler',{isLoading: false});
                })
                .catch(error => {
                    console.log(error.response)
                    this.$emit('emitHandler',{isLoading: false});
                });
        },
        add_store(){
            this.$router.push({name: 'storeAdd'});
        }
    }
}
</script>
