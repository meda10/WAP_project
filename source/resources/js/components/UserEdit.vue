<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <h2 class="form-title">Upravit uživatele</h2>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="jmeno" type="text" label="Jmeno" validation="required"/>
                    <FormulateInput class="mb-2" name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                </div>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="email" type="email" label="Email" validation="required|email"/>
                    <FormulateInput class="mb-2" name="role" type="select" label="Role" placeholder="Vyberte moznost"
                                    :options="{admin: 'Administrátor', customer: 'Zákazník'}" validation="required|matches:admin,customer"/>
                </div>
        <!--        <div class="double-wide">-->
        <!--            <FormulateInput name="password" type="password" label="Heslo" validation="required|min:7,length"/>-->
        <!--            <FormulateInput name="password_confirm" type="password" label="Potvrďte heslo" validation="required|confirm" validation-name="Potvrzení"/>-->
        <!--        </div>-->
                <FormulateInput class="mb-2" name="adresa" type="text" label="Adresa" validation="required"/>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="mesto" type="text" label="Město" validation="required"/>
                    <FormulateInput class="mb-2" name="psc" type="number" label="PSČ" validation="required|number"/>
                    <!--            Todo check-->
                </div>
                <FormulateInput class="mb-2" type="select" name="obchod" label="Obchod" validation="required" :options='this.stores'/>
                <FormulateInput class="mb-2" name="potvrzeni" type="checkbox" label="Potvrdit uživatele"/>
                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit změny"/>
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
            stores: [],
        }
    },
    mounted() {
        this.get_stores();
        if (this.$route.params.id != null){
            this.user_id = this.$route.params.id;
            this.get_user_by_id();
        }
    },
    methods: {
        async submitHandler (data) {
            this.$emit('emitHandler',  {isLoading: true});
            const response = await axios.put("/api/update_user/" + this.user_id, data).catch(error => {
                    console.log(error.response)
                });
            if (JSON.parse(response.status) == '200') {
                await this.$router.push({name: 'users'});
            }
        },
        get_stores() {
            axios.get('/api/get_stores_select').then((res) => {
                this.stores = res.data.data;
                // console.log(this.stores)
            });
        },
        get_user_by_id() {
            this.$emit('emitHandler',  {isLoading: true});
            axios.get('/api/get_one_user/' + this.user_id).then((res) => {
                this.formValues = res.data.data;
                // console.log(this.formValues);
                this.$emit('emitHandler',  {isLoading: false});
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
