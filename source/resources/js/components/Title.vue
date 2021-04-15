<template>
    <div v-if="titleInfo.title_name !== ''">
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
                <img :src="'/storage/img/' + titleInfo.url+'.jpg'" style="width: 100%;">
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
                        <h5 v-if="titleInfo.states !== undefined">
                            {{ titleInfo.states.state_name }}
                        </h5>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-2 justify-content-right">
                        <h5>Žánry</h5>
                    </div>
                    <div class="col-auto justify-content-left my-auto">
                        <h5><span v-for="(genre, idx) in titleInfo.genres" :key="genre.url">
                            {{genre.genre_name}}
                            <span v-if="idx + 1 !== titleInfo.genres.length">/ </span>
                        </span></h5>
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
                                <select class="form-select title-reservation-item" v-model="titleDabingName">
                                    <option v-for="language in titleInfo.languages" v-bind:value="language.language_name"
                                            v-bind:key="language.language_name">{{language.language}}</option>
                                </select>
                            </div>
                        </div>

                        <div div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Datum:</h5>
                            </div>
                            <div class="col-7">
                                <date-picker v-model="reservationTimeRange"
                                range :disabled-date="disabledDays" class="title-reservation-item"
                                :lang="datePickerLang" />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Kusů:</h5>
                            </div>
                            <div class="col-7">
                                <input type="number" class="form-control title-reservation-item" name="optionsRadios" value="1" min="1"
                                :max="maxItemCount" v-model="itemCount"
                                data-toggle="tooltip" :disabled="itemCount === 0"
                                data-placement="top" :title="itemCount === 0 ? 'Pro pokračování vyberte datum rezervace' : ''">
                            </div>
                        </div>

                        <hr style="background-color: white;">

                        <div class="row mb-1">
                            <div class="col-5 my-auto">
                                <h5>Celkem:</h5>
                            </div>
                            <div class="col-7">
                                {{ itemCount * titleInfo.price * reservationNumberOfDays }} Kč
                            </div>
                        </div>

                        <button :disabled="itemCount === 0" type="button" class="btn btn-primary"
                                v-on:click="addItemToCart" data-toggle="tooltip"
                                data-placement="top" :title="itemCount === 0 ? 'Pro pokračování vyberte datum rezervace' : ''">
                            Přidat do košíku
                        </button>

                        <!-- todo visible only for admin-->
                        <router-link type="button" class="btn btn-primary" :to="{ name: 'titleEdit', params: { id: this.titleId}}" append>Upravit</router-link>
                        <button type="button" class="btn btn-primary" v-on:click="removeTitle">Smazat</button>
                        <!-- todo visible only for admin-->

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>Popisek</h3>
                <hr style="background-color: white;">
                <div style="text-align: justify">{{ titleInfo.description }}</div>
            </div>
        </div>

        <b-modal ref="modal-change-date-range" :retain-focus="false" title="Změna datumu rezervace"
                no-close-on-backdrop hide-header-close @ok="changeReservationDate" @cancel="resetReservationDate">
            <div>
                Změnili jste datum rezervace, Váš stávající obsah košíku bude smazán. <br>
                Chcete pokračovat?
            </div>
        </b-modal>
    </div>
