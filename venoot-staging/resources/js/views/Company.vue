<template>
  <div class="company">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar color="primary" >
                <v-card-title class="white--text title">{{ $t('company.title').toUpperCase() }}</v-card-title>
              </v-toolbar>
              <v-card-text>
                <v-form ref="form">
                  <v-text-field
                    v-validate="'required|max:30'"
                    data-vv-name="social_reason"
                    :error-messages="errors.collect('social_reason')"
                    :counter="30"
                    required
                    prepend-icon="work" v-model="company.social_reason" :label="$t('form.social_reason').capitalize()" type="text"></v-text-field>
                  <v-text-field
                    v-validate="'required|max:30'"
                    data-vv-name="area"
                    :error-messages="errors.collect('area')"
                    :counter="30"
                    required
                    prepend-icon="business_center" v-model="company.area" :label="$t('form.area').capitalize()" type="text"></v-text-field>
                  <v-text-field
                    v-validate="'required|min:10'"
                    data-vv-name="rut"
                    :error-messages="errors.collect('rut')"
                    :counter="10"
                    required
                    prepend-icon="vpn_key" v-model="company.rut" :label="$t('form.rut').capitalize()" type="text"></v-text-field>
                  <v-text-field
                    v-validate="'required|max:80'"
                    data-vv-name="address"
                    :error-messages="errors.collect('address')"
                    :counter="80"
                    required
                    prepend-icon="directions" v-model="company.address" :label="$t('form.address').capitalize()" type="text"></v-text-field>
                  <v-select
                    :items="regions"
                    item-text="name"
                    item-value="id"
                    v-model="company.region_id"
                    prepend-icon="location_city"
                    :label="$t('form.region').capitalize()"
                    v-validate="'required'"
                    data-vv-name="region"
                    required
                  ></v-select>
                  <v-select
                    :items="communes.filter(commune => commune.region_id === company.region_id)"
                    item-text="name"
                    item-value="id"
                    v-model="company.commune_id"
                    prepend-icon="location_city"
                    :label="$t('form.commune').capitalize()"
                    v-validate="'required'"
                    data-vv-name="commune"
                    required
                  ></v-select>
                  <v-text-field
                    v-validate="'required|max:20'"
                    data-vv-name="city"
                    :error-messages="errors.collect('city')"
                    :counter="20"
                    required
                    prepend-icon="location_city" v-model="company.city" :label="$t('form.city').capitalize()" type="text"></v-text-field>                
                  <v-text-field
                    v-validate="'required|max:20'"
                    data-vv-name="phone"
                    :error-messages="errors.collect('phone')"
                    :counter="20"
                    required
                    prepend-icon="phone" v-model="company.phone" :label="$t('form.phone').capitalize()" type="text"></v-text-field>
                  <br />
                  <v-layout>
                    <v-flex xs12 md12>
                      <v-card>
                        <v-card-title primary-title>
                          <v-icon>image</v-icon>
                          <h4 class="headline mb-0">Logo</h4>
                        </v-card-title>
                        <v-card-text>
                          <file-pond
                            name='logo'
                            :server="serverOptions"
                            :label-idle=" $t('form.upload.idle')"
                            :files="logo"
                            allow-multiple='false'
                            accepted-file-types='image/jpeg, image/png'
                            @updatefiles="onUpdateFiles"
                            required
                            image-crop-aspect-ratio="1:1"
                            :image-edit-instant-edit="true"
                            :imageEditEditor="doka"
                            max-file-size="1536KB"
                            :label-max-file-size-exceeded="$t('file.uploadExcedeed')"
                            :label-max-file-size="$t('file.uploadDetailed')"
                          ></file-pond>
                        </v-card-text>
                      </v-card>
                    </v-flex>
                  </v-layout>
                  <v-select
                    v-validate="'required'"
                    data-vv-name="payment"
                    required
                    :items="paymentType"
                    v-model="company.payment_type"
                    prepend-icon="attach_money"
                    :label="$t('form.payment').capitalize()"
                  ></v-select>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="success" :loading="loading" :disabled="loading" @click="update">{{$t('update')}}</v-btn>
              </v-card-actions>
              <v-progress-linear :indeterminate="loading"></v-progress-linear>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
