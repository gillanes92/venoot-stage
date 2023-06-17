<template>
    <v-row id="summary" no-gutters>
        <template v-if="currentSummary === 'ticket_sale'">
            <v-col cols="12">
                <v-card>
                    <v-card-title class="pb-0">
                        <v-row no-gutters>
                            <v-col>
                                {{ $t('form.tickets') }}
                            </v-col>
                            <v-col cols="auto">
                                <v-select
                                    v-model="currentSummary"
                                    :items="options"
                                    label="Resumen"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-card-title>

                    <v-card-text>
                        <v-row v-for="ticket in event.tickets" :key="ticket.id" no-gutters>
                            <v-col cols="12">
                                <v-row no-gutters>
                                    <v-col cols="12">
                                        <span>
                                            {{ ticket.name }}
                                        </span>
                                        <v-spacer></v-spacer>
                                        <span>
                                            {{ ticket.quantity - ticket.left }}
                                            /
                                            {{ ticket.quantity }}
                                        </span>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col cols="12">
                                        <v-progress-linear
                                            :value="(100 * (ticket.quantity - ticket.left)) / ticket.quantity"
                                            height="25"
                                            color="light-blue"
                                        >
                                            <strong>
                                                {{ (100 * (ticket.quantity - ticket.left)) / ticket.quantity }}%
                                            </strong>
                                        </v-progress-linear>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </template>
        <template v-else-if="currentSummary === 'confirmation'">
            <v-col cols="12">
                <v-card>
                    <v-card-title class="pb-0">
                        <v-row no-gutters>
                            <v-col>
                                {{ 'Confirmaciones' }}
                            </v-col>
                            <v-col cols="auto">
                                <v-select
                                    v-model="currentSummary"
                                    :items="options"
                                    label="Resumen"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6" xl="3" class="mb-1">
                                <v-card color="blue lighten-1">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ participants.length }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.contacts').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="blue lighten-3" size="80"
                                                    >mdi-account-box-multiple</v-icon
                                                >
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" xl="3" class="mb-2">
                                <v-card color="yellow darken-3">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ confirmed }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.confirmed').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="yellow" size="80">mdi-account-check</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" xl="3" class="mb-2">
                                <v-card color="green darken-1">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ registered }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.registered').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="green lighten-2" size="80">mdi-account-badge</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" xl="3" class="mb-2">
                                <v-card color="indigo">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text">
                                                    {{ $t('event.confirmed').capitalize() }}
                                                    {{ confirmedEfectivity }}%
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text">
                                                    {{ $t('event.registered').capitalize() }}
                                                    {{ registeredEfectivity }}%
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.efectivity').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="indigo lighten-2" size="80">mdi-account-convert</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </template>
        <template v-else-if="currentSummary === 'app'">
            <v-col cols="12">
                <v-card>
                    <v-card-title class="pb-0">
                        <v-row no-gutters>
                            <v-col>
                                {{ 'App' }}
                            </v-col>
                            <v-col cols="auto">
                                <v-select
                                    v-model="currentSummary"
                                    :items="options"
                                    label="Resumen"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6" xl="4" class="mb-1">
                                <v-card color="blue lighten-1">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ participants.length }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.contacts').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="blue lighten-3" size="80"
                                                    >mdi-account-box-multiple</v-icon
                                                >
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" xl="4" class="mb-2">
                                <v-card color="yellow darken-3">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ onApp }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.using_app').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="yellow" size="80">mdi-account-check</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" xl="4" class="mb-2">
                                <v-card color="indigo">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ onAppEffectivity }}%
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.efectivity').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="indigo lighten-2" size="80">mdi-account-convert</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </template>
        <template v-else-if="currentSummary === 'mailings'">
            <v-col cols="12">
                <v-card>
                    <v-card-title class="pb-0">
                        <v-row>
                            <v-col>
                                {{ 'Envios' }}
                            </v-col>
                            <v-col cols="auto">
                                <v-select
                                    v-model="currentSummary"
                                    :items="options"
                                    label="Resumen"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-card-title>

                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6" class="mb-1">
                                <v-card color="blue lighten-1">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ participants.length }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.contacts').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="blue lighten-3" size="80"
                                                    >mdi-account-box-multiple</v-icon
                                                >
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" class="mb-2">
                                <v-card color="yellow darken-3">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ event.sent_mail_count }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.mailingQuantity').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="yellow" size="80">mdi-email-multiple</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </template>
        <template v-else-if="currentSummary === 'polls'">
            <v-col cols="12">
                <v-card>
                    <v-card-title class="pb-0">
                        <v-row no-gutters>
                            <v-col>
                                {{ $t('link.polls').capitalize() }}
                            </v-col>
                            <v-col cols="auto">
                                <v-select
                                    v-model="currentSummary"
                                    :items="options"
                                    label="Resumen"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12" sm="6" xl="4" class="mb-1">
                                <v-card color="blue lighten-1">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ participants.length }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.contacts').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="blue lighten-3" size="80"
                                                    >mdi-account-box-multiple</v-icon
                                                >
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" sm="6" xl="4" class="mb-2">
                                <v-card color="yellow darken-3">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ pollAverage }}
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.poll_average').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="yellow" size="80">mdi-account-check</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" xl="4" class="mb-2">
                                <v-card color="indigo">
                                    <v-card-text>
                                        <v-list-item two-line>
                                            <v-list-item-content>
                                                <v-list-item-title class="white--text display-1">
                                                    {{ onPollAverageEffectivity }}%
                                                </v-list-item-title>
                                                <v-list-item-title class="white--text headline">
                                                    {{ $t('event.efectivity').capitalize() }}
                                                </v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-avatar>
                                                <v-icon color="indigo lighten-2" size="80">mdi-account-convert</v-icon>
                                            </v-list-item-avatar>
                                        </v-list-item>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </template>
    </v-row>
