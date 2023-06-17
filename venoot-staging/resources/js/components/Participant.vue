<template>
    <v-card>
        <v-card-title primary-title>
            <v-list-item two-line>
                <v-list-item-content>
                    <v-list-item-title>{{ participant.name }} {{ participant.lastname }}</v-list-item-title>
                    <v-list-item-subtitle>{{ participant.email }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-spacer></v-spacer>
            <v-btn
                fab
                small
                color="success"
                :loading="loading"
                @click="exportParticipant"
            >
                <v-icon>mdi-file-excel</v-icon>
            </v-btn>
        </v-card-title>

        <v-card-text>
            <span class="title">Eventos</span>
            <br />
            <v-data-table
                :headers="headers"
                :items="participationsWithEvent"
                :items-per-page="5"
                class="elevation-1"
            >
                <template v-slot:item.name="{ item }">{{ item.event.name }}</template>
                <template v-slot:item.confirm="{ item }">{{ item.confirmed_at }}</template>
                <template
                    v-slot:item.register="{ item }"
                >{{ item.registered_at}}</template>
            </v-data-table>
            <br />
            <br />
            <span class="title">Ordenes</span>
            <br />
            <v-data-table
                :headers="headersOrders"
                :items="orders"
                :items-per-page="5"
                class="elevation-1"
            >
                <template
                    v-slot:item.total="{ item }"
                >{{ item.total | currency }}</template>
                <!-- <template
                    v-slot:item.return_url="{ item }"
                >{{ url(item.return_url) }}</template> -->
                <template
                    v-slot:item.status="{ item }"
                >{{ status(item.status) }}</template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    data: () => ({
        participationsWithEvent: [],
        orders: [],
        headers: [
            { text: 'Nombre Evento', value: 'name' },
            { text: 'Confirme en', value: 'confirm' },
            { text: 'Acredito en', value: 'register' }
        ],
        headersOrders: [
            { text: 'Order Number', value: 'number' },
            { text: 'Actualizada en', value: 'updated_at' },
            // { text: 'Desde', value: 'return_url' },
            { text: 'Total', value: 'total' },
            { text: 'Status', value: 'status' }
        ]
    }),

    props: ['participant', 'loading'],

    watch: {
        participant: function(val, old) {
            if (old !== val) {
                this.loadData()
            }
        }
    },

    methods: {
        exportParticipant() {
            this.$emit('setLoading', true)
            this.axios
                .get(`/participants/${this.participant.id}/export`, {
                    responseType: 'blob'
                })
                .then(response => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    )
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute(
                        'download',
                        `${this.participant.name}${this.participant.lastname}.xlsx`
                    )
                    document.body.appendChild(link)
                    link.click()
                })
                .finally(() => {
                    this.$emit('setLoading', false)
                })
        },
        url(u) {
            const url = new URL(u)
            return url.hostname
        },
        status(s) {
            switch (s) {
                case 0:
                    return 'Pendiente'
                case 1:
                    return 'Pagada'
                case 2:
                    return 'Anulada'
                case 3:
                    return 'Rechazada'

                default:
                    return ''
            }
        },
        loadData() {
            this.axios
                .get(`participations-data/${this.participant.email}`)
                .then(response => {
                    this.participationsWithEvent = []
                    response.data.participations.forEach(participant => {
                        this.participationsWithEvent = this.participationsWithEvent.concat(
                            participant.participations
                        )
                    })
                    this.orders = response.data.orders
                })
        }
    },

    created() {
        this.loadData()
    }
}
</script>

<style>
</style>
