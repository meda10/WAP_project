<template>
    <FormulateForm
        class="form"
        v-model="formValues"
        @submit="submitHandler"
    >
        <h2 class="form-title">Upravit herce</h2>
        <FormulateInput
            name="jmeno"
            type="text"
            label="Jmeno"
            validation="required"
        />
        <FormulateInput
            name="prijmeni"
            type="text"
            label="Prijmeni"
            validation="required"
        />
        <FormulateInput
            name="datum_narozeni"
            type="date"
            label="Datum narozeni"
            validation="required"
            min="1800-1-01"
        />
        <FormulateInput
            type="submit"
            label="Uložit"
        />

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
            alert('Thank you')
        },
        get_actor_by_id() {
            // this.$emit('emitHandler',  {isLoading: true});

            axios.post('/api/get_actor_by_id', {'id' : this.actor_id }).then((res) => {
                this.formValues = res.data.data[0];
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
