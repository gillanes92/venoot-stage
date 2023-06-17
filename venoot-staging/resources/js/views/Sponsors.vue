<template>
    <div class="sponsors">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar flat color="primary">
                            <v-toolbar-title class="white--text title">
                                {{
                                $t('link.sponsors').toUpperCase()
                                }}
                            </v-toolbar-title>
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
                                        <span class="headline">
                                            {{
                                            $t('form.new').capitalize() +
                                            ' ' +
                                            $t('sponsor.title')
                                            }}
                                        </span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form ref="form">
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs6>
                                                        <v-text-field
                                                            v-validate="
                                                                'required|max:30'
                                                            "
                                                            data-vv-name="name"
                                                            :error-messages="
                                                                errors.collect(
                                                                    'name'
                                                                )
                                                            "
                                                            :counter="30"
                                                            required
                                                            prepend-icon="short_text"
                                                            v-model="
                                                                sponsor.name
                                                            "
                                                            :label="
                                                                $t(
                                                                    'form.name'
                                                                ).capitalize()
                                                            "
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field
                                                            v-validate="
                                                                'url'
                                                            "
                                                            data-vv-name="url"
                                                            :error-messages="
                                                                errors.collect(
                                                                    'url'
                                                                )
                                                            "
                                                            required
                                                            prepend-icon="mdi-web"
                                                            v-model="
                                                                sponsor.url
                                                            "
                                                            :label="
                                                                $t(
                                                                    'form.url'
                                                                ).capitalize()
                                                            "
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs3>
                                                        <v-text-field
                                                            v-validate="
                                                                'required|max:50'
                                                            "
                                                            :data-vv-name="
                                                                $t(
                                                                    'form.category'
                                                                )
                                                            "
                                                            :error-messages="
                                                                errors.collect(
                                                                    'category'
                                                                )
                                                            "
                                                            :counter="50"
                                                            required
                                                            prepend-icon="short_text"
                                                            v-model="
                                                                sponsor.category
                                                            "
                                                            :label="
                                                                $t(
                                                                    'form.category'
                                                                ).capitalize()
                                                            "
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea
                                                            v-validate="
                                                                'max:180'
                                                            "
                                                            data-vv-name="description"
                                                            :error-messages="
                                                                errors.collect(
                                                                    'description'
                                                                )
                                                            "
                                                            :counter="180"
                                                            prepend-icon="notes"
                                                            v-model="
                                                                sponsor.description
                                                            "
                                                            :label="
                                                                $t(
                                                                    'form.description'
                                                                ).capitalize()
                                                            "
                                                            type="text"
                                                            rows="5"
                                                        ></v-textarea>
                                                    </v-flex>

                                                    <v-flex xs12>
                                                        <v-card>
                                                            <v-card-title
                                                                primary-title
                                                            >
                                                                <v-icon>image</v-icon>
                                                                <h4
                                                                    class="headline mb-0"
                                                                >
                                                                    {{
                                                                    $t(
                                                                    'form.logo'
                                                                    ).capitalize()
                                                                    }}
                                                                </h4>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <file-pond
                                                                    name="logo"
                                                                    :server="
                                                                        serverOptions
                                                                    "
                                                                    :label-idle="
                                                                        $t(
                                                                            'form.upload.idle'
                                                                        )
                                                                    "
                                                                    :files="
                                                                        logo
                                                                    "
                                                                    allow-multiple="false"
                                                                    accepted-file-types="image/jpeg, image/png"
                                                                    @updatefiles="
                                                                        onUpdateFiles
                                                                    "
                                                                    required
                                                                    image-crop-aspect-ratio="1:1"
                                                                    image-resize-target-width="600"
                                                                    :image-edit-instant-edit="
                                                                        true
                                                                    "
                                                                    :imageEditEditor="
                                                                        doka
                                                                    "
                                                                    max-file-size="1536KB"
                                                                    :label-max-file-size-exceeded="$t('file.uploadExcedeed')"
                                                                    :label-max-file-size="$t('file.uploadDetailed')"
                                                                ></file-pond>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-flex>
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
                            :headers="sponsorsHeaders"
                            :items="sponsors"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template v-slot:item.actions="{ item }">
                                <td class="layout px-0">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editSponsor(item)"
                                    >edit</v-icon>
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="showDeleteDialog(item)"
                                    >cancel</v-icon>
                                </td>
                            </template>
                        </v-data-table>
                        <v-dialog
                            v-model="deleteDialog"
                            persistent
                            max-width="15%"
                        >
                            <v-card>
                                <v-card-title class="headline">
                                    {{
                                    $t('delete').capitalize() +
                                    ' ' +
                                    $t('sponsor.title') +
                                    ' ' +
                                    sponsor.name +
                                    '?'
                                    }}
                                </v-card-title>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="warning"
                                        @click="deleteDialog = false"
                                    >{{ $t('cancel') }}</v-btn>
                                    <v-btn
                                        color="error"
                                        @click="deleteSponsor(sponsor)"
                                    >{{ $t('accept') }}</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
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
    data: function() {
        return {
            doka: Doka.create({
                cropAllowImageTurnRight: true,
                cropLimitToImageBounds: false
            }),
            dialog: false,
            deleteDialog: false,
            sponsor: {},
            sponsors: [],
            sponsorsHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('form.url').capitalize(),
                    align: 'left',
                    value: 'url'
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
                    url: './api/uploadLogoSponsor',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`
                    },
                    onload: response => {
                        let responseJson = JSON.parse(response)
                        this.sponsor.logo = responseJson.name
                    }
                },
                load: loadUrl
            }
        }
    },
    computed: {
        logo() {
            return this.sponsor.logo
                ? [{ source: this.sponsor.logo, options: { type: 'local' } }]
                : []
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        }
    },
    methods: {
        editSponsor(sponsor) {
            this.sponsor = Object.assign({}, sponsor)
            this.dialog = true
        },
        showDeleteDialog(sponsor) {
            this.sponsor = Object.assign({}, sponsor)
            this.deleteDialog = true
        },
        deleteSponsor(sponsor) {
            this.loading = true
            this.axios
                .delete(`/sponsors/${sponsor.id}`)
                .then(response => {
                    this.$snotify.success(this.$t('sponsor.delete.success'))
                    this.sponsors = response.data.sponsors
                    this.sponsor = {}
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
                        this.$t('sponsor.delete.failure')
                    )
                })
                .finally(() => {
                    this.loading = false
                    this.deleteDialog = false
                })
        },
        update() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true
                    if (this.sponsor.id) {
                        this.axios
                            .put(`/sponsors/${this.sponsor.id}`, {
                                ...this.sponsor
                            })
                            .then(response => {
                                this.$snotify.success(
                                    this.$t('sponsor.update.success')
                                )
                                this.sponsors = response.data.sponsors
                                this.close()
                            })
                            .catch(error => {
                                this.loading = false
                                let errors = ''
                                if (error.response) {
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
                                        this.$t('sponsor.update.failure')
                                    )
                                } else {
                                    console.error(error.message)
                                }
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    } else {
                        this.axios
                            .post('/sponsors', { ...this.sponsor })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('sponsor.store.success')
                                )
                                this.sponsors = response.data.sponsors
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
                                    this.$t('sponsor.store.failure')
                                )
                            })
                    }
                }
            })
        },
        onUpdateFiles(files) {
            if (files.length == 0) {
                this.sponsor.logo = null
            }
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.sponsor = Object.assign({}, {})
            }, 300)
        }
    },
    mounted() {
        this.loading = true
        this.axios
            .get('/sponsors')
            .then(response => {
                this.sponsors = response.data.sponsors
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

<style></style>
