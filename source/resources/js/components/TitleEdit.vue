<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upravit titul</div>
                <div class="card-body">
                    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput name="titul" type="text" label="Jméno filmu" validation="required"/>
                                <FormulateInput name="rok" type="number" label="Rok vydáni" validation="required|number" min="1800" max="9999"/>
                                <FormulateInput name="zeme_puvodu" type="select" label="Země původu" placeholder="Vyberte zemi" :options="this.states" validation="required"/>
                                <FormulateInput name="typ" type="select" label="Typ" placeholder="Vyberte možnost" :options="{serial: 'Serial', movie: 'Film'}" validation="required|matches:serial,movie"/>
                                <FormulateInput name="cena" type="number" label="Cena" validation="required|number" min="1"/>
                                <FormulateInput name="zanr" type="checkbox" label="Žánr" :options="{'1': 'Akční', '13': 'Dobrodružný', '9': 'Dokumentární', '4': 'Drama', '8': 'Fantasy', '2': 'Komedie', '5': 'Krimi', '11': 'Mysteriózní', '3': 'Romantický', '7': 'Sci-fy', '6': 'Thriller', '12': 'Western', '10': 'Životopisný',}"/>
                                <FormulateInput class="form_textarea" type="textarea" name="popis" label="Popis" validation="required|max:1000,length"/>
                                <FormulateForm id="image" style="margin-top: 15px" name="form_img" @submit="form_img_submit">
                                    <FormulateInput @input="new_image()" name="obrazek" upload-behavior="live" type="image" label="Select an image to upload" :value="obrazek" help="Vyberte soubor jpg." validation="mime:image/jpeg"/>
                                </FormulateForm>
                                <FormulateInput name="herci" type="group" :repeatable="true" label="Herci" add-label="+ Přidat herce" validation="required|min:1">
                                    <FormulateInput type="select" name="herec" label="Jmeno a prijmeni herce" validation="required" :options='this.actors'/>
                                    <FormulateInput class="mb-2" name="reziser" type="checkbox" :options="{ '1': 'Režisér'}"/>
                                </FormulateInput>
                                <FormulateInput name="novy_herec" type="group" :repeatable="true" label="Vytvořit herce" add-label="Vytvořit herce">
                                    <FormulateInput name="jmeno" type="text" label="Jmeno" validation="required"/>
                                    <FormulateInput name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                                    <FormulateInput name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
                                    <FormulateInput class="mb-2" name="reziser" type="checkbox" label="Režisér" :options="{ '1': 'Režisér'}"/>
                                </FormulateInput>
                                <FormulateInput class="mb-2" name="polozka" type="group" :minimum="1" :repeatable="true" label="Přidat kus" add-label="Přidat kus">
                                    <div class="double-wide border-bottom py-2">
                                        <FormulateInput class="mb-2" name="prodejna" type="select" label="Prodejna" :options="stores" validation="required"/>
                                        <FormulateInput class="mb-2" name="jazyk" type="select" label="Jazyk" :options="languages" validation="required"/>
                                        <FormulateInput class="mb-2" name="pocet" type="number" label="Počet" validation="required|number" min="1"/>
                                    </div>
                                </FormulateInput>
                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
                                <!--            :disabled="hasErrors"-->
                                <!--            :disabled="isLoading"-->
                                <!--            :label="isLoading ? 'Ukládání...' : 'Uložit'"-->
                                <!--TODO Remove-->
<!--                                <pre class="code" v-text="formValues"/>-->
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
            obrazek: [{"url":"http:\/\/localhost:8080\/storage\/img\/"+this.$route.params.titleName+".jpg","name": this.$route.params.titleName+".jpg"}],
            actors: [],
            states: [],
            image: {},
            stores: [],
            languages: [],
        }
    },
    mounted() {
        this.get_actors();
        this.get_states();
        this.get_stores();
        this.get_languages();
        if (this.$route.params.titleName != null){
            this.url = this.$route.params.titleName;
            this.get_title_by_url();
        }
    },
    computed: {

    },
    methods: {
        new_image(){
            this.$formulate.submit('form_img');
        },
        form_img_submit(data){
            this.image = data;
        },
        async submitHandler (data) {
            data.obrazek = this.image['obrazek'];
            await axios.put("/api/update_title/" + this.url, data)
                .then(res => {
                    // this.$router.push({path: '/film/' + res.data['url']});
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        async get_actors() {
            await axios.get('/api/get_actors_select')
                .then((res) => {
                    this.actors = res.data.data;
                    // console.log(this.actors);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        async get_states() {
            await axios.get('/api/get_states_select')
                .then((res) => {
                    this.states = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        async get_stores() {
            await axios.get('/api/get_stores_select')
                .then((res) => {
                    this.stores = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        async get_languages() {
            await axios.get('/api/get_languages_select')
                .then((res) => {
                    this.languages = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        async get_title_by_url() {
            this.$emit('emitHandler',  {isLoading: true});
            await axios.get('/api/get_one_title/' + this.url).then((res) => {
                this.formValues = res.data.data;
                console.log(res.data.data);
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
