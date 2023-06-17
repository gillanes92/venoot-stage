<template>
  <div class="user">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar color="primary" >
                <v-card-title class="white--text title">{{ $t('users.update.title').toUpperCase() }}</v-card-title>
              </v-toolbar>
              <v-card-text>
                <v-form ref="form">
                  <v-text-field
                    v-validate="'required|max:30'"
                    data-vv-name="name"
                    :error-messages="errors.collect('name')"
                    :counter="30"
                    required
                    prepend-icon="person" v-model="user.name" :label="$t('form.name').capitalize()" type="text"></v-text-field>
                  <v-text-field
                    v-validate="'required|max:30'"
                    data-vv-name="lastname"
                    :error-messages="errors.collect('lastname')"
                    :counter="30"
                    required
                    prepend-icon="person" v-model="user.lastname" :label="$t('form.lastname').capitalize()" type="text"></v-text-field>
                  <v-text-field
                    v-validate="'min:8'"
                    ref="password"
                    data-vv-name="password"
                    :error-messages="errors.collect('password')"
                    required
                    prepend-icon="lock" v-model="user.password" :label="$t('form.new').capitalize() + ' ' + $t('form.password')" type="password"></v-text-field>
                  <v-text-field
                    v-validate="'confirmed:password'"
                    data-vv-name="password_confirmation"
                    data-vv-as="password"
                    :error-messages="errors.collect('password_confirmation')"
                    required
                    prepend-icon="lock" v-model="user.password_confirmation" :label="$t('form.new').capitalize() + ' ' + $t('form.password_confirmation')" type="password"></v-text-field>
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
import { API } from '../app'
import { forEach } from 'lodash'
export default {
  data: () => ({
    user: {},
    loading: false
  }),
  methods: {
    update () {
      this.$validator.validate().then(valid => {
        if (valid) {
          this.loading = true
          this.axios.put(`users/${this.user.id}`, { ...this.user })
            .then((response) => {
              this.loading = false
              this.$snotify.success(this.$t('users.update.success'))
              if (this.user.id === this.$auth.user()) {
                this.$auth.user(response.user)
              }
            })
            .catch ((error) => {
              this.loading = false
              let errors = '';
              forEach(error.response.data.errors, (value, key) => {
                forEach(value, (e, k) => {
                  errors += e
                })
              })
              this.$snotify.error(errors, error.response.data.error)
            })
        }
      })
    }
  },
  mounted () {
    this.loading = true
    if (this.$route.params.id != this.$auth.user().id) {
      this.axios.get(`users/${this.$route.params.id}`)
        .then ((response) => {
          this.loading = false
          this.user = response.data.user
        })
        .catch ((error) => {
          this.loading = false
          this.$snotify.error(this.$t('users.show.error'))
          this.$router.push('/home')
        })
    } else {
      this.user = this.$auth.user()
      this.loading = false
    }
  }
}
</script>

<style>

</style>
