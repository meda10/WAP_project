<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pridat uživatele</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput class="mb-2" name="jmeno" type="text" label="Jmeno" validation="required"/>
                                <FormulateInput class="mb-2" name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                                <FormulateInput class="mb-2" name="email" type="email" label="Email" validation="required|email"/>
                                <FormulateInput class="mb-2" name="role" type="select" label="Role" placeholder="Vyberte moznost"
                                                :options="{director: 'Ředitel', customer: 'Uživatel', employee: 'Zaměstnanec', manager: 'Manager'}"
                                                    validation="required|matches:director,customer,manager,employee"/>
                                <FormulateInput class="mb-2" name="password" type="password" label="Heslo" validation="required|min:8,length" autocomplete="new-password"/>
                                <FormulateInput class="mb-2" name="password_confirm" type="password" label="Potvrďte heslo" validation="required|confirm" validation-name="Potvrzení"/>
                                <FormulateInput class="mb-2" name="adresa" type="text" label="Adresa" validation="required"/>
                                <FormulateInput class="mb-2" name="mesto" type="text" label="Město" validation="required"/>
                                <FormulateInput class="mb-2" name="psc" type="number" label="PSČ" validation="required|number|min:5,length|max:5,length"/>
                                <FormulateInput class="mb-2" type="select" name="obchod" label="Obchod" validation="required" :options='this.stores'/>
                                <FormulateInput class="mb-2" name="potvrzeni" type="checkbox" label="Potvrdit uživatele" validation="required"/>
                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Register"/>
                                <!--        todo remove-->
                                <!--                        <pre class="code" v-text="formValues"/>-->
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
            stores: [],
        }
    },
    mounted() {
        this.get_stores();
    },
    methods: {
        async submitHandler (data) {
            await axios.post('/api/set_user', data)
                .then(res => {
                    this.$router.push({name: 'users'});
                })
                .catch(error => {
                    console.log(error.response)
                });
        },
        async get_stores() {
            await axios.get('/api/get_stores_select')
                .then(res => {
                    this.stores = res.data.data;
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
    }
}
</script>

<style scoped>
</style>
