<template>
    <div>
        <h1>Mé rezervace</h1>

        <div v-if="myReservations.length === 0">
            Aktuálně nemáte žádné rezervace. <router-link :to="{ name: 'home' }" class="dropdown-item">Běžte si něco objednat :)</router-link>
        </div>

        <div class="accordion" id="accordionExample">
            <div v-for="(reservation, index) in myReservations" v-bind:key="reservation.id" class="card">
                <div class="card-header" :id="'heading' + reservation.id">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" 
                        :data-target="'#collapse' + reservation.id" aria-expanded="true" :aria-controls="'collapse' + reservation.id">
                            <h4>{{reservation.titles.title_name}} ({{reservation.languages.language}} dabing)</h4>
                        </button>
                    </h5>
                </div>

                <div :id="'collapse' + reservation.id" class="collapse" :aria-labelledby="'heading' + reservation.id" data-parent="#accordionExample">
                    <div class="card-body">
                        <div>
                            <h6>Čas rezervace: {{dateFormat(reservation.reservation, 'dd. mm. yyyy')}} - {{dateFormat(reservation.reservation_till, 'dd. mm. yyyy')}}</h6>
                        </div>
                        <div>
                            <h6>Místo vyzvednutí: {{reservation.stores.address}}, {{reservation.stores.city}}, {{reservation.stores.zip_code}}</h6>
                        </div>
                        <div v-if="reservation.fine > 0">
                            <h6>Pokuta: {{reservation.fine}} Kč</h6>
                            <h6>Zaplaceno: <span v-if="reservation.paid">Ano</span><span v-if="!reservation.paid">Ne</span></h6>
                        </div>
                        <div v-if="reservation.fine === 0">
                            <h6>Zaplaceno: <span v-if="reservation.paid">Ano</span><span v-if="!reservation.paid">Ne</span></h6>
                        </div>
                        <div>
                            <h6>Vyzvednuto: <span v-if="reservation.issued">Ano</span><span v-if="!reservation.issued">Ne</span></h6>
                        </div>
                        <div v-if="reservation.issued">
                            <h6>Vráceno: <span v-if="reservation.returned">Ano</span><span v-if="!reservation.returned">Ne</span></h6>
                        </div>
                        <div>
                            <h6>Cena za kus: {{reservation.price}} Kč</h6>
                        </div>
                        <div v-if="reservation.discounts !== undefined">
                            <h6>Sleva: {{reservation.discounts.percent}} %</h6>
                        </div>
                        <div v-if="reservation.discounts">
                            <h6>Cena celkem: {{(Number(reservation.price) * countNumberOfDaysFromDateRange(reservation.reservation, reservation.reservation_till)) * (1 - (reservation.discounts.percent/100))}} Kč</h6>
                        </div>
                        <div v-if="!reservation.discounts">
                            <h6>Cena celkem: {{(Number(reservation.price) * countNumberOfDaysFromDateRange(reservation.reservation, reservation.reservation_till))}} Kč</h6>
                        </div>
                        <div v-if="new Date(reservation.reservation) >= new Date()">
                            <button type="button" class="btn btn-primary" @click="cancelReservationModal(reservation.id, index)">Zrušit rezervaci</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="modal-cancel-reservation" title="Zrušit rezervaci?"
                v-model="modalCancelReservation" @ok="cancelReservation" @hidden="dontCancelReservation">
            <p class="my-4">Opravdu chcete zrušit rezervaci?</p>
        </b-modal>
    </div>
</template>
<script>
import dateFormat from 'dateformat';

export default {
    title: 'Mé rezervace',
    props: ['userProps'],
    data() {
        return {
            myReservations: [],
            reservationIdToBeDeleted: -1,
            reservationIdLocalToBeDeleted: -1,
            modalCancelReservation: false
        }
    },
    computed: {
        user: function () {
            return this.userProps;
        }
    },
    watch: {
        user: {
            handler: function (user) {
                if (user !== null) {
                    this.getMyReservations(user.id);
                    this.$emit('emitHandler', {isLoading: false});
                }
            },
            immediate: true
        }
    },
    mounted() {
        this.$emit('emitHandler', {isLoading: true});
    },
    methods: {
        dateFormat: dateFormat,
        getMyReservations(userId) {
            axios.post('/api/get_user_reservations', { user_id: userId }).then((res) => {
                this.myReservations = res.data;
                console.log(res.data);
            });
        },
        cancelReservationModal(reservationId, reservationIdLocal) {
            this.modalCancelReservation = true;
            this.reservationIdToBeDeleted = reservationId;
            this.reservationIdLocalToBeDeleted = reservationIdLocal;
        },
        cancelReservation() {
            this.$emit('emitHandler', {isLoading: true});
            axios.post('/api/cancel_reservation', { reservationId: this.reservationIdToBeDeleted }).then((res) => {
                this.myReservations.splice(this.reservationIdLocalToBeDeleted, 1);
                this.dontCancelReservation();
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        dontCancelReservation() {
            this.reservationIdToBeDeleted = -1;
            this.reservationIdLocalToBeDeleted = -1;
        },
        countNumberOfDaysFromDateRange(start, end) {
            var startDate = new Date(start);
            var endDate = new Date(end);
            return Math.round((endDate.getTime() - startDate.getTime()) / (1000 * 3600 * 24)) + 1;
        }
    }
}
</script>
