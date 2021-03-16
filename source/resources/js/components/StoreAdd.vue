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
            await axios.post('/api/set_store', data).catch(error => {
                console.log(error.response)
            });
            await this.$router.push({name: 'stores'});
        },
    }
}
</script>

<style scoped>
.form {
    padding: 2em;
    border: 1px solid #a8a8a8;
    border-radius: .5em;
    max-width: 500px;
    box-sizing: border-box;
}
.form-title {
    margin-top: 0;
}
.form::v-deep .formulate-input .formulate-input-element {
    max-width: none;
}
@media (min-width: 420px) {
    .double-wide {
        display: flex;
    }
    .double-wide .formulate-input {
        flex-grow: 1;
        width: calc(50% - .5em);
    }
    .double-wide .formulate-input:first-child {
        margin-right: .5em;
    }
    .double-wide .formulate-input:last-child {
        margin-left: .5em;
    }
}
</style>
