<template>
    <div>
        <h1>Košík</h1>

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
                <tr v-for="item in cartCookies" v-bind:key="item.url">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                            <div class="col-sm-10">
                                <h4 class="nomargin">{{item.name}}</h4>
                                <p>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet.</p>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">{{item.price}} Kč</td>
                    <td data-th="Quantity">
                        <input type="number" class="form-control text-center" min="0" value="0"
                        v-model="item.quantity" @change="changeItemQuantity(item.quantity, item.url)">
                    </td>
                    <td data-th="Subtotal" class="text-center">{{item.quantity * item.price}} Kč</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm" @click="removeFromCart(item.url)"><i class="fas fa-trash"></i></button>								
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td><router-link :to="{ name: 'home' }" class="btn btn-warning"><i class="fa fa-angle-left"></i> Pokračovat v nákupu</router-link></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Celková cena: {{ cartItemsPrice }} Kč</strong></td>
                    <td><a href="#" class="btn btn-success btn-block">Checkout <i class="fa fa-angle-right"></i></a></td>
                </tr>
            </tfoot>
        </table>

        <div v-if="cartCookies.length === 0">
            <!-- TODO -->
            Jsem tak prázdný ;(
        </div>
    </div>
</template>
<script>
export default {
    title: 'Košík',
    props: ['cartCookiesProps', 'cartItemsPriceProps'],
    computed: {
        cartCookies: function () {
            return this.cartCookiesProps || [];
        },
        cartItemsPrice: function () {
            return this.cartItemsPriceProps;
        }
    },
    methods: {
        removeFromCart(url) {
            var newCart = this.cartCookies.filter(item => {
                return item.url !== url;
            });
            this.$emit('emitHandler',  {cartCookies: newCart});
        },
        changeItemQuantity(quantity, url) {
            if (quantity == 0) this.removeFromCart(url);
            else this.$emit('emitHandler',  {cartCookies: this.cartCookies});
        }
    }
}
</script>
