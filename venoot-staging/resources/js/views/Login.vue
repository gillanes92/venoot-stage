<template>
    <div id="login">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar color="primary">
                                <v-card-title class="white--text title">
                                    {{
                                    $t('link.login').toUpperCase()
                                    }}
                                </v-card-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-form ref="form" data-vv-scope="login">
                                    <v-text-field
                                        v-validate="'required|email'"
                                        data-vv-name="email"
                                        :error-messages="
                                            errors.collect('login.email')
                                        "
                                        required
                                        prepend-icon="mail"
                                        v-model="user.email"
                                        :label="$t('form.mail').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                    <v-text-field
                                        v-validate="'required'"
                                        data-vv-name="password"
                                        :error-messages="
                                            errors.collect('login.password')
                                        "
                                        required
                                        prepend-icon="lock"
                                        v-model="user.password"
                                        :label="
                                            $t('form.password').capitalize()
                                        "
                                        type="password"
                                    ></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn
                                    text
                                    small
                                    color="primary"
                                    @click="requestResetPasswordDialog = true"
                                >{{ $t('login.forgot_password') }}</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="warning"
                                    :loading="loading"
                                    :disabled="loading"
                                    @click="$refs.form.reset()"
                                >{{ $t('clear') }}</v-btn>
                                <v-btn
                                    color="success"
                                    :loading="loading"
                                    :disabled="loading"
                                    @click="login"
                                >{{ $t('accept') }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog
            v-model="requestResetPasswordDialog"
            persistent
            max-width="500"
        >
            <v-card class="elevation-12">
                <v-toolbar color="primary">
                    <v-card-title class="white--text title">
                        {{
                        $t('login.forgot_password').toUpperCase()
                        }}
                    </v-card-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-validate="'required|email'"
                            data-vv-name="email"
                            :error-messages="
                                            errors.collect('reset.resetPasswordEmail')
                                        "
                            required
                            prepend-icon="mail"
                            v-model="requestResetPasswordEmail"
                            :label="$t('form.mail').capitalize()"
                            type="text"
                            class="mt-4"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        :loading="loading"
                        :disabled="loading"
                        @click="requestResetPasswordDialog = false"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="success"
                        :loading="loading"
                        :disabled="loading"
                        @click="requestPasswordReset"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { API } from '../app'
import { forEach } from 'lodash'
import { mapState } from 'vuex'
export default {
    data() {
        return {
            user: {
                email: '',
                password: ''
            },
            requestResetPasswordDialog: false,
            requestResetPasswordEmail: '',
            loading: false
        }
    },
    computed: {
        ...mapState({
            returnLink: 'returnLink',
        })
    },
    methods: {
        requestPasswordReset() {
            this.$validator.validateAll('reset').then(valid => {
                if (valid) {
                    this.loading = true
                    this.axios
                        .post('/password/create', {
                            email: this.requestResetPasswordEmail
                        })
                        .then(response => {
                            this.$snotify.success(
                                this.$t('password.reset.success')
                            )

                            this.requestResetPasswordDialog = false
                            this.requestResetPasswordEmail = ''
                        })
                        .catch(error => {
                            this.$snotify.error(
                                this.$t('password.reset.failure')
                            )
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            })
        },
        login() {
            this.$validator.validateAll('login').then(valid => {
                if (valid) {
                    this.loading = true
                    this.$auth.login({
                        data: { ...this.user },
                        success: function() {
                            this.loading = false
                            this.$snotify.success(this.$t('login.success'))


                        },
                        error: function(error) {
                            this.loading = false
                            switch (error.response.data.error) {
                                case 'email_not_verified':
                                    this.$snotify.error(
                                        this.$t('login.email_not_verified')
                                    )
                                    break

                                case 'login_error':
                                    this.$snotify.error(
                                        this.$t('login.login_error')
                                    )
                                    break
                            }
                        },
                        redirect: this.returnLink ? '/verify' : '/home',
                        fetchUser: true
                    })
                }
            })
        }
    },

    async mounted() {
        while (!this.$auth.ready()) {
            await new Promise((resolve) => setTimeout(() => resolve(true), 500))
        }

        if (this.$route.query.nav === 'webinars') {
            window.localStorage.setItem('goTo', 'webinarpackages')
            this.$router.push('/webinarpackages')
        }
    }
}
</script>

<style></style>
