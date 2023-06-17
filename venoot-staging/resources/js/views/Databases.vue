<template>
    <div class="databases">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-toolbar text color="primary">
                            <v-toolbar-title class="white--text title">
                                {{ $t('link.databases').toUpperCase() }}
                            </v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                            <v-dialog v-model="dialog" max-width="70%">
                                <template v-slot:activator="{ on }">
                                    <v-btn color="success" dark class="mb-2" v-on="on">{{ $t('form.new') }}</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">
                                            {{ $t('form.new').capitalize() + ' ' + $t('database.title') }}
                                        </span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form ref="form" data-vv-scope="databaseForm" onsubmit="return false;">
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12>
                                                        <v-text-field
                                                            v-validate="'required|max:60'"
                                                            :data-vv-as="$t('form.name')"
                                                            data-vv-name="databaseForm.name"
                                                            :error-messages="errors.collect('databaseForm.name')"
                                                            :counter="60"
                                                            required
                                                            prepend-icon="work"
                                                            v-model="database.name"
                                                            :label="$t('form.name').capitalize()"
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <h4>
                                                            {{ $t('database.fields').capitalize() }}
                                                        </h4>
                                                        <v-btn fab dark small color="success" @click="addField">
                                                            <v-icon dark>add</v-icon>
                                                        </v-btn>
                                                    </v-flex>
                                                    <v-flex xs12 v-for="(field, index) in database.fields" :key="index">
                                                        <v-layout row wrap>
                                                            <v-flex xs2>
                                                                <v-text-field
                                                                    :disabled="disableField(field.key)"
                                                                    v-validate="'required|max:200'"
                                                                    :data-vv-as="$t('form.key')"
                                                                    :data-vv-name="`databaseForm.key${index}`"
                                                                    :error-messages="
                                                                        errors.collect(`databaseForm.key${index}`)
                                                                    "
                                                                    :counter="200"
                                                                    required
                                                                    prepend-icon="short_text"
                                                                    v-model="field.key"
                                                                    :label="$t('form.key').capitalize()"
                                                                    type="text"
                                                                ></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs3>
                                                                <v-text-field
                                                                    v-validate="'required|max:500'"
                                                                    :data-vv-as="$t('form.field')"
                                                                    :data-vv-name="`databaseForm.field${index}`"
                                                                    :error-messages="
                                                                        errors.collect(`databaseForm.field${index}`)
                                                                    "
                                                                    :counter="500"
                                                                    required
                                                                    prepend-icon="short_text"
                                                                    v-model="field.name"
                                                                    :label="$t('form.field').capitalize()"
                                                                    type="text"
                                                                ></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs2>
                                                                <v-select
                                                                    :disabled="disableField(field.key)"
                                                                    v-model="field.type"
                                                                    :label="$t('form.type').capitalize()"
                                                                    v-validate="'required'"
                                                                    :data-vv-name="`databaseForm.type${index}`"
                                                                    :error-messages="
                                                                        errors.collect(`databaseForm.type${index}`)
                                                                    "
                                                                    :items="databaseTypes"
                                                                    outline
                                                                    required
                                                                ></v-select>
                                                            </v-flex>
                                                            <v-flex xs2>
                                                                <v-checkbox
                                                                    :disabled="disableField(field.key)"
                                                                    v-validate="'required'"
                                                                    :data-vv-name="`databaseForm.required${index}`"
                                                                    :error-messages="
                                                                        errors.collect(`databaseForm.required${index}`)
                                                                    "
                                                                    required
                                                                    v-model="field.required"
                                                                    :label="$t('form.required').capitalize()"
                                                                    color="success"
                                                                    @change="
                                                                        field.required ? (field.in_form = true) : null
                                                                    "
                                                                ></v-checkbox>
                                                            </v-flex>
                                                            <v-flex xs2>
                                                                <v-checkbox
                                                                    :disabled="disableField(field.key)"
                                                                    v-validate="'required'"
                                                                    :data-vv-name="`databaseForm.in_form${index}`"
                                                                    :error-messages="
                                                                        errors.collect(`databaseForm.in_form${index}`)
                                                                    "
                                                                    required
                                                                    v-model="field.in_form"
                                                                    :label="$t('form.in_form').capitalize()"
                                                                    color="success"
                                                                ></v-checkbox>
                                                            </v-flex>
                                                            <v-flex xs1>
                                                                <v-btn
                                                                    v-if="field.type === 'choice'"
                                                                    small
                                                                    fab
                                                                    color="success"
                                                                    dark
                                                                    class="mt-2 mr-2"
                                                                    @click="addChoice(field)"
                                                                >
                                                                    <v-icon dark>mdi-playlist-plus</v-icon>
                                                                </v-btn>
                                                                <v-btn
                                                                    v-if="index > 0 && !disableField(field.key)"
                                                                    small
                                                                    fab
                                                                    color="error"
                                                                    dark
                                                                    class="mt-2"
                                                                    @click="database.fields.splice(index, 1)"
                                                                >
                                                                    <v-icon dark>remove</v-icon>
                                                                </v-btn>
                                                            </v-flex>
                                                            <template v-for="(choice, choiceIndex) in field.choices">
                                                                <v-flex xs8 offset-xs2 :key="index + '-' + choiceIndex">
                                                                    <v-text-field
                                                                        v-validate="'required'"
                                                                        :data-vv-as="$t('form.choice')"
                                                                        :data-vv-name="
                                                                            `databaseForm.field${index +
                                                                                '-' +
                                                                                choiceIndex}`
                                                                        "
                                                                        :error-messages="
                                                                            errors.collect(
                                                                                `databaseForm.field${index +
                                                                                    '-' +
                                                                                    choiceIndex}`
                                                                            )
                                                                        "
                                                                        required
                                                                        prepend-icon="short_text"
                                                                        v-model="
                                                                            field.choices[field.choices.indexOf(choice)]
                                                                        "
                                                                        :label="$t('form.choice').capitalize()"
                                                                        type="text"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs1 :key="index + '-' + choiceIndex + '-b'">
                                                                    <v-btn
                                                                        small
                                                                        fab
                                                                        color="error"
                                                                        dark
                                                                        class="mt-2"
                                                                        @click="field.choices.splice(index, 1)"
                                                                    >
                                                                        <v-icon dark>remove</v-icon>
                                                                    </v-btn>
                                                                </v-flex>
                                                            </template>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="close">{{ $t('cancel') }}</v-btn>
                                        <v-btn color="blue darken-1" text @click="update">{{ $t('accept') }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-btn color="success" class="mb-2 ml-1" @click="openAdvanced">NUEVA AVANZADA</v-btn>
                            <v-dialog v-model="manualAddDialog" max-width="50%">
                                <v-card>
                                    <v-card-title class="headline">
                                        {{
                                            (isEmpty(participant.data)
                                                ? $t('add').capitalize()
                                                : $t('edit').capitalize()) +
                                                ' ' +
                                                $t('participant.title')
                                        }}
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form :ref="`manualAddForm`" onsubmit="return false;">
                                            <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12>
                                                        <template v-for="(field, index) in database.fields">
                                                            <v-text-field
                                                                v-if="field.name && (field.type === 'string' || field.type === 'pdf'|| field.type === 'image')"
                                                                :key="index"
                                                                v-validate="{
                                                                    required: field.required,
                                                                }"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :required="field.required"
                                                                prepend-icon="short_text"
                                                                v-model="participant.data[field.key]"
                                                                :label="field.name"
                                                                type="text"
                                                            ></v-text-field>
                                                            <v-text-field
                                                                v-if="field.name && field.type === 'email'"
                                                                :key="index"
                                                                v-validate="{
                                                                    required: field.required,
                                                                    email: true,
                                                                }"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :required="field.required"
                                                                prepend-icon="short_text"
                                                                v-model="participant.data[field.key]"
                                                                :label="field.name"
                                                                type="text"
                                                            ></v-text-field>
                                                            <v-text-field
                                                                v-if="field.name && field.type === 'integer'"
                                                                :key="index"
                                                                v-validate="{
                                                                    required: field.required,
                                                                    numeric: true,
                                                                }"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :required="field.required"
                                                                prepend-icon="dialpad"
                                                                v-model="participant.data[field.key]"
                                                                :label="field.name"
                                                                type="number"
                                                            ></v-text-field>
                                                            <v-text-field
                                                                v-if="field.name && field.type === 'float'"
                                                                :key="index"
                                                                v-validate="{
                                                                    required: field.required,
                                                                    decimal: 4,
                                                                }"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :required="field.required"
                                                                prepend-icon="dialpad"
                                                                v-model="participant.data[field.key]"
                                                                :label="field.name"
                                                                type="text"
                                                            ></v-text-field>
                                                            <v-checkbox
                                                                v-if="field.name && field.type === 'boolean'"
                                                                :key="index"
                                                                v-validate="'required'"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :required="field.required"
                                                                v-model="participant.data[field.key]"
                                                                :label="field.name"
                                                                color="success"
                                                            ></v-checkbox>
                                                            <v-menu
                                                                :key="index"
                                                                v-if="field.name && field.type === 'date'"
                                                                v-model="menu[field.id]"
                                                                :close-on-content-click="true"
                                                                :nudge-right="40"
                                                                lazy
                                                                transition="scale-transition"
                                                                offset-y
                                                                full-width
                                                                min-width="290px"
                                                            >
                                                                <template
                                                                    v-slot:activator="{
                                                                        on,
                                                                    }"
                                                                >
                                                                    <v-text-field
                                                                        v-model="participant.data[field.name]"
                                                                        :label="field.name"
                                                                        v-validate="{
                                                                            required: field.required,
                                                                            date_format: true,
                                                                        }"
                                                                        :data-vv-as="field.name.toLowerCase()"
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
                                                                    v-model="participant.data[field.key]"
                                                                    no-title
                                                                    scrollable
                                                                    :locale="$i18n.locale"
                                                                    color="success"
                                                                ></v-date-picker>
                                                            </v-menu>

                                                            <v-datetime-picker
                                                                v-if="field.name && field.type === 'datetime'"
                                                                v-validate="{
                                                                    required: field.required,
                                                                    date_format: true,
                                                                }"
                                                                :data-vv-as="field.name.toLowerCase()"
                                                                :data-vv-name="`manualAddForm.${field.key}${index}`"
                                                                :error-messages="
                                                                    errors.collect(`manualAddForm.${field.key}${index}`)
                                                                "
                                                                :key="index"
                                                                v-model="participant.data[field.key]"
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
                                        <v-btn color="warning" @click="manualAddDialog = false">{{
                                            $t('cancel')
                                        }}</v-btn>
                                        <v-btn color="error" @click="saveManualParticipant">{{ $t('accept') }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <!-- Participants List -->
                            <v-dialog v-model="participantListDialog" max-width="95%">
                                <v-card>
                                    <v-card-title class="headline">
                                        {{
                                            $t('participant.list').capitalize() + ': ' + (database.name || profile.name)
                                        }}
                                        <v-spacer></v-spacer>
                                        <v-btn fab small color="success" :loading="loading" @click="exportDatabase">
                                            <v-icon>mdi-file-excel</v-icon>
                                        </v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-layout row wrap>
                                            <v-row justify="end">
                                                <v-col cols="6">
                                                    <v-text-field
                                                        v-model="tempSearchParticipants"
                                                        :placeholder="$t('search')"
                                                        @keydown.enter="searchParticipants = tempSearchParticipants"
                                                        @blur="searchParticipants = tempSearchParticipants"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-data-table
                                                        :headers="participantsHeaders"
                                                        :items="participantsData"
                                                        :loading="loading"
                                                        class="elevation-1"
                                                        :search="searchParticipants"
                                                    >
                                                        <template v-slot:item="row">
                                                            <tr>
                                                                <td
                                                                    class="text-start"
                                                                    v-for="(header, index) of row.headers"
                                                                    :key="index"
                                                                >
                                                                    <template
                                                                        v-if="
                                                                            header.type === 'image' &&
                                                                                row.item[header.value]
                                                                        "
                                                                    >
                                                                        <v-btn
                                                                            icon
                                                                            :href="`${BASE_URL}/public/images/${row.item[header.value]}`"
                                                                            target="_blank"
                                                                        >
                                                                            <v-icon>mdi-image</v-icon>
                                                                        </v-btn>
                                                                    </template>
                                                                    <template
                                                                        v-else-if="
                                                                            header.type === 'pdf' &&
                                                                                row.item[header.value]
                                                                        "
                                                                    >
                                                                         <v-btn
                                                                            icon
                                                                            :href="`${BASE_URL}/public/images/${row.item[header.value]}`"
                                                                            target="_blank"
                                                                        >
                                                                            <v-icon>mdi-adobe-acrobat</v-icon>
                                                                        </v-btn>
                                                                    </template>

                                                                    <template v-else-if="header.value === 'actions'">
                                                                        <td class="layout px-0">
                                                                            <v-tooltip bottom>
                                                                                <template #activator="{ on }">
                                                                                    <v-icon
                                                                                        v-on="on"
                                                                                        class="mr-2"
                                                                                        @click="
                                                                                            showParticipantBoard(
                                                                                                row.item
                                                                                            )
                                                                                        "
                                                                                        >mdi-eye</v-icon
                                                                                    >
                                                                                </template>
                                                                                <span>
                                                                                    {{ $t('show').capitalize() }}
                                                                                </span>
                                                                            </v-tooltip>
                                                                            <v-tooltip bottom>
                                                                                <template #activator="{ on }">
                                                                                    <v-icon
                                                                                        :height="96"
                                                                                        :width="24"
                                                                                        v-on="on"
                                                                                        class="mr-2"
                                                                                        @click="
                                                                                            showManualAddForm(
                                                                                                database,
                                                                                                row.item
                                                                                            )
                                                                                        "
                                                                                        >edit</v-icon
                                                                                    >
                                                                                </template>
                                                                                <span>
                                                                                    {{ $t('edit') }}
                                                                                </span>
                                                                            </v-tooltip>

                                                                            <v-tooltip bottom>
                                                                                <template #activator="{ on }">
                                                                                    <v-icon
                                                                                        :height="96"
                                                                                        :width="24"
                                                                                        v-on="on"
                                                                                        class="mr-2"
                                                                                        @click="
                                                                                            openDeleteParticipantDialog(
                                                                                                row.item
                                                                                            )
                                                                                        "
                                                                                        >delete</v-icon
                                                                                    >
                                                                                </template>
                                                                                <span>
                                                                                    {{ $t('delete').capitalize() }}
                                                                                </span>
                                                                            </v-tooltip>
                                                                        </td>
                                                                    </template>
                                                                    <template
                                                                        v-else-if="header.value === 'allow_mailing'"
                                                                    >
                                                                        <span
                                                                            class="error--text"
                                                                            v-if="row.item['blocked']"
                                                                        >
                                                                            {{ $t('blocked').capitalize() }}
                                                                        </span>
                                                                        <span
                                                                            class="error--text"
                                                                            v-else-if="!row.item['allow_mailing']"
                                                                        >
                                                                            {{ $t('suspended').capitalize() }}
                                                                        </span>
                                                                        <span
                                                                            class="success--text"
                                                                            v-else-if="row.item['verified']"
                                                                        >
                                                                            {{ $t('verified').capitalize() }}
                                                                        </span>
                                                                        <span class="orange--text" v-else>
                                                                            {{ $t('not_verified').capitalize() }}
                                                                        </span>
                                                                    </template>
                                                                    <template v-else>
                                                                        {{ row.item[header.value] }}
                                                                    </template>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </v-data-table>
                                                </v-col>
                                            </v-row>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-dialog>
                            <v-dialog v-model="deleteParticipantDialog" max-width="15%">
                                <v-card>
                                    <v-card-title class="headline">
                                        {{ $t('delete').capitalize() + ' ' + $t('participant.title') + '?' }}
                                    </v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="warning" @click="deleteParticipantDialog = false">{{
                                            $t('cancel')
                                        }}</v-btn>
                                        <v-btn color="error" @click="deleteParticipant(database, participant.id)">{{
                                            $t('accept')
                                        }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                        <v-data-table
                            :headers="databasesHeaders"
                            :items="databases"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template v-slot:item.action="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            :height="96"
                                            :width="24"
                                            v-on="on"
                                            class="mr-2"
                                            @click="showManualAddForm(item)"
                                            >person_add</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('tooltip.manual_add') }}</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="d-inline-flex mr-2" @click="showExcelUpload(item)"
                                            >mdi-file-excel</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('tooltip.excel_add') }}</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="d-inline-flex mr-2" @click="showParticipantList(item)"
                                            >list</v-icon
                                        >
                                    </template>
                                    <span>
                                        {{ $t('tooltip.view_participants') }}
                                    </span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="mr-2" @click="openVerifyDialog(item)"
                                            >mdi-cloud-question</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('mailings.verify_mail').capitalize() }}</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="d-inline-flex mr-2" @click="showProfile(item)"
                                            >mdi-chart-pie</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('tooltip.profile') }}</span>
                                </v-tooltip>

                                 <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="d-inline-flex mr-2" @click="showExternalAccess(item)"
                                            >mdi-database-arrow-left</v-icon
                                        >
                                    </template>
                                    <span>{{ $t('database.externalAccess').capitalize() }}</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon v-on="on" class="mr-2" @click="editDatabase(item)">edit</v-icon>
                                    </template>
                                    <span>{{ $t('edit') }}</span>
                                </v-tooltip>

                                <v-dialog v-model="deleteDialog" persistent max-width="15%">
                                    <template #activator="{ on: dialog }">
                                        <v-tooltip bottom>
                                            <template #activator="{ on: tooltip }">
                                                <v-icon
                                                    :height="96"
                                                    :width="24"
                                                    v-on="{
                                                        ...tooltip,
                                                        ...dialog,
                                                    }"
                                                    class="mr-2"
                                                    >cancel</v-icon
                                                >
                                            </template>
                                            <span>
                                                {{ $t('cancel').capitalize() }}
                                            </span>
                                        </v-tooltip>
                                    </template>
                                    <v-card>
                                        <v-card-title class="headline">
                                            {{
                                                $t('delete').capitalize() +
                                                    ' ' +
                                                    $t('database.title') +
                                                    ': ' +
                                                    item.name +
                                                    '?'
                                            }}
                                        </v-card-title>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="warning" @click="deleteDialog = false">{{
                                                $t('cancel')
                                            }}</v-btn>
                                            <v-btn color="error" @click="deleteDatabase(item.id)">{{
                                                $t('accept')
                                            }}</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog v-model="showExcelUploadDialog" max-width="30%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('upload').capitalize() + ' Excel' }}
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="openWindow('/files/venoot.xlsx')">{{ $t('example') }}</v-btn>
                </v-card-title>
                <v-card-text>
                    <file-pond
                        name="database"
                        :server="serverOptionsDatabase"
                        :label-idle="$t('form.upload.idle')"
                        :files="excelFiles"
                        allow-multiple="false"
                        accepted-file-types="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        required
                    ></file-pond>
                </v-card-text>
                <v-card-actions></v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showProfileDialog" max-width="70%">
            <v-card>
                <v-card-title class="headline">
                    Profiles
                    <v-spacer></v-spacer>
                    <v-btn dark fab small color="success" :loading="loading" @click="addProfile">
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md v-if="currentDatabase">
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-expansion-panels multiple>
                                    <v-expansion-panel v-for="(profile, i) in currentDatabase.profiles" :key="i">
                                        <v-expansion-panel-header>
                                            {{ profile.name }}
                                        </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-container grid-list-md v-if="currentDatabase">
                                                <v-layout wrap>
                                                    <v-flex xs12>
                                                        <v-text-field
                                                            v-validate="'required'"
                                                            :data-vv-as="$t('form.name')"
                                                            data-vv-name="name"
                                                            :error-messages="errors.collect('name')"
                                                            required
                                                            prepend-icon="short_text"
                                                            v-model="profile.name"
                                                            :label="$t('form.name').capitalize()"
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs11>
                                                        <h3>
                                                            {{ $t('form.conditions') }}
                                                        </h3>
                                                    </v-flex>
                                                    <v-flex xs1>
                                                        <v-btn
                                                            fab
                                                            small
                                                            color="success"
                                                            :loading="loading"
                                                            @click="
                                                                profile.conditions.push({
                                                                    variable: 'name',
                                                                    operation: '=',
                                                                    parameter: 'param',
                                                                })
                                                            "
                                                        >
                                                            <v-icon>mdi-plus</v-icon>
                                                        </v-btn>
                                                    </v-flex>
                                                    <template v-for="(condition, j) in profile.conditions">
                                                        <v-flex xs12 :key="j + i * 1000">
                                                            <Condition
                                                                :campos="currentDatabase.fields"
                                                                :condition="condition"
                                                                @delete="profile.conditions.splice(j, 1)"
                                                            />
                                                        </v-flex>
                                                    </template>
                                                    <v-flex xs3 offset-xs9>
                                                        <v-spacer></v-spacer>
                                                        <v-btn
                                                            v-if="profile.id"
                                                            :loading="loading"
                                                            color="success"
                                                            @click="showProfileParticipants(profile)"
                                                        >
                                                            {{ 'participantes' }}
                                                        </v-btn>
                                                        <v-btn
                                                            :loading="loading"
                                                            color="success"
                                                            @click="updateProfile(profile)"
                                                        >
                                                            {{ 'Guardar' }}
                                                        </v-btn>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showParticipantDialog" scrollable max-width="1200px" transition="dialog-transition">
            <Participant :participant="shownParticipant" :loading="loading" @setLoading="setLoading" />
        </v-dialog>

        <!-- Reporte General de verificaion de mail -->
        <v-dialog v-model="reportVerifyParticipantDialog" max-width="700px" transition="dialog-transition">
            <v-card>
                <v-card-title>{{ $t('database.individualReportParticipant') }}</v-card-title>
                <v-card-text>
                    <v-alert type="info">{{ $t('database.individualAcceptParticipant') }}</v-alert>
                </v-card-text>
                <v-card-text v-if="currentDatabase">
                    {{ $t('database.individualVerificationsParticipant').capitalize() }}:
                    {{ currentDatabase.verifications }}
                </v-card-text>
                <v-card-text v-if="currentDatabase">
                    {{ $t('database.individualUsedVerificationsParticipant').capitalize() }}:
                    {{ currentDatabase.used_verifications }}
                </v-card-text>
                <v-card>
                    <v-card-actions>
                        <v-btn color="warning" @click="reportVerifyParticipantDialog = false">{{ $t('cancel') }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-card>
        </v-dialog>

        <!-- Configuracion Acceso Externo -->
        <v-dialog v-model="externalAccessDialog" max-width="700px" transition="dialog-transition">
            <v-card>
                <v-card-title>{{ $t('database.externalAccess').toUpperCase() }}</v-card-title>
                <v-card-text v-if="currentDatabase">
                    <v-row>
                        <v-col cols="12">
                            <v-switch
                                v-model="currentDatabase.external_access"
                                :label="$t('accept').capitalize() + ' ' + $t('database.externalAccess')"
                                @change="changeExternalAccess"
                                :loading="loadingAccess"
                            >
                            </v-switch>
                        </v-col>
                        <v-col cols="auto">
                            <span class="subtitle-2">
                                {{ $t('form.url').capitalize() }}:
                            </span>
                            <span id="access-link" v-if="currentDatabase.access_key">
                                {{ `${BASE_URL}/databases/${currentDatabase.id}/external?access_key=${currentDatabase.access_key}` }}
                            </span>
                            <template v-else>
                                {{ $t('no').capitalize() }}
                            </template>
                        </v-col>
                        <v-col cols="2">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon v-on="on" class="d-inline-flex mr-2" @click="updateExternalKey" :disabled="loadingKey"
                                        >mdi-key-arrow-right</v-icon
                                    >
                                </template>
                                <span>{{ $t('database.refreshKey').capitalize() }}</span>
                            </v-tooltip>
                            <v-tooltip bottom v-if="currentDatabase.external_access">
                                <template v-slot:activator="{ on }">
                                    <v-icon v-on="on" class="d-inline-flex mr-2" @click="copyAccesssLink" :disabled="loadingKey"
                                        >mdi-content-copy</v-icon
                                    >
                                </template>
                                <span>{{ $t('copy').capitalize() }}</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="advancedDialog" max-width="80%" persistent>
            <v-card>
                <v-card-title>NUEVA BASE AVANZADA</v-card-title>

                <v-card-text>
                    <v-stepper v-model="advancedStepper">
                        <v-stepper-header>
                            <v-stepper-step :complete="!!advancedSource" step="1">
                                Databases o Eventos?
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="advancedStepper > 2" step="2" >
                                Fuentes
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="advancedSource === 'databases'" step="3">
                                Condiciones
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step step="4">
                                Quienes
                            </v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-card>
                                    <v-card-text>
                                        <v-form>
                                            <v-radio-group v-model="advancedSource">
                                                <v-radio
                                                    label="Bases de Datos"
                                                    value="databases"
                                                ></v-radio>
                                                <v-radio
                                                    label="Eventos"
                                                    value="events"
                                                ></v-radio>
                                            </v-radio-group>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn @click="advancedDialog = false">CANCEL</v-btn>
                                        <v-btn @click="advancedSecondStep" color="success">SIGUIENTE</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card>
                                    <v-card-text>
                                        <template v-if="advancedSource === 'databases'">
                                            <v-data-table
                                                v-model="selected"
                                                :headers="databasesAdvancedHeaders"
                                                :items="databases"
                                                :items-per-page="20"
                                                :loading="stepLoading"
                                                show-select
                                            ></v-data-table>
                                        </template>
                                        <template v-if="advancedSource === 'events'">
                                            <v-data-table
                                                v-model="selected"
                                                :headers="eventsAdvancedHeaders"
                                                :items="events"
                                                :items-per-page="20"
                                                :loading="stepLoading"
                                                show-select
                                            ></v-data-table>
                                        </template>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn @click="advancedDialog = false">CANCEL</v-btn>
                                        <v-btn :disabled="selected.length === 0" @click="advancedThirdStep" color="success">SIGUIENTE</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card>
                                    <v-card-text>
                                        <v-row no-gutters>
                                            <v-col cols="12" class="subtitle-1">
                                                Confirmación
                                            </v-col>
                                            <v-col cols="12">
                                                <v-radio-group
                                                    v-model="advancedConfirmed"
                                                    row
                                                >
                                                    <v-radio
                                                        label="Cualquiera"
                                                        value="none"
                                                    ></v-radio>
                                                    <v-radio
                                                        label="No Confirmado"
                                                        value="false"
                                                    ></v-radio>
                                                    <v-radio
                                                        label="Confirmado"
                                                        value="true"
                                                    ></v-radio>
                                                    <v-radio
                                                        label="No Invitado"
                                                        value="uninvited"
                                                    ></v-radio>
                                                </v-radio-group>
                                            </v-col>

                                            <v-col cols="12" class="subtitle-1 mt-4">
                                                Registro
                                            </v-col>

                                            <v-col cols="12">
                                                <v-radio-group
                                                    :disabled="advancedConfirmed === 'false' || advancedConfirmed === 'uninvited'"
                                                    v-model="advancedRegistered"
                                                    row
                                                >
                                                    <v-radio
                                                        label="Cualquiera"
                                                        value="none"
                                                    ></v-radio>
                                                    <v-radio
                                                        label="No Registrado"
                                                        value="false"
                                                    ></v-radio>
                                                    <v-radio
                                                        label="Registrado"
                                                        value="true"
                                                    ></v-radio>
                                                </v-radio-group>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn @click="advancedDialog = false">CANCEL</v-btn>
                                        <v-btn @click="advancedFourthStep" color="success">SIGUIENTE</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="4">
                                <v-card>
                                    <v-card-text>
                                         <v-data-table
                                                v-model="selectedParticipants"
                                                :headers="advancedParticipantHeaders"
                                                :items="advancedParticipants"
                                                :items-per-page="20"
                                                :loading="stepLoading"
                                                show-select
                                            ></v-data-table>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-text-field
                                            v-model="advancedDBName"
                                            label="Nombre Base De Datos"
                                            class="mr-4"
                                            :disabled="stepLoading"
                                        ></v-text-field>
                                        <v-btn :loading ="stepLoading" @click="advancedDialog = false">CANCEL</v-btn>
                                        <v-btn :loading ="stepLoading" :disabled="selectedParticipants.length === 0 || advancedDBName.length < 3" @click="saveToDatabase" color="success">Guardar Como</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { FilePond, loadUrl } from '../app'
import { forEach, isEmpty } from 'lodash'
import Participant from '../components/Participant'

import Condition from '../components/Condition'

export default {
    components: {
        Condition,
        Participant,
    },
    data: function() {
        return {
            verifications: 0,
            searchParticipants: null,
            tempSearchParticipants: null,
            excelFiles: [],
            isEmpty: isEmpty,
            dialog: false,
            deleteDialog: false,
            manualAddDialog: false,
            participantListDialog: false,
            deleteParticipantDialog: false,
            showExcelUploadDialog: false,
            showProfileDialog: false,
            showParticipantDialog: false,
            reportVerifyParticipantDialog: false,

            advancedDialog: false,
            advancedStepper: 1,
            advancedSource: 'databases',
            advancedParticipants: [],
            advancedParticipantHeaders: [],
            advancedDBName: "",
            advancedConfirmed: "none",
            advancedRegistered: "none",
            stepLoading: false,
            selected: [],
            selectedParticipants: [],
            events: [],


            externalAccessDialog: false,
            menu: {},
            menuDateTime: {},
            participant: { data: {} },
            participants: [],
            shownParticipant: {},
            currentDatabase: null,
            database: {
                fields: [
                    {
                        key: 'name',
                        name: 'Nombre',
                        type: 'string',
                        required: true,
                        in_form: true,
                    },
                    {
                        key: 'lastname',
                        name: 'Apellido',
                        type: 'string',
                        required: true,
                        in_form: true,
                    },
                    {
                        key: 'email',
                        name: 'Correo',
                        type: 'email',
                        required: true,
                        in_form: true,
                    },
                ],
            },
            profile: {},
            databases: [],
            databasesHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },
                {
                    text: this.$t('form.created_at').capitalize(),
                    align: 'left',
                    value: 'created_at',
                },
                {
                    text: this.$t('form.updated_at').capitalize(),
                    align: 'left',
                    value: 'updated_at',
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    sortable: false,
                },
            ],

            databasesAdvancedHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },
                {
                    text: this.$t('form.created_at').capitalize(),
                    align: 'left',
                    value: 'created_at',
                },
                {
                    text: this.$t('form.updated_at').capitalize(),
                    align: 'left',
                    value: 'updated_at',
                },
            ],

            eventsAdvancedHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                },
                {
                    text: this.$t('form.start_date').capitalize(),
                    align: 'left',
                    value: 'start_date',
                },
            ],

            databaseTypes: [
                {
                    text: this.$t('database.types.string').capitalize(),
                    value: 'string',
                },
                {
                    text: this.$t('database.types.email').capitalize(),
                    value: 'email',
                },
                {
                    text: this.$t('database.types.number').capitalize(),
                    value: 'number',
                },
                {
                    text: this.$t('database.types.boolean').capitalize(),
                    value: 'boolean',
                },
                {
                    text: this.$t('database.types.date').capitalize(),
                    value: 'date',
                },
                {
                    text: this.$t('database.types.datetime').capitalize(),
                    value: 'datetime',
                },
                {
                    text: this.$t('database.types.image').capitalize(),
                    value: 'image',
                },
                {
                    text: this.$t('database.types.pdf').capitalize(),
                    value: 'pdf',
                },
                {
                    text: this.$t('database.types.choice').capitalize(),
                    value: 'choice',
                },
            ],
            serverOptionsDatabase: {
                process: {
                    url: `./api/databases/0/uploadDatabase`,
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.$snotify.success(this.$t('participant.excel.success'))
                        this.loading = true
                        this.database = this.currentDatabase
                        this.axios
                            .post(`/databases/${this.currentDatabase.id}/verify`)
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('database.update.success'))
                                this.$forceUpdate()
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('database.update.failure'))
                            })
                        this.reportVerifyParticipantDialog = true
                        this.showExcelUploadDialog = false
                        this.excelFiles = []
                    },
                    onerror: (response) => {
                        let responseJson = JSON.parse(response)
                        console.log(responseJson)
                        this.$snotify.error(
                            this.$t('participant.' + responseJson.error) +
                                ': ' +
                                Object.values(responseJson.keys).join(', '),
                            this.$t('participant.excel.failure')
                        )
                        this.showExcelUploadDialog = false
                        this.excelFiles = []
                    },
                },
                load: loadUrl,
            },
            loading: false,
            loadingAccess: false,
            loadingKey: false,
        }
    },
    computed: {
        participantsHeaders() {
            let headers = this.database.fields.map((field) => ({
                text: field.name,
                value: field.key,
                type: field.type,
            }))
            let statics = [
                {
                    text: this.$t('database.allow_mailing'),
                    value: 'allow_mailing',
                },
                { text: this.$t('form.created_at'), value: 'created_at' },
                { text: this.$t('form.updated_at'), value: 'updated_at' },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'actions',
                    sortable: false,
                },
            ]
            headers = headers.concat(statics)
            return headers
        },
        participantsData() {
            let participantsData = []
            let index = 0
            this.participants.forEach(function(participant) {
                if (participant.data) {
                    participantsData.push({
                        id: participant.id,
                        ...participant.data,
                        allow_mailing: participant.allow_mailing,
                        created_at: participant.created_at,
                        updated_at: participant.updated_at,
                        blocked: participant.blocked,
                        verified: participant.verified,
                        index,
                    })
                    index++
                }
            })
            return participantsData
        },
    },
    watch: {
        participantListDialog: function(val) {
            if (!this.participantListDialog) {
                this.searchParticipants = null
                this.tempSearchParticipants = null
                this.database = {
                    fields: [
                        {
                            key: 'name',
                            name: 'Nombre',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                        {
                            key: 'lastname',
                            name: 'Apellido',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                        {
                            key: 'email',
                            name: 'Correo',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                    ],
                }
            }
        },
        dialog: function(val) {
            if (!this.dialog) {
                this.database = {
                    fields: [
                        {
                            key: 'name',
                            name: 'Nombre',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                        {
                            key: 'lastname',
                            name: 'Apellido',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                        {
                            key: 'email',
                            name: 'Correo',
                            type: 'string',
                            required: true,
                            in_form: true,
                        },
                    ],
                }
            }
        },

        advancedConfirmed(val) {
            if (val === "false" || val === "uninvited") {
                this.advancedRegistered = "none"
            }
        }
    },
    methods: {
        openAdvanced() {
            this.advancedStepper = 1
            this.advancedSource = 'databases'
            this.stepLoading = true
            this.advancedDialog = true
            this.selected = []
        },

        async advancedSecondStep() {
            this.advancedStepper = 2
            if (this.advancedSource === 'events') {
                try {
                    if (this.events.length === 0 ) {
                        const response = await this.axios.get('/events')
                        this.events = response.data.events
                    }
                    this.stepLoading = false
                } catch (e) {
                    this.advancedDialog = false
                }
            } else if (this.advancedSource === 'databases') {
                this.stepLoading = false
            }
        },

        async advancedThirdStep() {
            if (this.advancedSource === 'databases') {
                this.advancedStepper = 4
                this.stepLoading = true

                const response = await this.axios.post('/databases/advanced' , { ids: this.selected.map(item => item.id) })
                this.advancedParticipantHeaders = response.data.headers
                this.advancedParticipants = response.data.participants
                this.stepLoading = false
            }

            if (this.advancedSource === 'events') {
                this.advancedStepper = 3
            }
        },

        async advancedFourthStep() {
            this.advancedStepper = 4
            this.stepLoading = true

            const response = await this.axios.post('/events/advanced' , { ids: this.selected.map(item => item.id), confirmed: this.advancedConfirmed, registered: this.advancedRegistered })
            this.advancedParticipantHeaders = response.data.headers
            this.advancedParticipants = response.data.participants
            this.stepLoading = false
        },

        saveToDatabase() {
            this.stepLoading = true
            this.axios
            .post('/databases', { name: this.advancedDBName, fields: this.advancedParticipantHeaders })
            .then((response) => {
                this.$snotify.success(this.$t('database.store.success'))
                this.databases = response.data.databases
                const databaseId = response.data.database_id

                this.axios.post(`databases/${databaseId}/participants/massStore`, { participants: this.selectedParticipants })
            })
            .catch((error) => {
                this.loading = false
                let errors = ''
                forEach(error.response.data.errors, (value, key) => {
                    forEach(value, (e, k) => {
                        errors += e
                    })
                })
                this.$snotify.error(errors, this.$t('database.store.failure'))
            }).finally(() => {
                this.advancedDialog = false
                this.stepLoading = false
            })
        },

        showExternalAccess(database) {
            this.currentDatabase = database
            this.externalAccessDialog = true
        },

        changeExternalAccess(value) {
            this.loadingAccess = true
            this.axios.post(`/databases/${this.currentDatabase.id}/externalAccess`, {
                access: value
            })
            .then((response) => {
                this.currentDatabase.external_access = response.data.external_access
                this.currentDatabase.access_key = response.data.access_key
            })
            .finally(() => {
                this.loadingAccess = false
            })
        },

        copyAccesssLink () {
            let node = document.getElementById('access-link')

            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(node);
            selection.removeAllRanges();
            selection.addRange(range);

            try {
                document.execCommand('copy');
                this.$snotify.success('Copiado')
            } catch (err) {
                this.$snotify.error('No se pudo copiar')
            }

            window.getSelection().removeAllRanges()
        },

        updateExternalKey(value) {
            this.loadingAccess = true
            this.loadingKey = true
            this.axios.patch(`/databases/${this.currentDatabase.id}/externalAccess`)
            .then((response) => {
                this.currentDatabase.access_key = response.data.access_key
            })
            .finally(() => {
                this.loadingAccess = false
                this.loadingKey = false
            })
        },

        openVerifyDialog(database) {
            this.currentDatabase = database
            this.verifyEmails()
        },

        setLoading(value) {
            this.loading = value
        },
        addChoice(field) {
            if (field.choices) {
                field.choices.push('')
            } else {
                field.choices = ['']
            }
            this.$forceUpdate()
        },
        openWindow: function(link) {
            window.open(link)
        },
        exportDatabase() {
            this.loading = true
            this.axios
                .get(`/databases/${this.currentDatabase.id}/export`, {
                    responseType: 'blob',
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'database.xlsx') //or any other extension
                    document.body.appendChild(link)
                    link.click()
                })
                .finally(() => {
                    this.loading = false
                })
        },
        showParticipantBoard(participant) {
            this.shownParticipant = participant
            this.showParticipantDialog = true
        },
        addProfile() {
            this.currentDatabase.profiles.push({ name: '', conditions: [] })
        },
        showProfileParticipants(profile) {
            this.profile = profile
            this.axios
                .get(`/profiles/${profile.id}/participants`)
                .then((response) => {
                    this.loading = false
                    this.participants = response.data.participants
                    this.participantListDialog = true
                })
                .catch((error) => {
                    this.loading = false
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('profile.participants.failure'))
                })
        },
        updateProfile(profile) {
            this.loading = true
            if (profile.id) {
                this.axios
                    .patch(`/databases/${this.currentDatabase.id}/profiles/${profile.id}`, { ...profile })
                    .then((response) => {
                        this.loading = false
                        this.$snotify.success(this.$t('profile.update.success'))
                        profile = response.data.profile
                        // this.showProfileDialog = false
                    })
                    .catch((error) => {
                        this.loading = false
                        let errors = ''
                        forEach(error.response.data.errors, (value, key) => {
                            forEach(value, (e, k) => {
                                errors += e
                            })
                        })
                        this.$snotify.error(errors, this.$t('profile.update.failure'))
                    })
                    .finally(() => {
                        this.loading = false
                    })
            } else {
                this.axios
                    .post(`/databases/${this.currentDatabase.id}/profiles`, {
                        ...profile,
                    })
                    .then((response) => {
                        this.loading = false
                        this.$snotify.success(this.$t('profile.update.success'))
                        this.currentDatabase.profiles.pop()
                        this.currentDatabase.profiles.push(response.data.profile)
                        console.log(this.currentDatabase.profiles)
                        // this.showProfileDialog = false
                    })
                    .catch((error) => {
                        this.loading = false
                        let errors = ''
                        forEach(error.response.data.errors, (value, key) => {
                            forEach(value, (e, k) => {
                                errors += e
                            })
                        })
                        this.$snotify.error(errors, this.$t('profile.update.failure'))
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        },
        showProfile(item) {
            this.currentDatabase = item
            this.showProfileDialog = true
        },
        showExcelUpload(item) {
            this.currentDatabase = item
            ;(this.serverOptionsDatabase.process.url = `./api/databases/${item.id}/uploadDatabase`),
                (this.showExcelUploadDialog = true)
        },
        disableField(fieldName) {
            return ['name', 'lastname', 'email'].includes(fieldName)
        },
        openDeleteParticipantDialog(participant) {
            this.participant = this.participants[participant.index]
            this.deleteParticipantDialog = true
        },
        deleteParticipant(database, id) {
            this.loading = true
            this.axios
                .delete(`/databases/${database.id}/participants/${id}`)
                .then((response) => {
                    this.participants = response.data.participants
                    this.$snotify.success(this.$t('participant.delete.success'))
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('participant.delete.failure'))
                })
                .finally(() => {
                    this.deleteParticipantDialog = false
                    this.loading = false
                })
        },
        showParticipantList(database) {
            this.database = database
            this.currentDatabase = database
            this.loading = true
            this.axios
                .get(`/databases/${this.database.id}/participants`)
                .then((response) => {
                    this.participants = response.data.participants
                    // this.verifyEmails()
                    this.participantListDialog = true
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('participant.index.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        showManualAddForm(database, participant) {
            if (!participant) {
                participant = { data: {} }
            } else {
                participant = this.participants[participant.index]
            }
            this.database = database
            this.currentDatabase = database
            this.participant = Object.assign({}, participant)
            this.participant.data = Object.assign({}, participant.data)
            this.manualAddDialog = true
        },
        saveManualParticipant() {
            this.$validator.validateAll('manualAddForm').then((valid) => {
                if (valid) {
                    this.loading = true
                    if (this.participant.id) {
                        this.axios
                            .patch(`/databases/${this.database.id}/participants/${this.participant.id}`, {
                                data: this.participant.data,
                            })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('participant.update.success'))
                                this.participants = response.data.participants
                                // this.verifyEmails()
                                this.manualAddDialog = false
                                this.participant = { data: {} }
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('participant.update.failure'))
                            })
                    } else {
                        this.axios
                            .post(`/databases/${this.database.id}/participants`, { data: this.participant.data })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('participant.store.success'))
                                this.participants = response.data.participants
                                // this.verifyEmails()
                                this.manualAddDialog = false
                                this.participant = { data: {} }
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('database.store.failure'))
                            })
                    }
                }
            })
        },
        addField() {
            this.database.fields.push({
                type: 'string',
                required: false,
                in_form: false,
            })
        },
        deleteDatabase(id) {
            this.loading = true
            this.axios
                .delete(`/databases/${id}`)
                .then((response) => {
                    this.$snotify.success(this.$t('database.delete.success'))
                    this.databases = response.data.databases
                    this.close()
                })
                .catch((error) => {
                    this.$snotify.error(this.$t('database.delete.failure'))
                })
                .finally(() => {
                    this.loading = false
                    this.deleteDialog = false
                })
        },
        editDatabase(database) {
            this.database = Object.assign({}, database)
            this.dialog = true
        },
        update() {
            this.$validator.validateAll('databaseForm').then((valid) => {
                if (valid) {
                    this.loading = true
                    if (this.database.id) {
                        this.axios
                            .patch(`/databases/${this.database.id}`, {
                                ...this.database,
                            })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('database.update.success'))
                                this.databases = response.data.databases
                                this.close()
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('database.update.failure'))
                            })
                    } else {
                        this.axios
                            .post('/databases', { ...this.database })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('database.store.success'))
                                this.databases = response.data.databases
                                this.close()
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('database.store.failure'))
                            })
                    }
                }
            })
        },
        verifyEmails() {
            console.log('Ingresando a verifyEmail............')
            this.loading = true
            this.axios
                .post(`/databases/${this.currentDatabase.id}/verify`)
                .then((response) => {
                    this.loading = false
                    // this.databases = response.data.databases
                    this.$forceUpdate()
                })
                .catch((error) => {
                    this.loading = false
                    let errors = ''
                    this.$forceUpdate()
                })
        },
        updateVerifications() {
            this.loading = true
            this.axios
                .patch(`/databases/${this.database.id}`, {
                    ...this.database,
                    verifications: this.database.verifications + this.verifications,
                })
                .then((response) => {
                    this.loading = false
                    this.$snotify.success(this.$t('database.update.success'))
                    this.databases = response.data.databases
                    this.$forceUpdate()
                })
                .catch((error) => {
                    this.loading = false
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('database.update.failure'))
                })
        },
        close() {
            this.dialog = false
        },
    },
    mounted() {
        this.loading = true
        this.axios
            .get('/databases/keys')
            .then((response) => {
                this.databases = response.data.databases
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
