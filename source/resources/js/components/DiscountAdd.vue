<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Generovat slevové kóody</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <FormulateInput class="mb-2" name="pocet" type="number" label="Počet kódů" validation="required|number|min:1"/>
                                <FormulateInput class="mb-2" name="procenta" type="number" label="Procenta" validation="required|number|min:1|max:100"/>
                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Register"/>
                                <!--        todo remove-->
                                <!--                <pre class="code" v-text="formValues"/>-->
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
