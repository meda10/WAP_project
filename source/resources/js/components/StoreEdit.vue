<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upravit obchod</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput class="mb-2" name="adresa" type="text" label="Adresa" validation="required|max:255,length"/>
                                <FormulateInput class="mb-2" name="mesto" type="text" label="Město" validation="required|max:255,length"/>
                                <FormulateInput class="mb-2" name="psc" type="number" label="PSČ" validation="required|number"/>
                                <FormulateInput class="mb-2" name="popis" type="textarea" label="Popis" validation="required|max:1000,length"/>
                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
                            </div>
                        </div>
                    </FormulateForm>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            formValues: {},
        }
    },
    mounted() {
        if (this.$route.params.id != null){
            this.store_id = this.$route.params.id;
            this.get_store_by_id();
        }
    },
    methods: {
        async submitHandler (data) {
            await axios.put("/api/update_store/" + this.store_id, data)
                .then(res => {
                    this.$router.push({name: 'stores'});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        async get_store_by_id() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_one_store/' + this.store_id).then((res) => {
                this.formValues = res.data.data;
                // console.log(this.formValues);
                this.$emit('emitHandler',  {isLoading: false});
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler',  {isLoading: false});
            });
        },
    }
}
</script>

<style scoped>
</style>
