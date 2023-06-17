<template>
    <div id="signup">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar color="primary">
                                <v-card-title
                                    class="white--text title"
                                >{{ $t('link.signup').toUpperCase() }}</v-card-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-form ref="form">
                                    <v-text-field
                                        v-validate="'required|max:30'"
                                        data-vv-name="name"
                                        :error-messages="errors.collect('name')"
                                        :counter="30"
                                        required
                                        prepend-icon="person"
                                        v-model="user.name"
                                        :label="$t('form.name').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                    <v-text-field
                                        v-validate="'required|max:30'"
                                        data-vv-name="lastname"
                                        :error-messages="errors.collect('lastname')"
                                        :counter="30"
                                        required
                                        prepend-icon="person"
                                        v-model="user.lastname"
                                        :label="$t('form.lastname').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                    <v-text-field
                                        v-validate="'required|email'"
                                        data-vv-name="email"
                                        :error-messages="errors.collect('email')"
                                        required
                                        prepend-icon="mail"
                                        v-model="user.email"
                                        :label="$t('form.mail').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                    <v-text-field
                                        v-validate="'required|min:8'"
                                        ref="password"
                                        data-vv-name="password"
                                        :error-messages="errors.collect('password')"
                                        required
                                        prepend-icon="lock"
                                        v-model="user.password"
                                        :label="$t('form.password').capitalize()"
                                        type="password"
                                    ></v-text-field>
                                    <v-text-field
                                        v-validate="'required|confirmed:password'"
                                        data-vv-name="password_confirmation"
                                        data-vv-as="password"
                                        :error-messages="errors.collect('password_confirmation')"
                                        required
                                        prepend-icon="lock"
                                        v-model="user.password_confirmation"
                                        :label="$t('form.password_confirmation').capitalize()"
                                        type="password"
                                    ></v-text-field>
                                    <v-select
                                        v-validate="''"
                                        data-vv-name="company"
                                        :items="companies"
                                        item-text="name"
                                        item-value="id"
                                        v-model="user.company_id"
                                        prepend-icon="business"
                                        :label="$t('form.company').capitalize()"
                                    ></v-select>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="warning"
                                    :loading="loading"
                                    :disabled="loading"
                                    @click="$refs.form.reset()"
                                >{{$t('clear')}}</v-btn>
                                <v-btn
                                    color="success"
                                    :loading="loading"
                                    :disabled="loading"
                                    @click="register"
                                >{{$t('accept')}}</v-btn>
                            </v-card-actions>
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
        user: {
            name: '',
            lastname: '',
            email: '',
            password: '',
            password_confirmation: '',
            company_id: null
        },
        companies: [],
        loading: false
    }),
    methods: {
        register() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true
                    this.$auth.register({
                        data: {
                            ...this.user
                        },
                        success: function() {
                            this.loading = false
                            this.$snotify.info(this.$t('verify.send_mail'))
                        },
                        error: function(error) {
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
                            this.$snotify.error(errors, 'Error')
                        }
                    })
                }
            })
        }
    },
    mounted() {
        this.loading = true
        this.axios
            .get('/api/companieslimited')
            .then(response => {
                this.loading = false
                this.companies = [{ id: null, name: 'Nueva' }].concat(
                    response.data.companies
                )
            })
            .catch(error => {
                this.loading = false
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/')
            })
    }
}
</script>

<style>
</style>
