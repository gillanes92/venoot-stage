<template>
    <div class="products">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-row>
                        <v-col cols="12">
                            <v-expansion-panels v-model="panelEstimate">
                                <v-expansion-panel
                                    expand
                                    focusable
                                    class="primary"
                                >
                                    <v-expansion-panel-header
                                        class="white--text title"
                                    >
                                        {{ $t('link.estimates').toUpperCase() }}
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="success"
                                            to="/estimator"
                                        >{{ $t('new') }}</v-btn>
                                    </v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <v-data-table
                                            :headers="estimateHeaders"
                                            :items="estimates"
                                            :loading="loading"
                                            class="elevation-1"
                                        >
                                            <template
                                                v-slot:item.landing="{ item }"
                                            >{{ item.landing ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.confirmation_form="{ item }"
                                            >{{ item.confirmation_form ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.ticket_sale="{ item }"
                                            >{{ item.ticket_sale ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.free_ticket="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.administration="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.graphical_design="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.registering="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.lanyards="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.credentials="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.collectors_half="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.collectors_full="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.development="{ item }"
                                            >{{ item.free_ticket ? $t('true') : $t('false') }}</template>
                                            <template
                                                v-slot:item.status="{ item }"
                                            >{{ (item.status == 0 ? $t('pending') : $t('used')).capitalize() }}</template>
                                            <template
                                                v-slot:item.net_total="{ item }"
                                            >{{ item.net_total | currency }}</template>
                                            <template
                                                v-slot:item.action="{ item }"
                                            >
                                                <v-tooltip bottom>
                                                    <template
                                                        v-slot:activator="{ on }"
                                                    >
                                                        <v-icon
                                                            small
                                                            v-on="on"
                                                            @click="goToEstimate(item)"
                                                        >mdi-eye</v-icon>
                                                    </template>
                                                    <span>{{ $t('show') }}</span>
                                                </v-tooltip>
                                                <v-tooltip
                                                    bottom
                                                    v-if="item.event"
                                                >
                                                    <template
                                                        v-slot:activator="{ on }"
                                                    >
                                                        <v-icon
                                                            small
                                                            v-on="on"
                                                            @click="goToEvent(item)"
                                                        >event</v-icon>
                                                    </template>
                                                    <span>{{ item.event.name }}</span>
                                                </v-tooltip>
                                                <template v-else>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{ on }"
                                                        >
                                                            <v-icon
                                                                v-on="on"
                                                                small
                                                                @click="createEvent(item)"
                                                            >mdi-calendar-plus</v-icon>
                                                        </template>
                                                        <span>{{ $t('add').capitalize() + ' ' + $t('event.title')}}</span>
                                                    </v-tooltip>
                                                    <v-tooltip bottom>
                                                        <template
                                                            v-slot:activator="{ on }"
                                                        >
                                                            <v-icon
                                                                v-on="on"
                                                                small
                                                                @click="deleteEstimate(item)"
                                                            >mdi-delete</v-icon>
                                                        </template>
                                                        <span>{{ $t('delete').capitalize() }}</span>
                                                    </v-tooltip>
                                                </template>
                                            </template>
                                        </v-data-table>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog
            v-if="estimate"
            v-model="dialogEstimate"
            :overlay="false"
            max-width="80%"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title primary-title>HEADER</v-card-title>

                <v-divider></v-divider>

                <v-card-text>
                    <v-row>
                        <v-col cols="6">COT-{{ estimate.id }}</v-col>
                        <v-col
                            cols="6"
                        >Evento Asociado: {{ estimate.event ? estimate.event.name : "Ninguno"}}</v-col>
                    </v-row>
                    <v-row v-if="estimate.landing">
                        <v-col cols="3" offset="3">Landing</v-col>
                        <v-col cols="3" class="text-right"></v-col>
                        <v-col cols="2" class="text-right">$55000</v-col>
                    </v-row>
                    <v-row v-if="estimate.confirmation_form">
                        <v-col cols="3" offset="3">Confirmación de Asistencia</v-col>
                        <v-col cols="3" class="text-right"></v-col>
                        <v-col cols="2" class="text-right">$15000</v-col>
                    </v-row>
                    <v-row v-if="estimate.ticket_sale">
                        <v-col cols="3" offset="3">Venta de Tickets</v-col>
                        <v-col cols="3" class="text-right">%9</v-col>
                        <v-col cols="2" class="text-right"></v-col>
                    </v-row>
                    <v-row v-if="estimate.mailings">
                        <v-col cols="3" offset="3">Envios de Correos</v-col>
                        <v-col
                            cols="3"
                            class="text-right"
                        >{{ estimate.mailings_quantity }}</v-col>
                        <v-col
                            cols="2"
                            class="text-right"
                        >{{ estimate.mailings_quantity * 5 | currency}}</v-col>
                    </v-row>
                    <v-row v-if="estimate.polls">
                        <v-col cols="3" offset="3">Encuestas</v-col>
                        <v-col
                            cols="3"
                            class="text-right"
                        >{{ estimate.polls_quantity }}</v-col>
                        <v-col
                            cols="2"
                            class="text-right"
                        >{{ estimate.polls_quantity * 10000 | currency}}</v-col>
                    </v-row>
                    <v-row v-if="estimate.register_keys">
                        <v-col cols="3" offset="3">Llaves de Registro</v-col>
                        <v-col
                            cols="3"
                            class="text-right"
                        >{{ estimate.register_keys_quantity }}</v-col>
                        <v-col
                            cols="2"
                            class="text-right"
                        >{{ estimate.register_keys_quantity * 20000 | currency }}</v-col>
                    </v-row>
                    <span v-for="extra in estimate.extras" :key="extra.id">
                        <v-row>
                            <v-col cols="11" offset="1">COT-{{ extra.id }}</v-col>
                        </v-row>
                        <v-row v-if="extra.landing">
                            <v-col cols="3" offset="3">Landing</v-col>
                            <v-col cols="3" class="text-right"></v-col>
                            <v-col cols="2" class="text-right">$55000</v-col>
                        </v-row>
                        <v-row v-if="extra.confirmation_form">
                            <v-col
                                cols="3"
                                offset="3"
                            >Confirmación de Asistencia</v-col>
                            <v-col cols="3" class="text-right"></v-col>
                            <v-col cols="2" class="text-right">$15000</v-col>
                        </v-row>
                        <v-row v-if="extra.ticket_sale">
                            <v-col cols="3" offset="3">Venta de Tickets</v-col>
                            <v-col cols="3" class="text-right">%9</v-col>
                            <v-col cols="2" class="text-right"></v-col>
                        </v-row>
                        <v-row v-if="extra.mailings">
                            <v-col cols="3" offset="3">Envios de Correos</v-col>
                            <v-col
                                cols="3"
                                class="text-right"
                            >{{ extra.mailings_quantity }}</v-col>
                            <v-col
                                cols="2"
                                class="text-right"
                            >{{ extra.mailings_quantity * 5 | currency}}</v-col>
                        </v-row>
                        <v-row v-if="extra.polls">
                            <v-col cols="3" offset="3">Encuestas</v-col>
                            <v-col
                                cols="3"
                                class="text-right"
                            >{{ extra.polls_quantity }}</v-col>
                            <v-col
                                cols="2"
                                class="text-right"
                            >{{ extra.polls_quantity * 10000 | currency}}</v-col>
                        </v-row>
                        <v-row v-if="extra.register_keys">
                            <v-col cols="3" offset="3">Llaves de Registro</v-col>
                            <v-col
                                cols="3"
                                class="text-right"
                            >{{ extra.register_keys_quantity }}</v-col>
                            <v-col
                                cols="2"
                                class="text-right"
                            >{{ extra.register_keys_quantity * 20000 | currency }}</v-col>
                        </v-row>
                    </span>
                    <v-divider inset></v-divider>
                    <v-row>
                        <v-col cols="3" offset="3">Total</v-col>
                        <v-col cols="3" class="text-right"></v-col>
                        <v-col
                            cols="2"
                            class="text-right"
                        >{{ estimate.net_total | currency }}</v-col>
                    </v-row>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>FOOTER</v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { forEach } from 'lodash'
export default {
    data: function() {
        return {
            searchParticipants: null,
            tempSearchParticipants: null,
            dialog: false,
            dialogEstimate: false,
            panelEstimate: 0,
            panelEventos: 0,
            estimate: null,
            estimates: [],
            events: [],
            estimateHeaders: [
                {
                    text: 'ID',
                    align: 'left',
                    value: 'id'
                },

                {
                    text: this.$t('estimate.landing').capitalize(),
                    align: 'left',
                    value: 'landing'
                },
                {
                    text: this.$t('estimate.confirmationForm').capitalize(),
                    align: 'left',
                    value: 'confirmation_form'
                },
                {
                    text: this.$t('estimate.mailings').capitalize(),
                    value: 'mailings_quantity'
                },
                {
                    text: this.$t('estimate.poll').capitalize(),
                    value: 'polls_quantity'
                },
                {
                    text: this.$t('estimate.registerKey').capitalize(),
                    value: 'register_keys_quantity'
                },
                {
                    text: this.$t('estimate.ticketSale').capitalize(),
                    value: 'ticket_sale'
                },
                // {
                //     text: this.$t('estimate.freeTickets').capitalize(),
                //     value: 'free_ticket'
                // },
                // {
                //     text: this.$t('estimate.administration').capitalize(),
                //     value: 'administration'
                // },
                // {
                //     text: this.$t('estimate.graphical_design').capitalize(),
                //     value: 'graphical_design'
                // },
                // {
                //     text: this.$t('estimate.registering').capitalize(),
                //     value: 'registering'
                // },
                // {
                //     text: this.$t('estimate.lanyards').capitalize(),
                //     value: 'lanyards'
                // },
                // {
                //     text: this.$t('estimate.credentials').capitalize(),
                //     value: 'credentials'
                // },
                // {
                //     text: this.$t('estimate.collectors_half').capitalize(),
                //     value: 'collectors_half'
                // },
                // {
                //     text: this.$t('estimate.collectors_full').capitalize(),
                //     value: 'collectors_half'
                // },
                // {
                //     text: this.$t('estimate.development').capitalize(),
                //     value: 'collectors_half'
                // },
                {
                    text: this.$t('estimate.netTotal').capitalize(),
                    value: 'net_total'
                },
                // {
                //     text: this.$t('form.created_at').capitalize(),
                //     value: 'created_at'
                // },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    sortable: false
                }
            ],
            eventHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('form.description').capitalize(),
                    align: 'left',
                    value: 'description'
                },
                {
                    text: this.$t('form.start_date').capitalize(),
                    align: 'left',
                    value: 'start_date'
                },
                {
                    text: this.$t('status').capitalize(),
                    align: 'left',
                    value: 'status'
                }
            ],
            loading: false
        }
    },
    methods: {
        goToEstimate(estimate) {
            this.estimate = estimate
            this.dialogEstimate = true
        },

        createEvent(estimate) {
            this.$router.push({ name: 'event', params: { estimate } })
        },

        goToEvent(estimate) {
            estimate.event.estimate = estimate
            this.$router.push({
                name: 'event',
                params: { event: estimate.event }
            })
        },
        deleteEstimate(estimate) {
            this.loading = true
            this.axios
                .delete(`/estimates/${estimate.id}`)
                .then(response => {
                    this.$snotify.success(this.$t('estimate.delete.success'))
                    this.estimates = response.data.estimates
                    this.close()
                })
                .catch(error => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(
                        errors,
                        this.$t('estimate.delete.failure')
                    )
                })
                .finally(() => {
                    this.loading = false
                })
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.order = Object.assign({}, {})
            }, 300)
        }
    },
    mounted() {
        this.loading = true
        let estimates, events
        estimates = this.axios
            .get(`/estimates`)
            .then(response => {
                this.estimates = response.data.estimates
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        events = this.axios
            .get(`/events`)
            .then(response => {
                this.events = response.data.events
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        Promise.all([estimates, events])
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
</style>
