<template>
    <div class=" row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Přidat Tituly</div>
                <div class="card-body">
                    <FormulateForm class="form" @submit="submitHandler">
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <FormulateInput class="mb-2" name="titul" type="text" label="Jméno filmu" validation="required"/>
                                <FormulateInput class="mb-2" name="rok" type="number" label="Rok vydáni" validation="required|number" min="1901" max="9999"/>
                                <FormulateInput class="mb-2" name="zeme_puvodu" type="select" label="Země původu" placeholder="Vyberte zemi" :options="this.states" validation="required"/>
                                <FormulateInput class="mb-2" name="typ" type="select" label="Typ" placeholder="Vyberte moznost" :options="{serial: 'Serial', movie: 'Film'}" validation="required|matches:serial,movie"/>
                                <FormulateInput class="mb-2" name="cena" type="number" label="Cena" validation="required|number" min="1"/>
                                <!--                <FormulateInput class="mb-2" name="zanr" type="checkbox" label="Žánr" :options="genres"/>-->
                                <span style="font-size: 0.875rem">Žánry<br></span>
                                <span v-for="(g, key) in genresSelected" :key="key">
                                    <span class="genre-tag" v-if="key !== ''">{{ g }} <i class="fas fa-times pl-1" @click="removeGenre(key)"></i></span>
                                    <span v-if="key !== ''">, </span>
                                </span>
                                <FormulateInput class="mb-2 mt-2" name="zanrSelect" type="select" :options="genres"
                                                placeholder="Vyber žánr" @change="selectGenre($event)"
                                                :error="Object.keys(genresSelected).length === 0 ? 'Vyberte alespoň jeden žánr.' : null"/>

                                <FormulateInput class="mb-2" type="textarea" name="popis" label="Popis" validation="required|max:10000,length"/>
                                <FormulateInput class="mb-2" name="obrazek" type="image" upload-behavior="live" label="Vyberte obrázek" help="Vyberte soubor png nebo jpg." validation="required|mime:image/jpeg,image/png"/>

                                <hr class="white-hr pt-1">

                                <FormulateInput class="mb-2" name="herci" type="group" :repeatable="true" label="Herci" add-label="+ Přidat herce" validation="required|min:1">
                                    <div class="border-bottom py-2">
                                        <FormulateInput class="mb-2" type="select" name="herec" label="Jméno a přijmení herce" validation="required" :options='this.actors'/>
                                        <FormulateInput class="mb-2" name="reziser" type="checkbox" label="Režisér" :options="{ director: 'Režisér'}"/>
                                    </div>
                                </FormulateInput>

                                <hr class="white-hr pt-1">

                                <FormulateInput class="mb-2" name="novy_herec" type="group" :minimum="0" :repeatable="true" label="Vytvoř herce" add-label="Vytvorit herce">
                                    <div class="double-wide border-bottom py-2">
                                        <FormulateInput class="mb-2" name="jmeno" type="text" label="Jméno" validation="required"/>
                                        <FormulateInput class="mb-2" name="prijmeni" type="text" label="Přijmeni" validation="required"/>
                                        <FormulateInput class="mb-2" name="datum_narozeni" type="date" label="Datum narození" validation="required" min="1900-1-01"/>
                                        <FormulateInput class="mb-2" name="reziser" type="checkbox" label="Režisér" :options="{ director: 'Režisér'}"/>
                                    </div>
                                </FormulateInput>

                                <FormulateInput class="mb-2" name="polozka" type="group" :minimum="1" :repeatable="true" label="Přidat kus" add-label="Přidat kus">
                                    <div class="double-wide border-bottom py-2">
                                        <FormulateInput class="mb-2" name="prodejna" type="select" label="Prodejna" :options="stores" validation="required"/>
                                        <FormulateInput class="mb-2" name="jazyk" type="select" label="Jazyk" :options="languages" validation="required"/>
                                        <FormulateInput class="mb-2" name="pocet" type="number" label="Počet" validation="required|number" min="1"/>
                                    </div>
                                </FormulateInput>

                                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
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
<!-- //todo add director -->
<!-- //todo add items -->
<!-- //todo add fix genres -->

<script>
export default {
    data() {
        return {
            genres: {'1': 'Akční', '13': 'Dobrodružný', '9': 'Dokumentární',
                '4': 'Drama', '8': 'Fantasy', '2': 'Komedie', '5': 'Krimi',
                '11': 'Mysteriózní', '3': 'Romantický', '7': 'Sci-fy', '6': 'Thriller', '12': 'Western', '10': 'Životopisný'},
            genresSelected: {},
            formValues: {
                novy_herec: [{}]
            },
            actors: [],
            states: [],
            stores: [],
            languages: [],
        }
    },
    mounted() {
        this.get_actors();
        this.get_states();
        this.get_stores();
        this.get_languages();
        this.formValues.novy_herec = [];
        // this.$forceUpdate();
    },
    methods: {
        removeGenre(key) {
            // console.log(key, this.genresSelected[key]);
            this.genres[key] = this.genresSelected[key];
            delete this.genresSelected[key];
            // console.log("genres:", this.genres);
            // console.log("genresSelected:",this.genresSelected);
            // console.log(this.genresSelected['']);
            this.$forceUpdate();
        },
        selectGenre(event) {
            let i = event.target.value
            this.genresSelected[i] = this.genres[i];
            delete this.genres[i];
            delete this.genresSelected[''];
            // console.log("genres:", this.genres);
            // console.log("genresSelected:",this.genresSelected);
            this.$forceUpdate();
        },
        async submitHandler (data) {
            let genData = [];
            for (const prop in this.genresSelected) {
                genData.push(prop);
            }
            // if (genData.length < 1) {
            //     console.log("chyba"); //todo chyba
            // }
            // console.log(genData);
            data["zanr"] = genData;
            // console.log(data["zanr"]);
            // data["zanr"] = ["1","4"]; //todo Genre does not work
            await axios.post('/api/set_titles', data)
                .then(res => {
                    this.$router.push({path: '/film/' + res.data['url']});
                })
                .catch(err => {
                    alert(err.message);
                    console.log(err.response)
                });
        },
        get_actors() {
            axios.get('/api/get_actors_select')
                .then((res) => {
                    this.actors = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        get_states() {
            axios.get('/api/get_states_select')
                .then((res) => {
                    this.states = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        get_stores() {
            axios.get('/api/get_stores_select')
                .then((res) => {
                    this.stores = res.data.data;
                    // console.log(this.states);
                })
                .catch(err => {
                    console.log(err.response)
                });
        },
        get_languages() {
            axios.get('/api/get_languages_select')
                .then((res) => {
                    this.languages = res.data.data;
                    // console.log(this.states);
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
