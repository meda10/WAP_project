<template>
    <div>
        <h2>Herci</h2>
        <div v-if="can('Edit all participants')">
            <b-button style="margin-top: 20px; margin-bottom: 20px;" variant="success" size="sm" @click="add_actor()" class="mr-2" >Přidat herce</b-button>
        </div>
        <div class="table-responsive">
            <b-table :items="actors" :fields="fields" striped responsive="sm">
                <template #cell(actions)="row">
                    <div style="display: flex;">
                        <div v-if="can('Edit all participants')">
                            <b-button size="sm" :to="{ name: 'actorEdit', params: {id: row.item.id}}" class="mr-2">
                                Upravit
                            </b-button>
                            <b-button variant="danger" size="sm" @click="remove_actor(row.item.id)" class="mr-2">
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
            fields: [{ key: 'jmeno', label: 'Jméno' },
                { key: 'prijmeni', label: 'Přijmení' },
                { key: 'datum_narozeni', label: 'Datum narození' },
                { key: 'actions', label: 'Akce' }],
            actors: [],
        }
    },
    mounted() {
        this.get_actors();
    },
    methods: {
        async get_actors() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_all_actors').then((res) => {
                this.actors = res.data.data;
                // console.log(this.actors)
                this.$emit('emitHandler',  {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler',  {isLoading: false});
            });
        },
        async remove_actor($id){
            await axios.delete("/api/delete_actor/" + $id)
                .then(res => {
                    this.get_actors();
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        add_actor(){
            this.$router.push({name: 'actorAdd'});
        }
    }
}
</script>
