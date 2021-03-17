<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <h2 class="form-title">Pridat uživatele - NEFUNGUJE</h2>
        <div class="double-wide">
            <FormulateInput name="jmeno" type="text" label="Jmeno" validation="required"/>
            <FormulateInput name="prijmeni" type="text" label="Prijmeni" validation="required"/>
        </div>
        <div class="double-wide">
            <FormulateInput name="email" type="email" label="Email" validation="required|email"/>
            <FormulateInput name="role" type="select" label="Role" placeholder="Vyberte moznost"
                :options="{admin: 'Administrátor', customer: 'Uživatel'}" validation="required|matches:admin,customer"/>
        </div>
        <div class="double-wide">
            <FormulateInput name="password" type="password" label="Heslo" validation="required|min:7,length"/>
            <FormulateInput name="password_confirm" type="password" label="Potvrďte heslo" validation="required|confirm" validation-name="Potvrzení"/>
        </div>
        <FormulateInput name="adresa" type="text" label="Adresa" validation="required"/>
        <div class="double-wide">
            <FormulateInput name="mesto" type="text" label="Město" validation="required"/>
            <FormulateInput name="psc" type="number" label="PSČ" validation="required|number"/>
            <!--            Todo check-->
        </div>
        <FormulateInput type="select" name="obchod" label="Obchod" validation="required" :options='this.stores'/>
        <FormulateInput name="potvrzeni" type="checkbox" label="Potvrdit uživatele"/>
        <FormulateInput type="submit" label="Register"/>
        <!--        todo remove-->
        <pre class="code" v-text="formValues"/>
    </FormulateForm>
</template>


<script>
export default {
    data() {
        return {
            formValues: {},
            stores: [],
        }
    },
    mounted() {
        this.get_stores();
    },
    methods: {
        async submitHandler (data) {
            await axios.post('/api/set_user', data).catch(error => { //todo
                console.log(error.response)
            });
            // alert('Thank you')
            await this.$router.push({name: 'users'}); //todo redirect
        },
        get_stores() {
            axios.get('/api/get_stores_select').then((res) => {
                this.stores = res.data.data;
                console.log(this.stores)
            });
        },


    }
}
</script>

<style scoped>
</style>
