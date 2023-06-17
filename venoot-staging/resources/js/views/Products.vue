<template>
  <div class="products">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12>
            <v-toolbar flat color="primary">
              <v-toolbar-title class="white--text title">{{ $t('link.products').toUpperCase() }}</v-toolbar-title>
              <v-divider
                class="mx-2"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>
              <v-dialog v-model="dialog" max-width="70%">
                <template v-slot:activator="{ on }">
                  <v-btn color="success" dark class="mb-2" v-on="on">{{ $t('form.new') }}</v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">{{ $t('form.new').capitalize() + " " + $t('product.title') }}</span>
                  </v-card-title>

                  <v-card-text>
                    <v-form ref="form">
                      <v-container grid-list-md>
                        <v-layout wrap>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.event_publish"
                              color="success"
                              :label="$t('form.event_publish').capitalize()"
                              min="0"
                              max="100"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.event_landing"
                              color="success"
                              :label="$t('form.event_landing').capitalize()"
                              min="0"
                              max="100"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.poll_publish"
                              color="success"
                              :label="$t('form.poll_publish').capitalize()"
                              min="0"
                              max="100"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.mailings"
                              color="success"
                              :label="$t('form.mailings').capitalize()"
                              min="0"
                              max="500000"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.register_keys"
                              color="success"
                              :label="$t('form.register_keys').capitalize()"
                              min="0"
                              max="100"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                          <v-flex xs12>
                            <v-slider
                              v-model="order.register_days"
                              color="success"
                              :label="$t('form.register_days').capitalize()"
                              min="0"
                              max="100"
                              thumb-label="always"
                            ></v-slider>
                          </v-flex>

                        </v-layout>
                      </v-container>
                    </v-form>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="close">{{ $t('cancel') }}</v-btn>
                    <v-btn color="blue darken-1" flat @click="update">{{ $t('accept') }}</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-data-table
              :headers="ordersHeaders"
              :items="orders"
              :loading="loading"
              class="elevation-1"
            >
              <template v-slot:items="props">
                <td>{{ $t('product.title').toUpperCase() + '-' + props.item.id }}</td>
                <td>{{ props.item.event_publish }}</td>
                <td>{{ props.item.event_landing }}</td>
                <td>{{ props.item.poll_publish }}</td>
                <td>{{ props.item.mailings }}</td>
                <td>{{ props.item.register_keys }}</td>
                <td>{{ props.item.register_days }}</td>
                <td>{{ toStatus(props.item.status) }}</td>
                <td class="justify-center layout px-0">
                  <v-icon
                    v-if="props.item.status < 1"
                    small
                    class="mr-2"
                    @click="editOrder(props.item)"
                  >
                    edit
                  </v-icon>
                  <v-icon
                    v-if="props.item.status < 1"
                    small
                    class="mr-2"
                    @click="deleteOrder(props.item)"
                  >
                    cancel
                  </v-icon>

                  <v-icon
                    v-if="props.item.status < 1"
                    small
                    class="mr-2"
                    @click="payOrder(props.item)"
                  >
                    payment
                  </v-icon>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
import { forEach } from 'lodash'
export default {
  data: function () {
    return {
      dialog: false,
      order: { },
      orders: [],
      ordersHeaders: [
        {
          text: 'ID',
          align: 'left',
          value: 'id'
        },

        {
          text: this.$t('form.event_publish').capitalize(),
          align: 'left',
          value: 'event_publish'
        },
        {
          text: this.$t('form.event_landing').capitalize(),
          align: 'left',
          value: 'event_landing'
        },
        {
          text: this.$t('form.poll_publish').capitalize(),
          value: 'poll_publish'
        },
        {
          text: this.$t('form.mailings').capitalize(),
          value: 'mailings'
        },
        {
          text: this.$t('form.register_keys').capitalize(),
          value: 'register_keys'
        },
        {
          text: this.$t('form.register_days').capitalize(),
          value: 'register_days'
        },
        {
          text: this.$t('status').capitalize(),
          value: 'status'
        },
        { text: this.$t('actions').capitalize(), value: 'name', sortable: false }
      ],
      loading: false,
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    }
  },
  methods: {
    toStatus(status) {
      switch (status) {
        case 0:
          return 'Pendiente'

        case 1:
          return 'Pagado'

        default:
          return 'Error'
      }
    },
    payOrder(order) {
      this.loading = true
      this.axios.post(`/orders/${order.id}/pay`, { pay: 1 })
        .then((response) => {
          this.$snotify.success(this.$t('order.update.success'))
          this.orders = response.data.orders
          this.close()
        })
        .catch ((error) => {
          let errors = '';
          forEach(error.response.data.errors, (value, key) => {
            forEach(value, (e, k) => {
              errors += e
            })
          })
          this.$snotify.error(errors, this.$t('order.update.failure'))
        })
        .finally(() => {
          this.loading = false
        })
    },
    editOrder(order) {
      this.order = Object.assign({}, order)
      this.dialog = true
    },
    deleteOrder (order) {
      this.loading = true
      this.axios.delete(`/orders/${order.id}`)
        .then((response) => {
          this.$snotify.success(this.$t('order.delete.success'))
          this.orders = response.data.orders
          this.close()
        })
        .catch ((error) => {
          let errors = '';
          forEach(error.response.data.errors, (value, key) => {
            forEach(value, (e, k) => {
              errors += e
            })
          })
          this.$snotify.error(errors, this.$t('order.delete.failure'))
        })
        .finally(() => {
          this.loading = false
        })
    },
    update () {
      this.$validator.validate().then(valid => {
        if (valid) {
          this.loading = true

          if (this.order.id) {
            this.axios.put(`/orders/${this.order.id}`, { ...this.order })
              .then((response) => {
                this.loading = false
                this.$snotify.success(this.$t('order.update.success'))
                this.orders = response.data.orders
                this.close()
              })
              .catch ((error) => {
                this.loading = false
                let errors = '';
                forEach(error.response.data.errors, (value, key) => {
                  forEach(value, (e, k) => {
                    errors += e
                  })
                })
                this.$snotify.error(errors, this.$t('order.update.failure'))
              })
          } else {
            this.axios.post('/orders', { ...this.order })
            .then((response) => {
              this.loading = false
              this.$snotify.success(this.$t('order.store.success'))
              this.orders = response.data.orders
              this.close()
            })
            .catch ((error) => {
              this.loading = false
              let errors = '';
              forEach(error.response.data.errors, (value, key) => {
                forEach(value, (e, k) => {
                  errors += e
                })
              })
              this.$snotify.error(errors, this.$t('order.store.failure'))
            })
          }
        }
      })
    },
    close () {
      this.dialog = false
      setTimeout(() => {
        this.order = Object.assign({}, {})
      }, 300)
    },
  },
  mounted () {
    this.loading = true
    this.axios.get('/orders')
      .then ((response) => {
        this.orders = response.data.orders
      })
      .catch ((error) => {
        this.$snotify.error(this.$t('general_error'))
        this.$router.push('/home')
      })
      .finally (() => {
        this.loading = false
      })
  }
}
</script>

<style>

</style>
