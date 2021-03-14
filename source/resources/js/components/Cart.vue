<template>
    <div>
        <h1>Košík (prodejna {{store.address}})</h1>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="!isLoggedIn">
            <strong>Nejste přihlášen(a)!</strong> Musíte se <router-link :to="{ name: 'login' }">přihlásit.</router-link>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <table id="cart" class="table table-condensed" v-if="cartCookies.length !== 0">
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
                <tr v-for="(item, index) in cartCookies" v-bind:key="index">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                            <div class="col-sm-10">
                                <h4 class="nomargin">{{item.name}} ({{item.language}} dabing)</h4>
                                <p>{{dateFormat(new Date(item.reservationTimeRange[0]), 'dd. mm. yyyy')}} - {{dateFormat(new Date(item.reservationTimeRange[1]), 'dd. mm. yyyy')}}</p>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">{{item.price}} Kč</td>
                    <td data-th="Quantity">
                        <input type="number" class="form-control text-center" value="0"
                        v-model="item.quantity" @change="changeItemQuantity(item, index)"
                        v-on:keyup="changeItemQuantity(item, index)"
                        :max="item.maxItemCount">
                    </td>
                    <td data-th="Subtotal" class="text-center">{{item.quantity * item.price * item.reservationNumberOfDays}} Kč</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm" @click="removeFromCart(item, index)"><i class="fas fa-trash"></i></button>								
                    </td>
                </tr>
            </tbody>
            <tbody v-if="!discountApplied">
                <tr>
                    <td data-th="DiscountCode">
                        <input type="text" class="form-control text-center" value=""
                        placeholder="Zadejte slevový kód" v-model="discountCode"
                        :class="{ 'is-invalid' : discountCodeWrong}">
                    </td>
                    <td data-th="Button" colspan="2">
                        <button @click="applyDiscount()" class="btn btn-success btn-block">Použij slevu  <i class="fas fa-percentage"></i></button>
                    </td>
                </tr>
            </tbody>
            <tbody v-if="discountApplied">
                <tr>
                    <td data-th="DiscountCode">
                        <h4 class="nomargin">Slevový kupón <strong>W61K37I3T9</strong></h4>
                    </td>
                    <td colspan="2">
                    </td>
                    <td class="text-center">
                        - 30%
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" @click="removeDiscount()"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><router-link :to="{ name: 'home' }" class="btn btn-warning"><i class="fa fa-angle-left"></i> Pokračovat v nákupu</router-link></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Celková cena: {{ cartItemsPrice - (discountPercent * cartItemsPrice / 100) }} Kč</strong></td>
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
import dateFormat from 'dateformat';

export default {
    title: 'Košík',
    props: ['cartCookiesProps', 'cartItemsPriceProps', 'chosenStoreProps', 'storesProps'],
    data() {
        return {
            isLoggedIn: true,
            modalRemoveFromCart: false,
            urlToRemove: '', 
            languageToRemove: '',
            countOfItemToRemove: 1,
            itemToBeRemoved: null,
            itemIndexToBeRemoved: -1,
            discountCode: '',
            discountApplied: false,
            discountCodeWrong: false,
            discountPercent: 0,
            store: {
                id: 0,
                address: ''
            },
        }
    },
    computed: {
        cartCookies: function () {
            return this.cartCookiesProps || [];
        },
        cartItemsPrice: function () {
            return this.cartItemsPriceProps;
        },
        chosenStore: function () {
            return this.chosenStoreProps;
        },
        stores: function () {
            return this.storesProps;
        }
    },
    watch: {
        stores: {
            handler: function (stores) {
                stores.forEach(storeItem => {
                    if (storeItem.id === this.chosenStore) {
                        this.store = storeItem
                        return;
                    }
                });
            },
            immediate: true
        }
    },
    mounted() {
        var cookiesDiscount = this.$cookies.get('wap-cart-discount') || {};
        if (JSON.stringify(cookiesDiscount) !== JSON.stringify({})) {
            this.discountCode = cookiesDiscount.discountCode;
            this.discountPercent = Number(cookiesDiscount.discountPercent);
            this.discountApplied = true;
        }
    },
    methods: {
        dateFormat: dateFormat,
        applyDiscount() {
            this.$emit('emitHandler', {isLoading: true});

            axios.post('/api/check_discount_code', { code: this.discountCode }).then((res) => {
                this.discountPercent = Number(res.data.percent);
                this.discountApplied = true;
                this.$cookies.set('wap-cart-discount', JSON.stringify({ code: this.discountCode, percent: this.discountPercent }));
                this.$emit('emitHandler', {isLoading: false});
            }).catch(error => {
                if (Number(error.response.status) === 404) {
                    $('#discountCodeInput').popover({ trigger: 'focus', title: 'Twitter Bootstrap Popover', content: "It's so simple to create a tooltop for my website!" });
                    this.discountCodeWrong = true;
                }
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        removeDiscount() {
            this.discountPercent = 0;
            this.discountApplied = false;
            this.discountCode = '';
            this.$cookies.set('wap-cart-discount', JSON.stringify({}));
        },
        handleDontRemoveFromCart() {
            if (Number(this.itemToBeRemoved.quantity) === 0) this.itemToBeRemoved.quantity = 1;
            this.$emit('emitHandler', {cartCookies: this.cartCookies});
            this.$forceUpdate();
        },
        handleRemoveFromCart() {
            this.cartCookies.splice(this.itemIndexToBeRemoved, 1);
            this.$emit('emitHandler',  {cartCookies: this.cartCookies});
        },
        removeFromCart(item, itemIndex) {
            this.itemToBeRemoved = item;
            this.itemIndexToBeRemoved = itemIndex;
            this.modalRemoveFromCart = true;
        },
        changeItemQuantity(item, itemIndex) {
            if (item.quantity !== '' && Number(item.quantity) === 0)
                this.removeFromCart(item, itemIndex);
            else {
                if (Number(item.quantity) > Number(item.maxItemCount)) item.quantity = item.maxItemCount;
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
