<template>
    <div>
        <h1>Platba</h1>
        <div id="paypal-button"></div>
    </div>
</template>

<script>
export default {
    mounted() {
        paypal.Buttons().render('#paypal-button');
    }
}
</script>

