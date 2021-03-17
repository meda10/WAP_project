<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <h2 class="form-title">Herci</h2>
        <FormulateInput name="novy_herec" type="group" :repeatable="true" label="Vytvor verce" add-label="Pridat herce">
            <FormulateInput name="jmeno" type="text" label="Jmeno" validation="required"/>
            <FormulateInput name="prijmeni" type="text" label="Prijmeni" validation="required"/>
            <FormulateInput name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
        </FormulateInput>
        <FormulateInput type="submit" label="Uložit"/>
        <!--TODO Remove-->
        <pre class="code" v-text="formValues"/>
    </FormulateForm>
</template>

<script>
export default {
    data() {
        return {
            formValues: {},
        }
    },
    methods: {
        async submitHandler (data) {
            await axios.post('/api/set_actor', data).catch(error => {
                console.log(error.response)
            });
            alert('Thank you')
            await this.$router.push({name: 'actors'}); //todo redirect to actors
        },
    }
}
</script>

<style scoped>
</style>