</template>
<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import dateFormat from 'dateformat';

    export default {
        components: { DatePicker },
        title: '',
        props: ['cartCookiesProps', 'chosenStoreProps', 'user'],
        data() {
            return {
                titleName: '',
                titleType: '',
                titleId: '',
                titleDabingName: '',
                titleDabingId: '',
                itemCount: 0,
                maxItemCount: 0,
                maxPossibleItemCount: 0,
                maxPossibleItemCartCount: 0,
                titleInfo: {
                    title_name: '',
                    url: '',
                    description: '',
                    price: '',
                    year: '',
                    states: {state_name: ''},
                    languages: [],
                    reservations: []
                },
                addedItem: false,
                itemCountAdded: 0,
                reservationTimeRange: [null, null],
                itemToDeleteFromCart: -1,
                reservationNumberOfDays: 0,
                datePickerLang: {
                    formatLocale: {
                        // MMMM
                        months: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
                        // MMM
                        monthsShort: ['Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čer', 'Črv', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'],
                        // dddd
                        weekdays: ['Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'],
                        // ddd
                        weekdaysShort: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'],
                        // dd
                        weekdaysMin: ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'],
                        // first day of week
                        firstDayOfWeek: 1,
                        // first week contains January 1st.
                        firstWeekContainsDate: 1,
                        // parse ampm
                        meridiemParse: /[ap]\.?m?\.?/i,

                    },
                    // the calendar header, default formatLocale.weekdaysMin
                    days: [],
                    // the calendar months, default formatLocale.monthsShort
                    months: [],
                    // the calendar title of year
                    yearFormat: 'YYYY',
                    // the calendar title of month
                    monthFormat: 'MMM',
                    // the calendar title of month before year
                    monthBeforeYear: false,
                }
            }
        },
        computed: {
            cartCookies: function () {
                return this.cartCookiesProps;
            },
            chosenStoreId: function () {
                return this.chosenStoreProps;
            }
        },
        watch: {
            beforeRouterUpdate (to, from) {
                this.getTitleInfo();
            },
            titleInfo: {
                handler: function (titleInfo) {
                    if (titleInfo !== undefined && titleInfo !== null && this.user !== null) {
                        this.$emit('emitHandler',  {isLoading: false});
                    }
                },
                immediate: true
            },
            itemCount: {
                handler: function (itemCount) {
                    if (itemCount < 0) this.itemCount = 0;
                    if (itemCount > this.maxItemCount) this.itemCount = this.maxItemCount;
                },
                immediate: true
            },
            titleDabingName: {
                handler: function (titleDabingName) {
                    this.reservationTimeRange = [null, null];
                },
                immediate: true
            },
            maxItemCount: {
                handler: function (maxItemCount) {
                    if (maxItemCount > 0) this.itemCount = 1;
                    else this.itemCount = 0;
                },
                immediate: true
            },
            reservationTimeRange: {
                handler: function (reservationTimeRange) {
                    if (reservationTimeRange[0] === null && reservationTimeRange[1] === null) {
                        this.itemCount = 0;
                        this.maxItemCount = 0;
                    } else {
                        this.countMaxItemInCart();
                        var startDate = new Date(reservationTimeRange[0]);
                        var endDate = new Date(reservationTimeRange[1]);
                        this.reservationNumberOfDays = Math.round((endDate.getTime() - startDate.getTime()) / (1000 * 3600 * 24)) + 1;
                    }
                },
                immediate: true
            }
        },
        mounted() {
            if (this.$route.params.titleName != null) this.titleName = this.$route.params.titleName;
            if (this.$route.name != null) this.type = this.$route.name;

            this.getTitleInfo();
        },
        methods: {
            disabledDays(date) {
                var disabled = false;
                date.setHours(0,0,0,0);

                var reservations = this.titleInfo.reservations[this.titleDabingName];
                var titlesCountInDate = 0;
                if (reservations !== undefined) {
                    for (const [reservationDate, titlesCount] of Object.entries(reservations)) {
                        if (reservationDate === dateFormat(date, 'yyyy-mm-dd')) {
                            if (titlesCount === this.maxPossibleItemCount)
                                disabled = true;
                            titlesCountInDate = titlesCount;
                            break;
                        }
                    }
                }

                if (!disabled) {
                    this.cartCookies.forEach(item => {
                        if (item.url === this.titleName) {
                            for (var reservationDate = new Date(item.reservationTimeRange[0]);
                            reservationDate <= new Date(item.reservationTimeRange[1]);
                            reservationDate.setDate(reservationDate.getDate() + 1)) {
                                if (dateFormat(reservationDate, 'yyyy-mm-dd') === dateFormat(date, 'yyyy-mm-dd')) {
                                    if (item.maxItemCount <= titlesCountInDate + item.quantity)
                                        disabled = true;
                                    break;
                                }
                            }
                        }
                    });
                }

                return date < new Date() || disabled;
            },
            countMaxPossibleItemCartCount() {
                if (this.reservationTimeRange[0] === null && this.reservationTimeRange[1] === null) return;
                var numberOfReservations = 0;

                var reservations = this.titleInfo.reservations[this.titleDabingName];
                if (reservations !== undefined) {
                    for (var date = new Date(this.reservationTimeRange[0]); date <= new Date(this.reservationTimeRange[1]); date.setDate(date.getDate() + 1)) {
                        var tmp = reservations[dateFormat(date, 'yyyy-mm-dd')];

                        if (tmp != undefined && tmp > numberOfReservations) numberOfReservations = tmp;
                    }
                }

                this.maxPossibleItemCartCount = this.maxPossibleItemCount - numberOfReservations;
            },
            countMaxItemInCart() {
                var skip = false;
                var index = 0;
                this.cartCookies.forEach(item => {
                    if (item.language_name === this.titleDabingName && item.url === this.titleInfo.url) {
                        var isIntersection = this.dateIntersection(item.reservationTimeRange, this.reservationTimeRange);

                        if (isIntersection) {
                            this.$refs['modal-change-date-range'].show();
                            this.itemToDeleteFromCart = index;
                            skip = true;
                            return;
                        }

                        index++;
                    }
                });

                if (!skip) {
                    this.titleInfo.languages.forEach(lang => {
                        if (lang.language_name === this.titleDabingName)
                            this.maxPossibleItemCount = lang.total;
                    });

                    this.countMaxPossibleItemCartCount();
                    this.maxItemCount = this.maxPossibleItemCartCount;
                }
            },
            dateIntersection(dateRange1, dateRange2) {
                var dateRange1_start = new Date(dateRange1[0]);
                var dateRange1_end = new Date(dateRange1[1]);
                var dateRange2_start = new Date(dateRange2[0]);
                var dateRange2_end = new Date(dateRange2[1]);

                if (dateRange1_start <= dateRange2_start && dateRange1_end >= dateRange2_start ||
                dateRange1_start >= dateRange2_start && dateRange2_end >= dateRange1_start) return true;

                return false;
            },
            resetReservationDate() {
                this.reservationTimeRange = [null, null];
            },
            changeReservationDate() {
                this.cartCookies.splice(this.itemToDeleteFromCart, 1);
                this.$emit('emitHandler', {cartCookies: this.cartCookies});
                this.countMaxItemInCart();
            },
            getTitleInfo() {
                this.$emit('emitHandler',  {isLoading: true});

                axios.post('/api/get_title', {'type' : this.titleType, 'name': this.titleName, 'store_id': this.chosenStoreId}).then((res) => {
                    this.titleInfo = res.data;
                    this.titleId = this.titleInfo.id;
                    this.title = this.titleInfo.title_name;
                    this.titleDabingName = this.titleInfo.languages[0].language_name;
                    this.maxItemCount = this.titleInfo.languages[0].total;
                    this.maxPossibleItemCount = this.titleInfo.languages[0].total;
                    this.$emit('emitHandler',  {isLoading: false});
                }).catch((error) => {
                    this.$router.push({ name: 'notfound' });
                });
            },
            closedMessage() {
                this.addedItem = false;
            },
            addItemToCart() {
                var addItem = true;
                this.cartCookies.forEach(element => {
                    var sameDateRange = new Date(this.reservationTimeRange[0]) === new Date(element.reservationTimeRange[0]) &&
                                        new Date(this.reservationTimeRange[1]) === new Date(element.reservationTimeRange[1]);

                    if (element.url === this.titleInfo.url && element.language_name === this.titleDabingName && sameDateRange) {
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
                        title_id: this.titleInfo.id,
                        type: this.titleInfo.type,
                        price: this.titleInfo.price,
                        quantity: this.itemCount,
                        url: this.titleInfo.url,
                        language_name: this.titleDabingName,
                        language: this.titleDabingId,
                        maxItemCount: this.maxPossibleItemCartCount,
                        reservationTimeRange: [
                            dateFormat(new Date(this.reservationTimeRange[0]), 'yyyy-mm-dd'),
                            dateFormat(new Date(this.reservationTimeRange[1]), 'yyyy-mm-dd'),
                        ],
                        reservationNumberOfDays: this.reservationNumberOfDays
                    };

                    this.cartCookies.push(item);
                }

                this.reservationTimeRange = [null, null];
                this.countMaxItemInCart();
                this.$emit('emitHandler', {cartCookies: this.cartCookies});
                this.addedItem = true;
                this.itemCountAdded = this.itemCount;
                this.itemCount = 1;
            },
            async removeTitle(){
                this.$emit('emitHandler',  {isLoading: true});
                await axios.delete("/api/delete_title/" + this.titleId)
                    .then(res => {
                        this.$emit('emitHandler',  {isLoading: false});
                        this.$router.push({path: '/'}); //todo redirect
                    })
                    .catch(err => {
                        this.$emit('emitHandler',  {isLoading: false});
                        if (err.response) {
                            // client received an error response (5xx, 4xx)
                            alert(err.response.data['message'])
                        } else if (err.request) {
                            // client never received a response, or request never left
                        } else {
                            // anything else
                        }
                    });
            }
        }
    }
</script>
