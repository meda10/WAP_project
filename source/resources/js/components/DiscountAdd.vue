<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h2 class="form-title">Generovat slevové kóody</h2>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="pocet" type="number" label="Počet kódů" validation="required|number|min:1"/>
                    <FormulateInput class="mb-2" name="procenta" type="number" label="Procenta" validation="required|number|min:1|max:100"/>
                </div>
                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Register"/>
                <!--        todo remove-->
<!--                <pre class="code" v-text="formValues"/>-->
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
    methods: {
        async submitHandler (data) {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.post('/api/set_discount', data)
                .then(res => {
                    this.$emit('emitHandler',  {isLoading: false});
                    this.$router.push({name: 'discounts'});
                })
                .catch(error => {
                    console.log(error.response)
                    this.$emit('emitHandler',  {isLoading: false});
                });
        },
    }
}
</script>

<style scoped>
</style>
