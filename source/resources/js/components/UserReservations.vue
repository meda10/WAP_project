<template>
    <div v-if="!dontShowTemplate">
        <b-modal id="modal-not-confirmed" title="Uživatel není ověřen!"
                v-model="showNotConfirmedModal" ok-only>
            <p class="my-4">Pro pokračování je nutné ověřit uživatele!</p>
        </b-modal>

        <b-modal id="modal-not-confirmed" title="Rezervace na špatné prodejně!"
                v-model="showWrongStoreModal" ok-only>
            <p class="my-4">Rezervace byla vytvořena na jiné prodejně!</p>
        </b-modal>

        <b-modal id="modal-not-confirmed" title="Nelze vydat!"
                v-model="showWrongDateModal" ok-only>
            <p class="my-4">Rezervace byla vytvořena na jiné datum!</p>
        </b-modal>


        <div v-if="enterEmail">
            <div class="row mb-5">
                <div class="col">
                    <h2>Zadejte email uživatele</h2>
                </div>
            </div>

            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="emailWrong">
                <strong>Neexistující uživatel!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control"
                    name="email" autocomplete="email" :class="{'is-invalid' : emailWrong}"
                    placeholder="Zadejte email uživatele" required v-model="userEmail">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary"
                        @click.prevent="enterUserEmail" style="margin-left: 20px; width: 200px;">Potvrdit</button>
                </div>
            </div>
        </div>

        <div v-if="!enterEmail">
            <div class="row mb-5">
                <div class="col">
                    <h2>Objednávky uživatele {{userReservation.name}} {{userReservation.surname}}</h2>
                </div>
            </div>

            <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="reservationIssued">
                Rezervace vyřízena.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="reservationIssued = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="reservationReturned">
                Rezervace vrácena.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="reservationReturned = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="alert alert-warning" role="alert" v-if="!userReservation.confirmed">
                <h4 class="alert-heading">Uživatel není ověřen</h4>
                <p>Aby bylo možné vydávat uživateli rezervace, je nutné aby byl ověřen</p>
                <hr>
                <b-button size="sm" :to="{ name: 'userEdit', params: {id: this.userReservation.id}}" class="mr-2">
                    Potvrdit uživatele
                </b-button>
            </div>

            <div v-if="reservationsToBeIssued.length !== 0">
                <div class="row mb-1 mt-5">
                    <div class="col">
                        <h3>Vystavení rezervací</h3>
                    </div>
                </div>

                <table class="table" id="titlesTable">
                    <thead>
                        <tr>
                            <th scope="col">Název titulu</th>
                            <th scope="col">Datum rezervace</th>
                            <th scope="col">Celkový mezisoučet</th>
                            <th scope="col">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(reservation, index) in reservationsToBeIssued"
                            v-bind:class="!(index%2) ? 'table-dark' : ''" :key="index">
                                <td>{{ reservation.title_name }} ({{ reservation.language }} dabing)</td>
                                <td>{{dateFormat(reservation.reservation, 'dd. mm. yyyy')}} - {{dateFormat(reservation.reservation_till, 'dd. mm. yyyy')}}</td>
                                <td>
                                    {{reservation.intSum}} Kč
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="removeFromToBeIssued(index)">
                                        Odebrat
                                    </button>
                                </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <th>Cena celkem:</th>
                            <th>{{priceSumToBeIssued}} Kč</th>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary" @click="payReservation()">
                    Zaplaceno a poslat fakturu
                </button>
            </div>

            <div v-if="finedReservations.length !== 0">
                <div class="row mb-1 mt-5">
                    <div class="col">
                        <h3>Zaplacení pokut</h3>
                    </div>
                </div>

                <table class="table" id="titlesTable">
                    <thead>
                        <tr>
                            <th scope="col">Název titulu</th>
                            <th scope="col">Datum rezervace</th>
                            <th scope="col">Celkový mezisoučet</th>
                            <th scope="col">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(reservation, index) in finedReservations"
                            v-bind:class="!(index%2) ? 'table-dark' : ''" :key="index">
                                <td>{{ reservation.title_name }} ({{ reservation.language }} dabing)</td>
                                <td>{{dateFormat(reservation.reservation, 'dd. mm. yyyy')}} - {{dateFormat(reservation.reservation_till, 'dd. mm. yyyy')}}</td>
                                <td>
                                    {{ reservation.fineSum }} Kč
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="removeFromToBeFined(index)">
                                        Odebrat
                                    </button>
                                </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <th>Cena celkem:</th>
                            <th>{{fineSumToBeIssued}} Kč</th>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary" @click="payFines()">
                    Zaplaceno a poslat fakturu
                </button>
            </div>

            <div class="row mb-1 mt-5">
                <div class="col">
                    <h3>Nevyřízené</h3>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div v-for="(reservation, index) in notIssuedReservations" v-bind:key="reservation.id" class="card">
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
                            <div>
                                <h6>Zaplaceno: <span v-if="reservation.paid">Ano</span><span v-if="!reservation.paid">Ne</span></h6>
                            </div>
                            <div v-if="reservation.fine > 0">
                                <h6>Pokuta: {{reservation.fine}} Kč</h6>
                                <h6>Pokuta zaplacena: <span v-if="reservation.fine_paid">Ano</span><span v-if="!reservation.fine_paid">Ne</span></h6>
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
                                <h6>Cena celkem: {{reservation.intSum}} Kč</h6>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary" @click="issueReservation(index)">
                                    Vystavit rezervaci
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1 mt-5">
                <div class="col">
                    <h3>Půjčené</h3>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div v-for="(reservation, index) in issuedReservations" v-bind:key="reservation.id" class="card">
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
                            <div>
                                <h6>Zaplaceno: <span v-if="reservation.paid">Ano</span><span v-if="!reservation.paid">Ne</span></h6>
                            </div>
                            <div v-if="reservation.fine > 0">
                                <h6>Pokuta: {{reservation.fine}} Kč</h6>
                                <h6>Pokuta zaplacena: <span v-if="reservation.fine_paid">Ano</span><span v-if="!reservation.fine_paid">Ne</span></h6>
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
                                <h6>Cena celkem: {{reservation.intSum}} Kč</h6>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary" @click="returnReservation(index)" v-if="reservation.fine === 0">
                                    Potvrdit vrácení
                                </button>
                                <button type="button" class="btn btn-primary" @click="reservationToBeFined(index)" v-if="reservation.fine > 0">
                                    Zaplatit pokutu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1 mt-5">
                <div class="col">
                    <h3>Vrácené</h3>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div v-for="reservation in returnedReservations" v-bind:key="reservation.id" class="card">
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
                                <h6>Cena celkem: {{reservation.intSum}} Kč</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dateFormat from 'dateformat';

