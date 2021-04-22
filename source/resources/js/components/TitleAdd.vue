<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <!--        #default="{ hasErrors }"-->
                <!--        #default="{isLoading}"-->
                <h2 class="form-title">Tituly</h2>
                <FormulateInput class="mb-2" name="titul" type="text" label="Jmeno filmu" validation="required"/>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="rok" type="number" label="Rok vydani" validation="required|number" min="1800" max="9999"/>
                    <FormulateInput class="mb-2" name="zeme_puvodu" type="select" label="Země původu" placeholder="Vyberte zemi" :options="this.states" validation="required"/>
                </div>
                <div class="double-wide">
                    <FormulateInput class="mb-2" name="typ" type="select" label="Typ" placeholder="Vyberte moznost" :options="{serial: 'Serial', movie: 'Film'}" validation="required|matches:serial,movie"/>
                    <FormulateInput class="mb-2" name="cena" type="number" label="Cena" validation="required|number" min="1"/>
                </div>
<!--                <FormulateInput class="mb-2" name="zanr" type="checkbox" label="Žánr" :options="genres"/>-->
                <span style="font-size: 0.875rem">Žánry<br></span>
                    <span v-for="(g, key) in genresSelected" :key="key">
                        <span class="genre-tag" v-if="key !== ''">{{ g }} <i class="fas fa-times pl-1" @click="removeGenre(key)"></i></span><span v-if="key !== ''">, </span>
                    </span>
                <FormulateInput class="mb-2 mt-2" name="zanrSelect" type="select" :options="genres"
                                placeholder="Vyber žánr" @change="selectGenre($event)"
                                :error="Object.keys(genresSelected).length === 0 ? 'Vyberte alespoň jeden žánr.' : null"/>

                <FormulateInput class="mb-2" type="textarea" name="popis" label="Popis" validation="required|max:1000,length"/>
                <FormulateInput class="mb-2" name="obrazek" type="image" label="Select an image to upload" help="Vyberte soubor png nebo jpg." validation="mime:image/jpeg,image/png"/>

                <hr class="white-hr pt-1">

                <FormulateInput class="mb-2" name="herci" type="group" :repeatable="true" label="Herci" add-label="+ Pridat herce" validation="required|min:1">
                    <div class="border-bottom py-2">
                        <FormulateInput class="mb-2" type="select" name="herec" label="Jmeno a prijmeni herce" validation="required" :options='this.actors'/>
                    </div>
                </FormulateInput>

                <hr class="white-hr pt-1">

                <FormulateInput class="mb-2" name="novy_herec" type="group" :minimum="0" :repeatable="true" label="Vytvor herce" add-label="Vytvorit herce">
                    <div class="double-wide border-bottom py-2">
                        <FormulateInput class="mb-2" name="jmeno" type="text" label="Jmeno" validation="required"/>
                        <FormulateInput class="mb-2" name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                        <FormulateInput class="mb-2" name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
                    </div>
                </FormulateInput>
                <FormulateInput input-class="btn btn-success mt-3" type="submit" label="Uložit"/>
                <!--            :disabled="hasErrors"-->
                <!--            :disabled="isLoading"-->
                <!--            :label="isLoading ? 'Ukládání...' : 'Uložit'"-->
                <!--TODO Remove-->
                <pre class="code" v-text="formValues"/>
            </div>
        </div>
    </FormulateForm>
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
        }
    },
    mounted() {
        this.get_actors();
        this.get_states();
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
        },
        async submitHandler (data) {
            let genData = [];
            for (const prop in this.genresSelected) {
                genData.push(prop);
            }
            if (genData.length < 1) {
                console.log(chyba);
            }
            data["zanr"] = this.genData;
            await axios.post('/api/set_titles', data).catch(error => {
                console.log(error.response)
            });
            await this.$router.push({path: '/film/'}); //todo redirect to current film
        },
        get_actors() {
            axios.get('/api/get_actors_select').then((res) => {
                this.actors = res.data.data;
                // console.log(this.actors);
            });
        },
        get_states() {
            axios.get('/api/get_states_select').then((res) => {
                this.states = res.data.data;
                // console.log(this.states);
            });
        },
    }
}
</script>
<style scoped>
</style>
