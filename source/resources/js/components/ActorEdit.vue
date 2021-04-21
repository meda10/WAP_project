<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upravit herce</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput class="mb-2" name="jmeno" type="text" label="Jmeno" validation="required"/>
                                <FormulateInput class="mb-2" name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                                <FormulateInput class="mb-2" name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
                                <!--TODO Remove-->
                                <!--        <pre class="code" v-text="formValues"/>-->
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
            this.actor_id = this.$route.params.id;
            this.get_actor_by_id();
        }
    },
    methods: {
        async submitHandler (data) {
            await axios.post('/api/set_actor', data)
                .then(res => {
                    this.$router.push({name: 'actors'});
                })
                .catch(err => {
                    console.log(err.response)
                    if (err.response) {
                        // client received an error response (5xx, 4xx)
                    } else if (err.request) {
                        // client never received a response, or request never left
                    } else {
                        // anything else
                    }
                });
        },
        async get_actor_by_id() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_one_actor/' + this.actor_id).then((res) => {
                this.formValues = res.data.data;
                // console.log(this.formValues);
                this.$emit('emitHandler',  {isLoading: false});
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
