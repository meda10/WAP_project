<template>
    <div>
        <h1>Pokladna</h1>
        <div id="paypal-button"></div>
    </div>
</template>

<script>
export default {
    title: 'Pokladna',
    props: ['cartCookiesProps', 'cartItemsPriceProps'],
    computed: {
        cartCookies: function () {
            return this.cartCookiesProps || [];
        },
        cartItemsPrice: function () {
            return this.cartItemsPriceProps;
        }
    },
    created() {
        if (this.cartCookies.length === 0) {
            this.$emit('emitHandler',  {isLoading: false});
            this.$router.push({ name: 'cart' });
        }
        axios.get('/api/user').then((res) => {
        }).catch(error => {
            this.$router.push({ name: 'cart' });
        });
    },
    mounted() {
        paypal.Buttons().render('#paypal-button');
        this.$emit('emitHandler',  {isLoading: false});
    }
}
</script>

