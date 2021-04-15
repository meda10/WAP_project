<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <h2 class="form-title">Přidat obchod</h2>
        <FormulateInput name="adresa" type="text" label="Adresa" validation="required|max:255,length"/>
        <div class="double-wide">
            <FormulateInput name="mesto" type="text" label="Město" validation="required|max:255,length"/>
            <FormulateInput name="psc" type="number" label="PSČ" validation="required|number"/>
        </div>
        <FormulateInput name="popis" type="textarea" label="Popis" validation="required|max:1000,length"/>
        <FormulateInput type="submit" label="Uložit"/>
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
