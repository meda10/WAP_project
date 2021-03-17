<template>
    <FormulateForm
        class="form"
        v-model="formValues"
        @submit="submitHandler"
    >
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h2 class="form-title">Přidat nové herce</h2>
                <FormulateInput
                    name="novy_herec"
                    type="group"
                    :repeatable="true"
                    add-label="Další herec"
                >
                    <div class="border-bottom py-2">
                        <FormulateInput
                            class="mb-2"
                            input-is-valid-class="is-valid"
                            name="jméno"
                            type="text"
                            label="Jméno"
                            validation="required"
                        />
                        <FormulateInput
                            class="mb-2"
                            input-is-valid-class="is-valid"
                            name="příjmení"
                            type="text"
                            label="Příjmení"
                            validation="required"
                        />
                        <FormulateInput
                            input-is-valid-class="is-valid"
                            name="datum narození"
                            type="date"
                            label="Datum narození"
                            :validation="'required|before:' + today"
                            min="1800-1-01"
                        />
                    </div>
                </FormulateInput>

                <FormulateInput
                    input-class="btn btn-success mt-3"
                    type="submit"
                    label="Uložit"
                />
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
            await axios.post('/api/set_actor', data).catch(error => {
                console.log(error.response)
            });
            alert('Thank you')
        },
    }
}
</script>

<!--<style scoped>-->
<!--    .form {-->
<!--        padding: 2em;-->
<!--        border: 1px solid #a8a8a8;-->
<!--        border-radius: .5em;-->
<!--        max-width: 500px;-->
<!--        box-sizing: border-box;-->
<!--    }-->
<!--    .form-title {-->
<!--        margin-top: 0;-->
<!--    }-->
<!--    .form::v-deep .formulate-input .formulate-input-element {-->
<!--        max-width: none;-->
<!--    }-->
<!--    @media (min-width: 420px) {-->
<!--        .double-wide {-->
<!--            display: flex;-->
<!--        }-->
<!--        .double-wide .formulate-input {-->
<!--            flex-grow: 1;-->
<!--            width: calc(50% - .5em);-->
<!--        }-->
<!--        .double-wide .formulate-input:first-child {-->
<!--            margin-right: .5em;-->
<!--        }-->
<!--        .double-wide .formulate-input:last-child {-->
<!--            margin-left: .5em;-->
<!--        }-->
<!--    }-->
<!--</style>-->