import { FilePond, loadUrl } from '../app'
import * as Doka from '../plugins/doka/index'
import { forEach } from 'lodash'
export default {
  data: function () {
    return {
      doka: Doka.create({
        cropAllowImageTurnRight: true,
        cropLimitToImageBounds: false
      }),
      company: {},
      communes: [],
      regions: [],
      loading: false,
      serverOptions: {
        process: {
            url: '/api/uploadLogo',
            headers: {
                'Authorization': `Bearer ${this.$auth.token()}`,
            },
            onload: (response) => {
              let responseJson = JSON.parse(response)
              this.company.logo = responseJson.name
            }
        },
        // revert: './revert/',
        // restore: './restore/',
        load: loadUrl,
        // fetch: './fetch/',
      },
      paymentType: [
        { value: 0, text: this.$t('form.paymentType.normal') },
        { value: 1, text: this.$t('form.paymentType.sheet') },
        { value: 2, text: this.$t('form.paymentType.subscription') },
      ]
    }
  },
  computed: {
    logo () {
      return this.company.logo ? [{ source: this.company.logo, options: { type: 'local' }}] : []
    }
  },
  methods: {
    update () {
      this.$validator.validate().then(valid => {
        if (valid) {
          this.loading = true

          if (this.$auth.user().company_id) {
            this.axios.put(`companies/${this.company.id}`, { ...this.company })
              .then((response) => {
                this.loading = false
                this.$snotify.success(this.$t('company.update.success'))
                this.$auth.user().company_id = response.data.id
                this.$auth.user().company = response.data
                this.company = response.data
              })
              .catch ((error) => {
                this.loading = false
                let errors = '';
                forEach(error.response.data.errors, (value, key) => {
                  forEach(value, (e, k) => {
                    errors += e
                  })
                })
                this.$snotify.error(errors, this.$t('company.update.failure'))
              })
          } else {
            this.axios.post('companies', { ...this.company })
            .then((response) => {
              this.loading = false
              this.$snotify.success(this.$t('company.store.success'))
              this.$auth.user().company_id = response.data.id
              this.$auth.user().company = response.data
              this.company = response.data
            })
            .catch ((error) => {
              this.loading = false
              let errors = '';
              forEach(error.response.data.errors, (value, key) => {
                forEach(value, (e, k) => {
                  errors += e
                })
              })
              this.$snotify.error(errors, this.$t('company.store.failure'))
            })
          }
        }
      })
    },
    onUpdateFiles(files) {
      if (files.length == 0) {
        this.company.logo = null
      }
    }
  },
  mounted () {
    this.loading = true
    this.axios.get('communes')
      .then ((response) => {
        this.communes = response.data
      })
      .catch ((error) => {
        this.$snotify.error(this.$t('general_error'))
        this.$router.push('/home')
      })

    this.axios.get('regions')
      .then ((response) => {
        this.regions = response.data
      })
      .catch ((error) => {
        this.$snotify.error(this.$t('general_error'))
        this.$router.push('/home')
      })

    if (this.$auth.user().company_id) {
      // console.log(this.$route)
      if (this.$route.params.id) {
        if (this.$auth.user().company_id == this.$route.params.id) {
          this.loading = false
          this.company = this.$auth.user().company
        } else {
          this.axios.get(`companies/${this.$route.params.id}`)
            .then ((response) => {
              this.loading = false
              this.company = response.data.company
            })
            .catch ((error) => {
              this.$snotify.error(this.$t('general_error'))
              this.$router.push('/home')
            })
        }
      } else {
        this.$router.push(`/companies/${this.$auth.user().company_id}`)
        this.loading = false
        this.company = this.$auth.user().company
      }
    } else {
      this.loading = false
    }
  }
}
</script>

<style>

</style>
