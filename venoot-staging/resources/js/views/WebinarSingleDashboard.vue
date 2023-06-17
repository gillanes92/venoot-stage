<template>
    <div class="webinar-single-dashboard">
        <v-overlay :value="showOverlay">
            <v-card color="white" min-width="150" min-height="150" class="d-flex flex-column justify-center align-center">
                <v-progress-circular :size="40" :width="5" indeterminate color="primary"></v-progress-circular>
                <div class="black--text mt-2">{{ $t('loading').capitalize() }}</div>
            </v-card>
        </v-overlay>
        <v-content>
            <v-container fluid fill-height grid-list-md>
                <v-layout wrap>
                    <!-- TITLE -->

                    <v-flex align-self-start>
                        <span class="primary white--text subtitle pa-1 mr-2">webinar |</span>
                        <span class="headline" v-if="event">
                            {{ event.name.toUpperCase() }}
                        </span>
                    </v-flex>

                    <!-- NEW ESTIMATE BUTTON -->

                    <v-flex shrink mb-1>
                        <!-- <v-btn color="success" @click="openDialog">
                            {{ $t('estimate.extra') + ' ' + $t('estimate.title') }}
                        </v-btn> -->
                    </v-flex>

                    <!-- DOWNLOAD REPORTS -->

                    <v-flex xs12 class="d-flex align-center">
                        <!-- <span class="subtitle-1 mr-2">
                            {{ $t('event.downloadReport') }}
                        </span>
                        <v-btn dark fab small class="mb-2" color="green" @click="openExportDialog" :loading="exporting">
                            <v-icon>mdi-file-excel</v-icon>

                        </v-btn>
                        <v-btn v-if="event.secure_video" dark fab small class="mb-2" color="green"
                            @click="openExportVideoParticipants" :loading="exporting">
                            <v-icon>mdi-timetable</v-icon>

                        </v-btn>

                        <v-spacer></v-spacer>

                        <div class="align-self-end subtitle-1 mr-6" v-if="event.secure_video">
                            <span class="mr-2">Active In Chat:</span>
                            {{ activeInChat }}
                            <v-btn dark fab small class="ml-2 mb-2" color="green" @click="refreshChatParticipants"
                                :loading="loadingChatParticipants">
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                        </div> -->
                    </v-flex>

                    <!-- TOP SUMMARY -->
                    <v-flex xs12 v-if="event.id && participants && polls">
                        <v-row>
                            <!-- <Summary :event="event" :participants="participants" :polls="polls" /> -->
                            <v-col cols="3">
                                <v-card>
                                    <v-card-subtitle>Acceso por Dispositivo</v-card-subtitle>
                                    <v-card-text>
                                        <apexchart ref="chartDevices" width="100%" height="250" type="donut"
                                            :options="optionsDevices" :series="seriesDevices" />
                                    </v-card-text>
                                </v-card>
                            </v-col>

                            <v-col cols="4">
                                <v-card>
                                    <v-card-subtitle>Asistentes</v-card-subtitle>
                                    <v-card-text>
                                        <apexchart ref="chartConfirmed" width="100%" height="250" type="bar"
                                            :options="optionsConfirmed" :series="seriesConfirmed" />
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-flex>
                    <v-flex xs12 v-else>
                        <v-skeleton-loader class="mx-auto" type="card"></v-skeleton-loader>
                    </v-flex>

                    <!-- ENVIOS Y CONFIRMACIONES -->
                    <v-flex xs12 v-if="event.estimate.mailings">
                        <v-card>
                            <v-card-title class="white--text primary">
                                {{ $t('event.mailingsConfirmations').toUpperCase() }}
                            </v-card-title>
                            <v-card-text class="mt-4">
                                <template v-if="event.estimate.confirmation_form || event.estimate.mailings">
                                    <v-row dense>
                                        <v-col cols="12" md="8">
                                            <v-card>
                                                <v-card-text>
                                                    <apexchart ref="chart" width="100%" height="400" type="area"
                                                        :options="options" :series="series" />
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                        <v-col cols="12" md="4">
                                            <v-card height="100%">
                                                <v-card-title class="justify-center">
                                                    {{ $t('event.mailingSent') }}
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-container fill-height>
                                                        <v-layout column>
                                                            <v-flex row>
                                                                <span>
                                                                    {{ $t('event.mailingQuantity') }}
                                                                </span>
                                                                <v-spacer></v-spacer>
                                                                <span>
                                                                    {{ sentTotal }}
                                                                    /
                                                                    {{ event.estimate.mailings_quantity }}
                                                                </span>
                                                            </v-flex>
                                                            <v-flex>
                                                                <v-progress-linear v-model="mailingsPercentage" height="25"
                                                                    color="light-blue">
                                                                    <strong> {{ mailingsPercentage }}% </strong>
                                                                </v-progress-linear>
                                                            </v-flex>
                                                            <v-flex row>
                                                                <span>
                                                                    {{ $t('event.seenQuantity') }}
                                                                </span>
                                                                <v-spacer></v-spacer>
                                                                <span>
                                                                    {{ seenTotal }}
                                                                    /
                                                                    {{ sentTotal }}
                                                                </span>
                                                            </v-flex>
                                                            <v-flex>
                                                                <v-progress-linear v-model="seenPercentage" height="25"
                                                                    color="green darken-2">
                                                                    <strong class="white--text">
                                                                        {{ seenPercentage }}%
                                                                    </strong>
                                                                </v-progress-linear>
                                                            </v-flex>

                                                            <v-flex row v-if="event.estimate.confirmation_form">
                                                                <span>
                                                                    {{ $t('event.rejectedQuantity') }}
                                                                </span>
                                                                <v-spacer></v-spacer>
                                                                <span>
                                                                    {{ rejected }} /
                                                                    {{ participants.length }}
                                                                </span>
                                                            </v-flex>
                                                            <v-flex v-if="event.estimate.confirmation_form">
                                                                <v-progress-linear v-model="rejectedPercentage" height="25"
                                                                    color="red darken-2">
                                                                    <strong class="white--text">
                                                                        {{ rejectedPercentage }}%
                                                                    </strong>
                                                                </v-progress-linear>
                                                            </v-flex>

                                                            <v-flex row>
                                                                <span>
                                                                    {{ $t('event.bouncedQuantity') }}
                                                                </span>
                                                                <v-spacer></v-spacer>
                                                                <span>
                                                                    {{ event.bounced_mail_count }}
                                                                    /
                                                                    {{ event.sent_mail_count }}
                                                                </span>
                                                            </v-flex>
                                                            <v-flex>
                                                                <v-progress-linear v-model="bouncedPercentage" height="25"
                                                                    color="red darken-2">
                                                                    <strong class="white--text">
                                                                        {{ bouncedPercentage }}%
                                                                    </strong>
                                                                </v-progress-linear>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </template>
                                <template v-else>
                                    <v-flex xs12>
                                        <v-card height="100%">
                                            <v-card-title class="justify-center">
                                                {{ $t('event.mailingSent') }}
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container fill-height>
                                                    <v-layout column>
                                                        <v-flex row>
                                                            <span>
                                                                {{ $t('event.mailingQuantity') }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <span>
                                                                {{ sentTotal }}
                                                                /
                                                                {{ event.estimate.mailings_quantity }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-progress-linear v-model="mailingsPercentage" height="25"
                                                                color="light-blue">
                                                                <strong> {{ mailingsPercentage }}% </strong>
                                                            </v-progress-linear>
                                                        </v-flex>
                                                        <v-flex row>
                                                            <span>
                                                                {{ $t('event.seenQuantity') }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <span>
                                                                {{ seenTotal }}
                                                                /
                                                                {{ sentTotal }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-progress-linear v-model="seenPercentage" height="25"
                                                                color="green darken-2">
                                                                <strong class="white--text">
                                                                    {{ seenPercentage }}%
                                                                </strong>
                                                            </v-progress-linear>
                                                        </v-flex>
                                                        <v-flex row v-if="event.estimate.confirmation_form">
                                                            <span>
                                                                {{ $t('event.rejectedQuantity') }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <span>
                                                                {{ rejected }} /
                                                                {{ participants.length }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex v-if="event.estimate.confirmation_form">
                                                            <v-progress-linear v-model="rejectedPercentage" height="25"
                                                                color="red darken-2">
                                                                <strong class="white--text">
                                                                    {{ rejectedPercentage }}%
                                                                </strong>
                                                            </v-progress-linear>
                                                        </v-flex>

                                                        <v-flex row>
                                                            <span>
                                                                {{ $t('event.bouncedQuantity') }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <span>
                                                                {{ event.bounced_mail_count }}
                                                                /
                                                                {{ event.sent_mail_count }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-progress-linear v-model="bouncedPercentage" height="25"
                                                                color="red darken-2">
                                                                <strong class="white--text">
                                                                    {{ bouncedPercentage }}%
                                                                </strong>
                                                            </v-progress-linear>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-flex>
                                </template>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <!-- LANDING -->
                    <v-flex xs12>
                        <v-card v-if="event.estimate.landing && event.estimate.landing.which > 0">
                            <v-card-title class="white--text primary">
                                {{ $t('landing.title').toUpperCase() }}
                            </v-card-title>

                            <v-card-text class="mt-4">
                                <v-card v-if="$auth.user().category !== 'webinar'">
                                    <v-card-subtitle>
                                        {{ $t('event.shared_title') }}
                                    </v-card-subtitle>
                                    <v-card-text>
                                        <v-row>
                                            <!-- TWITTER -->
                                            <v-col cols="12" sm="6" md="4">
                                                <v-card>
                                                    <v-row no-gutters>
                                                        <v-col cols="auto">
                                                            <v-card class="d-flex justify-center" color="#00C0EF"
                                                                height="100" width="100">
                                                                <v-icon size="50" color="white">mdi-twitter</v-icon>
                                                            </v-card>
                                                        </v-col>
                                                        <v-col>
                                                            <v-list-item three-line>
                                                                <v-list-item-content>
                                                                    <v-list-item-title
                                                                        class="subtitle-1">TWITER</v-list-item-title>
                                                                    <v-list-item-subtitle class="title">
                                                                        <span class="font-bold">
                                                                            {{ event.twitter_shares }}
                                                                        </span>
                                                                        {{ $t('times') }}
                                                                    </v-list-item-subtitle>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </v-col>
                                                    </v-row>
                                                </v-card>
                                            </v-col>

                                            <!-- FACEBOOK -->
                                            <v-col cols="12" sm="6" md="4">
                                                <v-card>
                                                    <v-row no-gutters>
                                                        <v-col cols="auto">
                                                            <v-card class="d-flex justify-center" color="#0073B7"
                                                                height="100" width="100">
                                                                <v-icon size="50" color="white">mdi-facebook</v-icon>
                                                            </v-card>
                                                        </v-col>
                                                        <v-col>
                                                            <v-list-item three-line>
                                                                <v-list-item-content>
                                                                    <v-list-item-title
                                                                        class="subtitle-1">FACEBOOK</v-list-item-title>
                                                                    <v-list-item-subtitle class="title">
                                                                        <span class="font-bold">
                                                                            {{ event.facebook_shares }}
                                                                        </span>
                                                                        {{ $t('times') }}
                                                                    </v-list-item-subtitle>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </v-col>
                                                    </v-row>
                                                </v-card>
                                            </v-col>

                                            <!-- LINKEDIN -->
                                            <v-col cols="12" sm="6" md="4">
                                                <v-card>
                                                    <v-row no-gutters>
                                                        <v-col cols="auto">
                                                            <v-card class="d-flex justify-center" color="#DD4B39"
                                                                height="100" width="100">
                                                                <v-icon size="50" color="white">mdi-linkedin</v-icon>
                                                            </v-card>
                                                        </v-col>
                                                        <v-col class="text-left">
                                                            <v-list-item three-line>
                                                                <v-list-item-content>
                                                                    <v-list-item-title
                                                                        class="subtitle-1">LINKEDIN</v-list-item-title>
                                                                    <v-list-item-subtitle class="title">
                                                                        <span class="font-bold">
                                                                            {{ event.linkedin_shares }}
                                                                        </span>
                                                                        {{ $t('times') }}
                                                                    </v-list-item-subtitle>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </v-col>
                                                    </v-row>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </v-card>

                                <v-row>
                                    <!-- REFERERS CHART -->
                                    <v-col cols="12" md="6">
                                        <v-card>
                                            <v-card-subtitle>Referers</v-card-subtitle>
                                            <v-card-text>
                                                <apexchart ref="chartReferers" width="100%" height="400" type="donut"
                                                    :options="optionsReferer" :series="seriesReferer" />
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                    <!-- DEVICES CHART -->
                                    <v-col cols="12" md="6">
                                        <v-card>
                                            <v-card-subtitle>Devices</v-card-subtitle>
                                            <v-card-text>
                                                <apexchart ref="chartDevices" width="100%" height="400" type="donut"
                                                    :options="optionsDevices" :series="seriesDevices" />
                                            </v-card-text>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <!-- POLLS -->
                    <v-flex xs12 v-if="event.estimate.polls">
                        <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header class="white--text title primary">
                                    {{ $t('poll.title').toUpperCase() }}
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-data-table :headers="pollHeaders" :items="polls" :loading="loading"
                                        class="elevation-1 mt-4">
                                        <template v-slot:item.actions="{ item }">
                                            <v-btn icon @click="exportPollExcel(item)" :loading="exporting">
                                                <v-icon>mdi-file-excel</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-data-table>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-flex>

                    <!-- PARTICIPANTS TABLE -->
                    <v-flex
                        v-if="event.estimate.confirmation_form || event.estimate.ticket_sale || event.estimate.mailings">
                        <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header class="white--text title primary">
                                    {{ $t('event.participants').toUpperCase() }}
                                </v-expansion-panel-header>
                                <v-expansion-panel-content class="mt-4">
                                    <v-text-field v-model="tempSearchParticipants" :placeholder="$t('search').capitalize()"
                                        @focus.prevent @keydown.enter="searchParticipants = tempSearchParticipants"
                                        @blur="searchParticipants = tempSearchParticipants" solo></v-text-field>
                                    <v-data-table :headers="participantsHeaders" :items="participants" :loading="loading"
                                        class="elevation-1" :search="searchParticipants">
                                        <template v-slot:item.confirmed_status="{
                                            item,
                                        }">
                                            {{
                                                item.confirmed_status === null
                                                ? $t('pending').capitalize()
                                                : item.confirmed_status
                                                    ? $t('yes')
                                                    : $t('rejected').capitalize()
                                            }}
                                        </template>

                                        <template v-slot:item.status="{
                                                        item,
                                                    }">
                                            {{
                                                (item.bounced
                                                    ? $t('mailings.bounced')
                                                    : item.view_mail
                                                        ? $t('seen')
                                                        : $t('pending')
                                                ).capitalize()
                                            }}
                                        </template>
                                    </v-data-table>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-flex>

                    <!-- SECURE VIDEO VIEWS TABLE -->
                    <v-flex xs12 v-if="event.secure_video">
                        <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header class="white--text title primary">
                                    {{ $t('svv.title').toUpperCase() }}
                                </v-expansion-panel-header>
                                <v-expansion-panel-content class="mt-4">
                                    <v-text-field v-model="tempSearchSVV" :placeholder="$t('search').capitalize()"
                                        @focus.prevent @keydown.enter="searchSVV = tempSearchSVV"
                                        @blur="searchSVV = tempSearchSVV" solo></v-text-field>
                                    <v-data-table :headers="secureVideoViewsHeaders" :items="secureVideoViews"
                                        :loading="loading" class="elevation-1" :search="searchSVV"></v-data-table>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-dialog v-model="dialog" persistent max-width="80%">
            <v-card>
                <v-card-title>
                    <span class="headline">
                        {{ $t('estimate.extra').capitalize() + ' ' + $t('estimate.title') }}
                    </span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-switch :disabled="event.estimate.landing" v-model="estimate.landing"
                                    :label="$t('estimate.createLanding')"></v-switch>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-switch :disabled="event.estimate.confirmation_form" v-model="estimate.confirmation_form"
                                    :label="$t('estimate.confirmationForm')"></v-switch>
                            </v-flex>
                            <v-flex xs4>
                                <v-switch :disabled="event.estimate.mailings" v-model="estimate.mailings"
                                    :label="$t('estimate.mailings')"></v-switch>
                            </v-flex>
                            <v-flex xs2>
                                <v-text-field v-if="estimate.mailings" class="inputQuantity"
                                    v-model="estimate.mailings_quantity" hide-details single-line
                                    type="number"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-slider v-if="estimate.mailings" hint="Max: 500000" persistent-hint
                                    v-model="estimate.mailings_quantity" :max="500000" :min="0"></v-slider>
                            </v-flex>
                            <v-flex xs4>
                                <v-switch :disabled="event.estimate.polls" v-model="estimate.polls"
                                    :label="$t('estimate.polls')"></v-switch>
                            </v-flex>
                            <v-flex xs2>
                                <v-text-field v-if="estimate.polls" class="inputQuantity" v-model="estimate.polls_quantity"
                                    hide-details single-line type="number"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-slider v-if="estimate.polls" hint="Max: 100" persistent-hint
                                    v-model="estimate.polls_quantity" :max="100" :min="0"></v-slider>
                            </v-flex>
                            <v-flex xs4>
                                <v-switch :disabled="event.estimate.register_keys" v-model="estimate.register_keys"
                                    :label="$t('estimate.registerKeys')"></v-switch>
                            </v-flex>
                            <v-flex xs2>
                                <v-text-field v-if="estimate.register_keys" class="inputQuantity"
                                    v-model="estimate.register_keys_quantity" hide-details single-line
                                    type="number"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-slider v-if="estimate.register_keys" hint="Max: 50" persistent-hint
                                    v-model="estimate.register_keys_quantity" :max="50" :min="0"></v-slider>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-switch :disabled="event.estimate.ticket_sale" v-model="estimate.ticket_sale"
                                    :label="$t('estimate.ticketSale')"></v-switch>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-switch :disabled="event.estimate.free_tickets" v-model="estimate.free_tickets"
                                    :label="$t('estimate.freeTickets')"></v-switch>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog = false">
                        {{ $t('cancel') }}
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="saveExtraEstimate">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="landingDialog" persistent scrollable max-width="90%">
            <v-card>
                <v-card-title primary-title>Cotizaciones Extra</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-simple-table>
                                    <template v-slot:default>
                                        <tbody>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="title">$ CLP</td>
                                            </tr>
                                            <tr v-if="['full'].includes(category) && !originalEstimate.landing">
                                                <td class="title">Landing</td>
                                                <td>
                                                    <v-switch v-model="estimate.landing"></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.landing">
                                                        {{ 55000 | currency }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="['full', 'form'].includes(category) &&
                                                !originalEstimate.ticket_sale &&
                                                !originalEstimate.confirmation_form
                                                ">
                                                <td class="title">Confirmación de Asistencía</td>
                                                <td>
                                                    <v-switch :disabled="estimate.ticket_sale"
                                                        v-model="estimate.confirmation_form"></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.confirmation_form">
                                                        {{ 15000 | currency }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="['full', 'cart'].includes(category) &&
                                                !originalEstimate.confirmation_form &&
                                                !originalEstimate.ticket_sale
                                                ">
                                                <td class="title">Venta de eTickets</td>
                                                <td>
                                                    <v-switch :disabled="estimate.confirmation_form"
                                                        v-model="estimate.ticket_sale"></v-switch>
                                                </td>
                                                <td class="justify-center subtitle">Webpay 9% | Paypal 15%</td>
                                                <td class="subtitle"></td>
                                            </tr>
                                            <tr v-if="['full', 'emailing', 'form', 'poll', 'app', 'cart'].includes(
                                                category
                                            )
                                                ">
                                                <td class="title">Envios de eMails</td>
                                                <td>
                                                    <v-switch v-model="estimate.mailings"></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field v-if="estimate.mailings" class="inputQuantity"
                                                        v-model="estimate.mailings_quantity" hide-details single-line
                                                        type="number"></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.mailings">
                                                        {{ (estimate.mailings_quantity * 5) | currency }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="['full', 'poll', 'app'].includes(category)">
                                                <td class="title">Creación de Encuestas</td>
                                                <td>
                                                    <v-switch v-model="estimate.polls"></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field v-if="estimate.polls" class="inputQuantity"
                                                        v-model="estimate.polls_quantity" hide-details single-line
                                                        type="number"></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.polls">
                                                        {{ (estimate.polls_quantity * 10000) | currency }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="['full', 'cart'].includes(category)">
                                                <td class="title">Llaves de Acreditación QR</td>
                                                <td>
                                                    <v-switch v-model="estimate.register_keys"></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field v-if="estimate.register_keys" class="inputQuantity"
                                                        v-model="estimate.register_keys_quantity" hide-details single-line
                                                        type="number"></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.register_keys">
                                                        {{ (estimate.register_keys_quantity * 20000) | currency }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="['full', 'app'].includes(category) && !originalEstimate.app">
                                                <td class="title">Aplicación Telefono</td>
                                                <td>
                                                    <v-switch v-model="estimate.app"></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span v-if="estimate.app">Te contactaremos</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                                <v-expansion-panels>
                                    <v-expansion-panel>
                                        <v-expansion-panel-header> Otros Servicios </v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-simple-table>
                                                <template v-slot:default>
                                                    <tbody>
                                                        <tr v-if="[
                                                            'full',
                                                            'emailing',
                                                            'form',
                                                            'poll',
                                                            'app',
                                                            'cart',
                                                        ].includes(category)
                                                            ">
                                                            <td class="title title-columm" style="max-width: 200px">
                                                                Administración
                                                            </td>
                                                            <td>
                                                                <v-switch v-model="estimate.administration"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.administration">
                                                                    {{ 45000 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="[
                                                            'full',
                                                            'emailing',
                                                            'form',
                                                            'poll',
                                                            'app',
                                                            'cart',
                                                        ].includes(category)
                                                            ">
                                                            <td class="title">
                                                                DISEÑO GRAFICO ( PIEZAS GRAFICAS NECESARIAS PARA
                                                                TRABAJAR )
                                                            </td>
                                                            <td>
                                                                <v-switch v-model="estimate.graphical_design"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.graphical_design">
                                                                    {{ 55000 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'cart'].includes(category)">
                                                            <td class="title">
                                                                ACREDITACION ( POR PERSONA ACREDITADA )
                                                            </td>
                                                            <td>
                                                                <v-switch v-model="estimate.registering"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.registering">
                                                                    {{ 1000 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'cart'].includes(category)">
                                                            <td class="title">LANYAR SIN LOGO</td>
                                                            <td>
                                                                <v-switch v-model="estimate.lanyards"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.lanyards">
                                                                    {{ 800 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'cart'].includes(category)">
                                                            <td class="title">CREDENCIALES 4X0</td>
                                                            <td>
                                                                <v-switch v-model="estimate.credentials"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.credentials">
                                                                    {{ 800 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'cart'].includes(category)">
                                                            <td class="title">ACREDITADOR MEDIA JORNADA</td>
                                                            <td>
                                                                <v-switch v-model="estimate.collectors_half"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.collectors_half">
                                                                    {{ 25000 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'cart'].includes(category)">
                                                            <td class="title">ACREDITADOR JORNADA COMPLETA</td>
                                                            <td>
                                                                <v-switch v-model="estimate.collectors_full"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.collectors_full">
                                                                    {{ 50000 | currency }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="['full', 'form', 'poll', 'cart'].includes(category)">
                                                            <td class="title">DESARROLLO</td>
                                                            <td>
                                                                <v-switch v-model="estimate.development"></v-switch>
                                                            </td>
                                                            <td class="subtitle"></td>
                                                            <td class="subtitle">
                                                                <span v-if="estimate.development">
                                                                    Te contactaremos
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </template>
                                            </v-simple-table>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-expansion-panels>
                                <v-simple-table>
                                    <template v-slot:default>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="title text-right">Total Neto</td>
                                                <td class="subtitle">{{ netTotal | currency }}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue" class="white--text" :loading="loading" @click="saveExtraEstimate">Guardar
                        Cotizacion</v-btn>
                    <v-btn color="black" class="white--text" :loading="loading"
                        @click="landingDialog = false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="exportDialog" persistent scrollable max-width="600px">
            <v-card>
                <v-toolbar color="primary">
                    <v-toolbar-title class="white--text text-uppercase">Enviar Reporte</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-alert type="info">
                                    Se le enviara un enlace a su excel a la brevedad. Este proceso puede demorar varios
                                    minutos, por lo cual le pedimos esperar. Si se inicia otro requerimiento se perdera
                                    la prioridad del primero.
                                </v-alert>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-form v-model="isExportFormValid">
                                    <v-text-field label="Correo Electronico para Envio" v-model="exportMail" :rules="[
                                        (v) =>
                                            !v ||
                                            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                                            'Correo debe ser valido',
                                    ]"></v-text-field>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" class="white--text" :disabled="!isExportFormValid" :loading="loading"
                        @click="exportEventExcel">enviar</v-btn>
                    <v-btn color="grey" class="white--text" :loading="loading"
                        @click="exportDialog = false">cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="exportSuccessDialog" max-width="600px">
            <v-card>
                <v-toolbar color="primary">
                    <v-toolbar-title class="white--text text-uppercase">Reporte Excel requerido</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-alert type="success">
                                    Su reporte excel se ha requerido con exito. Enviaremos un enlace al correo ingresado
                                    a la brevedad. Le recordamos que este proceso puede tomar varios minutos, por lo
                                    cual le pedimos , por favor, esperar.
                                </v-alert>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import VueApexCharts from 'vue-apexcharts'

import Summary from '../components/dashboard/Summary'

export default {
    components: {
        apexchart: VueApexCharts,
        Summary,
    },

    data: function () {
        return {
            user: null,
            exporting: false,
            loading: false,
            loadingSeries: false,
            loadingPolls: false,
            loadingDatabases: false,
            loadingVideos: false,
            loadingChatParticipants: false,
            category: 'full',
            landingDialog: false,
            exportDialog: false,
            exportMail: null,
            isExportFormValid: false,
            exportSuccessDialog: false,
            searchParticipants: null,
            tempSearchParticipants: null,
            searchSVV: null,
            tempSearchSVV: null,
            loading: false,
            event: {
                name: '',
                estimate: {},
            },
            estimate: {},
            originalEstimate: {},
            participants: [],
            polls: [],
            database: null,
            dialog: false,
            rawSeries: [],
            series: [],
            seriesReferer: [],
            seriesDevices: [],
            secureVideoViews: [],
            activeInChat: 0,
            pollHeaders: [
                { text: this.$t('form.name').capitalize(), value: 'name' },
                { text: this.$t('poll.sent').capitalize(), value: 'sent' },
                {
                    text: this.$t('poll.sent_at').capitalize(),
                    value: 'sent_at',
                },
                {
                    text: this.$t('poll.incomplete').capitalize(),
                    value: 'incomplete',
                },
                {
                    text: this.$t('poll.complete').capitalize(),
                    value: 'complete',
                },
                { text: this.$t('actions').capitalize(), value: 'actions' },
            ],

            secureVideoViewsHeaders: [
                { text: this.$t('svv.name').capitalize(), value: 'name' },
                {
                    text: this.$t('svv.lastname').capitalize(),
                    value: 'lastname',
                },
                { text: this.$t('svv.email').capitalize(), value: 'email' },
                {
                    text: this.$t('svv.video_url').capitalize(),
                    value: 'video_url',
                },
                { text: this.$t('svv.seen_at').capitalize(), value: 'seen_at' },
                {
                    text: this.$t('svv.stopped_at').capitalize(),
                    value: 'stopped_at',
                },
            ],

            optionsReferer: {
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: '100%',
                            },
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                ],
                labels: [],
            },
            optionsDevices: {
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: '100%',
                            },
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                ],
                labels: [],
            },
            options: {
                stroke: {
                    curve: 'smooth',
                },
                dataLabels: {
                    enabled: false,
                },
                title: {
                    text: this.$t('event.graphs.mailingsConfirmations').capitalize(),
                    align: 'center',
                },
                colors: ['#388E3C', '#F44336', '#4267B2', '#DB3074', '#DD4B39', '#00C0EF'],
            },
        }
    },
    computed: {
        showOverlay() {
            return (
                this.loading || this.loadingSeries || this.loadingDatabases || this.loadingPolls || this.loadingVideos
            )
        },

        netTotal: function () {
            return (
                (this.estimate.landing ? 55000 : 0) +
                (this.estimate.confirmation_form ? 15000 : 0) +
                (this.estimate.mailings ? this.estimate.mailings_quantity * 5 : 0) +
                (this.estimate.polls ? this.estimate.polls_quantity * 10000 : 0) +
                (this.estimate.register_keys ? this.estimate.register_keys_quantity * 20000 : 0)
            )
        },

        maxY() {
            return this.participants.length * 2
        },
        participantsHeaders() {
            if (!this.database) return []

            let headers = this.database.fields.map((field) => ({
                text: field.name,
                value: field.key,
            }))

            let statics = []
            if (this.estimate.confirmation_form || this.estimate.ticket_sale) {
                statics = [
                    {
                        text: this.$t('participant.invitation_sent_at'),
                        value: 'invitation_sent_at',
                    },
                    {
                        text: this.$t('participant.confirmed_status'),
                        value: 'confirmed_status',
                    },
                    {
                        text: this.$t('participant.confirmed_at'),
                        value: 'confirmed_at',
                    },
                    {
                        text: this.$t('participant.registered_at'),
                        value: 'registered_at',
                    },
                ]
            } else if (this.estimate.mailings) {
                statics = [
                    {
                        text: 'Fecha Envio',
                        value: 'sent_mail_date',
                    },
                    {
                        text: 'Fecha Visto',
                        value: 'view_mail_date',
                    },
                    {
                        text: this.$t('status').capitalize(),
                        value: 'status',
                    },
                ]
            }

            headers = headers.concat(statics)
            return headers
        },
        rejected() {
            return _.filter(this.participants, ['confirmed_status', false]).length
        },
        confirmed() {
            return _.filter(this.participants, ['confirmed_status', true]).length
        },
        registered() {
            return _.reject(this.participants, ['registered_at', null]).length
        },
        confirmedEfectivity() {
            if (this.event.quota && this.event.quota >= 0) {
                return Math.round((100 * this.confirmed) / this.event.quota)
            } else {
                if (this.participants.length === 0) return 0
                return Math.round((100 * this.confirmed) / this.participants.length)
            }
        },
        registeredEfectivity() {
            if (this.event.quota && this.event.quota >= 0) {
                return Math.round((100 * this.registered) / this.event.quota)
            } else {
                if (this.participants.length) return 0
                return Math.round((100 * this.registered) / this.participants.length)
            }
        },
        sentTotal() {
            return _.sumBy(this.rawSeries.sent, (dataPoint) => {
                return dataPoint.y
            })
        },
        mailingsPercentage() {
            if (this.event.estimate.mailings_quantity === 0) {
                return 0
            }

            return Math.round((this.sentTotal / this.event.estimate.mailings_quantity) * 100)
        },
        seenTotal() {
            return _.sumBy(this.rawSeries.seen, (dataPoint) => {
                return dataPoint.y
            })
        },
        seenPercentage() {
            if (this.participants.length === 0) {
                return 0
            }

            return Math.round((this.seenTotal / this.sentTotal) * 100)
        },
        bouncedPercentage() {
            if (this.event.sent_mail_count === 0) {
                return 0
            }

            return Math.round((this.event.bounced_mail_count / this.event.sent_mail_count) * 100)
        },
        confirmPercentage() {
            if (this.participants.length === 0) {
                return 0
            }

            return Math.round((this.confirmed / this.participants.length) * 100)
        },
        rejectedPercentage() {
            if (this.participants.length === 0) {
                return 0
            }

            return Math.round((this.rejected / this.participants.length) * 100)
        },
    },
    methods: {
        async refreshChatParticipants() {
            this.loadingChatParticipants = true
            const response = await this.axios.get(`events/${this.$route.params.id}/activeInChat`)
            this.activeInChat = response.data.participant_count
            this.loadingChatParticipants = false
        },

        openDialog() {
            this.category = 'full'
            this.estimate = {
                landing: true,
                confirmation_form: false,
                mailings: false,
                mailings_quantity: 0,
                polls: false,
                polls_quantity: 0,
                register_keys: false,
                register_keys_quantity: 0,
                ticket_sale: false,
                app: false,
                free_tickets: true,
                administration: false,
                graphical_design: false,
                registering: false,
                lanyards: false,
                credentials: false,
                collectors_half: false,
                collectors_full: false,
                development: false,
            }
            this.landingDialog = true
        },

        async openExportVideoParticipants() {
            this.exporting = true

            try {
                const response = await this.axios.get(`/events/${this.event.id}/exportVideoParticipants`, {
                    responseType: 'blob',
                })
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'event.xlsx') //or any other extension
                document.body.appendChild(link)
                link.click()
            } catch (e) { }
            this.exporting = false
        },

        openExportDialog() {
            if (!this.exportMail) {
                this.exportMail = this.user.email
            }
            this.exportDialog = true
        },

        exportPollExcel(poll) {
            this.exporting = true

            this.axios
                .get(`/events/${this.event.id}/polls/${poll.id}/export`, {
                    responseType: 'blob',
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'event.xlsx') //or any other extension
                    document.body.appendChild(link)
                    link.click()
                })
                .finally(() => {
                    this.exporting = false
                })
        },

        exportEventExcel() {
            this.exporting = true
            this.exportDialog = false

            let url = null
            if (this.event.estimate.confirmation_form) {
                url = `/events/${this.event.id}/exportConfirmation`
            } else if (this.event.estimate.ticket_sale) {
                // url = `/events/${this.event.id}/exportTicketSale`
                url = `/events/${this.event.id}/exportSimpleConfirmation`
            } else if (this.event.estimate.app) {
                url = `/events/${this.event.id}/exportApp`
            } else {
                url = `/events/${this.event.id}/export`
            }

            this.axios
                .post(url, { to: this.exportMail })
                .then((response) => {
                    this.exportSuccessDialog = true
                })
                .catch(() => {
                    this.$snotify.success(this.$t('excel.queue.error'))
                })
                .finally(() => {
                    this.exporting = false
                })
        },
        exportEventPDF() {
            this.axios
                .get(`/events/${this.event.id}/export`, {
                    responseType: 'blob',
                })
                .then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'event.pdf') //or any other extension
                    document.body.appendChild(link)
                    link.click()
                })
        },
        saveExtraEstimate() {
            this.loading = true
            this.axios
                .post('/estimates', { ...this.estimate })
                .then((response) => {
                    this.estimate = Object.assign({}, this.event.estimate)
                    this.estimate.mailings_quantity = 0
                    this.estimate.polls_quantity = 0
                    this.estimate.register_keys_quantity = 0
                    this.estimate.estimate_id = this.estimate.id
                    delete this.estimate.id
                    delete this.estimate.extras
                    this.dialog = false
                    this.$snotify.error(errors, this.$t('estimate.update.success'))
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('estimate.update.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        formatConfirmedDate(participant) {
            return moment(participant.confirmed_at).format('YYYY-MM-DD')
        },
    },
    async mounted() {
        this.loadingSeries = true
        this.loadingDatabases = true
        this.loadingPolls = true
        this.loadingVideos = true
        this.loading = true

        try {
            let result = await this.axios.get(`/events/${this.$route.params.id}`)
            console.log("event")
            console.log(result)
            this.event = result.data.event
            this.originalEstimate = Object.assign({}, this.event.estimate)
            this.originalEstimate.mailings_quantity = 0
            this.originalEstimate.polls_quantity = 0
            this.originalEstimate.register_keys_quantity = 0
            this.originalEstimate.estimate_id = this.originalEstimate.id
            this.estimate = Object.assign({}, this.originalEstimate)
            delete this.originalEstimate.id
            delete this.originalEstimate.extras

            this.estimate = Object.assign({}, this.originalEstimate)

            _.forEach(this.event.referers, (value, key) => {
                this.optionsReferer.labels.push(key)
                this.seriesReferer.push(value)
            })

            _.forEach(this.event.devices, (value, key) => {
                this.optionsDevices.labels.push(key)
                this.seriesDevices.push(value)
            })


            const user = this.axios.get(`/user`)
            const series = this.axios.get(`/events/${this.$route.params.id}/participants/series`)
            const databases = this.axios.get(`/databases/${this.event.profile.database_id}`)
            const polls = this.axios.get(`events/${this.$route.params.id}/polls`)

            let secure_video = Promise.resolve(false)
            let activeInChat = Promise.resolve(false)
            if (this.event.secure_video) {
                secure_video = this.axios.get(`events/${this.$route.params.id}/secureVideoViews`)
                activeInChat = this.axios.get(`events/${this.$route.params.id}/activeInChat`)
            }

            try {
                console.log("result2")
                const result2 = await Promise.all([series, databases, polls, secure_video, user, activeInChat])
                console.log(result2)
                this.participants = result2[0].data.participants
                console.log("this.participants")
                console.log(this.participants)
                this.rawSeries = result2[0].data
                if (this.estimate.mailings) {
                    this.series = this.series.concat([
                        {
                            name: this.$t('event.sent'),
                            data: result2[0].data.sent,
                        },
                        {
                            name: this.$t('event.seen'),
                            data: result2[0].data.seen,
                        },
                    ])
                }

                if (this.estimate.confirmation_form) {
                    this.series = this.series.concat([
                        {
                            name: this.$t('event.confirmed'),
                            data: result2[0].data.confirmed,
                        },
                        {
                            name: this.$t('event.declined'),
                            data: result2[0].data.declined,
                        },
                    ])
                }

                if (this.estimate.landing) {
                    this.series = this.series.concat([
                        {
                            name: 'facebook',
                            data: result2[0].data.facebook,
                        },

                        {
                            name: 'instagram',
                            data: result2[0].data.instagram,
                        },

                        {
                            name: 'linkedin',
                            data: result2[0].data.linkedin,
                        },

                        {
                            name: 'twitter',
                            data: result2[0].data.twitter,
                        },
                    ])
                }

                this.database = result2[1].data.database
                this.polls = result2[2].data.polls

                if (this.event.secure_video) {
                    this.secureVideoViews = result2[3].data.secure_video_views
                    this.activeInChat = result2[5].data.participant_count
                }

                this.user = result2[4].data.data

                this.loadingSeries = false
                this.loadingDatabases = false
                this.loadingPolls = false
                this.loadingVideos = false
                this.loading = false
            } catch (e) {
                console.error(e.message)
            }
        } catch (e) {
            console.error(e.message)
        }
    },
}
</script>

<style>
.inputQuantity input[type='number'] {
    -moz-appearance: textfield;
}

.inputQuantity input::-webkit-outer-spin-button,
.inputQuantity input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

[v-cloak]>* {
    display: none;
}
</style>
