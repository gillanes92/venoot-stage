<template>
    <div class="estimate">
        <v-content>
            <v-container fluid fill-height>
                <v-layout v-if="category == 'event'" wrap>
                    <v-flex xs12 pb-2>
                        <h1>{{ $t('estimate.eventTitle') }}</h1>
                    </v-flex>
                    <v-flex xs12 py-2>
                        <v-alert
                            :value="true"
                            type="info"
                        >{{ $t('estimate.info') }}</v-alert>
                    </v-flex>
                    <v-flex xs12 py-2>
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title>{{ $t('estimate.createMessage') }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card>
                            <v-card-text>
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th
                                                width="20%"
                                                class="text-xs-left"
                                            >{{ $t('estimate.description') }}</th>
                                            <th
                                                width="10%"
                                                class="text-xs-left"
                                            >{{ $t('estimate.selection') }}</th>
                                            <th
                                                width="50%"
                                                class="text-xs-left"
                                            >{{ $t('estimate.detail') }}</th>
                                            <th
                                                width="20%"
                                                class="text-xs-left"
                                            >$</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $t('estimate.createLanding') }}</td>
                                            <td>
                                                <v-switch
                                                    v-model="estimate.landing"
                                                ></v-switch>
                                            </td>
                                            <td></td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ landingPrice }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $t('estimate.confirmationForm') }}</td>
                                            <td>
                                                <v-switch
                                                    :disabled="estimate.ticket_sale"
                                                    v-model="estimate.confirmation_form"
                                                ></v-switch>
                                            </td>
                                            <td></td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ confirmationFormPrice }}</td>
                                        </tr>
                                        <tr height="120px">
                                            <td>{{ $t('estimate.mailings') }}</td>
                                            <td>
                                                <v-switch
                                                    v-model="estimate.mailings"
                                                ></v-switch>
                                            </td>
                                            <td>
                                                <v-container
                                                    fluid
                                                    v-if="estimate.mailings"
                                                >
                                                    <v-layout>
                                                        <v-flex xs1>
                                                            <v-text-field
                                                                class="inputQuantity"
                                                                v-model="estimate.mailings_quantity"
                                                                hide-details
                                                                single-line
                                                                type="number"
                                                            ></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs11 pl-4>
                                                            <v-slider
                                                                hint="Max: 500000"
                                                                persistent-hint
                                                                v-model="estimate.mailings_quantity"
                                                                :max="500000"
                                                                :min="0"
                                                            ></v-slider>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ mailingPrice }}</td>
                                        </tr>
                                        <tr height="120px">
                                            <td>{{ $t('estimate.polls') }}</td>
                                            <td>
                                                <v-switch
                                                    v-model="estimate.polls"
                                                ></v-switch>
                                            </td>
                                            <td>
                                                <v-container
                                                    fluid
                                                    v-if="estimate.polls"
                                                >
                                                    <v-layout>
                                                        <v-flex xs1>
                                                            <v-text-field
                                                                class="inputQuantity"
                                                                v-model="estimate.polls_quantity"
                                                                hide-details
                                                                single-line
                                                                type="number"
                                                            ></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs11 pl-4>
                                                            <v-slider
                                                                hint="Max: 100"
                                                                persistent-hint
                                                                v-model="estimate.polls_quantity"
                                                                :max="100"
                                                                :min="0"
                                                            ></v-slider>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ pollsPrice }}</td>
                                        </tr>
                                        <tr height="120px">
                                            <td>{{ $t('estimate.registerKeys') }}</td>
                                            <td>
                                                <v-switch
                                                    v-model="estimate.register_keys"
                                                ></v-switch>
                                            </td>
                                            <td>
                                                <v-container
                                                    fluid
                                                    v-if="estimate.register_keys"
                                                >
                                                    <v-layout>
                                                        <v-flex xs1>
                                                            <v-text-field
                                                                class="inputQuantity"
                                                                v-model="estimate.register_keys_quantity"
                                                                hide-details
                                                                single-line
                                                                type="number"
                                                            ></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs11 pl-4>
                                                            <v-slider
                                                                hint="Max: 50"
                                                                persistent-hint
                                                                v-model="estimate.register_keys_quantity"
                                                                :max="50"
                                                                :min="0"
                                                            ></v-slider>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ registerKeysPrice }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $t('estimate.ticketSale') }}</td>
                                            <td>
                                                <v-switch
                                                    :disabled="estimate.confirmation_form"
                                                    v-model="estimate.ticket_sale"
                                                ></v-switch>
                                            </td>
                                            <td></td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ ticketSalePrice }}</td>
                                        </tr>
                                        <tr>
                                            <td
                                                colspan="3"
                                                class="text-xs-right pr-1"
                                            >{{ $t('estimate.netTotal') + ':' }}</td>
                                            <td
                                                class="text-xs-right"
                                            >$ {{ netTotal }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    :loading="loading"
                                    color="success"
                                    @click="save(false)"
                                >{{ $t('estimate.save') }}</v-btn>

                                <v-btn
                                    :loading="loading"
                                    color="success"
                                    @click="save(true)"
                                >{{ $t('estimate.saveAndEvent') }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </div>
</template>

<script>
import { forEach } from 'lodash'
export default {
    props: {
        category: {
            type: String,
            default: 'event'
        }
    },
    data: function() {
        return {
            estimate: {
                landing: false,
                confirmation_form: false,
                mailings: false,
                mailings_quantity: 0,
                polls: false,
                polls_quantity: 0,
                register_keys: false,
                register_keys_quantity: 0,
                ticket_sale: false,
                free_tickets: false,
                netTotal: 0
            },
            prices: {},
            loading: false
        }
    },
    computed: {
        landingPrice: function() {
            return this.estimate.landing ? this.prices.landing : 0
        },
        confirmationFormPrice: function() {
            return this.estimate.confirmation_form ? this.prices.confirmForm : 0
        },
        mailingPrice: function() {
            return this.estimate.mailings
                ? this.estimate.mailings_quantity * this.prices.mailing
                : 0
        },
        pollsPrice: function() {
            return this.estimate.polls
                ? this.estimate.polls_quantity * this.prices.poll
                : 0
        },
        registerKeysPrice: function() {
            return this.estimate.register_keys
                ? this.estimate.register_keys_quantity * this.prices.registerKey
                : 0
        },
        ticketSalePrice: function() {
            return this.estimate.ticket_sale ? this.prices.ticketSale : 0
        },
        freeTicketsPrice: function() {
            return this.estimate.free_tickets ? this.prices.freeTicket : 0
        },
        netTotal: function() {
            this.estimate.netTotal =
                this.prices.base +
                this.landingPrice +
                this.confirmationFormPrice +
                this.mailingPrice +
                this.pollsPrice +
                this.registerKeysPrice +
                this.ticketSalePrice +
                this.freeTicketsPrice
            return (
                10000 +
                this.landingPrice +
                this.confirmationFormPrice +
                this.mailingPrice +
                this.pollsPrice +
                this.registerKeysPrice +
                this.ticketSalePrice +
                this.freeTicketsPrice
            )
        }
    },
    methods: {
        save(goToEvent) {
            this.loading = true
            this.axios
                .post('/estimates', { ...this.estimate })
                .then(response => {
                    this.estimate = response.data.estimate

                    if (goToEvent) {
                        this.$router.push({
                            name: 'event',
                            params: { estimate: this.estimate }
                        })
                    } else {
                        this.$router.push('/home')
                    }
                })
                .catch(error => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })
                .finally(() => {
                    this.loading = false
                })
        }
    },
    mounted() {
        this.loading = true
        this.axios
            .get('/constants/prices')
            .then(response => {
                this.prices = response.data.prices
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                this.loading = false
            })
    }
}
</script>

<style>
.inputQuantity input[type='number'] {
    -moz-appearance: textfield;
}
.inputQuantity input::-webkit-outer-spin-button,
.inputQuantity input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}
</style>
