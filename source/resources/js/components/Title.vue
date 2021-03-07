<template>
    <div>
        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="addedItem">
            <strong>Položka přidána do košíku!</strong>
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
            <input type="number" class="form-control" name="optionsRadios" value="1" min="1" v-model="itemNumber">
            <button type="button" class="btn btn-primary" v-on:click="addItemToCart">Přidat do košíku</button>
            Celkem za titul: {{ itemNumber * titleInfo.price }} Kc
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
                itemNumber: 1,
                titleInfo: {
                    title_name: '',
                    description: '',
                    price: '',
                    year: '',
                    states: {state_name: ''}
                },
                addedItem: false
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
                handler: function (isLoading) {
                    this.$emit('emitIsLoading', false);
                },
                immediate: true
            },
            itemNumber: {
                handler: function (itemNumber) {
                    if (itemNumber < 1) this.itemNumber = 1;
                },
                immediate: true
            },
            addedItem: {
                handler: function (addedItem) {
                    this.$forceUpdate();
                },
                immediate: true
            }
        },
        mounted() {
            if (this.$route.params.titleName != null)
                this.titleName = this.$route.params.titleName;
            if (this.$route.name != null)
                this.type = this.$route.name;

            this.getTitleInfo();
        },
        methods: {
            getTitleInfo() {
                this.$emit('emitHandler',  {isLoading: true});

                axios.post('/api/get_title', {'type' : this.titleType, 'name': this.titleName}).then((res) => {
                    this.titleInfo = res.data;
                    this.title = this.titleInfo.title_name;

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
                    if (element.url == this.titleInfo.url) {
                        addItem = false;
                        element.quantity = Number(element.quantity) + Number(this.itemNumber);
                    }
                });

                if (addItem) {
                    var item = {
                        name: this.titleInfo.title_name,
                        price: this.titleInfo.price,
                        quantity: this.itemNumber,
                        url: this.titleInfo.url
                    };  
                    this.cartCookies.push(item);
                }
                
                this.$emit('emitHandler',  {cartCookies: this.cartCookies});
                this.addedItem = true;
            }
        }
    }
</script>
