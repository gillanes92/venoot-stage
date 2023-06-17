<template>
  <div class="webpay-response">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12>
            <h3>{{ $route.query.order }}</h3>
            <h3>{{ $route.query.status }}</h3>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data () {
    return {
      onPayment: false
    }
  },
  methods: {
    pruebaPago () {
      this.onPayment = true
      this.axios.post(`/webpay/payment`, { pay: true })
        .then((response) => {

          var form = document.createElement('form')
          form.setAttribute('method', 'post')
          form.setAttribute('id', 'popUpWebpayForm')
          form.setAttribute('action', response.data.webpay.url)

          var hiddenField = document.createElement('input')
          hiddenField.setAttribute('name', 'TBK_TOKEN')
          hiddenField.setAttribute('value', response.data.webpay.token)

          form.appendChild(hiddenField)
          document.body.appendChild(form)

          form.submit()
        })
        .catch ((error) => {
          this.loading = false
        })
    }
  },
  mounted () {
  }
}
</script>

<style>

</style>
