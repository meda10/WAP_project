<template>
    <div>
        <h2>Slevové kódy</h2>
        <div v-if="can('Edit all discounts')">
            <b-button style="margin-top: 20px; margin-bottom: 20px;" variant="success" size="sm" @click="add_discount()" class="mr-2" >Vytvořit slevové kódy</b-button>
        </div>
        <div class="table-responsive">
            <b-table :items="discounts" :fields="fields" striped responsive="sm">
                <template #cell(percent)="row">
                    {{row.item.percent}}%
                </template>
                <template #cell(actions)="row">
                    <div v-if="can('Edit all discounts')">
                        <b-button variant="danger" size="sm" @click="remove_discount(row.item.id)" class="mr-2">
                            Odstranit
                        </b-button>
                    </div>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            fields: [
                { key: 'code', label: 'Kód' },
                { key: 'percent', label: 'Sleva' },
                { key: 'actions', label: 'Akce' }],
            discounts: [],
        }
    },
    mounted() {
        this.get_discounts();
    },
    methods: {
        async get_discounts() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_all_discounts').then((res) => {
                this.discounts = res.data.data;
                // console.log(this.discounts);
            }).catch(error => {
                console.log(error.response)
            });
            this.$emit('emitHandler',  {isLoading: false});
        },
        async remove_discount($id){
            this.$emit('emitHandler',  {isLoading: true});
            await axios.delete("/api/delete_discount/" + $id)
                .then(res => {
                    this.get_discounts();
                    this.$emit('emitHandler',  {isLoading: false});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        add_discount(){
            this.$router.push({name: 'discountAdd'});
        }
    }
}
</script>

<style scoped>

</style>
