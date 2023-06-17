<template>
    <div class="polls">
        <v-overlay :value="showOverlay">
            <v-card
                color="white"
                min-width="150"
                min-height="150"
                class="d-flex flex-column justify-center align-center"
            >
                <v-progress-circular
                    :size="40"
                    :width="5"
                    indeterminate
                    color="primary"
                ></v-progress-circular>
                <div class="black--text mt-2">
                    {{ $t('loading').capitalize() }}
                </div>
            </v-card>
        </v-overlay>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar text color="primary">
                            <v-toolbar-title class="white--text title">{{
                                $t('link.polls').toUpperCase()
                            }}</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="70%">
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                        color="success"
                                        dark
                                        class="mb-2"
                                        v-on="on"
                                        >{{ $t('form.new') }}</v-btn
                                    >
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline"
                                            >{{
                                                poll.id
                                                    ? $t('edit').capitalize()
                                                    : $t(
                                                          'form.new'
                                                      ).capitalize()
                                            }}
                                            {{ $t('poll.title') }}</span
                                        >
                                    </v-card-title>

                                    <v-card-text>
                                        <v-form ref="form">
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12>
                                                        <v-text-field
                                                            v-validate="
                                                                'required|max:100'
                                                            "
                                                            data-vv-name="name"
                                                            :error-messages="
                                                                errors.collect(
                                                                    'name'
                                                                )
                                                            "
                                                            :counter="100"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="poll.name"
                                                            :label="
                                                                $t(
                                                                    'form.name'
                                                                ).capitalize()
                                                            "
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea
                                                            v-validate="
                                                                'required'
                                                            "
                                                            data-vv-name="description"
                                                            :error-messages="
                                                                errors.collect(
                                                                    'description'
                                                                )
                                                            "
                                                            required
                                                            prepend-icon="work"
                                                            v-model="
                                                                poll.description
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
                                                        <h4>
                                                            {{
                                                                $t(
                                                                    'poll.questions'
                                                                ).capitalize()
                                                            }}
                                                            <v-btn
                                                                fab
                                                                dark
                                                                small
                                                                color="success"
                                                                @click="
                                                                    addQuestion
                                                                "
                                                            >
                                                                <v-icon dark
                                                                    >add</v-icon
                                                                >
                                                            </v-btn>
                                                        </h4>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-expansion-panels>
                                                            <v-expansion-panel
                                                                v-for="(question,
                                                                index) in poll.questions"
                                                                :key="index"
                                                            >
                                                                <v-expansion-panel-header
                                                                    >{{
                                                                        $t(
                                                                            'poll.question'
                                                                        ).capitalize() +
                                                                            ' #' +
                                                                            (index +
                                                                                1) +
                                                                            ' - ' +
                                                                            question.question
                                                                    }}</v-expansion-panel-header
                                                                >
                                                                <v-expansion-panel-content>
                                                                    <v-container>
                                                                        <v-layout
                                                                            row
                                                                            wrap
                                                                        >
                                                                            <v-flex
                                                                                xs9
                                                                            >
                                                                                <v-text-field
                                                                                    v-validate="
                                                                                        'required|max:190'
                                                                                    "
                                                                                    data-vv-as="question"
                                                                                    :data-vv-name="
                                                                                        `question${index}`
                                                                                    "
                                                                                    :error-messages="
                                                                                        errors.collect(
                                                                                            `question${index}`
                                                                                        )
                                                                                    "
                                                                                    :counter="
                                                                                        190
                                                                                    "
                                                                                    required
                                                                                    prepend-icon="short_text"
                                                                                    v-model="
                                                                                        question.question
                                                                                    "
                                                                                    :label="
                                                                                        $t(
                                                                                            'poll.question'
                                                                                        ).capitalize()
                                                                                    "
                                                                                    type="text"
                                                                                ></v-text-field>
                                                                            </v-flex>
                                                                            <v-flex
                                                                                xs2
                                                                            >
                                                                                <v-select
                                                                                    :items="
                                                                                        types
                                                                                    "
                                                                                    :label="
                                                                                        $t(
                                                                                            'poll.category'
                                                                                        ).capitalize()
                                                                                    "
                                                                                    v-model="
                                                                                        question.type
                                                                                    "
                                                                                    v-validate="
                                                                                        'required'
                                                                                    "
                                                                                    prepend-icon="link"
                                                                                    required
                                                                                ></v-select>
                                                                            </v-flex>
                                                                            <v-flex
                                                                                xs1
                                                                            >
                                                                                <v-btn
                                                                                    fab
                                                                                    dark
                                                                                    small
                                                                                    color="error"
                                                                                    @click="
                                                                                        removeQuestion(
                                                                                            index
                                                                                        )
                                                                                    "
                                                                                >
                                                                                    <v-icon
                                                                                        dark
                                                                                        >remove</v-icon
                                                                                    >
                                                                                </v-btn>
                                                                            </v-flex>

                                                                            <v-flex
                                                                                v-if="
                                                                                    question.type !=
                                                                                        2
                                                                                "
                                                                                xs12
                                                                            >
                                                                                <h4>
                                                                                    {{
                                                                                        $t(
                                                                                            'poll.alternatives'
                                                                                        ).capitalize()
                                                                                    }}
                                                                                    <v-btn
                                                                                        fab
                                                                                        outlined
                                                                                        dark
                                                                                        small
                                                                                        color="success"
                                                                                        @click="
                                                                                            addAlternative(
                                                                                                question
                                                                                            )
                                                                                        "
                                                                                    >
                                                                                        <v-icon
                                                                                            dark
                                                                                            >add</v-icon
                                                                                        >
                                                                                    </v-btn>
                                                                                </h4>
                                                                            </v-flex>

                                                                            <template
                                                                                v-if="
                                                                                    question.type !=
                                                                                        2
                                                                                "
                                                                            >
                                                                                <v-flex
                                                                                    xs12
                                                                                    v-for="(alternative,
                                                                                    index) in question.alternatives"
                                                                                    :key="
                                                                                        index
                                                                                    "
                                                                                >
                                                                                    <v-container>
                                                                                        <v-layout
                                                                                            row
                                                                                            wrap
                                                                                        >
                                                                                            <v-flex
                                                                                                xs11
                                                                                            >
                                                                                                <v-text-field
                                                                                                    v-validate="
                                                                                                        'required|max:190'
                                                                                                    "
                                                                                                    data-vv-as="alternative"
                                                                                                    :data-vv-name="
                                                                                                        `alternative${index}`
                                                                                                    "
                                                                                                    :error-messages="
                                                                                                        errors.collect(
                                                                                                            `alternative${index}`
                                                                                                        )
                                                                                                    "
                                                                                                    :counter="
                                                                                                        190
                                                                                                    "
                                                                                                    required
                                                                                                    prepend-icon="short_text"
                                                                                                    v-model="
                                                                                                        alternative.alternative
                                                                                                    "
                                                                                                    :label="
                                                                                                        $t(
                                                                                                            'poll.alternative'
                                                                                                        ).capitalize()
                                                                                                    "
                                                                                                    type="text"
                                                                                                ></v-text-field>
                                                                                            </v-flex>
                                                                                            <v-flex
                                                                                                xs1
                                                                                            >
                                                                                                <v-btn
                                                                                                    fab
                                                                                                    dark
                                                                                                    small
                                                                                                    color="error"
                                                                                                    @click="
                                                                                                        removeAlternative(
                                                                                                            question,
                                                                                                            index
                                                                                                        )
                                                                                                    "
                                                                                                >
                                                                                                    <v-icon
                                                                                                        dark
                                                                                                        >remove</v-icon
                                                                                                    >
                                                                                                </v-btn>
                                                                                            </v-flex>
                                                                                        </v-layout>
                                                                                    </v-container>
                                                                                </v-flex>
                                                                            </template>
                                                                        </v-layout>
                                                                    </v-container>
                                                                </v-expansion-panel-content>
                                                            </v-expansion-panel>
                                                        </v-expansion-panels>
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
                                            >{{ $t('cancel') }}</v-btn
                                        >
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="update"
                                            >{{ $t('accept') }}</v-btn
                                        >
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                        <v-data-table
                            :headers="pollsHeaders"
                            :items="polls"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template v-slot:item.action="{ item }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="sendPoll(item)"
                                    >mdi-send</v-icon
                                >
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editPoll(item)"
                                    >mdi-pencil</v-icon
                                >
                                <v-icon small @click="deletePoll(item)"
                                    >mdi-cancel</v-icon
                                >
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>

            <v-dialog v-model="dialogSend" persistent max-width="400px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Envio de Encuestas</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-select
                                        v-if="dialogSend"
                                        :items="events"
                                        item-text="name"
                                        item-value="id"
                                        v-model="event"
                                        single-line
                                        :label="$t('event.title').capitalize()"
                                    ></v-select>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-checkbox
                                        v-model="confirmed"
                                        label="Solo Confirmados"
                                    ></v-checkbox>
                                </v-col>
                                <v-col>
                                    <v-checkbox
                                        v-model="registered"
                                        label="Solo Aceptados"
                                    ></v-checkbox>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="dialogSend = false"
                            >{{ $t('cancel') }}</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="!event"
                            :loading="loadingEvent"
                            @click="getParticipants"
                            >{{ $t('accept') }}</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-content>

        <v-dialog v-model="dialogEmails" persistent max-width="90%">
            <v-card>
                <v-card-title>
                    <span class="headline"
                        >Selección Participantes para Envio</span
                    >
                </v-card-title>

                <v-card-text>
                    <v-row>
                        <v-col cols="12"
                            >Esta seguro que quiere enviar esta encuesta a
                            {{ selectedParticipants.length }}
                            participantes?</v-col
                        >
                        <v-col cols="12">
                            <v-text-field
                                v-model="fromName"
                                label="De"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field
                                v-model="subject"
                                label="Asunto"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-data-table
                                v-model="selectedParticipants"
                                :headers="participantHeaders"
                                :items="participants"
                                show-select
                                loading="true"
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialogEmails = false"
                        >{{ $t('cancel') }}</v-btn
                    >
                    <v-btn
                        :disabled="selectedParticipants.length === 0"
                        color="blue darken-1"
                        text
                        @click="sendPollToParticipants"
                        >{{ $t('accept') }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { forEach } from 'lodash'
import { VScrollYReverseTransition } from 'vuetify/lib'
export default {
    data: function() {
        return {
            dialogEmails: false,
            showOverlay: false,
            dialog: false,
            dialogSend: false,
            poll: { questions: [] },
            polls: [],
            event: null,
            confirmed: false,
            registered: false,
            fromName: null,
            subject: null,
            events: [],
            participants: [],
            selectedParticipants: [],
            participantHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'data.name'
                },
                {
                    text: this.$t('form.lastname').capitalize(),
                    align: 'left',
                    value: 'data.lastname'
                },
                {
                    text: this.$t('form.mail').capitalize(),
                    value: 'data.email'
                }
            ],
            pollsHeaders: [
                {
                    text: 'ID',
                    value: 'id'
                },
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('form.description').capitalize(),
                    align: 'left',
                    value: 'description'
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    sortable: false
                }
            ],
            loading: false,
            loadingEvent: false,
            types: [
                { text: this.$t('poll.type.single'), value: 0 },
                { text: this.$t('poll.type.multiple'), value: 1 },
                { text: this.$t('poll.type.open'), value: 2 }
            ]
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        confirmed(val) {
            if (!val) {
                this.registered = val
            }
        },

        registered(val) {
            if (val) {
                this.confirmed = val
            }
        }
    },
    methods: {
        addQuestion() {
            if (!this.poll.questions) {
                this.poll.questions = []
            }
            this.poll.questions.push({
                id: null,
                type: 0,
                alternatives: []
            })
        },
        removeQuestion(index) {
            this.poll.questions.splice(index, 1)
        },
        addAlternative(question) {
            if (!question.alternatives) {
                question.alternatives = []
            }
            question.alternatives.push({ id: null })
        },
        removeAlternative(question, index) {
            question.alternatives.splice(index, 1)
        },
        editPoll(poll) {
            this.poll = Object.assign({}, poll)
            this.dialog = true
        },
        deletePoll(poll) {
            this.loading = true
            this.axios
                .delete(`/polls/${poll.id}`)
                .then(response => {
                    this.$snotify.success(this.$t('poll.delete.success'))
                    this.polls = response.data.polls
                    this.close()
                })
                .catch(error => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('poll.delete.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        update() {
            this.$validator.validate().then(valid => {
                if (valid) {
                    this.loading = true

                    if (this.poll.id) {
                        this.axios
                            .put(`/polls/${this.poll.id}`, { ...this.poll })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('poll.update.success')
                                )
                                this.polls = response.data.polls
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
                                    this.$t('poll.update.failure')
                                )
                            })
                    } else {
                        this.axios
                            .post('/polls', { ...this.poll })
                            .then(response => {
                                this.loading = false
                                this.$snotify.success(
                                    this.$t('poll.store.success')
                                )
                                this.polls = response.data.polls
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
                                    this.$t('poll.store.failure')
                                )
                            })
                    }
                }
            })
        },
        sendPoll(poll) {
            this.poll = Object.assign({}, poll)
            this.dialogSend = true
        },
        getParticipants() {
            this.showOverlay = true
            this.dialogSend = false
            this.axios
                .post(
                    `/events/${this.event}/poll/${this.poll.id}/participants`,
                    {
                        confirmed: this.confirmed,
                        registered: this.registered
                    }
                )
                .then(response => {
                    this.selectedParticipants = []
                    this.participants = response.data.participants
                    this.dialogEmails = true
                    this.fromName = response.data.event_name
                    this.subject = 'Encuesta ' + this.poll.name
                })
                .catch(error => {
                    this.$snotify.error(errors, this.$t('poll.send.failure'))
                })
                .finally(() => {
                    this.showOverlay = false
                })
        },
        sendPollToParticipants() {
            this.loadingEvent = true
            this.showOverlay = true
            this.dialogEmails = false
            this.axios
                .post(`/events/${this.event}/poll/${this.poll.id}`, {
                    participant_ids: this.selectedParticipants.map(
                        participant => participant.id
                    ),
                    fromName: this.fromName,
                    subject: this.subject
                })
                .then(response => {
                    this.dialogEmails = false
                    this.event = null
                    this.$snotify.success(this.$t('poll.send.success'))
                })
                .catch(error => {})
                .finally(() => {
                    this.loadingEvent = false
                    this.showOverlay = false
                })
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.poll = Object.assign({}, {})
                // if (this.$refs.form) {
                //     this.$refs.form.resetValidation()
                //     this.$refs.form.reset()
                // }
            }, 300)
        }
    },
    async mounted() {
        this.loading = true

        const polls = this.axios.get('/polls')
        const events = this.axios.get('/events')

        try {
            let response = await Promise.all([polls, events])
            this.polls = response[0].data.polls
            this.events = response[1].data.events
        } catch (e) {
            console.log(e)
            this.$snotify.error(this.$t('general_error'))
        }

        this.loading = false
    }
}
</script>

<style>
</style>
