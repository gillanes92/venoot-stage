<template>
    <div class="discounts">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar text color="primary">
                            <v-toolbar-title
                                class="white--text title"
                            >{{ $t('link.discounts').toUpperCase() }}</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="70%">
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                        color="success"
                                        dark
                                        class="mb-2"
                                        v-on="on"
                                    >{{ $t('form.new') }}</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span
                                            class="headline"
                                        >{{ discount.id ? $t('edit').capitalize() : $t('form.new').capitalize() }} {{ $t('discount.title') }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form ref="form">
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12>
                                                        <v-text-field
                                                            v-validate="'required|max:50'"
                                                            data-vv-name="name"
                                                            :error-messages="errors.collect('name')"
                                                            :counter="50"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="discount.name"
                                                            :label="$t('form.name').capitalize()"
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 xl6>
                                                        <v-select
                                                            :items="itemsTrigger"
                                                            v-model="discount.trigger"
                                                            :label="$t('discount.trigger').capitalize()"
                                                            required
                                                            @change="setTriggerValue"
                                                            :disabled="discount.in_use"
                                                        ></v-select>
                                                    </v-flex>
                                                    <v-flex
                                                        xs12
                                                        xl6
                                                        v-if="discount.trigger === 'none'"
                                                    ></v-flex>
                                                    <v-flex
                                                        xs12
                                                        xl6
                                                        v-if="discount.trigger === 'key'"
                                                    >
                                                        <v-text-field
                                                            v-validate="'required|max:16'"
                                                            data-vv-name="discount.trigger_value"
                                                            :error-messages="errors.collect('discount.trigger_value')"
                                                            :counter="16"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="discount.trigger_value"
                                                            :label="$t('discount.trigger_value').capitalize()"
                                                            :disabled="discount.in_use"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex
                                                        xs12
                                                        v-if="discount.trigger === 'key'"
                                                    >
                                                        <v-card>
                                                            <v-card-title
                                                                class="headline"
                                                            >{{ $t('upload').capitalize() + ' Excel' }}</v-card-title>
                                                            <v-card-text>
                                                                <file-pond
                                                                    name="keys"
                                                                    :server="serverOptionsDiscount"
                                                                    :label-idle=" $t('form.upload.idle')"
                                                                    allow-multiple="false"
                                                                    accepted-file-types="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                                    required
                                                                ></file-pond>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-flex>
                                                    <v-flex
                                                        xs8
                                                        xl4
                                                        v-if="discount.trigger === 'cupon'"
                                                    >
                                                        <v-text-field
                                                            v-validate="'required|max:16'"
                                                            data-vv-name="discount.trigger_value"
                                                            :error-messages="errors.collect('discount.trigger_value')"
                                                            :counter="16"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="discount.trigger_value"
                                                            :label="$t('discount.trigger_value').capitalize()"
                                                            :disabled="discount.in_use"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex
                                                        xs4
                                                        xl2
                                                        v-if="discount.trigger === 'cupon'"
                                                    >
                                                        <v-text-field
                                                            v-validate="'integer'"
                                                            data-vv-name="discount.quota"
                                                            :error-messages="errors.collect('discount.quota')"
                                                            prepend-icon="work"
                                                            v-model="discount.quota"
                                                            :label="$t('discount.quota').capitalize()"
                                                            :disabled="discount.in_use"
                                                            type="number"
                                                            clearable
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex
                                                        xs12
                                                        xl6
                                                        v-if="discount.trigger === 'quantity'"
                                                    >
                                                        <v-text-field
                                                            v-validate="'required'"
                                                            data-vv-name="discount.trigger_quantity"
                                                            :error-messages="errors.collect('discount.trigger_quantity')"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="discount.trigger_quantity"
                                                            :label="$t('discount.trigger_quantity').capitalize()"
                                                            type="number"
                                                            :disabled="discount.in_use"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 xl6>
                                                        <v-select
                                                            :items="itemsType"
                                                            v-model="discount.type"
                                                            :label="$t('discount.type').capitalize()"
                                                            required
                                                            :disabled="discount.in_use"
                                                        ></v-select>
                                                    </v-flex>

                                                    <v-flex xs12 xl6>
                                                        <v-text-field
                                                            v-validate="'required'"
                                                            data-vv-name="discount.type_quantity"
                                                            :error-messages="errors.collect('discount.type_quantity')"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="discount.type_quantity"
                                                            :label="$t('discount.type_quantity').capitalize()"
                                                            type="number"
                                                            :disabled="discount.in_use"
                                                        ></v-text-field>
                                                    </v-flex>

                                                    <v-combobox
                                                        v-model="discount.tickets"
                                                        :items="tickets"
                                                        :label="$t('form.tickets').capitalize()"
                                                        prepend-icon="mdi-ticket"
                                                        multiple
                                                        chips
                                                        :disabled="discount.in_use"
                                                    >
                                                        <template
                                                            slot="selection"
                                                            slot-scope="data"
                                                        >
                                                            <v-chip>{{ data.item.name }} ({{ data.item.event_name }})</v-chip>
                                                        </template>
                                                        <template
                                                            slot="item"
                                                            slot-scope="data"
                                                        >{{ data.item.name }} ({{ data.item.event_name }})</template>
                                                    </v-combobox>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="close"
                                        >{{ $t('cancel') }}</v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="update"
                                        >{{ $t('accept') }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                        <v-data-table
                            :headers="discountHeaders"
                            :items="discounts"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template
                                v-slot:item.trigger="{ item }"
                            >{{ $t('discount.' + item.trigger).capitalize() }}</template>

                            <template
                                v-slot:item.type="{ item }"
                            >{{ $t('discount.' + item.type).capitalize() }}</template>

                            <template v-slot:item.tickets="{ item }">
                                <v-chip
                                    v-for="(ticket, index) in item.tickets"
                                    :key="index"
                                    class="mr-1"
                                >{{ ticket.name }} ({{ ticket.event_name }})</v-chip>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editDiscount(item)"
                                >mdi-pencil</v-icon>
                                <v-icon
                                    small
                                    @click="deleteDiscount(item)"
                                >mdi-cancel</v-icon>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </div>
</template>

<script>
import { FilePond, loadUrl } from '../app'
import { forEach } from 'lodash'
import { Promise } from 'q'
export default {
    data: function() {
        return {
            dialog: false,
            discountModel: {
                trigger: 'none',
                type: 'percentual',
                type_quantity: 0
            },
            discount: {},
            discounts: [],
            tickets: {},
            discountHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('discount.trigger').capitalize(),
                    value: 'trigger'
                },
                {
                    text: this.$t('discount.type').capitalize(),
                    value: 'type'
                },
                {
                    text: this.$t('form.tickets').capitalize(),
                    value: 'tickets'
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    sortable: false
                }
            ],
            loading: false,
            itemsType: [
                {
                    text: this.$t('discount.percentual').capitalize(),
                    value: 'percentual'
                },
                { text: this.$t('discount.flat').capitalize(), value: 'falt' }
            ],
            itemsTrigger: [
                { text: this.$t('none').capitalize(), value: 'none' },
                {
                    text: this.$t('discount.trigger_quantity').capitalize(),
                    value: 'quantity'
                },
                {
                    text: this.$t('discount.trigger_cupon').capitalize(),
                    value: 'cupon'
                },
                {
                    text: this.$t('discount.trigger_key').capitalize(),
                    value: 'key'
                }
            ],
            serverOptionsDiscount: {
                process: {
                    url: `./api/discounts/uploadKeyValues`,
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`
                    },
                    onload: response => {
                        let responseJson = JSON.parse(response)
                        this.discount.excelFile = responseJson.name
                        this.$snotify.success(this.$t('discount.excel.success'))
                        this.showExcelUploadDialog = false
                    },
                    onerror: response => {
                        let responseJson = JSON.parse(response)
                        console.log(responseJson)
                        this.$snotify.error(
                            this.$t('discount.' + responseJson.error) +
                                ': ' +
                                Object.values(responseJson.keys).join(', '),
                            this.$t('discount.excel.failure')
                        )
                        this.showExcelUploadDialog = false
                    }
                },
                load: loadUrl
            }
        }
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
    },
    methods: {
        ticketId(ticket) {
            return ticket.id
        },
        setTriggerValue() {
            if (
                this.discount.trigger === 'cupon' &&
                !this.discount.trigger_value
            ) {
                this.randomTriggerValue()
            } else if (
                this.discount.trigger === 'key'
                // !this.discount.trigger_value
            ) {
                this.discount.trigger_value = 'email'
            }
        },
        randomTriggerValue() {
            this.discount.trigger_value =
                Math.random()
                    .toString(36)
                    .substring(2, 10) +
                Math.random()
                    .toString(36)
                    .substring(2, 10)
        },
        editDiscount(discount) {
            this.discount = Object.assign({}, discount)
            this.dialog = true
        },
        deleteDiscount(discount) {
            this.loading = true
            this.axios
                .delete(`/discounts/${discount.id}`)
                .then(response => {
                    this.$snotify.success(this.$t('discount.delete.success'))
                    this.discounts = response.data.discounts
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
                        this.$t('discount.delete.failure')
                    )
                })
                .finally(() => {
                    this.loading = false
                })
        },
        update() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true

                    if (this.discount.id) {
                        this.axios
                            .put(`/discounts/${this.discount.id}`, {
                                ...this.discount
                            })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('discount.update.success')
                                )
                                this.discounts = response.data.discounts
                                this.close()
                            })
                            .catch(error => {
                                this.loading = false
                                let errors = ''
                                forEach(
                                    error.response.data.errors,
                                    (value, key) => {
                                        forEach(value, (e, k) => {
                                            errors += e
                                        })
                                    }
                                )
                                this.$snotify.error(
                                    errors,
                                    this.$t('discount.update.failure')
                                )
                            })
                    } else {
                        this.axios
                            .post('/discounts', { ...this.discount })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('discount.store.success')
                                )
                                this.discounts = response.data.discounts
                                this.close()
                            })
                            .catch(error => {
                                this.loading = false
                                let errors = ''
                                forEach(
                                    error.response.data.errors,
                                    (value, key) => {
                                        forEach(value, (e, k) => {
                                            errors += e
                                        })
                                    }
                                )
                                this.$snotify.error(
                                    errors,
                                    this.$t('discount.store.failure')
                                )
                            })
                    }
                }
            })
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.discount = Object.assign({}, this.discountModel)
                if (this.$refs.form) {
                    this.$refs.form.resetValidation()
                    this.$refs.form.reset()
                }
            }, 300)
        }
    },
    mounted() {
        this.loading = true
        this.discount = Object.assign({}, this.discountModel)
        let discounts = this.axios
            .get('/discounts')
            .then(response => {
                this.discounts = response.data.discounts
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
        // .finally(() => {
        //     this.loading = false
        // })

        let tickets = this.axios
            .get('/tickets')
            .then(response => {
                this.tickets = response.data.tickets
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                // this.loading = false
            })

        Promise.all([discounts, tickets]).finally(() => (this.loading = false))
    }
}
</script>

<style>
</style>
