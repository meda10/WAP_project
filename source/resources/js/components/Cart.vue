<template>
    <div>
        <h1>Košík</h1>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="!isLoggedIn">
            <strong>Nejste přihlášen(a)!</strong> Musíte se <router-link :to="{ name: 'login' }">přihlásit.</router-link>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <table id="cart" class="table table-hover table-condensed" v-if="cartCookies.length !== 0">
            <thead>
                <tr>
                    <th style="width:50%">Položka</th>
                    <th style="width:10%">Cena</th>
                    <th style="width:8%">Množství</th>
                    <th style="width:22%" class="text-center">Mezisoučet</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in cartCookies" v-bind:key="item.url + '-' + item.language">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                            <div class="col-sm-10">
                                <h4 class="nomargin">{{item.name}} ({{item.language}} dabing)</h4>
                                <p>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet.</p>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">{{item.price}} Kč</td>
                    <td data-th="Quantity">
                        <input type="number" class="form-control text-center" value="0"
                        v-model="item.quantity" @change="changeItemQuantity(item.quantity, item.url, item.language_name)"
                        v-on:keyup="changeItemQuantity(item.quantity, item.url, item.language_name)"
                        :max="item.maxItemCount">
                    </td>
                    <td data-th="Subtotal" class="text-center">{{item.quantity * item.price}} Kč</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm" @click="removeFromCart(item.url, item.language_name)"><i class="fas fa-trash"></i></button>								
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td><router-link :to="{ name: 'home' }" class="btn btn-warning"><i class="fa fa-angle-left"></i> Pokračovat v nákupu</router-link></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Celková cena: {{ cartItemsPrice }} Kč</strong></td>
                    <td><button @click="checkout()" class="btn btn-success btn-block" >Do pokladny <i class="fa fa-angle-right"></i></button></td>
                </tr>
            </tfoot>
        </table>

        <div v-if="cartCookies.length === 0">
            <!-- TODO -->
            Jsem tak prázdný ;(
        </div>

        <b-modal id="modal-remove-from-cart" title="Položka bude odebrána"
                v-model="modalRemoveFromCart" @hidden="handleDontRemoveFromCart" @ok="handleRemoveFromCart">
            <p class="my-4">Potvrzením odeberete položku z košíku!</p>
        </b-modal>
    </div>
</template>
<script>
export default {
    title: 'Košík',
    props: ['cartCookiesProps', 'cartItemsPriceProps'],
    data() {
        return {
            isLoggedIn: true,
            modalRemoveFromCart: false,
            urlToRemove: '', 
            language_nameToRemove: '',
            countOfItemToRemove: 1
        }
    },
    computed: {
        cartCookies: function () {
            return this.cartCookiesProps || [];
        },
        cartItemsPrice: function () {
            return this.cartItemsPriceProps;
        }
    },
    methods: {
        handleDontRemoveFromCart() {
            this.cartCookies.forEach(item => {
                if (item.url === this.urlToRemove && 
                    item.language_name === this.language_nameToRemove) {
                        if (item.quantity === 0) item.quantity = 1;
                        this.$forceUpdate();
                }
            });
            this.$emit('emitHandler',  {cartCookies: this.cartCookies});
        },
        handleRemoveFromCart() {
            var newCart = this.cartCookies.filter(item => {
                return item.url !== this.urlToRemove || item.language_name !== this.language_nameToRemove;
            });
            this.$emit('emitHandler',  {cartCookies: newCart});
        },
        removeFromCart(url, language_name) {
            this.urlToRemove = url;
            this.language_nameToRemove = language_name;
            this.modalRemoveFromCart = true;
        },
        changeItemQuantity(quantity, url, language_name) {
            if (quantity !== '' && Number(quantity) === 0)
                this.removeFromCart(url, language_name);

            else {
                this.cartCookies.forEach(item => {
                    if (item.url === url && 
                        item.language_name === language_name && 
                        Number(quantity) > Number(item.maxItemCount)) {
                            item.quantity = item.maxItemCount;
                            this.$forceUpdate();
                    }
                });
                this.$emit('emitHandler',  {cartCookies: this.cartCookies});
            }
        },
        checkout() {
            this.$emit('emitHandler',  {isLoading: true});
            axios.get('/api/user').then((res) => {
                this.$router.push({ name: 'checkout' });
            }).catch(error => {
                this.$emit('emitHandler',  {isLoading: false});
                this.isLoggedIn = false;
                this.$forceUpdate();
            });
        }
    }
}
</script>
