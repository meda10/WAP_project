<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <h2 class="form-title">Upravit obchod</h2>
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
        if (this.$route.params.id != null){
            this.store_id = this.$route.params.id;
            this.get_store_by_id();
        }
    },
    methods: {
        async submitHandler (data) {
            await axios.put("/api/update_store/" + this.store_id, data).catch(error => {
                console.log(error.response)
            });
            await this.$router.push({name: 'stores'});
        },
        get_store_by_id() {
            // this.$emit('emitHandler',  {isLoading: true});

            axios.get('/api/get_one_store/' + this.store_id).then((res) => {
                this.formValues = res.data.data;
                console.log(this.formValues);
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
