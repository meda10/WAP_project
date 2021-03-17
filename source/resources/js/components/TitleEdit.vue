<template>
    <FormulateForm class="form" v-model="formValues" @submit="submitHandler">
        <!--        #default="{ hasErrors }"-->
        <!--        #default="{isLoading}"-->
        <h2 class="form-title">Upravit titul</h2>
        <FormulateInput name="titul" type="text" label="Jmeno filmu" validation="required"/>
        <div class="double-wide">
            <FormulateInput name="rok" type="number" label="Rok vydani" validation="required|number" min="1800" max="9999"/>
            <FormulateInput name="zeme_puvodu" type="select" label="Země původu" placeholder="Vyberte zemi" :options="this.states" validation="required"/>
        </div>
        <div class="double-wide">
            <FormulateInput name="typ" type="select" label="Typ" placeholder="Vyberte moznost" :options="{serial: 'Serial', movie: 'Film'}" validation="required|matches:serial,movie"/>
            <FormulateInput name="cena" type="number" label="Cena" validation="required|number" min="1"/>
        </div>
        <FormulateInput name="zanr" type="checkbox" label="Žánr" :options="{'1': 'Akční', '13': 'Dobrodružný', '9': 'Dokumentární', '4': 'Drama', '8': 'Fantasy', '2': 'Komedie', '5': 'Krimi', '11': 'Mysteriózní', '3': 'Romantický', '7': 'Sci-fy', '6': 'Thriller', '12': 'Western', '10': 'Životopisný',}"/>
        <FormulateInput type="textarea" name="popis" label="Popis" validation="required|max:1000,length"/>
        <FormulateInput name="obrazek" type="image" label="Select an image to upload" help="Vyberte soubor png nebo jpg." validation="mime:image/jpeg,image/png"/>
        <FormulateInput name="herci" type="group" :repeatable="true" label="Herci" add-label="+ Pridat herce" validation="required|min:1">
            <FormulateInput type="select" name="herec" label="Jmeno a prijmeni herce" validation="required" :options='this.actors'/>
        </FormulateInput>
        <FormulateInput name="novy_herec" type="group" :repeatable="true" label="Vytvor verce" add-label="Vytvorit herce">
            <div class="double-wide">
                <FormulateInput name="jmeno" type="text" label="Jmeno" validation="required"/>
                <FormulateInput name="prijmeni" type="text" label="Prijmeni" validation="required"/>
                <FormulateInput name="datum_narozeni" type="date" label="Datum narozeni" validation="required" min="1800-1-01"/>
            </div>
        </FormulateInput>
        <FormulateInput type="submit" label="Uložit"/>
        <!--            :disabled="hasErrors"-->
        <!--            :disabled="isLoading"-->
        <!--            :label="isLoading ? 'Ukládání...' : 'Uložit'"-->
        <!--TODO Remove-->
        <pre class="code" v-text="formValues"/>
    </FormulateForm>
</template>


<script>
export default {
    data() {
        return {
            formValues: {},
            actors: [],
            states: [],
        }
    },
    mounted() {
        this.get_actors();
        this.get_states();
        if (this.$route.params.id != null){
            this.title_id = this.$route.params.id;
            this.get_title_by_id();
        }
    },
    computed: {

    },
    methods: {
        async submitHandler (data) {
            await axios.put("/api/update_title/" + this.title_id, data).catch(error => {
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
        get_title_by_id() {
            // this.$emit('emitHandler',  {isLoading: true});

            axios.get('/api/get_one_title/' + this.title_id).then((res) => {
                this.formValues = res.data.data[0];
                console.log(this.formValues);
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
