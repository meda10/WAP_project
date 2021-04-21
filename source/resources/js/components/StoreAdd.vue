<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Přidat obchod</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
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

    },
    methods: {
        async submitHandler (data) {
            await axios.post('/api/set_store', data)
                .then(res => {
                    this.$router.push({name: 'stores'});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
    }
}
</script>

<style scoped>
</style>
