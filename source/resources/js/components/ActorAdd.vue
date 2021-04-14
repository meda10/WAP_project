<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h2 class="form-title">Přidat nové herce</h2>
                <FormulateInput name="novy_herec" type="group" :repeatable="true" add-label="Další herec">
                    <div class="border-bottom py-2">
                        <FormulateInput class="mb-2" input-is-valid-class="is-valid" name="jméno"
                                        type="text" label="Jméno" validation="required"/>
                        <FormulateInput class="mb-2" input-is-valid-class="is-valid" name="příjmení" type="text"
                                        label="Příjmení" validation="required"/>
                        <FormulateInput input-is-valid-class="is-valid" name="datum narození" type="date"
                                        label="Datum narození" :validation="'required|before:' + today" min="1800-1-01"/>
                    </div>
                </FormulateInput>

                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
            </div>
        </div>

        <!--TODO Remove-->
<!--        <pre class="code" v-text="formValues"/>-->
    </FormulateForm>
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
            const response = await axios.post('/api/set_actor', data).catch(error => {
                    console.log(error.response)
                });
            if (JSON.parse(response.status) == '200') {
                await this.$router.push({name: 'actors'});
            }
        },
    }
}
</script>
