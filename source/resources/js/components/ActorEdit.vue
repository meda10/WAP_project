<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <h2 class="form-title">Upravit herce</h2>
        <FormulateInput name="jmeno" type="text" label="Jmeno" validation="required"/>
        <FormulateInput name="prijmeni" type="text" label="Prijmeni" validation="required"/>
        <FormulateInput name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
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
    mounted() {
        if (this.$route.params.id != null){
            this.actor_id = this.$route.params.id;
            this.get_actor_by_id();
        }
    },
    methods: {
        async submitHandler (data) {
            await axios.put('/api/update_actor/' + this.actor_id, data).catch(error => {
                console.log(error.response)
            });
            // alert('Thank you')
            await this.$router.push({name: 'actors'}); //todo redirect to actors
        },
        get_actor_by_id() {
            // this.$emit('emitHandler',  {isLoading: true});

            axios.get('/api/get_actor/' + this.actor_id).then((res) => {
                this.formValues = res.data.data;
                console.log(this.formValues);
            }).catch((error) => {
                // TODO handle this error
                console.log(error);
                this.$emit('emitHandler',  {isLoading: false});
            });
        }
    }
}
</script>

<style scoped>
</style>
