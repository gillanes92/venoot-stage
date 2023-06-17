<template>
    <v-content>
        <v-container>
            <v-flex xs12>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span class="title white--text">
                                {{
                                $t('participant.list').toUpperCase()
                                }}
                            </span>
                            <v-spacer></v-spacer>
                            <v-text-field
                                append-icon="search"
                                dark
                                height="30"
                                hide-details
                                single-line
                                class="mr-4"
                                v-model="slug"
                                clearable
                                @click.native.stop
                                @keydown.enter="search = slug"
                            ></v-text-field>
                            <v-btn
                                color="success"
                                @click.native.stop="create"
                            >{{ $t('new') }}</v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.native.stop="togglePrintChecking"
                                :loading="lookingPrints"
                            >
                                <v-icon
                                    :color="checkPrints ? 'white' : 'grey'"
                                >mdi-printer</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                v-if="database && participants.length > 0"
                                :headers="filteredEventHeaders"
                                :items="participants"
                                class="elevation-1"
                                :search="search"
                                dense
                                :key="redraw"
                            >
                                <template #item.actions="{ item }">
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="edit(item)"
                                        class="my-1"
                                    >Editar</v-btn>
                                    <v-btn
                                        v-if="!item.confirmed_status"
                                        x-small
                                        color="success"
                                        @click="confirm(item)"
                                        class="ml-1 my-1"
                                    >Confirmar</v-btn>
                                    <v-btn
                                        v-if="item.confirmed_status && !item.registered_at"
                                        x-small
                                        color="success"
                                        @click="register(item)"
                                        class="ml-1 my-1"
                                    >Acreditar</v-btn>
                                    <v-btn
                                        v-if="item.registered_at"
                                        x-small
                                        color="success"
                                        @click="printQr(item)"
                                        class="ml-1 my-1"
                                    >Imprimir</v-btn>
                                </template>
                            </v-data-table>
                            <v-skeleton-loader
                                v-else
                                class="mx-auto"
                                type="table"
                            ></v-skeleton-loader>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>

            <v-flex xs12 mt-6>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span class="title white--text">PREGUNTAS APP</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="refreshAppQuestions"
                                :loading="refreshing"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="toggleRefreshQuestions"
                                :loading="refreshing"
                            >
                                <v-icon
                                    :color="checkQuestions ? 'white' : 'grey'"
                                >mdi-calendar-refresh</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                v-if="appQuestions"
                                :headers="appQuestionsHeaders"
                                :items="appQuestions"
                                class="elevation-1"
                                dense
                                show-group-by
                                multi-sort
                            >
                                <template
                                    v-slot:group.header="{
                                        group,
                                        groupBy,
                                        headers,
                                        remove
                                    }"
                                >
                                    <td :colspan="headers.length">
                                        <template v-if="groupBy == 'answered'">
                                            <v-icon
                                                v-if="group == 'true'"
                                                color="green"
                                            >mdi-check</v-icon>
                                            <v-icon
                                                v-else
                                                color="yellow darken-3"
                                            >mdi-exclamation</v-icon>
                                        </template>
                                        <template v-else>{{ group }}</template>
                                        <v-icon
                                            size="20"
                                            color="red"
                                            clas="ml-4"
                                            @click="remove"
                                        >mdi-cancel</v-icon>
                                    </td>
                                </template>

                                <template #item.answered="{ item }">
                                    <v-icon
                                        v-if="item.answered"
                                        color="green"
                                    >mdi-check</v-icon>
                                    <v-icon
                                        v-else
                                        color="yellow darken-3"
                                    >mdi-exclamation</v-icon>
                                </template>

                                <template #item.actions="{ item }">
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="toggleQuestion(item)"
                                        class="ml-1 my-1"
                                        :loading="loading"
                                    >
                                        {{
                                        item.answered
                                        ? 'No Respondida'
                                        : 'Respondida'
                                        }}
                                    </v-btn>
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="deleteQuestion(item)"
                                        class="ml-1 my-1"
                                        :loading="loading"
                                    >Borrar</v-btn>
                                </template>
                            </v-data-table>
                            <v-skeleton-loader
                                v-else
                                class="mx-auto"
                                type="table"
                            ></v-skeleton-loader>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>

            <v-flex xs12 mt-6>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span class="title white--text">REUNIONES</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="success"
                                @click.native.stop="
                                    meetingTimeWindowDialog = true
                                "
                            >{{ $t('new') }}</v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="refreshMeetings"
                                :loading="refreshingMeetings"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="toggleRefreshMeetings"
                                :loading="refreshingMeetings"
                            >
                                <v-icon
                                    :color="checkMeetings ? 'white' : 'grey'"
                                >mdi-calendar-refresh</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                v-if="meetings"
                                :headers="meetingsHeaders"
                                :items="meetings"
                                class="elevation-1"
                                dense
                                show-group-by
                                multi-sort
                            >
                                <template #item.host="{ item }">
                                    {{
                                    `${item.host.data.name} ${item.host.data.lastname}`
                                    }}
                                </template>

                                <template #item.attendant="{ item }">
                                    <v-autocomplete
                                        v-model="item.attendant_id"
                                        :items="participants"
                                        :item-text="
                                            item =>
                                                `${item.name} ${item.lastname}`
                                        "
                                        item-value="id"
                                        clearable
                                        dense
                                        hide-selected
                                        @change="selectAttendant(item)"
                                    ></v-autocomplete>
                                </template>

                                <!-- {{ item.attendant ? `${item.attendant.data.name} ${item.attendant.data.lastname}` : "" }}</template> -->

                                <template #item.actions="{ item }">
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="deleteMeeting(item)"
                                        class="ml-1 my-1"
                                        :loading="loading"
                                    >Borrar</v-btn>
                                </template>
                            </v-data-table>
                            <v-skeleton-loader
                                v-else
                                class="mx-auto"
                                type="table"
                            ></v-skeleton-loader>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>

            <v-flex xs12 mt-6>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span class="title white--text">CHAT</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="showQuestionVotes = true"
                            >
                                <v-icon>mdi-vote</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="showQuestionRequest = true"
                            >
                                <v-icon>mdi-account-question</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="refreshChat"
                                :loading="refreshingChat"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="toggleRefreshChat"
                                :loading="refreshingChat"
                            >
                                <v-icon
                                    :color="checkChat ? 'white' : 'grey'"
                                >mdi-calendar-refresh</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                v-if="chatMessages"
                                :headers="chatHeaders"
                                :items="chatMessages"
                                class="elevation-1"
                                dense
                                show-group-by
                                multi-sort
                            >
                                <template #item.actions="{ item }">
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="openChatDialog(item)"
                                        class="ml-1 my-1"
                                        :loading="loading"
                                    >Responder</v-btn>
                                </template>
                            </v-data-table>
                            <v-skeleton-loader
                                v-else
                                class="mx-auto"
                                type="table"
                            ></v-skeleton-loader>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>

            <v-flex xs12 mt-6>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span class="title white--text">PREGUNTAS STREAMING</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="refreshQuestions"
                                :loading="refreshingQuestions"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="toggleRefreshSubmittedQuestions"
                                :loading="refreshingQuestions"
                            >
                                <v-icon
                                    :color="checkSubmittedQuestions ? 'white' : 'grey'"
                                >mdi-calendar-refresh</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                v-if="!!submittedQuestions"
                                :headers="submittedQuestionsHeaders"
                                :items="submittedQuestions"
                                class="elevation-1"
                                dense
                                show-group-by
                                multi-sort
                            ></v-data-table>
                            <v-skeleton-loader
                                v-else
                                class="mx-auto"
                                type="table"
                            ></v-skeleton-loader>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>

            <v-flex xs12 mt-6>
                <v-expansion-panels>
                    <v-expansion-panel>
                        <v-expansion-panel-header class="primary">
                            <span
                                class="title white--text"
                            >PREGUNTAS A LOS PARTICIPANTES</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="showSendPollQuestion = true"
                            >
                                <v-icon>mdi-poll</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="refreshPollQuestions"
                                :loading="refreshingPollQuestions"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn
                                icon
                                color="white"
                                @click.stop.native="toggleRefreshPolls"
                                :loading="refreshingPolls"
                            >
                                <v-icon
                                    :color="checkPolls ? 'white' : 'grey'"
                                >mdi-calendar-refresh</v-icon>
                            </v-btn>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-row>
                                <v-col
                                    cols="4"
                                    v-for="(answer, index) of answers"
                                    :key="index"
                                >
                                    <apexchart
                                        ref="chartReferers"
                                        width="100%"
                                        height="400"
                                        type="pie"
                                        :options="optionsReferer[index]"
                                        :series="seriesReferer[index]"
                                    />
                                </v-col>
                            </v-row>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-flex>
        </v-container>

        <v-dialog
            v-model="meetingTimeWindowDialog"
            max-width="500px"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Nueva Ventana de Reuniones</v-card-title>

                <v-card-text>
                    <v-form :ref="`manualAddForm`" onsubmit="return false;">
                        <v-row>
                            <v-col>
                                <v-autocomplete
                                    v-model="meetingTimeWindow.host_id"
                                    :items="participants"
                                    :item-text="
                                        item => `${item.name} ${item.lastname}`
                                    "
                                    item-value="id"
                                    label="Anfitrión"
                                ></v-autocomplete>
                            </v-col>
                            <v-col>
                                <v-menu
                                    v-model.lazy="startDayMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-validate="'required'"
                                            data-vv-name="start_date"
                                            :error-messages="
                                                errors.collect(
                                                    'meetingTimeWindow.date'
                                                )
                                            "
                                            v-model.lazy="
                                                meetingTimeWindow.date
                                            "
                                            :label="
                                                $t('form.date').capitalize()
                                            "
                                            prepend-icon="calendar_today"
                                            readonly
                                            v-on="on"
                                            required
                                            format="YYYY-MM-DD"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="meetingTimeWindow.date"
                                        no-title
                                        scrollable
                                        :locale="$i18n.locale"
                                        @input="startDayMenu = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-menu
                                    ref="startTimeMenu"
                                    v-model.lazy="startTimeMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="
                                        meetingTimeWindow.start_time
                                    "
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            data-vv-validate-on="blur"
                                            v-validate="'required'"
                                            data-vv-name="start_time"
                                            :error-messages="
                                                errors.collect(
                                                    'meetingTimeWindow.start_time'
                                                )
                                            "
                                            v-model.lazy="
                                                meetingTimeWindow.start_time
                                            "
                                            :label="
                                                $t(
                                                    'form.start_time'
                                                ).capitalize()
                                            "
                                            prepend-icon="mdi-clock"
                                            readonly
                                            v-on="on"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        format="24hr"
                                        v-if="startTimeMenu"
                                        v-model.lazy="
                                            meetingTimeWindow.start_time
                                        "
                                        scrollable
                                        :locale="$i18n.locale"
                                        @click:minute="
                                            $refs.startTimeMenu.save(
                                                meetingTimeWindow.start_time
                                            )
                                        "
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                            <v-col>
                                <v-menu
                                    ref="endTimeMenu"
                                    v-model.lazy="endTimeMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="
                                        meetingTimeWindow.end_time
                                    "
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            data-vv-validate-on="blur"
                                            v-validate="'required'"
                                            data-vv-name="end_time"
                                            :error-messages="
                                                errors.collect(
                                                    'meetingTimeWindow.end_time'
                                                )
                                            "
                                            v-model.lazy="
                                                meetingTimeWindow.end_time
                                            "
                                            :label="
                                                $t('form.end_time').capitalize()
                                            "
                                            prepend-icon="mdi-clock"
                                            readonly
                                            v-on="on"
                                            required
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        format="24hr"
                                        v-if="endTimeMenu"
                                        v-model.lazy="
                                            meetingTimeWindow.end_time
                                        "
                                        scrollable
                                        :locale="$i18n.locale"
                                        @click:minute="
                                            $refs.endTimeMenu.save(
                                                meetingTimeWindow.end_time
                                            )
                                        "
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-select
                                    v-model="meetingTimeWindow.interval"
                                    :items="[
                                        5,
                                        10,
                                        15,
                                        20,
                                        25,
                                        30,
                                        35,
                                        40,
                                        45,
                                        50,
                                        55,
                                        60
                                    ]"
                                    label="Intervalo de Tiempo"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        @click="meetingTimeWindowDialog = false"
                        :loading="loading"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="error"
                        @click="saveMeetingWindow"
                        :loading="loading"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-if="participant && database"
            v-model="manualAddDialog"
            max-width="50%"
        >
            <v-card>
                <v-card-title class="headline">
                    {{
                    participant.id
                    ? $t('edit').capitalize() +
                    ' ' +
                    $t('participant.title')
                    : $t('add').capitalize()
                    }}
                </v-card-title>
                <v-card-text>
                    <v-form :ref="`manualAddForm`" onsubmit="return false;">
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <template
                                        v-for="(field,
                                        index) in database.fields"
                                    >
                                        <v-text-field
                                            v-if="
                                                field.name &&
                                                    field.type === 'string'
                                            "
                                            :key="index"
                                            v-validate="{
                                                required: field.required
                                            }"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :required="field.required"
                                            prepend-icon="short_text"
                                            v-model="participant[field.key]"
                                            :label="field.name"
                                            type="text"
                                        ></v-text-field>
                                        <v-text-field
                                            v-if="
                                                field.name &&
                                                    field.type === 'email'
                                            "
                                            :key="index"
                                            v-validate="{
                                                required: field.required,
                                                email: true
                                            }"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :required="field.required"
                                            prepend-icon="short_text"
                                            v-model="participant[field.key]"
                                            :label="field.name"
                                            type="text"
                                        ></v-text-field>
                                        <v-text-field
                                            v-if="
                                                field.name &&
                                                    field.type === 'integer'
                                            "
                                            :key="index"
                                            v-validate="{
                                                required: field.required,
                                                numeric: true
                                            }"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :required="field.required"
                                            prepend-icon="dialpad"
                                            v-model="participant[field.key]"
                                            :label="field.name"
                                            type="number"
                                        ></v-text-field>
                                        <v-text-field
                                            v-if="
                                                field.name &&
                                                    field.type === 'float'
                                            "
                                            :key="index"
                                            v-validate="{
                                                required: field.required,
                                                decimal: 4
                                            }"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :required="field.required"
                                            prepend-icon="dialpad"
                                            v-model="participant[field.key]"
                                            :label="field.name"
                                            type="text"
                                        ></v-text-field>
                                        <v-checkbox
                                            v-if="
                                                field.name &&
                                                    field.type === 'boolean'
                                            "
                                            :key="index"
                                            v-validate="'required'"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :required="field.required"
                                            v-model="participant[field.key]"
                                            :label="field.name"
                                            color="success"
                                        ></v-checkbox>
                                        <v-menu
                                            :key="index"
                                            v-if="
                                                field.name &&
                                                    field.type === 'date'
                                            "
                                            v-model="menu[field.id]"
                                            :close-on-content-click="true"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    v-model="
                                                        participant[field.name]
                                                    "
                                                    :label="field.name"
                                                    v-validate="{
                                                        required:
                                                            field.required,
                                                        date_format: true
                                                    }"
                                                    :data-vv-as="
                                                        field.name.toLowerCase()
                                                    "
                                                    :data-vv-name="
                                                        `manualAddForm.${field.key}${index}`
                                                    "
                                                    :error-messages="
                                                        errors.collect(
                                                            `manualAddForm.${field.key}${index}`
                                                        )
                                                    "
                                                    prepend-icon="event"
                                                    :required="field.required"
                                                    readonly
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="participant[field.key]"
                                                no-title
                                                scrollable
                                                :locale="$i18n.locale"
                                                color="success"
                                            ></v-date-picker>
                                        </v-menu>

                                        <v-datetime-picker
                                            v-if="
                                                field.name &&
                                                    field.type === 'datetime'
                                            "
                                            v-validate="{
                                                required: field.required,
                                                date_format: true
                                            }"
                                            :data-vv-as="
                                                field.name.toLowerCase()
                                            "
                                            :data-vv-name="
                                                `manualAddForm.${field.key}${index}`
                                            "
                                            :error-messages="
                                                errors.collect(
                                                    `manualAddForm.${field.key}${index}`
                                                )
                                            "
                                            :key="index"
                                            v-model="participant[field.key]"
                                            :locale="$i18n.locale"
                                            :label="field.name"
                                            prepend-icon="event"
                                            :okText="$t('accept')"
                                            :clearText="$t('clear')"
                                        ></v-datetime-picker>
                                    </template>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="warning" @click="manualAddDialog = false">
                        {{
                        $t('cancel')
                        }}
                    </v-btn>
                    <v-btn color="error" @click="saveManualParticipant">
                        {{
                        $t('accept')
                        }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="chatResponseDialog"
            max-width="500px"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Enviar Mensaje</v-card-title>

                <v-card-text>
                    <v-form :ref="`manualAddForm`" onsubmit="return false;">
                        <v-row>
                            <v-col>
                                <v-text-field
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="chat_message"
                                    :error-messages="
                                        errors.collect('chat.chat_message')
                                    "
                                    v-model.lazy="chatMessage"
                                    label="Mensaje"
                                    prepend-icon="mdi-short-text"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        @click="chatResponseDialog = false"
                        :loading="loading"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="error"
                        @click="sendChatMessage"
                        :loading="loading"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="showQuestionRequest"
            max-width="500px"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Enviar Requerimiento de Pregunta</v-card-title>
                <v-card-text>
                    <v-form onsubmit="return false;">
                        <v-row>
                            <v-col>
                                <v-select
                                    v-model="currentActivity"
                                    :items="activities"
                                    label="Actividad"
                                    item-text="name"
                                    return-object
                                    :loading="loading"
                                    full-width
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_request_message"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.question_request_message'
                                        )
                                    "
                                    v-model.lazy="questionRequest.message"
                                    label="Mensaje"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-slider
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_limit"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.question_limit'
                                        )
                                    "
                                    v-model.lazy="
                                        questionRequest.question_limit
                                    "
                                    label="Limite de Preguntas"
                                    :min="1"
                                    :max="100"
                                    required
                                >
                                    <template v-slot:append>
                                        <v-text-field
                                            v-model="
                                                questionRequest.question_limit
                                            "
                                            class="mt-0 pt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                            style="width: 60px"
                                        ></v-text-field>
                                    </template>
                                </v-slider>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-slider
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_limit"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.per_person_question_limit'
                                        )
                                    "
                                    v-model.lazy="
                                        questionRequest.per_person_question_limit
                                    "
                                    label="Limite de Preguntas por Persona"
                                    :min="1"
                                    :max="5"
                                    required
                                >
                                    <template v-slot:append>
                                        <v-text-field
                                            v-model="
                                                questionRequest.per_person_question_limit
                                            "
                                            class="mt-0 pt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                            style="width: 60px"
                                        ></v-text-field>
                                    </template>
                                </v-slider>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-checkbox
                                    v-model="questionRequest.vote_instead"
                                    label="Permitir voto en vez de pregunta"
                                ></v-checkbox>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        @click="showQuestionRequest = false"
                        :loading="loading"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="error"
                        @click="sendQuestionRequest"
                        :loading="loading"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="pollsDialog"
            max-width="500px"
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Enviar Pregunta al Streaming</v-card-title>
                <v-card-text>
                    <v-form onsubmit="return false;">
                        <v-row>
                            <v-col>
                                <v-text-field
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_request_message"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.question_request_message'
                                        )
                                    "
                                    v-model.lazy="questionRequest.message"
                                    label="Mensaje"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-slider
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_limit"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.question_limit'
                                        )
                                    "
                                    v-model.lazy="
                                        questionRequest.question_limit
                                    "
                                    label="Limite de Preguntas"
                                    :min="1"
                                    :max="100"
                                    required
                                >
                                    <template v-slot:append>
                                        <v-text-field
                                            v-model="
                                                questionRequest.question_limit
                                            "
                                            class="mt-0 pt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                            style="width: 60px"
                                        ></v-text-field>
                                    </template>
                                </v-slider>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-slider
                                    data-vv-validate-on="blur"
                                    v-validate="'required'"
                                    data-vv-name="question_limit"
                                    :error-messages="
                                        errors.collect(
                                            'question_request.per_person_question_limit'
                                        )
                                    "
                                    v-model.lazy="
                                        questionRequest.per_person_question_limit
                                    "
                                    label="Limite de Preguntas por Persona"
                                    :min="1"
                                    :max="5"
                                    required
                                >
                                    <template v-slot:append>
                                        <v-text-field
                                            v-model="
                                                questionRequest.per_person_question_limit
                                            "
                                            class="mt-0 pt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                            style="width: 60px"
                                        ></v-text-field>
                                    </template>
                                </v-slider>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-checkbox
                                    v-model="questionRequest.vote_instead"
                                    label="Permitir voto en vez de pregunta"
                                ></v-checkbox>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        @click="pollsDialog = false"
                        :loading="loading"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="error"
                        @click="sendQuestionRequest"
                        :loading="loading"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="showQuestionVotes"
            scrollable
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Votos Preguntas</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col></v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-data-table
                                v-if="questionVotes"
                                :headers="questionVotesHeaders"
                                :items="questionVotes"
                                class="elevation-1"
                                dense
                                show-group-by
                                multi-sort
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="showSendPollQuestion"
            max-width="500px"
            scrollable
            transition="dialog-transition"
        >
            <v-card>
                <v-card-title class="headline">Preguntas de Encuesta</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-select
                                v-model="currentPoll"
                                :items="polls"
                                label="Encuesta"
                                item-text="name"
                                return-object
                                :loading="loading"
                                full-width
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select
                                v-model="currentQuestion"
                                :items="currentQuestions"
                                label="Pregunta"
                                item-text="question"
                                return-object
                                :loading="loading"
                                full-width
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select
                                v-model="currentActivity"
                                :items="activities"
                                label="Actividad"
                                item-text="name"
                                return-object
                                :loading="loading"
                                full-width
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="warning"
                        @click="showSendPollQuestion = false"
                        :loading="loading"
                    >{{ $t('cancel') }}</v-btn>
                    <v-btn
                        color="error"
                        @click="sendPollQuestion"
                        :loading="loading"
                    >{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-content>
</template>

<script>
import { map, pick, forEach } from 'lodash'
import VueApexCharts from 'vue-apexcharts'
export default {
    name: 'ControlPanel',
    components: {
        apexchart: VueApexCharts
    },
    data: () => ({
        redraw: false,
        currentParticipation: null,
        currentPoll: null,
        currentQuestion: null,
        currentActivity: null,
        showSendPollQuestion: false,
        checkPrints: false,
        lookingPrints: false,
        queuedPrints: [],
        printInterval: null,
        manualAddDialog: false,
        meetingTimeWindowDialog: false,
        event: null,
        participants: [],
        participant: {},
        appQuestions: null,
        database: null,
        selectedFields: null,
        printWindow: null,
        slug: null,
        search: null,
        refreshing: false,
        refreshingMeetings: false,
        refreshingChat: false,
        refreshingQuestions: false,
        refreshChatInterval: null,
        checkQuestions: false,
        loading: false,
        meetings: [],
        chatMessages: [],
        checkMeetings: false,
        checkChat: false,
        meetingTimeWindow: {
            host_id: null,
            date: null,
            start_time: null,
            end_time: null,
            interval: null
        },
        startDayMenu: false,
        startTimeMenu: false,
        endTimeMenu: false,
        chatMessage: '',
        chatResponseDialog: false,
        currentChatActivity: 0,
        showQuestionRequest: false,
        questionRequest: {
            message: null,
            question_limit: 20
        },
        showQuestionVotes: false,
        questionVotes: [],
        refreshingQuestionVotes: false,
        refreshQuestionVotesInterval: null,
        submittedQuestions: [],
        polls: [],
        refreshingPolls: false,
        checkPolls: false,
        refreshPollsInterval: null,
        pollsDialog: false,
        checkSubmittedQuestions: false,
        refreshingPollQuestions: false,
        polls: [],
        answers: [],
        submittedQuestionsHeaders: [
            {
                text: 'Mensaje',
                value: 'message_sent'
            },

            {
                text: 'Pregunta',
                value: 'question'
            },

            {
                text: 'Votos',
                value: 'votes'
            },

            {
                text: 'Participante',
                value: 'participant_name'
            }
        ],

        questionVotesHeaders: [
            {
                text: 'Activity',
                value: 'activity_name'
            },

            {
                text: 'Mensaje Enviado',
                value: 'message_sent'
            },

            {
                text: 'Pregunta',
                value: 'question'
            },

            {
                text: 'Votos',
                value: 'votes'
            }
        ],

        appQuestionsHeaders: [
            {
                text: 'Pregunta',
                value: 'question'
            },

            {
                text: 'Respondida',
                value: 'answered'
            },

            {
                text: 'Actividad',
                value: 'activityName'
            },

            {
                text: 'Acciones',
                value: 'actions'
            }
        ],

        meetingsHeaders: [
            {
                text: 'Anfrition',
                value: 'host'
            },

            {
                text: 'Fecha',
                value: 'date'
            },

            {
                text: 'Hora Inicio',
                value: 'start_time'
            },

            {
                text: 'Hora Fin',
                value: 'end_time'
            },

            {
                text: 'Huésped',
                value: 'attendant'
            },

            {
                text: 'Acciones',
                value: 'actions'
            }
        ],

        chatHeaders: [
            {
                text: 'Actividad',
                value: 'activity_name'
            },

            {
                text: 'Nombre',
                value: 'full_name'
            },

            {
                text: 'Mensaje',
                value: 'message_data.text'
            },

            {
                text: 'Acciones',
                value: 'actions'
            }
        ],

        optionsReferer: [],
        seriesReferer: []
    }),

    computed: {
        filteredEventHeaders: function() {
            if (!this.database) return []
            let fields = this.database.fields.map(field => {
                return {
                    text: field.name,
                    value: field.key
                }
            })
            fields.push({
                text: 'Actions',
                value: 'actions',
                width: '200px',
                sortable: false
            })

            return fields
        },

        currentQuestions() {
            return this.currentPoll ? this.currentPoll.questions : []
        },

        activities() {
            return [
                { id: 0, name: 'Ningúna' },
                ...(this.event ? this.event.activities : [])
            ].sort((a, b) => a.id > b.id)
            //  + this.event
            //     ? this.event.activities
            //     : []
        }
    },

    watch: {
        showQuestionVotes(value) {
            if (value) {
                this.refreshQuestionVotes()
                this.refreshQuestionVotesInterval = setInterval(() => {
                    this.refreshQuestionVotes()
                }, 10000)
            } else {
                clearInterval(this.refreshQuestionVotesInterval)
            }
        }
    },
    methods: {
        async sendPollQuestion() {
            this.loading = true
            const activity_id =
                this.currentActivity.id === 0 ? null : this.currentActivity.id
            try {
                const response = await this.axios.post(
                    `/events/${this.$route.params.id}/questions/${this.currentQuestion.id}`,
                    { activity_id }
                )
            } catch (e) {
                console.log(e)
            }

            this.loading = false
        },

        async sendQuestionRequest() {
            const activity_id =
                this.currentActivity.id === 0 ? null : this.currentActivity.id
            const response = await this.axios.post(
                `/events/${this.$route.params.id}/questionRequest`,
                {
                    activity_id,
                    ...this.questionRequest
                }
            )
        },

        openChatDialog(item) {
            this.currentParticipation = item.participation_id
            this.currentChatActivity = item.activity_id
            this.chatResponseDialog = true
        },

        openPollDialog() {
            this.pollsDialog = true
        },

        async sendChatMessage() {
            this.loading = true
            try {
                const response = await this.axios.post(
                    `/events/${this.$route.params.id}/streamChatMessages`,
                    {
                        participation_id: this.currentParticipation,
                        activity_id: this.currentChatActivity,
                        message: this.chatMessage
                    }
                )
            } catch (e) {
                console.log(e)
                this.loading = false
            }
            this.loading = false
        },

        async selectAttendant(item) {
            this.loading = true

            if (!item.attendant_id) {
                item.attendant_id = null
            }

            try {
                const response = await this.axios.post(
                    `/events/${this.$route.params.id}/meetings/${item.id}/attendant`,
                    { attendant_id: item.attendant_id }
                )

                item.attendant = response.data.attendant
                item.attendant_id = item.attendant.id
            } catch (e) {
                console.log(e)
                item.attendant_id = null
                this.loading = false
            }
            this.loading = false
        },

        async deleteMeeting(item) {
            this.loading = true
            try {
                const response = await this.axios.delete(
                    `/events/${this.$route.params.id}/meetings/${item.id}`
                )

                this.meetings = response.data.meetings
            } catch (e) {
                console.log(e)
                this.loading = false
            }
            this.loading = false
        },

        async refreshMeetings() {
            this.refreshingMeetings = true
            try {
                const response = await this.axios.get(
                    `/events/${this.$route.params.id}/meetings`
                )

                this.meetings = response.data.meetings
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshInterval)
                this.refreshingMeetings = false
            }
            this.refreshingMeetings = false
        },

        async refreshSubmittedQuestions() {
            this.refreshingMeetings = true
            try {
                const response = await this.axios.get(
                    `/events/${this.$route.params.id}/submittedQuestions`
                )

                this.submittedQuestions = response.data.submitted_questions
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshInterval)
                this.refreshingQuestions = false
            }
            this.refreshingQuestions = false
        },

        async refreshChat() {
            this.refreshingChat = true
            try {
                const response = await this.axios.get(
                    `/events/${this.$route.params.id}/streamChatMessages`
                )

                this.chatMessages = response.data.chat_messages
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshChatInterval)
                this.refreshingChat = false
            }
            this.refreshingChat = false
        },

        async saveMeetingWindow() {
            this.loading = true
            try {
                const response = await this.axios.post(
                    `/events/${this.$route.params.id}/meetingWindow`,
                    { ...this.meetingTimeWindow }
                )

                this.meetings = response.data.meetings

                this.meetingTimeWindow = {
                    host_id: null,
                    date: null,
                    start_time: null,
                    end_time: null,
                    interval: null
                }

                this.meetingTimeWindowDialog = false
            } catch (e) {
                console.log(e)
            }
            this.loading = false
        },

        async toggleQuestion(question) {
            this.loading = true
            try {
                const response = await this.axios.post(
                    `/appQuestion/${question.id}/toggle`
                )

                if (response.status === 200) {
                    question.answered = !question.answered
                }
            } catch (e) {
                console.log(e)
            }
            this.loading = false
        },

        async refreshAppQuestions() {
            this.refreshing = true
            try {
                const appQuestions = await this.axios.get(
                    `/events/${this.$route.params.id}/appQuestions`
                )

                this.appQuestions = appQuestions.data.appQuestions
                for (let appQuestion of this.appQuestions) {
                    appQuestion.activityName = appQuestion.activity.name
                }
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshInterval)
                this.refreshing = false
            }
            this.refreshing = false
        },

        async refreshQuestions() {
            this.refreshingQuestions = true
            try {
                const submittedQuestions = await this.axios.get(
                    `/events/${this.$route.params.id}/submittedQuestions`
                )

                this.submittedQuestions =
                    submittedQuestions.data.submitted_questions
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshInterval)
                this.refreshingQuestions = false
            }
            this.refreshingQuestions = false
        },

        async refreshPollQuestions() {
            this.refreshingPollQuestions = true
            try {
                const response = await this.axios.get(
                    `/events/${this.$route.params.id}/answers`
                )

                this.answers = response.data.answers
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshPollsInterval)
                this.refreshingPollQuestions = false
            }
            this.refreshingPollQuestions = false
        },

        async toggleRefreshMeetings() {
            this.checkMeetings = !this.checkMeetings

            if (this.checkMeetings) {
                this.refreshMeetingsInterval = setInterval(async () => {
                    this.refreshingMeetings = true
                    try {
                        const result = await this.axios.get(
                            `/events/${this.$route.params.id}/meetings`
                        )

                        this.meetings = result.data.meetings
                    } catch (e) {
                        console.log(e)
                        clearInterval(this.refreshMeetingsInterval)
                        this.refreshingMeetings = false
                    }
                    this.refreshingMeetings = false
                }, 20000)
            } else {
                clearInterval(this.refreshInterval)
            }
        },

        async toggleRefreshChat() {
            this.checkChat = !this.checkChat

            if (this.checkChat) {
                this.refreshChatInterval = setInterval(async () => {
                    this.refreshingChat = true
                    try {
                        const result = await this.axios.get(
                            `/events/${this.$route.params.id}/streamChatMessages`
                        )

                        this.chatMessages = result.data.chat_messages
                    } catch (e) {
                        console.log(e)
                        clearInterval(this.refreshChatInterval)
                        this.refreshingChat = false
                    }
                    this.refreshingChat = false
                }, 20000)
            } else {
                clearInterval(this.refreshChatInterval)
            }
        },

        async toggleRefreshQuestions() {
            this.checkQuestions = !this.checkQuestions

            if (this.checkQuestions) {
                this.refreshInterval = setInterval(async () => {
                    this.refreshing = true
                    try {
                        const appQuestions = await this.axios.get(
                            `/events/${this.$route.params.id}/appQuestions`
                        )

                        this.appQuestions = appQuestions.data.appQuestions
                        for (let appQuestion of this.appQuestions) {
                            appQuestion.activityName = appQuestion.activity.name
                        }
                    } catch (e) {
                        console.log(e)
                        clearInterval(this.refreshInterval)
                        this.refreshing = false
                    }
                    this.refreshing = false
                }, 20000)
            } else {
                clearInterval(this.refreshInterval)
            }
        },

        async toggleRefreshSubmittedQuestions() {
            this.checkSubmittedQuestions = !this.checkSubmittedQuestions

            if (this.checkSubmittedQuestions) {
                this.refreshQuestionsInterval = setInterval(async () => {
                    this.refreshingQuestions = true
                    try {
                        const response = await this.axios.get(
                            `/events/${this.$route.params.id}/submittedQuestions`
                        )

                        this.submittedQuestions =
                            response.data.submitted_questions
                    } catch (e) {
                        console.log(e)
                        clearInterval(this.refreshInterval)
                        this.refreshingQuestions = false
                    }
                    this.refreshingQuestions = false
                }, 20000)
            } else {
                clearInterval(this.refreshInterval)
            }
        },

        async deleteQuestion(question) {
            this.loading = true
            try {
                const response = await this.axios.delete(
                    `/appQuestion/${question.id}`
                )

                if (response.status === 204) {
                    const index = this.appQuestions.findIndex(
                        appQuestion => appQuestion.id === question.id
                    )
                    this.appQuestions.splice(index, 1)
                }
            } catch (e) {
                console.log(e)
            }
            this.loading = false
        },

        togglePrintChecking() {
            this.checkPrints = !this.checkPrints

            if (this.checkPrints) {
                this.printInterval = setInterval(async () => {
                    this.lookingPrints = true
                    try {
                        const response = await this.axios.post(
                            `events/${this.event.id}/participation/checkPrints`
                        )
                        response.data.queued.forEach(queuedPrint => {
                            this.printQrByUuid(queuedPrint.participation_uuid)
                        })
                    } catch (e) {
                        console.log(e)
                        clearInterval(this.printInterval)
                        this.checkPrints = false
                    }
                    this.lookingPrints = false
                }, 4000)
            } else {
                clearInterval(this.printInterval)
            }
        },

        create() {
            this.participant = {}
            this.manualAddDialog = true
        },

        edit(participant) {
            this.participant = Object.assign({}, participant)
            this.manualAddDialog = true
        },

        async saveManualParticipant() {
            const fields = this.database.fields.map(field => {
                return field.key
            })
            this.participant.data = pick(this.participant, fields)

            if (this.participant.id) {
                // update
                try {
                    const response = await this.axios.patch(
                        `/databases/${this.database.id}/participants/${this.participant.id}`,
                        { data: this.participant.data }
                    )
                    if (response.status == 200) {
                        let oldParticipant = this.participants.find(
                            oldParticipant => {
                                return oldParticipant.id === this.participant.id
                            }
                        )

                        for (var key in this.participant.data) {
                            if (this.participant.data.hasOwnProperty(key)) {
                                oldParticipant[key] = this.participant.data[key]
                            }
                        }

                        this.$snotify.success(
                            this.$t('participant.update.success')
                        )
                        this.manualAddDialog = false
                    }
                } catch (e) {
                    console.log(e)
                    this.$snotify.error(this.$t('participant.update.failure'))
                }
            } else {
                // create
                try {
                    const response = await this.axios.post(
                        `/databases/${this.database.id}/participants/storeFromControlPanel`,
                        { data: this.participant.data }
                    )
                    if (response.status == 200) {
                        this.participants.push(response.data.participant)
                        // sortBy(this.participant, o => o.id)

                        this.$snotify.success(
                            this.$t('participant.update.success')
                        )
                        this.manualAddDialog = false
                    }
                } catch (e) {
                    console.log(e)
                    this.$snotify.error(this.$t('participant.update.failure'))
                }
            }
        },

        async confirm(participant) {
            try {
                const response = await this.axios.post(
                    `events/${this.$route.params.id}/participants/${participant.id}/confirmsFromApp`
                )
                const updatedParticipant = response.data.participant
                console.log(updatedParticipant)
                console.log(this.participants)
                let oldParticipant = this.participants.find(oldParticipant => {
                    return oldParticipant.id === updatedParticipant.id
                })
                console.log(oldParticipant)
                oldParticipant.confirmed_status =
                    updatedParticipant.confirmed_status
                oldParticipant.confirmed_at = updatedParticipant.confirmed_at
                oldParticipant.registered_at = null
                this.redraw = !this.redraw
            } catch (error) {
                console.log(error)
            }
        },

        async register(participant) {
            try {
                const response = await this.axios.post(
                    `participations/registerFromControlPanel`,
                    {
                        uuid: participant.uuid
                        // device_id: Constants.installationId
                    }
                )

                const updatedParticipant = response.data.participant
                let oldParticipant = this.participants.find(oldParticipant => {
                    return oldParticipant.id === updatedParticipant.id
                })
                oldParticipant.registered_at = updatedParticipant.registered_at
            } catch (error) {
                console.log(error)
            }
        },

        async printQr(participant) {
            this.printQrByUuid(participant.uuid)
        },

        printQrByUuid(uuid) {
            var printWindow = window.open(
                `${this.BASE_URL}/participations/autoprint/${uuid}`,
                '_blank',
                'width=800,height=600'
            )

            var printing = setInterval(() => {
                console.log('Printing...')
                printWindow.close()
                if (printWindow.closed) {
                    clearInterval(printing)
                }
            }, 10000)
        },

        async refreshQuestionVotes() {
            this.refreshingQuestionVotes = true
            try {
                const questionVotes = await this.axios.get(
                    `/events/${this.$route.params.id}/questionVotes`
                )

                this.questionVotes = questionVotes.data.questionVotes
            } catch (e) {
                console.log(e)
                clearInterval(this.refreshQuestionVotesInterval)
                this.refreshing = false
            }
            this.refreshingQuestionVotes = false
        }
    },

    async created() {
        this.loading = true

        const event = this.axios.get(`/events/${this.$route.params.id}`)

        const participants = this.axios.get(
            `/events/${this.$route.params.id}/participants`
        )

        const appQuestions = this.axios.get(
            `/events/${this.$route.params.id}/appQuestions`
        )

        const meetings = await this.axios.get(
            `/events/${this.$route.params.id}/meetings`
        )

        const chatMessages = await this.axios.get(
            `/events/${this.$route.params.id}/streamChatMessages`
        )

        const submittedQuestions = await this.axios.get(
            `/events/${this.$route.params.id}/submittedQuestions`
        )

        const polls = await this.axios.get(
            `/events/${this.$route.params.id}/polls`
        )

        const answers = await this.axios.get(
            `/events/${this.$route.params.id}/answers`
        )

        try {
            let response = await Promise.all([
                event,
                participants,
                appQuestions,
                meetings,
                chatMessages,
                submittedQuestions,
                polls,
                answers
            ])

            this.event = response[0].data.event
            this.currentActivity = this.activities[0]
            this.participants = response[1].data.participants
            console.log("participants");
            console.log(this.participants);

            this.appQuestions = response[2].data.appQuestions
            console.log("appQuestions");
            console.log(this.appQuestions);

            this.meetings = response[3].data.meetings
            this.chatMessages = response[4].data.chat_messages
            this.submittedQuestions = response[5].data.submitted_questions
            this.polls = response[6].data.polls
            this.answers = response[7].data.answers

            for (let appQuestion of this.appQuestions) {
                appQuestion.activityName = appQuestion.activity.name
            }

            let i = 0
            for (let question of this.answers) {
                this.optionsReferer[i] = {
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: '100%'
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    ],
                    title: {
                        text: question.question
                    },
                    labels: []
                }

                this.seriesReferer[i] = []
                for (let alternative of question.alternatives) {
                    this.optionsReferer[i].labels.push(alternative.alternative)
                    this.seriesReferer[i].push(
                        question.answers[alternative.id]
                            ? question.answers[alternative.id]
                            : 0
                    )
                }
                i++
            }
        } catch (e) {
            console.log(e)
            this.$snotify.error(this.$t('general_error'))
        }

        try {
            let response = await this.axios.get(
                `/profiles/${this.event.profile_id}/database`
            )
            this.database = response.data.database
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