export default {
    props: ['user'],
    data() {
        return {
            userId: null,
            userEmail: '',
            enterEmail: true,
            emailWrong: false,
            userReservation: {},
            reservations: [],
            issuedReservations: [],
            notIssuedReservations: [],
            finedReservations: [],
            returnedReservations: [],
            reservationsToBeIssued: [],
            dontShowTemplate: false,
            priceSumToBeIssued: 0,
            fineSumToBeIssued: 0,
            showNotConfirmedModal: false,
            showWrongStoreModal: false,
            showWrongDateModal: false,
            reservationIssued: false,
            reservationReturned: false
        }
    },
    mounted() {
        if (this.$route.params.id != null) {
            this.$emit('emitHandler', {isLoading: true});
            this.userId = this.$route.params.id;

            if (this.$route.params.userReservation == null) {
                this.dontShowTemplate = true;
                this.getUserEmailById(this.userId);
            } else {
                this.enterEmail = false;
                this.userReservation = this.$route.params.userReservation;
                this.reservations = this.$route.params.reservations;
                this.splitReservations();
                this.$emit('emitHandler', {isLoading: false});
            }
        }
    },
    methods: {
        dateFormat: dateFormat,
        enterUserEmail() {
            this.$emit('emitHandler', {isLoading: true});
            this.getUsersReservations();
        },
        getUserEmailById(id) {
            axios.get('/api/get_one_user/' + id).then((res) => {
                this.userEmail = res.data.data.email;
                this.getUsersReservations(true);
            }).catch((error) => {
                this.$router.push({ name: 'notfound' });
            });
        },
        getUsersReservations(dontRedirect) {
            axios.post('/api/get_user_reservations_by_email', {user_email: this.userEmail}).then((res) => {
                this.userReservation = res.data.user;
                this.reservations = res.data.reservations;
                if (dontRedirect === undefined || !dontRedirect)
                    this.$router.push({ name: 'userReservationsId', params: {id: this.userReservation.id, user: this.user, reservations: this.reservations} });
                this.enterEmail = false;
                this.dontShowTemplate = false;
                this.splitReservations();
                this.$emit('emitHandler', {isLoading: false});
            }).catch((error) => {
                if (Number(error.response.status) === 400 && error.response.data.error === "user_not_found")
                    this.emailWrong = true;
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        splitReservations() {
            this.reservations.forEach(reservation => {
                reservation.reservationNumberOfDays = this.countNumberOfDaysFromDateRange(reservation.reservation, reservation.reservation_till);
                this.countPrice(reservation);

                if (reservation.issued) {
                    if (reservation.returned) this.returnedReservations.push(reservation);
                    else this.issuedReservations.push(reservation);
                } else this.notIssuedReservations.push(reservation);
            });
        },
        countNumberOfDaysFromDateRange(start, end) {
            var startDate = new Date(start);
            var endDate = new Date(end);
            return Math.round((endDate.getTime() - startDate.getTime()) / (1000 * 3600 * 24)) + 1;
        },
        countPrice(reservation) {
            var finalPrice = Number(reservation.price) * reservation.reservationNumberOfDays * reservation.quantity;
            if (reservation.discount !== 0) {
                var discount = (1 - (reservation.discount / 100));
                finalPrice *= discount;
            }
            reservation.intSum = Math.round(finalPrice + reservation.fine);
            if (!reservation.paid) reservation.fineSum = reservation.intSum
            else reservation.fineSum = reservation.fine;
        },
        issueReservation(resId) {
            if (!this.userReservation.confirmed) {
                this.showNotConfirmedModal = true;
                return;
            }
            var reservation = this.notIssuedReservations[resId];

            if (this.user.store_id !== reservation.store_id) {
                this.showWrongStoreModal = true;
                return;
            }
            var startDate = new Date(reservation.reservation);
            var today = new Date();
            startDate.setHours(0,0,0,0);
            today.setHours(0,0,0,0);
            if (startDate.getTime() !== today.getTime()) {
                this.showWrongDateModal = true;
                return;
            }
            this.priceSumToBeIssued += Number(reservation.intSum);
            reservation.returnId = resId;
            this.reservationsToBeIssued.push(reservation);
            this.notIssuedReservations.splice(resId, 1);
        },
        removeFromToBeIssued(resId) {
            var retId = this.reservationsToBeIssued[resId].returnId;
            this.priceSumToBeIssued -= Number(this.reservationsToBeIssued[resId].intSum);
            this.notIssuedReservations.splice(retId, 0, this.reservationsToBeIssued[resId]);
            this.reservationsToBeIssued.splice(resId, 1);
        },
        payReservation() {
            this.$emit('emitHandler', {isLoading: true});
            axios.post('/api/pay_reservation', {reservations: this.reservationsToBeIssued, user: this.userReservation}).then((res) => {
                this.issuedReservations = this.issuedReservations.concat(this.reservationsToBeIssued);
                this.reservationsToBeIssued = [];
                this.reservationIssued = true;
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        returnReservation(resId) {
            this.$emit('emitHandler', {isLoading: true});
            axios.post('/api/return_reservation', {reservationId: this.issuedReservations[resId].id}).then((res) => {
                this.returnedReservations.push(this.issuedReservations[resId]);
                this.issuedReservations.splice(resId, 1);
                this.reservationReturned = true;
                this.$emit('emitHandler', {isLoading: false});
            });
        },
        reservationToBeFined(resId) {
            var reservation = this.issuedReservations[resId];

            if (this.user.store_id !== reservation.store_id) {
                this.showWrongStoreModal = true;
                return;
            }

            reservation.returnId = resId;
            this.fineSumToBeIssued += Number(reservation.fineSum);
            this.finedReservations.push(reservation);
            this.issuedReservations.splice(resId, 1);
        },
        removeFromToBeFined(resId) {
            var retId = this.finedReservations[resId].returnId;
            this.fineSumToBeIssued -= Number(this.finedReservations[resId].fineSum);
            this.issuedReservations.splice(retId, 0, this.finedReservations[resId]);
            this.finedReservations.splice(resId, 1);
        },
        payFines() {
            this.$emit('emitHandler', {isLoading: true});
            axios.post('/api/pay_fines', {reservations: this.finedReservations, user: this.userReservation}).then((res) => {
                this.returnedReservations = this.returnedReservations.concat(this.finedReservations);
                this.finedReservations = [];
                this.reservationReturned = true;
                this.$emit('emitHandler', {isLoading: false});
            });
        }
    }
}
</script>
