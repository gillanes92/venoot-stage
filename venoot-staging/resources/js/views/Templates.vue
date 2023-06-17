<template>
   <div class="templates">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-row>
                        <v-col>
                            <v-toolbar text color="primary">
                                <v-toolbar-title class="white--text title">{{
                                    $t('link.templates').toUpperCase()
                                }}
                                </v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="success"
                                    dark
                                    class="mb-2"
                                    @click="dialogTemplate = true"
                                >
                                    {{ $t('form.new') }}
                                </v-btn>
                            </v-toolbar>
                            <v-data-table
                                :headers="templateHeaders"
                                :items="templates"
                                :loading="loading"
                                class="elevation-1"
                                :sort-by="['id']"
                            >
                                <template v-slot:[`item.actions`]="{ item }">
                                    <td class="justify-center layout px-0">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-icon small class="mr-2" @click="editTemplate(item)" v-on="on">edit</v-icon>
                                            </template>
                                            <span>{{ $t('edit').capitalize() + ' ' + $t('templates.name') }}</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-icon small class="mr-2" @click="modifyTemplate(item)" v-on="on">mdi-floor-plan</v-icon>
                                            </template>
                                            <span>{{ $t('modify').capitalize() }}</span>
                                        </v-tooltip>
                                         <v-tooltip bottom v-if="item.id > 3">
                                            <template v-slot:activator="{ on }">
                                                <v-icon small class="mr-2" @click="deleteTemplate(item)" v-on="on">mdi-delete</v-icon>
                                            </template>
                                            <span>{{ $t('delete').capitalize() }}</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </v-layout>
            </v-container>
        </v-content>

          <v-dialog v-model="dialogTemplate" max-width="600px" transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>
                    {{ template.id ? $t('templates.edit') : $t('templates.new') }}
                </v-card-title>
                <v-card-text>
                   <v-row>
                       <v-col>
                           <v-form ref="form">
                                <v-text-field
                                    v-model="template.name"
                                    :rules="nameRules"
                                    :label="$t('templates.name')"
                                ></v-text-field>

                                <v-text-field
                                    v-model="template.test_email"
                                    :rules="emailRules"
                                    :label="$t('templates.test_email')"
                                ></v-text-field>
                            </v-form>
                       </v-col>
                   </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click.native="dialogTemplate = false" :loading="submitting">{{ $t('cancel') }}</v-btn>
                    <v-btn text class="success" @click.native="submitTemplate" :loading="submitting">{{ $t('accept') }}</v-btn>
                    <!-- <v-btn text class="success" @click.native="submitEditTemplate" :loading="submitting">{{ $t('acceptedit') }}</v-btn> -->
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDeleteTemplate" max-width="400px" transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>
                    {{ $t('are_sure') + " " + $t("delete") + " " + template.name + "?" }}
                </v-card-title>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click.native="dialogDeleteTemplate = false" :loading="submitting">{{ $t('no') }}</v-btn>
                    <v-btn text class="error" @click.native="destroyTemplate" :loading="submitting">{{ $t('yes') }}</v-btn>
                    <!-- <v-btn text class="success" @click.native="submitEditTemplate" :loading="submitting">{{ $t('acceptedit') }}</v-btn> -->
                </v-card-actions>
            </v-card>
        </v-dialog>


   </div>
</template>

<script>
export default {
    name: 'Templates',

    data() {
        return {
            loading: false,
            submitting: false,
            dialogTemplate: false,
            dialogDeleteTemplate: false,
            templates: [],

            defaultTemplate: {
                name: '',
                content: null,
                test_email: null,
            },

            template: {
                name: '',
                content: null,
                test_email: null,
            },

            nameRules: [
                v => (v || '').length > 3 || `Minimo 3 caracteres esta permitido`
            ],

            emailRules: [
                v => /.+@.+\..+/.test(v) || 'E-mail debe ser valido',
            ],

            templateHeaders: [
                {
                    text: this.$t('templates.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },

                {
                    text: this.$t('templates.test_email').capitalize(),
                    align: 'left',
                    value: 'test_email',
                },

                {
                    text: this.$t('actions').capitalize(),
                    align: 'center',
                    value: 'actions',
                    width: 100,
                    sortable: false,
                    filterable: false,
                },
            ],
        }
    },

    watch: {
        dialogTemplate (value) {
            if (!value) {
                this.dialogTemplate = false
                setTimeout(() => {
                    this.template = Object.assign({}, this.defaultTemplate)
                }, 500)
            }
        },

        dialogDeleteTemplate (value) {
            if (!value) {
                this.dialogDeleteTemplate = false
                setTimeout(() => {
                    this.template = Object.assign({}, this.defaultTemplate)
                }, 500)
            }
        }
    },

    methods: {
        editTemplate(template) {
            this.template = Object.assign({}, template)
            this.dialogTemplate = true
        },

        modifyTemplate(template) {
            window.open(`${this.BASE_URL}/templates/${template.id}/builderjs?token=${this.$auth.token()}`, "_blank")
            // this.$router.push(`templates/${template.id}/builder`)
        },

        deleteTemplate(template) {
            this.template = template
            this.dialogDeleteTemplate = true
        },

        async destroyTemplate() {
            this.submitting = true
            try {
                const respose = await this.axios.delete(`/templates/${this.template.id}`)
                this.$snotify.success(this.$t('templates.delete.success'))

                this.templates.splice(this.templates.indexOf(this.template), 1)
            } catch (e) {
                console.log(e)
                this.$snotify.error(this.$t('templates.delete.error'))
            }

            this.dialogDeleteTemplate = false
            this.submitting = false
        },

        async submitTemplate () {
            if (!this.$refs.form.validate()) return
            this.submitting = true

            if (this.template.id) {
                try {
                    const response = await this.axios.patch(`/templates/${this.template.id}/editName`, this.template)
                    this.dialogTemplate = false
                    this.templates = [ ...this.templates.filter(template => template.id != this.template.id), response.data.template ]
                    this.templates.sort(function (a, b) { b.id - a.id })
                    this.$snotify.success(this.$t('templates.save.success'))
                    // return Promise.success()
                } catch (e) {
                    console.log(e)
                    this.$snotify.error(this.$t('templates.save.error'))
                    // return Promise.reject()
                }
            } else {
                try {
                    const response = await this.axios.post(`/templates`, this.template)
                    this.dialogTemplate = false
                    this.templates.push(response.data.template)
                    this.$snotify.success(this.$t('templates.save.success'))
                    // return Promise.success()
                } catch (e) {
                    this.$snotify.error(this.$t('templates.save.error'))
                    // return Promise.reject()
                }
            }

            this.submitting = false
        },

        async submitEditTemplate () {
            try {
                await this.submitTemplate()
                this.modifyTemplate(this.template)
            } catch (e) {
                console.log(e)
            }

        }
    },

    async mounted() {
        this.loading = true
        try {
            const response = await this.axios.get(`/templates`)
            this.templates = response.data.templates
        } catch (e) {
            this.$snotify.error(this.$t('general_error'))
        }
        this.loading = false
    }
}
</script>

<style>

</style>
