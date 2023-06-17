<template>
    <div class="actors">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar flat color="primary">
                            <v-toolbar-title
                                class="white--text title"
                            >{{ $t('link.actors').toUpperCase() }}</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="success"
                                dark
                                class="mb-2"
                                @click="dialog = true"
                            >{{ $t('form.new') }}</v-btn>
                        </v-toolbar>
                        <v-data-table
                            :headers="actorsHeaders"
                            :items="actors"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template v-slot:item.actions="{ item }">
                                <td class="layout px-0">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editActor(item)"
                                    >edit</v-icon>
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="deleteActor(item)"
                                    >cancel</v-icon>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog v-model="dialog" max-width="80%">
            <v-card>
                <v-card-title>
                    <span
                        class="headline"
                    >{{ $t('form.new').capitalize() + " " + $t('actor.title') }}</span>
                </v-card-title>

                <v-card-text>
                    <Actor :actor="actor" :auth="$auth" />
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="update"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { FilePond, loadUrl } from '../app'
import { forEach } from 'lodash'
import Actor from '../components/Actor'

export default {
    components: {
        Actor
    },
    data: function() {
        return {
            dialog: false,
            actor: { links: [] },
            actors: [],
            actorsHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('form.lastname').capitalize(),
                    align: 'left',
                    value: 'lastname'
                },
                {
                    text: this.$t('svv.email').capitalize(),
                    value: 'email'
                },
                {
                    text: this.$t('form.category').capitalize(),
                    value: 'category'
                },
                {
                    text: this.$t('form.job').capitalize(),
                    value: 'job'
                },
                {
                    text: this.$t('form.organization').capitalize(),
                    value: 'organization'
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'actions',
                    sortable: false
                }
            ],
            loading: false,
            serverOptions: {
                process: {
                    url: './api/uploadPhoto',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`
                    },
                    onload: response => {
                        let responseJson = JSON.parse(response)
                        this.actor.photo = responseJson.name
                    }
                },
                load: loadUrl
            }
        }
    },
    computed: {
        photo() {
            return this.actor.photo
                ? [{ source: this.actor.photo, options: { type: 'local' } }]
                : []
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        }
    },
    methods: {
        addLink() {
            this.actor.links.push({})
        },
        removeLink(index) {
            this.actor.links.splice(index, 1)
        },
        editActor(actor) {
            this.actor = Object.assign({}, actor)
            this.dialog = true
        },
        deleteActor(actor) {
            this.loading = true
            this.axios
                .delete(`/actors/${actor.id}`)
                .then(response => {
                    this.$snotify.success(this.$t('actor.delete.success'))
                    this.actors = response.data.actors
                    this.close()
                })
                .catch(error => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('actor.delete.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        update() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true

                    if (this.actor.id) {
                        this.axios
                            .put(`/actors/${this.actor.id}`, { ...this.actor })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('actor.update.success')
                                )
                                this.actors = response.data.actors
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
                                    this.$t('actor.update.failure')
                                )
                            })
                    } else {
                        this.axios
                            .post('/actors', { ...this.actor })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('actor.store.success')
                                )
                                this.actors = response.data.actors
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
                                    this.$t('actor.store.failure')
                                )
                            })
                    }
                }
            })
        },
        onUpdateFiles(files) {
            if (files.length == 0) {
                this.actor.photo = null
            }
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.actor = Object.assign({}, { links: [] })
            }, 300)
        }
    },
    mounted() {
        this.loading = true
        this.axios
            .get('/actors')
            .then(response => {
                this.actors = response.data.actors
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                this.loading = false
            })
    }
}
</script>

<style>
</style>
