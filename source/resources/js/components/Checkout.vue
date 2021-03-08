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
    mounted() {
        paypal.Buttons().render('#paypal-button');
        this.$emit('emitHandler',  {isLoading: false});
    }
}
</script>

