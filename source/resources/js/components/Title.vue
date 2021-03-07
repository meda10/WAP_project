<template>
    <div>
        <h1>{{ titleInfo.title_name }}</h1>

        <div style="width: 700px">{{ titleInfo.description }}</div>
        <div style="width: 700px">{{ titleInfo.year }}</div>
        <div style="width: 700px">{{ titleInfo.price }} Kc</div>
        <div style="width: 700px">Zeme puvodu: {{ titleInfo.states.state_name }}</div>
        
        <button type="button" class="btn btn-primary">Přidat do košíku</button>
    </div>
</template>

<script>
    export default {
        title: '',
        data() {
            return {
                titleName: '',
                titleType: '',
                titleInfo: {
                    title_name: '',
                    description: '',
                    price: '',
                    year: '',
                    states: {state_name: ''}
                }
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
                this.$emit('emitIsLoading', true);

                axios.post('/api/get_title', {'type' : this.titleType, 'name': this.titleName}).then((res) => {
                    this.titleInfo = res.data;
                    this.title = this.titleInfo.title_name;

                }).catch((error) => {
                    // TODO handle this error
                    console.log(error);
                    this.$emit('emitIsLoading', false);
                });
            }
        }
    }
</script>
