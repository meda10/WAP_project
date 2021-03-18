<template>
    <div>
        <h2>Herci</h2>
        <b-button variant="success" size="sm" @click="add_actor()" class="mr-2" >Přidat herce</b-button>
        <b-table :items="actors" :fields="fields" striped responsive="sm">
            <template #cell(actions)="row">
                <b-button size="sm" :to="{ name: 'actorEdit', params: {id: row.item.id}}" class="mr-2">
                    Upravit
                </b-button>
                <b-button variant="danger" size="sm" @click="remove_actor(row.item.id)" class="mr-2">
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
        get_actors() {
            this.$emit('emitHandler',  {isLoading: true});

            axios.get('/api/get_all_actors').then((res) => {
                this.actors = res.data.data;
                console.log(this.actors)
                this.$emit('emitHandler',  {isLoading: false});
            });
        },
        async remove_actor($id){
            await axios.delete("/api/delete_actor/" + $id).catch(error => {
                console.log(error.response)
            });
            this.get_actors();
        },
        async add_actor(){
            await this.$router.push({name: 'actorAdd'});
        }
    }
}
</script>