</template>

<script>
export default {
    name: 'Summary',

    props: {
        event: {
            type: Object,
            default: [],
        },

        participants: {
            type: Array,
            default: [],
        },

        polls: {
            type: Array,
            default: [],
        },
    },

    data() {
        return {
            currentSummary: null,
        }
    },

    computed: {
        options() {
            let options = []
            if (this.event.estimate.ticket_sale) {
                options.push({
                    text: 'Ventas',
                    value: 'ticket_sale',
                })
            }

            if (this.event.estimate.confirmation_form) {
                options.push({
                    text: 'Confirmación',
                    value: 'confirmation',
                })
            }

            if (this.event.estimate.app) {
                options.push({
                    text: 'App',
                    value: 'app',
                })
            }

            if (this.event.estimate.mailings) {
                options.push({
                    text: 'Envios',
                    value: 'mailings',
                })
            }

            if (this.event.estimate.polls) {
                options.push({
                    text: 'Encuentas',
                    value: 'polls',
                })
            }

            return options
        },

        confirmed() {
            return _.filter(this.participants, ['confirmed_status', true]).length
        },
        confirmedEfectivity() {
            if (this.event.quota && this.event.quota >= 0) {
                return Math.round((100 * this.confirmed) / this.event.quota)
            } else {
                if (this.participants.length === 0) return 0
                return Math.round((100 * this.confirmed) / this.participants.length)
            }
        },
        registered() {
            return _.reject(this.participants, ['registered_at', null]).length
        },
        registeredEfectivity() {
            if (this.event.quota && this.event.quota >= 0) {
                return Math.round((100 * this.registered) / this.event.quota)
            } else {
                if (this.participants.length == 0) return 0
                return Math.round((100 * this.registered) / this.participants.length)
            }
        },
        onApp() {
            return _.reject(this.participants, ['expo_token', null]).length
        },
        onAppEffectivity() {
            if (!this.participants.length) return 0
            return Math.round((100 * this.onApp) / this.participants.length)
        },

        pollAverage() {
            return _.meanBy(this.polls, 'complete')
        },

        onPollAverageEffectivity() {
            if (!this.participants.length) return 0
            return Math.round((100 * this.pollAverage) / this.participants.length)
        },
    },

    mounted() {
        this.currentSummary = this.options[0].value
    },
}
</script>
