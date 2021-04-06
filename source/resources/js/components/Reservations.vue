<template>
    <div>
        <h1>Mé rezervace</h1>

        <div v-if="myReservations.length === 0">
            Aktuálně nemáte žádné rezervace. <router-link :to="{ name: 'home' }" class="dropdown-item">Běžte si něco objednat :)</router-link>
        </div>

        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="reservationDeleted">
            <strong>Rezervace byla zrušena!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="accordion" id="accordionExample">
            <div v-for="(reservation, index) in myReservations" v-bind:key="reservation.id" class="card">
                <div class="card-header" :id="'heading' + reservation.id">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" 
                        :data-target="'#collapse' + reservation.id" aria-expanded="true" :aria-controls="'collapse' + reservation.id">
                            <h4>{{reservation.title_name}} ({{reservation.language}} dabing)</h4>
                        </button>
                    </h5>
                </div>

                <div :id="'collapse' + reservation.id" class="collapse" :aria-labelledby="'heading' + reservation.id" data-parent="#accordionExample">
                    <div class="card-body">
                        <div>
                            <h6>Čas rezervace: {{dateFormat(reservation.reservation, 'dd. mm. yyyy')}} - {{dateFormat(reservation.reservation_till, 'dd. mm. yyyy')}}</h6>
                        </div>
                        <div>
                            <h6>Místo vyzvednutí: {{reservation.store_address}}</h6>
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
                        <div>
                            <h6>Kusů: {{reservation.quantity}}</h6>
                        </div>
                        <div v-if="reservation.discount != 0">
                            <h6>Sleva: {{reservation.discount}} %</h6>
                        </div>
                        <div>
                            <h6>Cena celkem: {{countPrice(reservation.price, reservation.quantity, reservation.reservation, reservation.reservation_till, reservation.discount)}} Kč</h6>
                        </div>
                        <div v-if="new Date(reservation.reservation) >= new Date() && !reservation.issued && !reservation.returned">
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
    props: ['user'],
    data() {
        return {
            myReservations: [],
            reservationIdToBeDeleted: -1,
            reservationIdLocalToBeDeleted: -1,
            modalCancelReservation: false,
            reservationDeleted: false
        }
    },
    watch: {
        user: {
            handler: function (user) {
                if (user !== undefined && user !== null) {
                    this.$emit('emitHandler', {isLoading: true});
                    this.getMyReservations();
                }
            },
            immediate: true
        },
        myReservations: {
            handler: function (myReservations) {
                if (myReservations !== undefined && myReservations !== null)
                    this.$emit('emitHandler', {isLoading: false});
            },
            immediate: true
        }
    },
    mounted() {
        this.$emit('emitHandler', {isLoading: true});
    },
    methods: {
        dateFormat: dateFormat,
        getMyReservations() {
            axios.post('/api/get_user_reservations').then((res) => {
                this.myReservations = res.data.data;
            });
        },
        cancelReservationModal(reservationId, reservationIdLocal) {
            this.modalCancelReservation = true;
            this.reservationIdToBeDeleted = reservationId;
            this.reservationIdLocalToBeDeleted = reservationIdLocal;
        },
        cancelReservation() {
            this.myReservations.splice(this.reservationIdLocalToBeDeleted, 1);
            this.reservationDeleted = true;

            axios.post('/api/cancel_reservation', { reservationId: this.reservationIdToBeDeleted }).then((res) => {
                this.dontCancelReservation();
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
        },
        countPrice(reservationPrice, piecesCount, start, end, discountPercent) {
            var finalPrice = Number(reservationPrice) * this.countNumberOfDaysFromDateRange(start, end) * piecesCount;
            if (discountPercent !== 0) {
                var discount = (1 - (discountPercent / 100));
                finalPrice *= discount;
            }
            return Math.round(finalPrice);
        }
    }
}
</script>
