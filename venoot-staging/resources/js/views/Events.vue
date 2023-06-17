<template>
    <div class="events">
        <v-overlay :value="showOverlay">
            <v-card
                color="white"
                min-width="150"
                min-height="150"
                class="d-flex flex-column justify-center align-center"
            >
                <v-progress-circular :size="40" :width="5" indeterminate color="primary"></v-progress-circular>
                <div class="black--text mt-2">{{ $t('loading').capitalize() }}</div>
            </v-card>
        </v-overlay>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar text color="primary">
                            <v-toolbar-title class="white--text title">{{
                                $t('link.events').toUpperCase()
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-data-table
                            :headers="eventsHeaders"
                            :items="events"
                            :loading="loading"
                            :items-per-page.sync="itemsPerPage"
                            class="elevation-1"
                        >
                            <template v-slot:item.description="{ item }">
                                <div class="ellipsis">{{ item.description }}</div>
                            </template>
                            <template v-slot:item.status="{ item }">
                                <v-btn
                                    v-if="item.published == false && item.status == 'pending'"
                                    outlined
                                    color="green"
                                    x-small
                                    @click="publish(item)"
                                    >publicar</v-btn
                                >
                                <template v-else>
                                    {{ $t(item.status).capitalize() }}
                                </template>
                            </template>

                            <template v-slot:item.action="{ item }">
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click="editEvent(item)" v-on="on">edit</v-icon>
                                        </template>
                                        <span>{{ $t('edit').capitalize() }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="showDialogMailings(item)" v-on="on"
                                                >mdi-at</v-icon
                                            >
                                        </template>
                                        <span>{{ $t('mailings.tooltip').capitalize() }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="showDialogEmails(item)" v-on="on"
                                                >mdi-email-multiple</v-icon
                                            >
                                        </template>
                                        <span>Lista de Envios</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click.stop="
                                                    $router.push({ name: 'event_dashboard', params: { id: item.id } })
                                                "
                                                v-on="on"
                                                >mdi-view-dashboard-variant</v-icon
                                            >
                                        </template>
                                        <span>Dashboard del Evento</span>
                                    </v-tooltip>

                                    <v-tooltip bottom v-if="item.estimate_landing">
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="showDialogLanding(item)" v-on="on"
                                                >mdi-bulletin-board</v-icon
                                            >
                                            <!-- <v-icon small class="mr-2" @click.stop="configurarLandingDialog = true" v-on="on"
                                                >mdi-bulletin-board</v-icon
                                            > -->
                                        </template>
                                        <span>{{ $t('configure').capitalize() + ' ' + $t('landing.title') }}</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="showDialogDevices(item)" v-on="on"
                                                >mdi-tablet-cellphone</v-icon
                                            >
                                        </template>
                                        <span>Devices</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click.stop="
                                                    $router.push({ name: 'control_panel', params: { id: item.id } })
                                                "
                                                v-on="on"
                                                >mdi-auto-fix</v-icon
                                            >
                                        </template>
                                        <span>Panel</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="pay(item)" v-on="on"
                                                >mdi-cash-multiple</v-icon
                                            >
                                        </template>
                                        <span>{{ $t('pay').capitalize() }}</span>
                                    </v-tooltip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog v-model="dialogConfirmation" persistent max-width="90%">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t('mailings.confirmation') }}</span>
                </v-card-title>

                <v-card-subtitle> <strong>Template: </strong>{{ currentTemplate.name }} </v-card-subtitle>

                <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <v-row>
                                <v-col cols="6" v-if="data.scheduleEmails">
                                    <v-menu
                                        ref="scheduleDateMenu"
                                        v-model="scheduleDateMenu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="data.date"
                                                :label="$t('form.date').capitalize()"
                                                prepend-icon="calendar_today"
                                                readonly
                                                v-on="on"
                                                format="YYYY-MM-DD HH:mm"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="data.date"
                                            :locale="$i18n.locale"
                                            full-width
                                            @input="scheduleDateMenu = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="6" v-if="data.scheduleEmails">
                                    <v-menu
                                        ref="scheduleTimeMenu"
                                        v-model="scheduleTimeMenu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="data.time"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="data.time"
                                                :label="$t('form.start_time').capitalize()"
                                                prepend-icon="mdi-clock"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-if="scheduleTimeMenu"
                                            v-model="data.time"
                                            :locale="$i18n.locale"
                                            full-width
                                            format="24hr"
                                            @click:minute="$refs.scheduleTimeMenu.save(data.time)"
                                        ></v-time-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="data.fromName" label="De"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="data.subject" label="Asunto"></v-text-field>
                                </v-col>
                            </v-row>
                        </v-col>

                        <v-col cols="6">
                            <div id="wrap">
                                <iframe
                                    id="scaled-frame"
                                    :src="`/templates/${currentTemplate.id}/preview`"
                                    frameborder="0"
                                />
                            </div>
                        </v-col>

                        <v-col cols="12"
                            >Esta seguro que quiere enviar una invitación a
                            {{ selectedParticipants.length }} participantes?</v-col
                        >
                        <v-col cols="12">
                            <v-data-table
                                v-model="selectedParticipants"
                                :headers="participantHeaders"
                                :items="participants"
                                :footer-props="participantFooterOptions"
                                show-select
                                loading="true"
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogConfirmation = false">{{ $t('cancel') }}</v-btn>
                    <v-btn
                        :disabled="selectedParticipants.length === 0"
                        color="blue darken-1"
                        text
                        @click="sendMultipleConfirmation"
                        >{{ $t('accept') }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogQR" persistent max-width="90%">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t('mailings.qr') }}</span>
                </v-card-title>

                <v-card-subtitle> <strong>Template: </strong>{{ currentTemplate.name }} </v-card-subtitle>

                <v-card-text>
                    <v-row>
                        <v-col cols="6" v-if="data.scheduleEmails">
                            <v-menu
                                ref="scheduleDateMenu"
                                v-model="scheduleDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="data.date"
                                        :label="$t('form.date').capitalize()"
                                        prepend-icon="calendar_today"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-if="scheduleDateMenu"
                                    v-model="data.date"
                                    :locale="$i18n.locale"
                                    full-width
                                    @input="scheduleDateMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="6" v-if="data.scheduleEmails">
                            <v-menu
                                ref="scheduleTimeMenu"
                                v-model="scheduleTimeMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="data.time"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="data.time"
                                        :label="$t('form.start_time').capitalize()"
                                        prepend-icon="mdi-clock"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="scheduleTimeMenu"
                                    v-model="data.time"
                                    :locale="$i18n.locale"
                                    full-width
                                    format="24hr"
                                    @click:minute="$refs.scheduleTimeMenu.save(data.time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field v-model="data.fromName" label="De"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field v-model="data.subject" label="Asunto"></v-text-field>
                        </v-col>
                        <v-col cols="12"
                            >Esta seguro que quiere reenviar QRs a
                            {{ selectedParticipants.length }} participantes?</v-col
                        >
                        <v-col cols="12">
                            <v-data-table
                                v-model="selectedParticipants"
                                :headers="participantHeaders"
                                :items="participants"
                                :footer-props="participantFooterOptions"
                                show-select
                                loading="true"
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogQR = false">{{ $t('cancel') }}</v-btn>
                    <v-btn
                        :disabled="selectedParticipants.length === 0"
                        color="blue darken-1"
                        text
                        @click="sendMultipleQR"
                        >{{ $t('accept') }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogReconfirmation" persistent max-width="90%">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t('mailings.reconfirmation') }}</span>
                </v-card-title>

                <v-card-subtitle> <strong>Template: </strong>{{ currentTemplate.name }} </v-card-subtitle>

                <v-card-text>
                    <v-row>
                        <v-col cols="6" v-if="data.scheduleEmails">
                            <v-menu
                                ref="scheduleDateMenu"
                                v-model="scheduleDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="data.date"
                                        :label="$t('form.date').capitalize()"
                                        prepend-icon="calendar_today"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-if="scheduleDateMenu"
                                    v-model="data.date"
                                    :locale="$i18n.locale"
                                    full-width
                                    @input="scheduleDateMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="6" v-if="data.scheduleEmails">
                            <v-menu
                                ref="scheduleTimeMenu"
                                v-model="scheduleTimeMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="data.time"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="data.time"
                                        :label="$t('form.start_time').capitalize()"
                                        prepend-icon="mdi-clock"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="scheduleTimeMenu"
                                    v-model="data.time"
                                    :locale="$i18n.locale"
                                    full-width
                                    format="24hr"
                                    @click:minute="$refs.scheduleTimeMenu.save(data.time)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12">
                            <v-text-field v-model="data.fromName" label="De"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field v-model="data.subject" label="Asunto"></v-text-field>
                        </v-col>
                        <v-col cols="12"
                            >Esta seguro que quiere enviar una reconfirmación a
                            {{ selectedParticipants.length }} participantes?</v-col
                        >
                        <v-col cols="12">
                            <v-data-table
                                v-model="selectedParticipants"
                                :headers="participantHeaders"
                                :items="participants"
                                :footer-props="participantFooterOptions"
                                show-select
                                loading="true"
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogReconfirmation = false">{{ $t('cancel') }}</v-btn>
                    <v-btn
                        :disabled="selectedParticipants.length === 0"
                        color="blue darken-1"
                        text
                        @click="sendMultipleReconfirmation"
                        >{{ $t('accept') }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogFree" persistent>
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t('mailings.free') }}</span>
                </v-card-title>

                <v-card-text>
                    <form>
                        <v-container grid-list-xs>
                            <v-row>
                                <v-col cols="6">
                                    <v-select
                                        :items="tickets"
                                        v-model="ticket"
                                        item-text="name"
                                        item-value="id"
                                        :label="$t('form.tickets').capitalize()"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="toSearch"
                                        append-icon="search"
                                        :label="$t('search').capitalize()"
                                        single-line
                                        hide-details
                                        @keydown.enter="search = toSearch"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-data-table
                                        v-model="selectedParticipants"
                                        :headers="participantHeaders"
                                        :items="participants"
                                        :footer-props="participantFooterOptions"
                                        class="elevation-1"
                                        show-select
                                        multi-sort
                                        item-key="id"
                                        :search="search"
                                    ></v-data-table>
                                </v-col>
                            </v-row>
                        </v-container>
                    </form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogFree = false">{{ $t('cancel') }}</v-btn>
                    <v-btn
                        :disabled="selectedParticipants.length === 0"
                        color="blue darken-1"
                        text
                        @click="sendMultipleFree"
                        >{{ $t('accept') }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogScheduled" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t('mailings.scheduled') }}</span>
                </v-card-title>

                <v-card-text>
                    <form>
                        <v-container grid-list-xs>
                            <v-row>
                                <v-col cols="12" class="subtitle-1">{{
                                    mailing.type ? $t('mailings.' + mailing.type) : ''
                                }}</v-col>
                                <v-col cols="6">
                                    <v-menu
                                        v-model="menu1"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="mailing.date"
                                                label="Fecha Envio"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="mailing.date" @input="menu1 = false"></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="6">
                                    <v-menu
                                        v-model="menu2"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="mailing.time"
                                                label="Hora Envio"
                                                prepend-icon="mdi-clock"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-model="mailing.time"
                                            @input="menu2 = false"
                                            format="24hr"
                                        ></v-time-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                        </v-container>
                    </form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogScheduled = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="blue darken-1" text @click="sendScheduled">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogMailings" persistent transition="dialog-transition">
            <v-stepper v-model="stepperMailings">
                <v-stepper-header>
                    <v-stepper-step step="1" :complete="stepperMailings > 1">{{
                        $t('mailings.select')
                    }}</v-stepper-step>
                    <v-divider></v-divider>
                    <v-stepper-step step="2" :complete="stepperMailings > 2">{{
                        $t('mailings.mailSelect')
                    }}</v-stepper-step>
                </v-stepper-header>
                <v-stepper-items>
                    <v-stepper-content step="1">
                        <v-container grid-list-xs>
                            <v-row>
                                <!--                                <v-col cols="12" sm="6" md="3" lg="2">-->
                                <!--                                    <v-card-->
                                <!--                                        color="grey lighten-4"-->
                                <!--                                        max-width="300"-->
                                <!--                                        height="200"-->
                                <!--                                        @click.native="selectMailing('scheduled')"-->
                                <!--                                    >-->
                                <!--                                        <v-card-text>-->
                                <!--                                            <v-container>-->
                                <!--                                                <v-row wrap>-->
                                <!--                                                    <v-img-->
                                <!--                                                        max-height="100"-->
                                <!--                                                        src="../storage/iconos/envio-programado.svg"-->
                                <!--                                                        aspect-ratio="1"-->
                                <!--                                                        contain-->
                                <!--                                                    ></v-img>-->
                                <!--                                                </v-row>-->
                                <!--                                                <v-row>-->
                                <!--                                                    <v-col class="text-center">{{ $t('mailings.scheduled') }}</v-col>-->
                                <!--                                                </v-row>-->
                                <!--                                            </v-container>-->
                                <!--                                        </v-card-text>-->
                                <!--                                    </v-card>-->
                                <!--                                </v-col>-->
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="3"
                                    lg="2"
                                    v-if="event.estimate_id && event.estimate.ticket_sale"
                                >
                                    <v-card
                                        color="grey lighten-4"
                                        max-width="300"
                                        height="200"
                                        @click.native="selectMailing('free')"
                                    >
                                        <v-card-text>
                                            <v-container>
                                                <v-row wrap>
                                                    <!-- <v-img
                                                        max-height="100"
                                                        src="../storage/iconos/invitacion.svg"
                                                        aspect-ratio="1"
                                                        contain
                                                    ></v-img>-->
                                                </v-row>
                                                <v-row>
                                                    <v-col class="text-center">{{ $t('mailings.free') }}</v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="6" md="3" lg="2">
                                    <v-card
                                        color="grey lighten-4"
                                        max-width="300"
                                        height="200"
                                        @click.native="selectMailing('confirmation')"
                                    >
                                        <v-card-text>
                                            <v-container>
                                                <v-row wrap>
                                                    <v-img
                                                        max-height="100"
                                                        src="../storage/iconos/invitacion.svg"
                                                        aspect-ratio="1"
                                                        contain
                                                    ></v-img>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="text-center">{{ $t('mailings.confirmation') }}</v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="6" md="3" lg="2">
                                    <v-card
                                        color="grey lighten-4"
                                        max-width="300"
                                        height="200"
                                        @click.native="selectMailing('qr')"
                                    >
                                        <v-card-text>
                                            <v-container>
                                                <v-row wrap>
                                                    <v-img
                                                        max-height="100"
                                                        src="../storage/iconos/invitacion-qr.svg"
                                                        aspect-ratio="1"
                                                        contain
                                                    ></v-img>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="text-center">{{ $t('mailings.qr') }}</v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col cols="12" sm="6" md="3" lg="2">
                                    <v-card
                                        color="grey lighten-4"
                                        max-width="300"
                                        height="200"
                                        @click.native="selectMailing('reconfirmation')"
                                    >
                                        <v-card-text>
                                            <v-container>
                                                <v-row wrap>
                                                    <v-img
                                                        max-height="100"
                                                        src="../storage/iconos/invitacion-reconfirmar.svg"
                                                        aspect-ratio="1"
                                                        contain
                                                    ></v-img>
                                                </v-row>
                                                <v-row>
                                                    <v-col class="text-center">{{
                                                        $t('mailings.reconfirmation')
                                                    }}</v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>
                        <v-btn text @click.native="dialogMailings = false">{{ $t('cancel') }}</v-btn>
                    </v-stepper-content>
                    <v-stepper-content step="2">
                        <v-container grid-list-xs>
                            <v-row>
                                <template v-if="mailing.category === 'scheduled'">
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card
                                            color="grey lighten-1"
                                            height="200px"
                                            @click.native="selectMailType('confirmation')"
                                        >
                                            <v-card-text>{{ $t('mailings.confirmation') }}</v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    block
                                                    color="success"
                                                    @click.stop="openWindow(`events/${event.id}/confirmation/preview`)"
                                                    >preview</v-btn
                                                >
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card
                                            color="grey lighten-1"
                                            height="200px"
                                            @click.native="selectMailType('qr')"
                                        >
                                            <v-card-text>{{ $t('mailings.qr') }}</v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    block
                                                    color="success"
                                                    @click.stop="openWindow(`events/${event.id}/qr/preview`)"
                                                    >preview</v-btn
                                                >
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card
                                            color="grey lighten-1"
                                            height="200px"
                                            @click.native="selectMailType('reconfirmation')"
                                        >
                                            <v-card-text>{{ $t('mailings.reconfirmation') }}</v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    block
                                                    color="success"
                                                    @click.stop="
                                                        openWindow(`events/${event.id}/reconfirmation/preview`)
                                                    "
                                                    >preview</v-btn
                                                >
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card color="grey lighten-1" height="200px">
                                            <v-card-text>{{ $t('mailings.create') }}</v-card-text>
                                        </v-card>
                                    </v-col>
                                </template>
                                <template v-else>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card
                                            color="grey lighten-1"
                                            height="200px"
                                            @click.native="selectMailType('default')"
                                        >
                                            <v-card-text>{{ $t('mailings.default') }}</v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    v-if="mailing.category === 'confirmation'"
                                                    block
                                                    color="success"
                                                    @click.stop="openWindow(`events/${event.id}/confirmation/preview`)"
                                                    >preview</v-btn
                                                >
                                                <v-btn
                                                    v-if="mailing.category === 'qr'"
                                                    block
                                                    color="success"
                                                    @click.stop="openWindow(`events/${event.id}/qr/preview`)"
                                                    >preview</v-btn
                                                >
                                                <v-btn
                                                    v-if="mailing.category === 'reconfirmation'"
                                                    block
                                                    color="success"
                                                    @click.stop="
                                                        openWindow(`events/${event.id}/reconfirmation/preview`)
                                                    "
                                                    >preview</v-btn
                                                >
                                            </v-card-actions>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card color="grey lighten-1" height="200px">
                                            <v-card-text>
                                                {{ $t('mailings.template') }}
                                                <v-select
                                                    :items="emailTemplates"
                                                    item-text="name"
                                                    item-value="id"
                                                    v-model="selectedEmailTemplate"
                                                    :label="$t('template').capitalize()"
                                                ></v-select>
                                                <v-btn
                                                    block
                                                    color="success"
                                                    :disabled="!selectedEmailTemplate"
                                                    @click.native="selectMailType('template')"
                                                    >{{ $t('accept') }}</v-btn
                                                >
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="3" lg="2">
                                        <v-card color="grey lighten-1" height="200px">
                                            <v-card-text>
                                                {{ $t('mailings.create') }}
                                                <v-select
                                                    :items="emailTemplatesToSave"
                                                    item-text="name"
                                                    item-value="id"
                                                    v-model="selectedDefaultEmailTemplate"
                                                    :label="$t('template').capitalize()"
                                                ></v-select>
                                                <v-btn
                                                    block
                                                    color="success"
                                                    @click.native="selectDefaultEmailTemplate"
                                                    >{{ $t('accept') }}</v-btn
                                                >
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                        lg="2"
                                        v-if="['confirmation', 'reconfirmation', `qr`].includes(this.mailing.category)"
                                    >
                                        <v-card color="grey lighten-1" height="200px">
                                            <v-card-text>
                                                {{ $t('mailings.scheduled') }}
                                                <v-radio-group v-model="data.scheduleEmails">
                                                    <v-radio :label="$t('yes')" :value="true"></v-radio>
                                                    <v-radio :label="$t('no')" :value="false"></v-radio>
                                                </v-radio-group>
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </template>
                            </v-row>
                        </v-container>
                        <v-btn text @click.native="stepperMailings = 1">{{ $t('back') }}</v-btn>
                        <v-btn text @click.native="dialogMailings = false">{{ $t('cancel') }}</v-btn>
                    </v-stepper-content>
                </v-stepper-items>
            </v-stepper>
        </v-dialog>

        <v-dialog v-model="dialogEmails" max-width="90%" transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>
                    Lista de Envios
                    <v-btn class="ml-2" small color="success" :loading="loading" @click="exportBounced">
                        <v-icon left>mdi-file-excel</v-icon>
                        {{ $t('mailings.bounced') }}
                    </v-btn>
                    <v-btn class="ml-2" small color="primary" :loading="loading" @click="dialogsuppressEmails">
                        <v-icon left>mdi-email-lock</v-icon>
                        {{ $t('mailings.suppress') }}
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="emailsSlug"
                        append-icon="search"
                        :label="$t('search').capitalize()"
                        single-line
                        hide-details
                        @keydown.enter="emailsSearch = emailsSlug"
                    ></v-text-field>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        :headers="emailsHeaders"
                        :items="emails"
                        class="elevation-1"
                        group-by="recipient"
                        loading="true"
                        :search="emailsSearch"
                    >
                        <template v-slot:group.header="{ group, headers }">
                            <td :colspan="headers.length">{{ group.substring(2, group.length - 1) }}</td>
                        </template>

                        <template v-slot:item.bounced="{ item }">
                            <span v-if="item.bounced" class="red--text">{{ $t('true') }}</span>
                            <span v-else>{{ $t('false') }}</span>
                        </template>

                        <template v-slot:item.bounce_type="{ item }">
                            <span v-if="item.bounced">{{ $t('mailings.' + item.bounce_type).capitalize() }}</span>
                        </template>

                        <template v-slot:item.bounce_reason="{ item }">
                            <span v-if="item.bounced">{{ $t('mailings.' + item.bounce_reason).capitalize() }}</span>
                        </template>

                        <template v-slot:item.opens="{ item }"
                            >{{ item.opens > 0 ? $t('true') : $t('false') }} ({{
                                item.opens + ' ' + $t('times')
                            }})</template
                        >
                    </v-data-table>
                </v-card-text>
                <v-card-title primary-title>Lista de Programados</v-card-title>
                <v-card-text>
                    <v-data-table :headers="scheduledHeaders" :items="scheduled" class="elevation-1" loading="true">
                        <template v-slot:item.progress="{ item }">
                            <v-progress-linear
                                :value="(1 - item.unprocessed / item.mail_count) * 100"
                                height="18"
                                color="green lighten-1"
                                background-color="green lighten-3"
                            >
                                <strong
                                    >{{ (1 - item.unprocessed / item.mail_count) * 100 }}% ({{
                                        item.mail_count - item.unprocessed
                                    }}/{{ item.mail_count }})</strong
                                >
                            </v-progress-linear>
                        </template>

                        <template v-slot:item.actions="{ item }">
                            <v-icon small class="mr-2" @click="refreshScheduled(item)" v-if="item.done">
                                mdi-refresh
                            </v-icon>
                            <v-icon small class="mr-2" @click="deleteScheduled(item)" v-if="!item.running && item.done">
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDevices" max-width="90%" transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>Devices</v-card-title>
                <v-card-text>
                    <v-data-table :headers="devicesHeaders" :items="devices" class="elevation-1" :loading="loading">
                        <template v-slot:item.actions="{ item }">
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon small class="mr-2" @click="releaseDevice(item)" v-on="on"
                                            >mdi-delete</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('delete').capitalize() }}</span>
                                </v-tooltip>
                            </td>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            id="landingSelection"
            v-model="configurarLandingDialog"
            max-width="95%"
            v-if="event.landing"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title primary-title>Configuración de LandingPage</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.landing.which === 0">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" @click="selectLanding(0)">
                                            <v-img src="/images/landing2.png"></v-img>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue white--text" @click.stop="openLandingPreview(0)"
                                            >Previsualizar</v-btn
                                        >
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.landing.which === 1">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" @click="selectLanding(1)">
                                            <v-img src="/images/landing1.png"></v-img>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue white--text" @click.stop="openLandingPreview(1)"
                                            >Previsualizar</v-btn
                                        >
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.landing.which === 2">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" @click="selectLanding(2)">
                                            <v-img src="/images/landing3.png"></v-img>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue white--text" @click.stop="openLandingPreview(2)"
                                            >Previsualizar</v-btn
                                        >
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.landing.which === -1">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="300" @click="buildLanding">
                                            <v-card-title primary-title
                                                >Quiero Construir mi Propio Landing</v-card-title
                                            >
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogsuppress" scrollable persistent max-width="800px" transition="dialog-transition">
            <v-card>
                <v-card-title>Suprimir Correos</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-combobox
                                v-model="suppress.types"
                                :items="suppress_types"
                                :label="$t('mailings.bounce_type').capitalize()"
                                multiple
                                chips
                            ></v-combobox>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-combobox
                                v-model="suppress.reasons"
                                :items="suppress_reasons"
                                :label="$t('mailings.bounce_reason').capitalize()"
                                multiple
                                chips
                            ></v-combobox>
                        </v-col>
                        <v-col cols="auto">
                            <v-btn text @click="selectAllReasons" class="mt-4">{{ $t('select_all') }}</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey white--text" @click="dialogsuppress = false" :loading="loading">{{
                        $t('cancel')
                    }}</v-btn>
                    <v-btn color="success white--text" @click="suppressEmails" :loading="loading">{{
                        $t('accept')
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { FilePond, loadUrl, claudio } from '../app'
import { forEach } from 'lodash'
import { Promise } from 'q'

export default {
    data: function () {
        return {
            itemsPerPage: 10,
            showOverlay: false,
            dialog: false,
            dialogInvite: false,
            dialogMailings: false,
            stepperMailings: 1,
            dialogScheduled: false,
            dialogFree: false,
            dialogConfirmation: false,
            dialogQR: false,
            dialogReconfirmation: false,
            dialogEmails: false,
            dialogDevices: false,
            dialogsuppress: false,
            configurarLandingDialog: false,
            withSelectedTemplate: false,
            menu1: false,
            menu2: false,
            search: null,
            toSearch: null,
            emailsSlug: null,
            emailsSearch: null,
            event: { category: 'recitales', landing: { which: 0 } },
            data: { scheduleEmails: false, },
            mailing: {},
            events: [],
            tickets: [],
            ticket: {},
            suppress: {},
            suppress_types: ['Transient', 'Permanent', 'Undetermined'],
            suppress_reasons: [
                'General',
                'NoEmail',
                'Suppressed',
                'OnAccountSuppressionList',
                'MailboxFull',
                'MessageTooLarge',
                'ContentRejected',
                'AttachmentRejected',
            ],
            scheduleTimeMenu: false,
            scheduleDateMenu: false,
            emails: [],
            scheduled: [],
            databases: [],
            participants: [],
            selectedParticipants: [],
            devices: [],
            emailTemplates: [],
            emailTemplatesToSave: [],
            selectedEmailTemplate: null,
            selectedDefaultEmailTemplate: null,
            eventsHeaders: [
                {
                    text: 'ID',
                    align: 'left',
                    value: 'estimate_id',
                },

                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },
                {
                    text: this.$t('form.description').capitalize(),
                    align: 'left',
                    value: 'description',
                },
                {
                    text: this.$t('form.start_date').capitalize(),
                    align: 'left',
                    value: 'start_date',
                },
                {
                    text: this.$t('status').capitalize(),
                    align: 'left',
                    value: 'status',
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    align: 'center',
                    sortable: false,
                },
            ],
            participantHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'data.name',
                },
                {
                    text: this.$t('form.lastname').capitalize(),
                    align: 'left',
                    value: 'data.lastname',
                },
                {
                    text: this.$t('form.mail').capitalize(),
                    value: 'data.email',
                },
            ],

            participantFooterOptions: {
                'items-per-page-options': [20, 100, 500, 1000, -1],
            },

            emailsHeaders: [
                {
                    text: this.$t('mailings.recipient').capitalize(),
                    align: 'left',
                    value: 'recipient',
                },
                {
                    text: this.$t('mailings.subject').capitalize(),
                    align: 'left',
                    value: 'subject',
                },
                {
                    text: this.$t('mailings.category').capitalize(),
                    align: 'left',
                    value: 'category',
                },
                {
                    text: this.$t('mailings.bounced').capitalize(),
                    align: 'left',
                    value: 'bounced',
                },
                {
                    text: this.$t('mailings.bounce_type').capitalize(),
                    align: 'left',
                    value: 'bounce_type',
                },
                {
                    text: this.$t('mailings.bounce_reason').capitalize(),
                    align: 'left',
                    value: 'bounce_reason',
                },
                {
                    text: this.$t('mailings.opens').capitalize(),
                    align: 'left',
                    value: 'opens',
                },
                {
                    text: this.$t('mailings.date').capitalize(),
                    align: 'left',
                    value: 'updated_at',
                },
            ],
            scheduledHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'from_name',
                },
                {
                    text: this.$t('mailings.subject').capitalize(),
                    align: 'left',
                    value: 'subject',
                },
                {
                    text: this.$t('mailings.category').capitalize(),
                    align: 'left',
                    value: 'category',
                },
                {
                    text: this.$t('mailings.date').capitalize(),
                    align: 'left',
                    value: 'send_at',
                },
                {
                    text: this.$t('mailings.progress').capitalize(),
                    align: 'left',
                    value: 'progress',
                },
                {
                    text: this.$t('actions').capitalize(),
                    align: 'left',
                    value: 'actions',
                },
            ],

            devicesHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    value: 'name',
                },
                {
                    text: this.$t('form.lastname').capitalize(),
                    value: 'lastname',
                },
                {
                    text: 'Device',
                    value: 'ident',
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'actions',
                    align: 'center',
                    sortable: false,
                },
            ],
            categories: [
                { text: this.$t('event.category.recital'), value: 'recitales' },
                {
                    text: this.$t('event.category.entertainment'),
                    value: 'entretenimientos',
                },
                { text: this.$t('event.category.sports'), value: 'deportes' },
                { text: this.$t('event.category.courses'), value: 'cursos' },
                {
                    text: this.$t('event.category.corporativec'),
                    value: 'corporativoc',
                },
                {
                    text: this.$t('event.category.corporativeo'),
                    value: 'corporativoa',
                },
                { text: this.$t('event.category.ceremony'), value: 'ceremonia' },
            ],
            serverOptionsLogo: {
                process: {
                    url: './api/uploadLogoEvent',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.event.logo_event = responseJson.name
                    },
                },
                load: loadUrl,
            },
            serverOptionsBanner: {
                process: {
                    url: './api/uploadBannerEvent',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.event.banner = responseJson.name
                    },
                },
                load: loadUrl,
            },
            loading: false,
            loadingTemplates: false,
        }
    },
    computed: {
        logo() {
            return this.event.logo_event
                ? [
                      {
                          source: this.event.logo_event,
                          options: { type: 'local' },
                      },
                  ]
                : []
        },
        banner() {
            return this.event.banner ? [{ source: this.event.banner, options: { type: 'local' } }] : []
        },

        currentTemplate() {
            let template = null
            if (this.withSelectedTemplate) {
                template = this.emailTemplates.find(template => template.id === this.selectedEmailTemplate)
            } else {
                template = this.emailTemplates.find(template => template.id === this.selectedDefaultEmailTemplate)
            }

            if (template) return template

            if (this.dialogConfirmation) return this.emailTemplates.find(template => template.id === 1)
            if (this.dialogReconfirmation) return this.emailTemplates.find(template => template.id === 2)
            if (this.dialogQR) return this.emailTemplates.find(template => template.id === 3)

            return { name: 'Default' }
        }
    },
    watch: {
        dialogMailings(val) {
            if (!val) {
                setTimeout(() => {
                    // this.mailing = {}
                    this.stepperMailings = 1
                }, 500)
            }
        },
        dialogScheduled(val) {
            if (!val) {
                setTimeout(() => {
                    this.mailing = {}
                }, 500)
            }
        },
    },
    methods: {
        async selectDefaultEmailTemplate() {
            this.loading = true
            try {
                const response = await this.axios.patch(`/events/${this.event.id}/templates/default`, { type: this.mailing.category, template_id: this.selectedDefaultEmailTemplate  })
                this.$snotify.success(this.$t('event.template.success'))
            } catch (e) {
                this.$snotify.error(this.$t('event.template.failure'))
            }
            this.loading = false
        },

        openLandingPreview(landing) {
            // console.log(this.$auth.token())
            this.openWindow(`events/${this.event.id}/landing/preview/${landing}?token=${this.$auth.token()}`)
        },

        publish(event) {
            if (!event.published) {
                this.axios
                    .post(`events/${event.id}/publish`)
                    .then((response) => {
                        this.$snotify.success(this.$t('event.publish.success'))
                        event.status = 'published'
                        event.publish = true
                    })
                    .catch((error) => {
                        this.$snotify.success(this.$t('event.publish.failure'))
                    })
            }
        },

        selectAllReasons() {
            this.suppress.reasons = [...this.suppress_reasons]
            this.$forceUpdate()
        },

        suppressEmails() {
            this.loading = true
            this.axios
                .post(`events/${this.event.id}/suppressEmails`, {
                    ...this.suppress,
                })
                .then((response) => {
                    this.$snotify.success(this.$t('suppress.sucess'))
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('suppress.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        dialogsuppressEmails() {
            this.dialogsuppress = true
        },

        exportBounced() {
            this.loading = true
            this.axios
                .get(`events/${this.event.id}/exportBounced`, {
                    responseType: 'blob',
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', `${this.event.name} - bounced.xlsx`)
                    document.body.appendChild(link)
                    link.click()
                })
                .finally(() => {
                    this.loading = false
                })
        },

        buildLanding() {
            this.openWindow(`events/${this.event.id}/landing/edit`)
        },

        releaseDevice(device) {
            this.loading = true
            this.axios
                .delete(`devices/${device.id}`)
                .then((response) => {
                    this.$snotify.success(this.$t('device.delete.sucess'))
                    this.event.devices = response.data.devices
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        showDialogDevices(event) {
            this.loading = true
            this.event = event

            this.axios
                .get(`events/${event.id}/devices`)
                .then((response) => {
                    this.dialogDevices = true
                    this.devices = response.data.devices
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        selectLanding(index) {
            this.axios
                .post(`events/${this.event.id}/landing`, { landing: index })
                .then((response) => {
                    this.event.landing.which = index
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                })
        },
        showDialogEmails(event) {
            this.loading = true
            this.showOverlay = true
            this.event = event

            let scheduled = this.axios
                .get(`/events/${event.id}/scheduled`)
                .then((response) => {
                    this.scheduled = response.data.scheduled
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                })

            let emails = this.axios
                .get(`events/${event.id}/emails`)
                .then((response) => {
                    this.emails = response.data.emails
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                })

            Promise.all([emails, scheduled])
                .then(() => {
                    this.dialogEmails = true
                })
                .finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
        },
        showDialogMailings(event) {
            this.event = event
            this.dialogMailings = true
            this.loadingTemplates = true

            this.axios
                .get('templates')
                .then((response) => {
                    this.emailTemplates = Object.assign([], response.data.templates)

                    this.emailTemplatesToSave = Object.assign([], response.data.templates)
                    this.emailTemplatesToSave.unshift({id: null, name: 'Default'})
                })
                .catch((error) => {
                    console.log(error)
                    this.emailTemplates = []
                })
                .finally(() => {
                    if (this.emailTemplates.length > 0) {
                        this.selectedEmailTemplate = this.emailTemplates[0].id
                    }
                    this.loadingTemplates = false
                })
        },
        async showDialogLanding(event) {

            this.configurarLandingDialog = true
            // console.log(this.configurarLandingDialog)

            // try {
            //     const response = await this.axios.get('sso_token')
            //     const sso_token = response.data.sso_token
            //     console.log(this.BASE_URL)
            //     this.openWindow(`http://localhost:8081/events/${event.id}/landing?sso_token=${sso_token}&editor=true`)
            // } catch (e) {
            //     console.log(e.message)
            // }
        },
        selectMailing(category) {
            this.mailing.category = category
            switch (category) {
                case 'confirmation':
                    this.selectedDefaultEmailTemplate = this.emailTemplatesToSave.find(template => template.id == this.event.confirmation_id).id || null
                    break

                case 'reconfirmation':
                    this.selectedDefaultEmailTemplate = this.emailTemplatesToSave.find(template => template.id == this.event.reconfirmation_id).id || null
                    break

                case 'qr':
                    this.selectedDefaultEmailTemplate = this.emailTemplatesToSave.find(template => template.id == this.event.qrcode_id).id || null
                    break

                case 'free':
                    this.selectedDefaultEmailTemplate = this.emailTemplatesToSave.find(template => template.id == this.event.free_id).id || null
                    break
            }

            this.stepperMailings = 2
        },
        selectMailType(mailType) {
            this.withSelectedTemplate = mailType === 'template'

            switch (this.mailing.category) {
                case 'scheduled':
                    this.mailing.type = mailType
                    this.dialogMailings = false
                    this.showDialogScheduled(this.event)
                    return

                case 'free':
                    this.dialogMailings = false
                    this.data = {
                        ...this.data,
                        fromName: `${this.$auth.user().name} ${this.$auth.user().lastname}`,
                        subject: ('Entrada Cortesia - ') + this.event.name,
                    }
                    this.showDialogFree(this.event)
                    return

                case 'confirmation':
                    this.dialogMailings = false
                    this.data = {
                        ...this.data,
                        fromName: `${this.$auth.user().name} ${this.$auth.user().lastname}`,
                        subject: (this.event.confirmation_form ? 'Confirmación ' : 'Invitación ') + this.event.name,
                    }
                    this.showDialogConfirmation(this.event)
                    return

                case 'qr':
                    this.dialogMailings = false
                    this.data = {
                        ...this.data,
                        fromName: `${this.$auth.user().name} ${this.$auth.user().lastname}`,
                        subject: `Reenvío Ticket ${this.event.name}`,
                    }
                    this.showDialogQR(this.event)
                    return

                case 'reconfirmation':
                    this.dialogMailings = false
                    this.data = {
                        ...this.data,
                        fromName: `${this.$auth.user().name} ${this.$auth.user().lastname}`,
                        subject: `Reconfirmación ${this.event.name}`,
                    }
                    this.showDialogReconfirmation(this.event)
                    return
            }
        },
        pay(event) {
            this.loading = true
            this.axios
                .post(`events/${event.id}/webpay/payment`)
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
                .catch((error) => {
                    this.loading = false
                })
        },
        openWindow: function (link) {
            window.open(link)
        },
        showDialogConfirmation(event) {
            this.loading = true
            this.showOverlay = true
            this.axios
                .get(`/events/${event.id}/participants/confirmation`)
                .then((response) => {
                    this.participants = response.data.participants

                    if (this.participants.length > 0) {
                        this.selectedParticipants = Object.assign([], this.participants)
                        this.event = event
                        this.dialogConfirmation = true
                    } else {
                        this.$snotify.error(this.$t('database.empty'))
                    }
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })
                .finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
        },
        showDialogQR(event) {
            this.loading = true
            this.showOverlay = true
            this.axios
                .get(`/events/${event.id}/participants/qr`)
                .then((response) => {
                    this.participants = response.data.participants

                    if (this.participants.length > 0) {
                        this.selectedParticipants = Object.assign([], this.participants)
                        this.event = event
                        this.dialogQR = true
                    } else {
                        this.$snotify.error(this.$t('database.empty'))
                    }
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })
                .finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
        },
        showDialogReconfirmation(event) {
            this.loading = true
            this.showOverlay = true
            this.axios
                .get(`/events/${event.id}/participants/reconfirmation`)
                .then((response) => {
                    this.participants = response.data.participants

                    if (this.participants.length > 0) {
                        this.selectedParticipants = Object.assign([], this.participants)
                        this.event = event
                        this.dialogReconfirmation = true
                    } else {
                        this.$snotify.error(this.$t('database.empty'))
                    }
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })
                .finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
        },
        showDialogFree(event) {
            this.loading = true
            this.showOverlay = true
            const participants = this.axios
                .get(`/profiles/${event.profile_id}/participants/`)
                .then((response) => {
                    this.participants = response.data.participants
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })

            const tickets = this.axios
                .get(`/events/${event.id}/tickets/`)
                .then((response) => {
                    this.tickets = response.data.tickets
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('general_error'))
                    this.$router.push('/home')
                })

            Promise.all([participants, tickets])
                .then(() => {
                    this.event = event
                    this.dialogFree = true
                })
                .finally(() => {
                    this.loading = false
                    this.showOverlay = false
                })
        },
        showDialogScheduled(event) {
            this.event = event
            this.dialogScheduled = true
        },
        sendMultipleConfirmation() {
            this.loading = true
            this.data = {
                ...this.data,
                participants: this.selectedParticipants.map(participant => participant.id),
                template_id: this.withSelectedTemplate ? this.selectedEmailTemplate : null,
            }

            this.axios
                .post(`/events/${this.event.id}/confirmation`, { ...this.data })
                .then((response) => {
                    this.$snotify.success(this.$t('event.confirmation.success'))
                    this.dialogConfirmation = false
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.confirmation.failure'))
                })
                .finally(() => {
                    this.data = {}
                    this.loading = false
                })
        },
        sendMultipleQR() {
            this.loading = true

            this.data = {
                ...this.data,
                participants: this.selectedParticipants.map(participant => participant.id),
                template_id: this.withSelectedTemplate ? this.selectedEmailTemplate : null,
            }

            this.axios
                .post(`/events/${this.event.id}/qr`, { ...this.data })
                .then((response) => {
                    this.$snotify.success(this.$t('event.qr.success'))
                    this.dialogQR = false
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.qr.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        sendMultipleReconfirmation() {
            this.loading = true

            this.data = {
                ...this.data,
                participants: this.selectedParticipants.map(participant => participant.id),
                template_id: this.withSelectedTemplate ? this.selectedEmailTemplate : null,
            }

            this.axios
                .post(`/events/${this.event.id}/reconfirmation`, {
                    ...this.data,
                })
                .then((response) => {
                    this.$snotify.success(this.$t('event.reconfirmation.success'))
                    this.dialogReconfirmation = false
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.reconfirmation.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        sendMultipleFree() {
            this.loading = true
            this.axios
                .post(`/events/${this.event.id}/free`, {
                    ticket_id: this.ticket,
                    template_id: this.withSelectedTemplate ? this.selectedEmailTemplate : null,
                    participants: this.selectedParticipants.map((o) => o.id),
                })
                .then((response) => {
                    this.$snotify.success(this.$t('event.free.success'))
                    this.dialogFree = false
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.free.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        sendScheduled() {
            this.loading = true
            this.axios
                .post(`/events/${this.event.id}/scheduled`, {
                    ...this.mailing,
                })
                .then((response) => {
                    this.$snotify.success(this.$t('event.scheduled.success'))
                    this.dialogScheduled = false
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.scheduled.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        editEvent(event) {
            this.$router.push({ name: 'event', params: { event } })
        },

        refreshScheduled(scheduled) {
            this.loading = true
             this.axios
                .get(`scheduled/${scheduled.id}`)
                .then(response => {
                    const index = this.scheduled.findIndex(s => scheduled.id === s.id)
                    const changedScheduled = [...this.scheduled]
                    changedScheduled[index] = response.data.scheduled
                    this.scheduled = changedScheduled

                    this.$snotify.success(this.$t('job.delete.success'))
                })
                .catch((e) => {
                    this.$snotify.error(this.$t('job.delete.error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        deleteScheduled(scheduled) {
            this.loading = true
             this.axios
                .delete(`scheduled/${scheduled.id}`)
                .then(() => {
                    const index = this.scheduled.findIndex(s => scheduled.id === s.id)
                    this.scheduled.splice(index, 1)
                    this.$snotify.success(this.$t('job.delete.success'))
                })
                .catch((e) => {
                    this.$snotify.error(this.$t('job.delete.error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        async getEvents() {
            this.events = []

            try {
                const response = await this.axios.get('/events/only')
                console.log(response);
                this.events = response.data.events
            } catch(error) {
                console.log(error)
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            }

            if (this.databases.length > 0) {
                this.loading = false
            }
        }
    },
    created() {
        this.loading = true

        this.getEvents()

        let databases = this.axios
            .get('/databases')
            .then((response) => {
                this.databases = response.data.databases
                if (this.databases.length > 0) {
                    this.event = {
                        database_id: this.databases[0].id,
                        category: 'recitales',
                    }
                }
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        Promise.all([databases]).finally(() => {
            this.loading = false
        })
    },
}
</script>

<style scoped>
.ellipsis {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 250px;
}

#wrap {
    width: 100%;
    height: 100%;
    max-height: 310px;
    padding: 0;
    overflow-x: hidden;
}

#scaled-frame {
    width: 100%;
    max-width: 1000px;
    height: 2000px;
    border: 0px;
}

#scaled-frame {
    zoom: 0.5;
    -moz-transform: scale(0.5);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.5);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.5);
    -webkit-transform-origin: 0 0;
}
</style>
