<template>
    <div >
        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="addedItem">
            <strong>Položka přidána do košíku!</strong> - {{itemCountAdded}}x {{titleInfo.title_name}} ({{titleDabingId}} dabing)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="closedMessage">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="row mb-2">
            <div class="col">
                <h1>{{ titleInfo.title_name }}</h1>
                <hr style="background-color: white;">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-4">
                <img :src="'/img/movies/' + titleInfo.url+'.jpg'" style="width: 100%;">
            </div>
            <div class="col-8">
                <div class="row mb-1">
                    <div class="col-2 justify-content-right">
                        <h5>Rok:</h5>
                    </div>
                    <div class="col-auto justify-content-left my-auto">
                        <h5>{{ titleInfo.year }}</h5>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-2 justify-content-right">
                        <h5>Cena za kus:</h5>
                    </div>
                    <div class="col-auto justify-content-left my-auto">
                        <h5>{{ titleInfo.price }} Kč</h5>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-2 justify-content-right">
                        <h5>Země původu:</h5>
                    </div>
                    <div class="col-auto justify-content-left my-auto">
                        <h5>{{ titleInfo.states.state_name }}</h5>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-2 justify-content-right">
                        <h5>Žánry</h5>
                    </div>
                    <div class="col-auto justify-content-left my-auto">
                        <h5><span v-for="(genre, key) in titleInfo.genres" :key="genre.url">{{genre.genre_name}} / </span></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <h3>Rezervace</h3>
                        <div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Dabing:</h5>
                            </div>
                            <div class="col-7">
                                <select class="form-select" v-model="titleDabingName">
                                    <option v-for="language in titleInfo.languages" v-bind:value="language.language_name"
                                            v-bind:key="language.language_name">{{language.language}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Kusů:</h5>
                            </div>
                            <div class="col-7">
                                <input type="number" class="form-control" name="optionsRadios" value="1" min="1" :max="maxItemCount" v-model="itemCount">
                            </div>
                        </div>
                        <hr style="background-color: white;">
                        <div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Celkem:</h5>
                            </div>
                            <div class="col-7">
                                {{ itemCount * titleInfo.price }} Kč
                            </div>
                        </div>
                        <button :disabled="itemCount === 0" type="button" class="btn btn-primary" v-on:click="addItemToCart">Přidat do košíku</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Popisek</h3>
                <hr style="background-color: white;">
                <div>{{ titleInfo.description }}</div>
            </div>
        </div>

          <div>
            <date-picker v-model="reservationTime" range :disabled-date="disabledBeforeTodayAndAfterAWeek"></date-picker>
        </div>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';

    export default {
        components: { DatePicker },
        title: '',
        props: ['cartCookiesProps'],
        data() {
            return {
                titleName: '',
                titleType: '',
                titleDabingName: '',
                titleDabingId: '',
                itemCount: 0,
                maxItemCount: 0,
                maxPossibleItemCount: 0,
                titleInfo: {
                    title_name: '',
                    url: '',
                    description: '',
                    price: '',
                    year: '',
                    states: {state_name: ''},
                    languages: []
                },
                addedItem: false,
                itemCountAdded: 0,
                reservationTime: null,
                reservedDays: []
            }
        },
        computed: {
            cartCookies: function () {
                return this.cartCookiesProps;
            }
        },
        watch: {
            $route (to, from) {
                this.getTitleInfo();
            },
            titleInfo: {
                handler: function (titleInfo) {
                    this.$emit('emitHandler', {isLoading: false});
                },
                immediate: true
            },
            itemCount: {
                handler: function (itemCount) {
                    if (itemCount < 0) this.itemCount = 0;
                    if (itemCount > this.maxItemCount) this.itemCount = this.maxItemCount;
                    this.$forceUpdate();
                },
                immediate: true
            },
            titleDabingName: {
                handler: function (titleDabingName) {
                    this.countMaxItemInCart();
                    this.$forceUpdate();
                },
                immediate: true
            },
            addedItem: {
                handler: function (addedItem) {
                    this.$forceUpdate();
                },
                immediate: true
            },
            maxItemCount: {
                handler: function (maxItemCount) {
                    if (maxItemCount > 0) this.itemCount = 1;
                    else this.itemCount = 0;
                    this.$forceUpdate();
                },
                immediate: true
            },
            reservationTime: {
                handler: function (reservationTime) {
                    console.log(reservationTime);
                },
                immediate: true
            }
        },
        mounted() {
            const today = new Date()
            today.setHours(0, 0, 0, 0);
            const tomorrow = new Date(today)
            tomorrow.setDate(tomorrow.getDate() + 1)
            tomorrow.setHours(0, 0, 0, 0);

            this.reservedDays.push(today);
            this.reservedDays.push(tomorrow);


            if (this.$route.params.titleName != null) this.titleName = this.$route.params.titleName;
            if (this.$route.name != null) this.type = this.$route.name;

            this.getTitleInfo();
        },
        methods: {
            disabledBeforeTodayAndAfterAWeek(date) {
                var disabled = false;
                this.reservedDays.forEach(day => {
                    if (new Date(day).getTime() === new Date(date).getTime()) disabled = true;
                });

                return disabled;               
            },
            countMaxItemInCart() {
                this.titleInfo.languages.forEach(lang => {
                    if (lang.language_name === this.titleDabingName)
                        this.maxPossibleItemCount = lang.total;
                });

                var coockieItemCount = 0;

                this.cartCookies.forEach(item => {
                    if (item.language_name === this.titleDabingName && item.url === this.titleInfo.url)
                        coockieItemCount = item.quantity;
                });

                this.maxItemCount = this.maxPossibleItemCount - coockieItemCount;
            },
            getTitleInfo() {
                this.$emit('emitHandler',  {isLoading: true});

                axios.post('/api/get_title', {'type' : this.titleType, 'name': this.titleName}).then((res) => {
                    this.titleInfo = res.data;
                    this.title = this.titleInfo.title_name;
                    this.titleDabingName = this.titleInfo.languages[0].language_name;
                    this.maxItemCount = this.titleInfo.languages[0].total;
                    this.countMaxItemInCart();
                }).catch((error) => {
                    // TODO handle this error
                    console.log(error);
                    this.$emit('emitHandler',  {isLoading: false});
                });
            },
            closedMessage() {
                this.addedItem = false;
            },
            addItemToCart() {
                this.$emit('emitHandler',  {isLoading: true});

                var addItem = true;
                this.cartCookies.forEach(element => {
                    if (element.url === this.titleInfo.url && element.language_name === this.titleDabingName) {
                        this.titleDabingId = element.language;
                        addItem = false;
                        element.quantity = Number(element.quantity) + Number(this.itemCount);
                    }
                });

                if (addItem) {
                    this.titleInfo.languages.forEach(lang => {
                        if (lang.language_name === this.titleDabingName)
                            this.titleDabingId = lang.language;
                    });

                    var item = {
                        name: this.titleInfo.title_name,
                        price: this.titleInfo.price,
                        quantity: this.itemCount,
                        url: this.titleInfo.url,
                        language_name: this.titleDabingName,
                        language: this.titleDabingId,
                        maxItemCount: this.maxPossibleItemCount,
                    };

                    this.cartCookies.push(item);
                }

                this.countMaxItemInCart();
                this.$emit('emitHandler', {cartCookies: this.cartCookies});
                this.addedItem = true;
                this.itemCountAdded = this.itemCount;
                this.itemCount = 1;
            }
        }
    }
</script>
