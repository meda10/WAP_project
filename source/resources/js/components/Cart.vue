<template>
    <div>
        <div style="margin-bottom: 50px">
            <h1>Košík (prodejna {{store.address}})</h1>
        </div>
        
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
                    <th style="width:17%" class="text-center">Mezisoučet</th>
                    <th style="width:15%" class="text-center">Odstranit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in cartCookies" v-bind:key="index">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs">
                                <img :src="'./img/movies/' + item.url + '.jpg'" alt="..." class="img-responsive" v-if="item.type === 'movie'" />
                                <img :src="'./img/series/' + item.url + '.jpg'" alt="..." class="img-responsive" v-if="item.type === 'serial'" />
                            </div>
                            <div class="col-sm-10 cart-item-name">
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
                    <td class="actions text-center">
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
                        <h4 class="nomargin">Slevový kupón <strong>{{discountCode}}</strong></h4>
                    </td>
                    <td colspan="2">
                    </td>
                    <td class="text-center">
                        - 30%
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" @click="removeDiscount()"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                <td colspan="5" style="padding: 40px 0px 10px 0px">
                <table id="paymentMethodTable" class="paymentMethodTable table table-condensed table-hover" style="width: 300px; float: right;">
                    <thead>
                        <tr>
                            <th style="width:50%">Vyberte způsob platby</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-on:click="rowClick('cash')">
                            <td>
                                <input type="radio" name="paymentMethod" id="cash_radio" value="cash" checked v-model="paymentMethod" />
                                <label for="cash_radio">Hotově</label>
                            </td>
                        </tr>
                        <tr v-on:click="rowClick('online')">
                            <td>
                                <input type="radio" name="paymentMethod" id="online_radio" value="online" v-model="paymentMethod" />
                                <label for="online_radio">Online</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><router-link :to="{ name: 'home' }" class="btn btn-warning"><i class="fa fa-angle-left"></i> Pokračovat v nákupu</router-link></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Celková cena: {{ cartItemsPrice - (discountPercent * cartItemsPrice / 100) }} Kč</strong></td>
                    <td><button @click="reservation()" class="btn btn-success btn-block" >Rezervovat <i class="fa fa-angle-right"></i></button></td>
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

        <b-modal id="modal-removed-from-cart" title="Hups, chyba!" no-close-on-backdrop hide-header-close
                v-model="modalReservationsError" :retain-focus="false">
            <p class="my-4">Zdá se, že Vás někdo předběhl a tyto rezervace budou smazány z Vašeho košíku :(</p>

            <div v-for="(reservation, index) in reservationsError" v-bind:key="index" style="margin-top: 20px">
                {{reservation.name}} ({{reservation.language}} dabing) <br>
                <p>{{dateFormat(new Date(reservation.reservationTimeRange[0]), 'dd. mm. yyyy')}} - {{dateFormat(new Date(reservation.reservationTimeRange[1]), 'dd. mm. yyyy')}}</p>
            </div>
        </b-modal>


        <b-modal id="modal-removed-from-cart" title="Hups, chyba!" no-close-on-backdrop hide-header-close
                v-model="modalDiscountError" :retain-focus="false">
            <p class="my-4">Zdá se, že Vámi zadaný slevový kupón již nelze použít :(</p>
        </b-modal>
    </div>
</template>
<script>
import dateFormat from 'dateformat';

export default {
    title: 'Košík',
    props: ['cartCookiesProps', 'cartItemsPriceProps', 'chosenStoreProps', 'storesProps', 'user'],
    data() {
        return {
            isLoggedIn: true,
            modalRemoveFromCart: false,
            modalReservationsError: false,
            modalDiscountError: false,
            reservationsError: [],
            urlToRemove: '', 
            languageToRemove: '',
            countOfItemToRemove: 1,
            itemToBeRemoved: null,
            itemIndexToBeRemoved: -1,
            discountCode: '',
            discountApplied: false,
            discountCodeWrong: false,
            discountPercent: 0,
            paymentMethod: 'cash',
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
            return Number(this.cartItemsPriceProps);
        }
    },
    watch: {
        storesProps: {
            handler: function (storesProps) {
                storesProps.forEach(storeItem => {
                    if (storeItem.id === this.chosenStoreProps) {
                        this.store = storeItem
                        return;
                    }
                });
                this.$emit('emitHandler', {isLoading: false});
            },
            immediate: true
        }
    },
    mounted() {
        this.$emit('emitHandler', {isLoading: true});
        var cookiesDiscount = this.$cookies.get('wap-cart-discount') || {};
        if (JSON.stringify(cookiesDiscount) !== JSON.stringify({})) {
            this.discountCode = cookiesDiscount.code;
            this.discountPercent = Number(cookiesDiscount.percent);
            this.discountApplied = true;
        }
    },
    methods: {
        dateFormat: dateFormat,
        rowClick(paymentMethod) {
            this.paymentMethod = paymentMethod;
        },
        applyDiscount() {
            this.$emit('emitHandler', {isLoading: true});

            axios.post('/api/check_discount_code', { code: this.discountCode }).then((res) => {
                this.discountPercent = Number(res.data.percent);
                this.discountApplied = true;
                this.$cookies.set('wap-cart-discount', JSON.stringify({ code: this.discountCode, percent: this.discountPercent }));
                this.$emit('emitHandler', {isLoading: false});
            }).catch(error => {
                if (Number(error.response.status) === 400) {
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
        reservation() {
            this.$emit('emitHandler',  {isLoading: true});
            
            axios.get('/api/user').then((res) => {
                var reservation = {
                    reservations: this.cartCookies,
                    paymentMethod: this.paymentMethod,
                    discount: this.discountCode,
                    storeId: this.store.id,
                    userId: this.user.id
                };

                axios.post('/api/make_reservation', reservation).then((res) => {
                    this.$cookies.set('wap-cart-discount', {});
                    this.$emit('emitHandler',  {cartCookies: []});
                    this.$emit('emitHandler',  {isLoading: false});
                    this.$router.push({ name: 'home', params: {reservation: true} });
                }).catch(error => {
                    if (Number(error.response.status) === 400) {
                        if (error.response.data.error === 'time_violation') {
                            this.reservationsError = error.response.data.violation;

                            this.reservationsError.forEach(reservationError => {
                                var index = this.cartCookies.forEach(function(item, index, arr) {
                                    if (reservationError.url !== item.url ||
                                            reservationError.language_name !== item.language_name ||
                                            reservationError.reservationTimeRange[0] !== item.reservationTimeRange[0] ||
                                            reservationError.reservationTimeRange[1] !== item.reservationTimeRange[1]) 
                                            
                                            return index;
                                });

                                this.cartCookies.splice(index, 1);
                            });

                            this.modalReservationsError = true;
                        }

                        if (error.response.data.error === 'discount_code')
                            return;

                        this.$emit('emitHandler', {cartCookies: this.cartCookies});
                        this.$emit('emitHandler', {isLoading: false});
                    }
                });

            }).catch(error => {
                this.$emit('emitHandler',  {isLoading: false});
                this.isLoggedIn = false;
                this.$forceUpdate();
            });
        }
    }
}
</script>
