<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h2 class="form-title">Přidat obchod</h2>
                <FormulateInput class="mb-2" name="adresa" type="text" label="Adresa" validation="required|max:255,length"/>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="mesto" type="text" label="Město" validation="required|max:255,length"/>
                    <FormulateInput class="mb-2" name="psc" type="number" label="PSČ" validation="required|number"/>
                </div>
                <FormulateInput class="mb-2" name="popis" type="textarea" label="Popis" validation="required|max:1000,length"/>
                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
            </div>
        </div>
    </FormulateForm>
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
            await axios.post('/api/set_store', data).catch(error => {
                console.log(error.response)
            });
            await this.$router.push({name: 'stores'});
        },
    }
}
</script>

<style scoped>
</style>
