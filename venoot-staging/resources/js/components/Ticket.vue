<template>
    <v-form data-vv-scope="ticketForm">
        <v-container grid-list-md>
            <v-layout wrap>
                <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:60'"
                        data-vv-name="name"
                        :error-messages="errors.collect('ticketForm.name')"
                        :counter="60"
                        required
                        prepend-icon="mdi-text-short"
                        v-model="ticket.name"
                        :label="$t('form.name').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs2>
                    <v-text-field
                        type="number"
                        v-validate="'required|integer|min_value:0'"
                        data-vv-name="quantity"
                        :error-messages="errors.collect('ticketForm.quantity')"
                        prepend-icon="mdi-ticket"
                        v-model="ticket.quantity"
                        :label="$t('form.quantity').capitalize()"
                    ></v-text-field>
                </v-flex>
                <v-flex xs3>
                    <v-text-field
                        type="number"
                        v-validate="'required|integer|min_value:0'"
                        data-vv-name="price"
                        :error-messages="errors.collect('ticketForm.price')"
                        prepend-icon="mdi-cash-usd"
                        v-model="ticket.price"
                        :label="$t('form.price').capitalize()"
                    ></v-text-field>
                </v-flex>

                <v-flex xs2>
                    <v-select
                        :items="['CPL', 'USD']"
                        v-validate="'required|in:CPL,USD'"
                        data-vv-name="currency"
                        :error-messages="errors.collect('ticketForm.currency')"
                        prepend-icon="mdi-cash-usd"
                        v-model="ticket.currency"
                        label="Moneda"
                    ></v-select>
                </v-flex>

                <v-flex xs8>
                    <v-textarea
                        v-validate="'max:120'"
                        data-vv-name="description"
                        :error-messages="errors.collect('ticketForm.description')"
                        :counter="120"
                        required
                        prepend-icon="work"
                        v-model="ticket.description"
                        :label="$t('form.description').capitalize()"
                        rows="2"
                    ></v-textarea>
                </v-flex>
                <v-flex xs2>
                    <v-switch
                        :label="$t('tickets.available')"
                        v-model="ticket.available"
                    ></v-switch>
                </v-flex>
                <v-flex xs2>
                    <v-btn
                        color="error"
                        @click="$emit('remove-ticket', index)"
                    >{{ $t('delete') }}</v-btn>
                </v-flex>

                <v-flex xs4>
                    <v-select
                        v-model="ticket.protection"
                        :items="[
                            {
                                name: 'Ninguna',
                                value: null
                            },
                            {
                                name: 'Llave',
                                value: 'key'
                            },
                            {
                                name: 'Por Correo',
                                value: 'email'
                            },
                        ]"
                        item-text="name"
                        item-value="value"
                        label="Protección"
                    ></v-select>
                </v-flex>

                <template v-if="ticket.protection === 'key'">
                    <v-flex xs2>
                         <v-text-field
                            type="number"
                            v-validate="'required|integer|min_value:0'"
                            data-vv-name="protection_quantity"
                            :error-messages="errors.collect('ticketForm.protection_quantity')"
                            prepend-icon="mdi-ticket-account"
                            v-model="ticket.protection_quantity"
                            label="Cantidad por Protección"
                        ></v-text-field>
                    </v-flex>

                    <v-flex xs3>
                        <v-select
                            v-model="ticket.database_id"
                            :items="databases"
                            item-text="name"
                            item-value="id"
                            label="Database"
                            required
                        ></v-select>
                    </v-flex>

                     <v-flex xs3>
                        <v-select
                            v-model="ticket.key"
                            :items="keys"
                            item-text="name"
                            item-value="key"
                            label="Database"
                            required
                        ></v-select>
                    </v-flex>
                </template>

                <template v-else-if="ticket.protection === 'email'">
                    <v-flex xs2>
                         <v-text-field
                            type="number"
                            v-validate="'required|integer|min_value:0'"
                            data-vv-name="protection_quantity"
                            :error-messages="errors.collect('ticketForm.protection_quantity')"
                            prepend-icon="mdi-ticket-account"
                            v-model="ticket.protection_quantity"
                            label="Cantidad por Protección"
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs3>
                        <v-select
                            v-model="ticket.database_id"
                            :items="databases"
                            item-text="name"
                            item-value="id"
                            label="Database"
                            required
                        ></v-select>
                    </v-flex>
                    <v-flex xs3>
                        <v-text-field
                            v-validate="'required|email'"
                            data-vv-name="email"
                            :error-messages="errors.collect('ticketForm.email')"
                            required
                            prepend-icon="at"
                            v-model="ticket.email"
                            label="Correo Electrónico"
                        ></v-text-field>
                    </v-flex>
                </template>

                <template v-else>
                    <v-flex xs8>
                    </v-flex>
                </template>


                <v-flex xs1>{{ $t('link.discounts') }}</v-flex>
                <v-flex xs9>
                    <v-chip
                        v-for="discount of discounts"
                        :key="discount.id"
                    >{{ discount.name }}</v-chip>
                </v-flex>
                <v-flex xs2 v-if="ticket.id">
                    <v-btn
                        color="success"
                        @click="$router.push('/discounts')"
                    >{{ $t('goto') + ' ' + $t('link.discounts') }}</v-btn>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>

<script>
import Templates from '../views/Templates.vue'
export default {
    components: { Templates },
    props: {
        ticket: {
            type: Object,
            default: { quantity: 0, price: 0 }
        },
        index: {
            type: Number,
            default: 0
        },
        databases: {
            type: Array,
            default: []
        }
    },
    data: () => ({ discounts: [] }),

    computed: {
        protection () {
            return this.ticket.protection
        },

        keys () {
            if (!this.ticket.database_id) {
                return []
            }

            else {
                return this.databases.find(db => db.id === this.ticket.database_id).fields
            }
        }
    },

    watch: {
        protection (val) {
            switch (val) {
                case null:
                    this.ticket.protection_quantity = null
                    this.ticket.database_id = null
                    this.ticket.key = null
                    this.ticket.email = null
                    break

                case 'email':
                    this.ticket.key = null
                    break

                case 'key':
                    this.ticket.key = null
                    break
            }
        }
    },

    created() {
        if (this.ticket.id) {
            this.axios
                .get(`/tickets/${this.ticket.id}/discounts`)
                .then(response => {
                    this.discounts = response.data.discounts
                })
                .catch(error => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })
                .finally(() => {
                    this.loading = false
                })
        } else {
            this.discounts = []
        }
    }
}
</script>

<style>
</style>
