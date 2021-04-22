<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Přidat nové herce</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput name="novy_herec" type="group" :repeatable="true" add-label="Další herec">
                                    <div class="border-bottom py-2">
                                        <FormulateInput class="mb-2" input-is-valid-class="is-valid" name="jmeno"
                                                        type="text" label="Jméno" validation="required"/>
                                        <FormulateInput class="mb-2" input-is-valid-class="is-valid" name="prijmeni" type="text"
                                                        label="Příjmení" validation="required"/>
                                        <FormulateInput input-is-valid-class="is-valid" name="datum_narozeni" type="date"
                                                        label="Datum narození" :validation="'required|before:' + today" min="1800-1-01"/>
                                    </div>
                                </FormulateInput>
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
    computed: {
        today() {
            return new Date().toLocaleDateString();
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
        }
    }
}
</script>
