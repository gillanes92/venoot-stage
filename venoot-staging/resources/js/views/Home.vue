<template>
    <v-content>
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-toolbar color="primary">
                        <v-toolbar-title
                            class="white--text"
                        >{{ $t('link.estimates').toUpperCase() }}</v-toolbar-title>
                    </v-toolbar>
                    <v-data-table
                        :headers="estimateHeaders"
                        :items="estimates"
                        class="elevation-1"
                        loading="true"
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
                        >{{ (item.has_unpaid_ids ? $t('unpaid') : $t('paid')).capitalize() }}</template>
                        <template
                            v-slot:item.net_total="{ item }"
                        >{{ item.net_total | currency }}</template>
                        <template v-slot:item.action="{ item }">
                            <v-tooltip bottom v-if="item.event">
                                <template v-slot:activator="{ on }">
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
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-on="on"
                                            small
                                            @click="createEvent(item)"
                                        >mdi-calendar-plus</v-icon>
                                    </template>
                                    <span>{{ $t('add').capitalize() + ' ' + $t('event.title')}}</span>
                                </v-tooltip>
                                <!-- <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-on="on"
                                            small
                                            @click="deleteEstimate(item)"
                                        >mdi-delete</v-icon>
                                    </template>
                                    <span>{{ $t('delete').capitalize() }}</span>
                                </v-tooltip>-->
                            </template>
                        </template>
                    </v-data-table>

                    <v-col cols="12">
                        <v-toolbar color="primary">
                            <v-toolbar-title
                                class="white--text"
                            >{{ $t('link.events').toUpperCase() }}</v-toolbar-title>
                        </v-toolbar>
                        <v-data-table
                            :headers="eventHeaders"
                            :items="events"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template
                                v-slot:item.status="{ item }"
                            >{{ $t(item.status).capitalize() }}</template>
                        </v-data-table>
                    </v-col>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    name: 'home',
    data: function() {
        return {
            searchParticipants: null,
            tempSearchParticipants: null,
            dialog: false,
            panelEstimate: 0,
            panelEventos: 0,
            estimates: [],
            events: [],
            estimateHeaders: [
                {
                    text: 'ID',
                    align: 'left',
                    value: 'id'
                },

                {
                    text: this.$t('form.created_at').capitalize(),
                    align: 'left',
                    value: 'created_at'
                },

                {
                    text: this.$t('status').capitalize(),
                    value: 'status'
                },

                // {
                //     text: this.$t('estimate.landing').capitalize(),
                //     align: 'left',
                //     value: 'landing'
                // },
                // {
                //     text: this.$t('estimate.confirmationForm').capitalize(),
                //     align: 'left',
                //     value: 'confirmation_form'
                // },
                // {
                //     text: this.$t('estimate.mailings').capitalize(),
                //     value: 'mailings_quantity'
                // },
                // {
                //     text: this.$t('estimate.poll').capitalize(),
                //     value: 'polls_quantity'
                // },
                // {
                //     text: this.$t('estimate.registerKey').capitalize(),
                //     value: 'register_keys_quantity'
                // },
                // {
                //     text: this.$t('estimate.ticketSale').capitalize(),
                //     value: 'ticket_sale'
                // },
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
                    text: 'ID',
                    align: 'left',
                    value: 'estimate.id'
                },
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                // {
                //     text: this.$t('form.description').capitalize(),
                //     align: 'left',
                //     value: 'description'
                // },
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
        createEvent(estimate) {
            this.$router.push({ name: 'event', params: { estimate } })
        },

        goToEvent(estimate) {
            estimate.event.estimate = estimate
            this.$router.push({
                name: 'event',
                params: { event: estimate.event }
            })
        }
    },

    created() {
        this.loading = true
        this.axios
            .get(`/home`)
            .then(response => {
                this.estimates = response.data.estimates
                this.events = response.data.events
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
            })
            .finally(() => {
                this.loading = false
            })

        if (this.$auth.user().category === 'webinar') {
            this.$router.push('/webinardashboard')
        }
    },

    async mounted() {
        while (!this.$auth.ready()) {
            await new Promise((resolve) => setTimeout(() => resolve(true), 500))
        }

        console.log('here')
        if (window.localStorage.getItem('goTo')) {
            var goTo = window.localStorage.getItem('goTo')
            window.localStorage.removeItem('goTo')
            this.$router.push('/' + goTo)
        }
    }
}
</script>

<style>
</style>
