<template>
    <div>
        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="addedItem">
            <strong>Položka přidána do košíku!</strong> - {{itemCountAdded}}x {{titleInfo.title_name}} ({{titleDabingId}} dabing)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="closedMessage">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <h1>{{ titleInfo.title_name }}</h1>

        <div style="width: 700px">{{ titleInfo.description }}</div>
        <div style="width: 700px">{{ titleInfo.year }}</div>
        <div style="width: 700px">{{ titleInfo.price }} Kc</div>
        <div style="width: 700px">Zeme puvodu: {{ titleInfo.states.state_name }}</div>
        <div style="width: 150px">
            <select class="form-select" v-model="titleDabingName">
                <option v-for="language in titleInfo.languages" v-bind:value="language.language_name" 
                v-bind:key="language.language_name">{{language.language}}</option>
            </select>
            <input type="number" class="form-control" name="optionsRadios" value="1" min="1" :max="maxItemCount" v-model="itemCount">
            <button :disabled="itemCount === 0" type="button" class="btn btn-primary" v-on:click="addItemToCart">Přidat do košíku</button>
            Celkem za titul: {{ itemCount * titleInfo.price }} Kc
        </div>
    </div>
</template>

<script>
    export default {
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
                    description: '',
                    price: '',
                    year: '',
                    states: {state_name: ''},
                    languages: []
                },
                addedItem: false,
                itemCountAdded: 0
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
            }
        },
        mounted() {
            if (this.$route.params.titleName != null) this.titleName = this.$route.params.titleName;
            if (this.$route.name != null) this.type = this.$route.name;

            this.getTitleInfo();
        },
        methods: {
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
