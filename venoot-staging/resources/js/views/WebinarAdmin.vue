<template>
    <div class="webinar-admin">
        <v-dialog v-model="newDialog" max-width="800">
            <v-card>
                <v-toolbar color="primary">
                    <v-card-title class="white--text title">WEBINAR</v-card-title>
                </v-toolbar>
                <v-card-text>
                    <v-form ref="form" data-vv-scope="webinar">
                        <v-text-field
                            data-vv-validate-on="blur"
                            v-validate="'min:8'"
                            data-vv-name="name"
                            :error-messages="errors.collect('meeting.name')"
                            prepend-icon="short_text"
                            v-model.lazy="meeting.meetingName"
                            :label="$t('form.name').capitalize() + ' Webinar'"
                            type="text"
                            hint="Minimo 8 caracteres."
                        ></v-text-field>
                    </v-form>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn dark color="success" @click="submit" :loading="loading">{{ $t('accept') }}</v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="linkDialog" max-width="800">
            <v-card>
                <v-toolbar color="primary">
                    <v-card-title class="white--text title">WEBINAR LINK</v-card-title>
                </v-toolbar>
                <v-card-text>
                    <v-form data-vv-scope="webinarLink">
                        <v-text-field
                            data-vv-validate-on="blur"
                            v-validate="'required|min:3'"
                            data-vv-name="name"
                            :error-messages="errors.collect('user.name')"
                            required
                            prepend-icon="short_text"
                            v-model="joinUserName"
                            :label="$t('form.name').capitalize() + ' ' + $t('link.user')"
                            type="text"
                            hint="Requerido. Minimo 3 caracteres."
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn dark color="success" @click="getLink" :loading="loading">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="linkDoneDialog" max-width="900">
            <v-card>
                <v-toolbar color="primary">
                    <v-card-title class="white--text title">JOIN LINK</v-card-title>
                </v-toolbar>
                <v-card-text>
                    <v-text-field
                        ref="url"
                        prepend-icon="link"
                        append-outer-icon="mdi-content-copy"
                        v-model="joinLink"
                        :label="$t('form.link').capitalize()"
                        @click:append-outer="copyLinkToClipboard"
                    ></v-text-field>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-content>
            <v-container fluid fill-height>
                <v-flex xs12>
                    <v-toolbar color="primary" flat>
                        <v-toolbar-title class="white--text title">WEBINARS</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn dark color="success" @click="newDialog = true" :loading="loading">{{ $t('new') }}</v-btn>
                    </v-toolbar>
                    <v-data-table
                        :loading="loading"
                        :headers="webinarHeaders"
                        :items="meetings"
                        :items-per-page="15"
                        class="elevation-1"
                    >
                        <template v-slot:[`item.actions`]="{ item }">
                            <v-btn small dark class="mr-2" color="blue" @click="join(item)">join</v-btn>
                            <v-btn small dark color="blue" @click="close(item)">close</v-btn>
                        </template>

                        <template v-slot:[`item.created_at`]="{ item }">{{
                            toDateTime(parseInt(item.created_at))
                        }}</template>

                        <template v-slot:[`item.type`]="{ item }">{{ item.type.capitalize() }}</template>
                    </v-data-table>
                </v-flex>
            </v-container>
        </v-content>
    </div>
</template>



<script>
export default {
    name: 'Webinar Admin',

    data: function () {
        return {
            webinarHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },
                {
                    text: this.$t('form.category').capitalize(),
                    value: 'type',
                },
                {
                    text: this.$t('form.created_at').capitalize(),
                    value: 'created_at',
                },
                {
                    text: this.$t('form.url').capitalize() + ' ' + this.$t('event.participants').capitalize(),
                    value: 'hosted_at.attendee',
                },
                {
                    text: this.$t('actions').capitalize(),
                    align: 'right',
                    value: 'actions',
                },
            ],
            meetings: [],
            meeting: { meetingID: 'LiveStream Test' },
            joinMeeting: null,
            joinUserName: null,
            joinLink: null,
            newDialog: false,
            linkDialog: false,
            linkDoneDialog: false,
            loading: false,
        }
    },

    methods: {
        toDateTime(secs) {
            var t = new Date(1970, 0, 1) // Epoch
            t.setSeconds(secs)
            return t.toLocaleString()
        },

        submit() {
            this.$validator.validateAll('webinar').then((valid) => {
                if (valid) {
                    this.loading = true
                    this.axios
                        .post('/meetings', { ...this.meeting })
                        .then((response) => {
                            this.newDialog = false
                            this.meetings.unshift(response.data[0].data)
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            })
        },

        join(meeting) {
            window.open(meeting.hosted_at.host, '_blank')
        },

        requestLink(meeting) {
            this.joinMeeting = meeting
            this.linkDialog = true
        },

        requestGuestLink(meeting) {
            this.joinLink = this.BASE_URL + '/meetings/join/' + meeting.metadata.shortuuid
            this.linkDoneDialog = true
        },

        getLink() {
            this.$validator.validateAll('webinarLink').then((valid) => {
                if (valid) {
                    const { meetingID, moderatorPW } = this.joinMeeting
                    this.loading = true
                    this.axios
                        .post('/meetings/join', { meetingID, userName: this.joinUserName, password: moderatorPW })
                        .then((response) => {
                            this.joinLink = response.data.joinLink
                            this.linkDialog = false
                            this.linkDoneDialog = true
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            })
        },

        close(meeting) {
            this.loading = true
            this.axios
                .post('/meetings/close', { id: meeting.id })
                .then(() => {
                    const index = this.meetings.findIndex((otherMeeting) => otherMeeting === meeting)
                    this.meetings.splice(index, 1)
                })
                .finally(() => {
                    this.loading = false
                })
        },

        copyLinkToClipboard() {
            const inputEl = this.$refs.url.$el.querySelector('input')
            inputEl.select()
            document.execCommand('copy')
            this.$snotify.success('Copiado')
        },
    },

    mounted() {
        this.loading = true
        this.axios
            .get('/meetings')
            .then((response) => {
                this.meetings = response.data[0].data
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                this.loading = false
            })
    },
}
</script>

<style>
</style>