<template>
    <div id="webinars">
        <v-content v-if="newWebinar">
            <v-card>
                <v-card-title class="headline">
                    {{ (webinar.id ? $t('edit').capitalize() : $t('new').capitalize()) + ' Webinar' }}
                </v-card-title>
                <v-card-text>
                    <v-stepper v-model="webinarStep">
                        <v-stepper-header>
                            <v-stepper-step :complete="database && webinarStep > 1" step="1">
                                {{ $t('form.database').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 2" step="2">
                                {{ $t('webinar.webinar_basic').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 2" step="3">
                                {{ $t('landing.title').capitalize() + ' & Inscripciones' }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 4" step="4">
                                {{ $t('actor.titlePlural').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 5" step="5">
                                {{ $t('form.sponsors').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 6" step="6">
                                {{ $t('link.polls').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 7" step="7">
                                {{ $t('reminder').capitalize() }}
                            </v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="webinar && webinarStep > 8" step="8">
                                {{ $t('form.mailings').capitalize() }}
                            </v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-card>
                                    <v-card-text>
                                        <v-container fluid>
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-select :label="$t('form.database').capitalize()"
                                                        :items="databases" item-text="name" v-model="selectedDatabase"
                                                        return-object></v-select>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-btn color="success" elevation="2" @click="openNewDatabase">
                                            {{ $t('newa').capitalize() + ' ' + $t('form.database').capitalize() }}
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('cancel') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep += 1">{{
                                        $t('next')
                                        }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card>
                                    <v-card-text>

                                        <v-container>
                                            <v-row>
                                                <v-col cols="5">
                                                    <v-text-field data-vv-validate-on="blur" v-validate="'required'"
                                                        data-vv-name="name"
                                                        :error-messages="errors.collect('webinar.name')" required
                                                        prepend-icon="short_text" v-model.lazy="webinar.name"
                                                        :label="$t('form.name').capitalize()" type="text">
                                                    </v-text-field>
                                                </v-col>
                                                <v-col cols="2">
                                                    <v-menu ref="startDateMenu" v-model="startDateMenu"
                                                        :close-on-content-click="false"
                                                        :return-value.sync="webinar.start_date"
                                                        transition="scale-transition" offset-y min-width="auto">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-text-field v-model="webinar.start_date"
                                                                :label="$t('form.start_date').capitalize()"
                                                                prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                v-on="on"></v-text-field>
                                                        </template>
                                                        <v-date-picker v-model="webinar.start_date" no-title scrollable
                                                            :allowedDates="allowedDates"
                                                            @input="$refs.startDateMenu.save(webinar.start_date)">
                                                        </v-date-picker>
                                                    </v-menu>
                                                </v-col>

                                                <v-col cols="1">
                                                    <v-menu ref="startTimeMenu" v-model="startTimeMenu"
                                                        :close-on-content-click="false" :nudge-right="40"
                                                        :return-value.sync="webinar.start_time"
                                                        transition="scale-transition" offset-y min-width="290px">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-text-field v-model="webinar.start_time"
                                                                :label="$t('form.start_time').capitalize()"
                                                                prepend-icon="mdi-clock-time-four-outline" readonly
                                                                v-bind="attrs" v-on="on"></v-text-field>
                                                        </template>
                                                        <v-time-picker v-if="startTimeMenu" v-model="webinar.start_time"
                                                            full-width @click:minute="
                                                                $refs.startTimeMenu.save(webinar.start_time)
                                                            " format="24hr"></v-time-picker>
                                                    </v-menu>
                                                </v-col>

                                                <v-col cols="1">
                                                    <v-menu ref="endTimeMenu" v-model="endTimeMenu"
                                                        :close-on-content-click="false" :nudge-right="40"
                                                        :return-value.sync="webinar.end_time"
                                                        transition="scale-transition" offset-y min-width="290px">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-text-field v-model="webinar.end_time"
                                                                :label="$t('form.end_time').capitalize()"
                                                                prepend-icon="mdi-clock-time-four-outline" readonly
                                                                v-bind="attrs" v-on="on"></v-text-field>
                                                        </template>
                                                        <v-time-picker v-if="endTimeMenu" v-model="webinar.end_time"
                                                            full-width
                                                            @click:minute="$refs.endTimeMenu.save(webinar.end_time)"
                                                            format="24hr">
                                                        </v-time-picker>
                                                    </v-menu>
                                                </v-col>

                                                <v-col cols="3">
                                                    <v-autocomplete :items="timezoneSelectOptions" label="Zona Horaria"
                                                        v-model="webinar.timezone"></v-autocomplete>
                                                </v-col>
                                            </v-row>

                                            <v-row>
                                                <v-col>
                                                    <v-card>
                                                        <v-card-title primary-title>
                                                            <v-icon>image</v-icon>
                                                            <h4 class="headline mb-0">
                                                                {{ $t('form.banner').capitalize() }} (Optional)
                                                            </h4>
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <file-pond name="banner" :server="serverOptionsBanner"
                                                                :label-idle="$t('form.upload.idle')" :files="banner"
                                                                allow-multiple="false"
                                                                accepted-file-types="image/jpeg, image/png"
                                                                @updatefiles="onUpdateFilesBanner"
                                                                image-crop-aspect-ratio="19:9"
                                                                :image-edit-instant-edit="true" :imageEditEditor="doka"
                                                                max-file-size="1536KB" :label-max-file-size-exceeded="
                                                                    $t('file.uploadExcedeed')
                                                                " :label-max-file-size="$t('file.uploadDetailed')">
                                                            </file-pond>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-col>
                                                <v-col>
                                                    <v-card>
                                                        <v-card-title primary-title>
                                                            <v-icon>image</v-icon>
                                                            <h4 class="headline mb-0">
                                                                {{ $t('form.logo').capitalize() }} (Optional)
                                                            </h4>
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <file-pond name="logo" :server="serverOptionsLogo"
                                                                :label-idle="$t('form.upload.idle')" :files="logo"
                                                                allow-multiple="false"
                                                                accepted-file-types="image/jpeg, image/png"
                                                                @updatefiles="onUpdateFilesLogo"
                                                                :crop-limit-to-image-bounds="true"
                                                                :image-edit-instant-edit="true" :imageEditEditor="doka"
                                                                max-file-size="1536KB" :label-max-file-size-exceeded="
                                                                    $t('file.uploadExcedeed')
                                                                " :label-max-file-size="$t('file.uploadDetailed')">
                                                            </file-pond>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-col>
                                            </v-row>
                                        </v-container>

                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('cancel') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="saveOrUpdate">{{
                                        $t('save')
                                        }}</v-btn>

                                        <v-btn v-if="webinar.id" color="success" :loading="loading" dark
                                            @click="webinarStep += 1">{{ $t('next') }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="6">
                                                    <v-switch v-model="webinar.estimate.confirmation_form" :label="
                                                        $t(
                                                            'estimate.confirmationForm'
                                                        ).capitalize()
                                                    " required @change="checkSwitches($event, 'confirmation')">
                                                    </v-switch>
                                                </v-col>

                                                <v-col cols="6">
                                                    <v-switch v-model="webinar.estimate.ticket_sale" :label="
                                                        $t(
                                                            'estimate.ticketSale'
                                                        ).capitalize()
                                                    " required @change="checkSwitches($event,'tickets')">
                                                    </v-switch>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="6">
                                                    <v-select data-vv-validate-on="blur" v-validate="'required'"
                                                        data-vv-name="name"
                                                        :error-messages="errors.collect('webinar.name')" required
                                                        prepend-icon="mdi-airplane-landing" :items="[
                                                            { text: 'Ninguno', value: -1 },
                                                            { text: 'Default', value: 3, },
                                                            { text: 'Segundo', value: 4 },
                                                            { text: 'Tercero', value: 5 }
                                                        ]" v-model.lazy="webinar.landing.which"
                                                        :label="$t('estimate.landing').capitalize()" type="text">
                                                    </v-select>
                                                </v-col>

                                                <v-col cols="6">
                                                    <v-img v-if="webinar.landing" max-height="150" max-width="300"
                                                        :src="`../images/webinar-landing-thumb-${webinar.landing.which}.jpg`">
                                                    </v-img>
                                                </v-col>
                                            </v-row>

                                            <v-row>
                                                <v-col cols="4">
                                                    <h3 class="font-weight-regular mb-2 ">Color Primario</h3>
                                                    <v-color-picker mode="hexa" v-model="webinar.primary">
                                                    </v-color-picker>
                                                </v-col>

                                                <v-col cols="4">
                                                    <h3 class="font-weight-regular mb-2">Color Secundario</h3>
                                                    <v-color-picker mode="hexa" v-model="webinar.secondary">
                                                    </v-color-picker>
                                                </v-col>

                                                <v-col cols="4">
                                                    <h3 class="font-weight-regular mb-2">Color Terciario</h3>
                                                    <v-color-picker mode="hexa" v-model="webinar.terciary">
                                                    </v-color-picker>
                                                </v-col>
                                            </v-row>

                                            <v-row v-if="webinar.estimate.ticket_sale">
                                                <v-col cols="12">
                                                    <v-card>
                                                        <v-card-title>
                                                            {{ $t('form.tickets').capitalize() }}

                                                            <v-spacer></v-spacer>

                                                            <v-btn color="success" dark fab small
                                                                @click.native.stop="addTicket">
                                                                <v-icon small dark>add</v-icon>
                                                            </v-btn>
                                                        </v-card-title>

                                                        <v-card-text>
                                                            <v-layout row wrap>
                                                                <v-flex xs3>
                                                                    <v-switch label="Webpay"
                                                                        v-model="webinar.webpay_accepted"></v-switch>
                                                                </v-flex>
                                                                <v-flex xs3>
                                                                    <v-switch label="Paypal"
                                                                        v-model="webinar.paypal_accepted"></v-switch>
                                                                </v-flex>
                                                                <v-flex xs3>
                                                                </v-flex>
                                                                <v-flex xs3>
                                                                    <v-switch
                                                                        :label="$t('tickets.personalized_tickets').capitalize()"
                                                                        v-model="webinar.personalize_tickets">
                                                                    </v-switch>
                                                                </v-flex>
                                                                <v-flex xs12>
                                                                    <v-container>
                                                                        <v-row dense
                                                                            v-for="(ticket, index) in webinar.tickets"
                                                                            :key="`ticket-${index}`">
                                                                            <v-col cols="12">
                                                                                <span class="headline">Ticket #{{ index
                                                                                + 1 }}</span>
                                                                            </v-col>
                                                                            <v-col cols="12">
                                                                                <Ticket :ticket="ticket" :index="index"
                                                                                    :databases="databases"
                                                                                    @remove-ticket="removeTicket" />
                                                                            </v-col>
                                                                        </v-row>
                                                                    </v-container>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-col>
                                            </v-row>
                                        </v-container>

                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>

                                        <v-btn v-if="webinar.id" color="success" :loading="loading" dark
                                            @click="saveLanding">{{ $t('next') }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="4">
                                <v-card>
                                    <v-card-title>
                                        {{ $t('actor.title').capitalize() }}s
                                        <v-spacer></v-spacer>
                                        <v-btn class="mr-1" color="success" @click="newSpeakers = true"> {{ $t('new') }}
                                            {{ $t('actor.title') }}</v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form data-vv-scope="speakers" lazy-validation>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-combobox v-model="selectedSpeakers" :items="actors"
                                                            multiple
                                                            :item-text="(item) => `${item.name} ${item.lastname} (${item.email})`"
                                                            :item-value="(item) => item.id" return-object chips>
                                                        </v-combobox>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>
                                        <v-btn color="success" :loading="loading" elevation="2"
                                            @click="sendSpeakerEmails">{{
                                            $t('send')
                                            }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="syncWebinarActors">{{
                                        $t('next')
                                        }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="5">
                                <v-card>
                                    <v-card-title>
                                        {{ $t('form.sponsors').capitalize() }}
                                        <v-spacer></v-spacer>
                                        <v-btn class="mr-1" color="success" @click="newSponsors = true"> {{ $t('new') }}
                                        </v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form data-vv-scope="sponsors" lazy-validation>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-combobox v-model="selectedSponsors" :items="sponsors"
                                                            multiple
                                                            :item-text="(item) => `${item.name} (${item.category})`"
                                                            :item-value="(item) => item.id" return-object chips>
                                                        </v-combobox>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="syncWebinarSponsors">{{
                                        $t('next')
                                        }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="6">
                                <v-card>
                                    <v-card-title>
                                        {{ $t('poll.title').capitalize() }}s
                                        <v-spacer></v-spacer>
                                        <v-btn class="mr-1" color="success" @click="newPolls = true"> {{ $t('newa') }}
                                        </v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form data-vv-scope="polls" lazy-validation>
                                            <v-container>
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-combobox v-model="selectedPolls" :items="polls" multiple
                                                            :item-text="(item) => `${item.name}`"
                                                            :item-value="(item) => item.id" return-object chips>
                                                        </v-combobox>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-form>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="syncWebinarPolls">{{
                                        $t('next')
                                        }}</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="7">
                                <v-card>
                                    <v-card-title>
                                        {{ $t('reminder').capitalize() }}s
                                        <v-spacer></v-spacer>
                                        <v-btn color="success" dark class="mb-2" @click="
                                            reminder = {}
                                            dialogReminders = true
                                        ">{{ $t('new') }}</v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container fluid fill-height>
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-spacer></v-spacer>

                                                </v-col>
                                                <v-col cols="12">
                                                    <v-data-table :headers="reminderHeaders" :items="reminders"
                                                        :loading="loading" :items-per-page.sync="itemsPerPage"
                                                        class="elevation-1">
                                                    </v-data-table>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep += 1">{{
                                        $t('next')
                                        }}</v-btn>

                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>

                            <v-stepper-content step="8">
                                <v-card>
                                    <v-card-text>
                                        <v-container grid-list-xs>
                                            <v-row>
                                                <v-col cols="12" sm="6" md="3" lg="2">
                                                    <v-card color="grey lighten-4" max-width="300" height="200"
                                                        @click.native="selectMailing('confirmation')">
                                                        <v-card-text>
                                                            <v-container>
                                                                <v-row wrap>
                                                                    <v-img max-height="100"
                                                                        src="../storage/iconos/invitacion.svg"
                                                                        aspect-ratio="1" contain></v-img>
                                                                </v-row>
                                                                <v-row>
                                                                    <v-col class="text-center">{{
                                                                    $t('mailings.confirmation') }}</v-col>
                                                                </v-row>
                                                            </v-container>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="3" lg="2">
                                                    <v-card color="grey lighten-4" max-width="300" height="200"
                                                        @click.native="selectMailing('qr')">
                                                        <v-card-text>
                                                            <v-container>
                                                                <v-row wrap>
                                                                    <v-img max-height="100"
                                                                        src="../storage/iconos/invitacion-qr.svg"
                                                                        aspect-ratio="1" contain></v-img>
                                                                </v-row>
                                                                <v-row>
                                                                    <v-col class="text-center">{{ $t('mailings.qr') }}
                                                                    </v-col>
                                                                </v-row>
                                                            </v-container>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="3" lg="2">
                                                    <v-card color="grey lighten-4" max-width="300" height="200"
                                                        @click.native="selectMailing('reconfirmation')">
                                                        <v-card-text>
                                                            <v-container>
                                                                <v-row wrap>
                                                                    <v-img max-height="100"
                                                                        src="../storage/iconos/invitacion-reconfirmar.svg"
                                                                        aspect-ratio="1" contain></v-img>
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
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="info" :loading="loading" @click="
                                            newWebinar = false
                                            webinarStep = 1
                                        ">{{ $t('close') }}</v-btn>
                                        <v-btn color="success" :loading="loading" dark @click="webinarStep -= 1">{{
                                        $t('previous')
                                        }}</v-btn>

                                    </v-card-actions>
                                </v-card>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-card-text>
            </v-card>
        </v-content>
        <v-content v-else>
            <v-container fluid fill-height>
                <v-row>
                    <v-col cols="12">
                        <v-toolbar text color="primary">
                            <v-toolbar-title class="white--text title">{{
                            $t('link.webinars').toUpperCase()
                            }}</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn color="success" dark class="mb-2" @click="
                                database = databases[0]
                                webinar = Object.assign({ profile_id: database.profiles[0].id }, webinarTemplate)
                                newWebinar = true
                            ">{{ $t('new') }}</v-btn>
                        </v-toolbar>
                        <v-data-table :headers="webinarHeaders" :items="webinars" :loading="loading"
                            :items-per-page.sync="itemsPerPage" class="elevation-1">

                            <template v-slot:item.webinar_link="{ item }">
                                <v-icon small class="ml-2" @click.stop="
                                    copyToClipboard(item.presenter_webinar_link)
                                ">mdi-content-copy</v-icon>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="
                                                open(item.host_webinar_link)
                                            " v-on="on">mdi-open-in-new</v-icon>
                                        </template>
                                        <span>Unirse como Host</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="
                                                reminders = item.reminders
                                                if (!item.landing) item.landing = { which: null }
                                                if (item.sponsors) selectedSponsors = item.sponsors
                                                if (item.speakers) selectedSpeakers = item.speakers
                                                if (item.polls) selectedPolls = item.polls
                                                webinar = item
                                                selectedDatabase = databases.find(
                                                    (db) => db.id === webinar.profile.database_id
                                                )
                                                newWebinar = true
                                            " v-on="on">mdi-pencil</v-icon>
                                        </template>
                                        <span>Editar</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="
                                                webinar = item
                                                selectMailing()
                                            " v-on="on">mdi-email-multiple</v-icon>
                                        </template>
                                        <span>Enviar invitaciones a participantes</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon small class="mr-2" @click.stop="
                                                $router.push({ name: 'event_dashboard', params: { id: item.id } })
                                            " v-on="on">mdi-view-dashboard-variant</v-icon>
                                        </template>
                                        <span>Dashboard del Webinar</span>
                                    </v-tooltip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>

        <v-dialog v-model="dialogReminders" width="500" max-width="95%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('new').capitalize() + ' ' + $t('reminder').capitalize() }}
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="reminderForm">
                        <v-container>
                            <v-row wrap>
                                <v-col cols="6">
                                    <v-menu ref="reminderDateMenu" v-model="reminderDateMenu"
                                        :close-on-content-click="false" :return-value.sync="reminder.date"
                                        transition="scale-transition" offset-y min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="reminder.date" :label="$t('form.date').capitalize()"
                                                prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                            </v-text-field>
                                        </template>
                                        <v-date-picker v-model="reminder.date" no-title scrollable
                                            @input="$refs.reminderDateMenu.save(reminder.date)">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="6">
                                    <v-menu ref="reminderTimeMenu" v-model="reminderTimeMenu"
                                        :close-on-content-click="false" :nudge-right="40"
                                        :return-value.sync="reminder.time" transition="scale-transition" offset-y
                                        max-width="290px" min-width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="reminder.time"
                                                :label="$t('form.start_time').capitalize()"
                                                prepend-icon="mdi-clock-time-four-outline" readonly v-bind="attrs"
                                                v-on="on"></v-text-field>
                                        </template>
                                        <v-time-picker v-model="reminder.time" full-width @click:minute="
                                            $refs.reminderTimeMenu.save(reminder.time)
                                        "></v-time-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" @click="dialogReminders = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="success" @click="saveReminder">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="newSpeakers" width="80%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('new').capitalize() + ' ' + $t('actor.title').capitalize() }}
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="actorForm">
                        <v-container>
                            <v-layout wrap>
                                <v-flex xs2>
                                    <v-text-field v-validate="'max:30'" data-vv-name="prefix"
                                        :error-messages="errors.collect('actorForm.prefix')" :counter="30" required
                                        prepend-icon="work" v-model="actor.prefix"
                                        :label="$t('actor.prefix').capitalize()" type="text"></v-text-field>
                                </v-flex>
                                <v-flex xs5>
                                    <v-text-field v-validate="'required|max:30'" data-vv-name="name"
                                        :error-messages="errors.collect('actorForm.name')" :counter="30" required
                                        prepend-icon="work" v-model="actor.name" :label="$t('form.name').capitalize()"
                                        type="text"></v-text-field>
                                </v-flex>
                                <v-flex xs5>
                                    <v-text-field v-validate="'required|max:30'" data-vv-name="lastname"
                                        :error-messages="errors.collect('actorForm.lastname')" :counter="30" required
                                        prepend-icon="work" v-model="actor.lastname"
                                        :label="$t('form.lastname').capitalize()" type="text"></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-validate="'required|email'" data-vv-name="email"
                                        :error-messages="errors.collect('form.mail')" required prepend-icon="email"
                                        v-model="actor.email" :label="$t('form.mail').capitalize()" type="text">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-card>
                                        <v-card-title primary-title>
                                            <v-icon>image</v-icon>
                                            <h4 class="headline mb-0">{{ $t('form.photo') }}</h4>
                                        </v-card-title>
                                        <v-card-text>
                                            <file-pond name="photo" :server="serverOptions"
                                                :label-idle="$t('form.upload.idle')" :files="photo"
                                                allow-multiple="false" accepted-file-types="image/jpeg, image/png"
                                                @updatefiles="onUpdateFiles" required image-crop-aspect-ratio="1:1"
                                                image-resize-target-width="400" :image-edit-instant-edit="true"
                                                :imageEditEditor="doka" max-file-size="1536KB"
                                                :label-max-file-size-exceeded="
                                                    $t('file.uploadExcedeed')
                                                " :label-max-file-size="$t('file.uploadDetailed')"></file-pond>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" @click="newSpeakers = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="success" @click="updateActor">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="newSponsors" width="80%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('new').capitalize() + ' ' + 'Sponsor' }}
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="sponsorForm">
                        <v-container>
                            <v-layout wrap>
                                <v-flex xs6>
                                    <v-text-field v-validate="
                                        'required|max:30'
                                    " data-vv-name="name" :error-messages="
                                        errors.collect(
                                            'name'
                                        )
                                    " :counter="30" required prepend-icon="short_text" v-model="
                                        sponsor.name
                                    " :label="
                                        $t(
                                            'form.name'
                                        ).capitalize()
                                    " type="text"></v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-validate="
                                        'required|max:50'
                                    " :data-vv-name="
                                        $t(
                                            'form.category'
                                        )
                                    " :error-messages="
                                        errors.collect(
                                            'category'
                                        )
                                    " :counter="50" required prepend-icon="short_text" v-model="
                                        sponsor.category
                                    " :label="
                                        $t(
                                            'form.category'
                                        ).capitalize()
                                    " type="text"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-card>
                                        <v-card-title primary-title>
                                            <v-icon>image</v-icon>
                                            <h4 class="headline mb-0">
                                                {{
                                                $t(
                                                'form.logo'
                                                ).capitalize()
                                                }}
                                            </h4>
                                        </v-card-title>
                                        <v-card-text>
                                            <file-pond name="logo" :server="
                                                serverSponsorOptions
                                            " :label-idle="
                                                $t(
                                                    'form.upload.idle'
                                                )
                                            " :files="
                                                sponsorLogo
                                            " allow-multiple="false" accepted-file-types="image/jpeg, image/png"
                                                @updatefiles="
                                                    onSponsorUpdateFiles
                                                " required image-crop-aspect-ratio="1:1"
                                                image-resize-target-width="600" :image-edit-instant-edit="
                                                    true
                                                " :imageEditEditor="
                                                    doka
                                                " max-file-size="1536KB"
                                                :label-max-file-size-exceeded="$t('file.uploadExcedeed')"
                                                :label-max-file-size="$t('file.uploadDetailed')"></file-pond>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" @click="newSponsors = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="success" @click="updateSponsor">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="newPolls" width="80%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('newa').capitalize() + ' ' + $t('estimate.poll') }}
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="pollsForm">
                        <v-container>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field v-validate="
                                        'required|max:100'
                                    " data-vv-name="name" :error-messages="
                                        errors.collect(
                                            'name'
                                        )
                                    " :counter="100" required prepend-icon="work" v-model="poll.name" :label="
                                        $t(
                                            'form.name'
                                        ).capitalize()
                                    " type="text"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <h4>
                                        {{
                                        $t(
                                        'poll.questions'
                                        ).capitalize()
                                        }}
                                        <v-btn fab dark small color="success" @click="
                                            addQuestion
                                        ">
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                    </h4>
                                </v-flex>
                                <v-flex xs12>
                                    <v-expansion-panels>
                                        <v-expansion-panel v-for="(question,
                                        index) in poll.questions" :key="index">
                                            <v-expansion-panel-header>{{
                                            $t(
                                            'poll.question'
                                            ).capitalize() +
                                            ' #' +
                                            (index +
                                            1) +
                                            ' - ' +
                                            (question.question ? question.question : '')
                                            }}</v-expansion-panel-header>
                                            <v-expansion-panel-content>
                                                <v-container>
                                                    <v-layout row wrap>
                                                        <v-flex xs9>
                                                            <v-text-field v-validate="
                                                                'required|max:190'
                                                            " data-vv-as="question" :data-vv-name="
                                                                `question${index}`
                                                            " :error-messages="
                                                                errors.collect(
                                                                    `question${index}`
                                                                )
                                                            " :counter="
                                                                190
                                                            " required prepend-icon="short_text" v-model="
                                                                question.question
                                                            " :label="
                                                                $t(
                                                                    'poll.question'
                                                                ).capitalize()
                                                            " type="text"></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-select :items="
                                                                types
                                                            " :label="
                                                                $t(
                                                                    'poll.category'
                                                                ).capitalize()
                                                            " v-model="
                                                                question.type
                                                            " v-validate="
                                                                'required'
                                                            " prepend-icon="link" required></v-select>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-btn fab dark small color="error" @click="
                                                                removeQuestion(
                                                                    index
                                                                )
                                                            ">
                                                                <v-icon dark>remove</v-icon>
                                                            </v-btn>
                                                        </v-flex>

                                                        <v-flex v-if="
                                                            question.type !=
                                                                2
                                                        " xs12>
                                                            <h4>
                                                                {{
                                                                $t(
                                                                'poll.alternatives'
                                                                ).capitalize()
                                                                }}
                                                                <v-btn fab outlined dark small color="success" @click="
                                                                    addAlternative(
                                                                        question
                                                                    )
                                                                ">
                                                                    <v-icon dark>add</v-icon>
                                                                </v-btn>
                                                            </h4>
                                                        </v-flex>

                                                        <template v-if="
                                                            question.type !=
                                                                2
                                                        ">
                                                            <v-flex xs12 v-for="(alternative,
                                                            index) in question.alternatives" :key="
                                                                index
                                                            ">
                                                                <v-container>
                                                                    <v-layout row wrap>
                                                                        <v-flex xs11>
                                                                            <v-text-field v-validate="
                                                                                'required|max:190'
                                                                            " data-vv-as="alternative" :data-vv-name="
                                                                                `alternative${index}`
                                                                            " :error-messages="
                                                                                errors.collect(
                                                                                    `alternative${index}`
                                                                                )
                                                                            " :counter="
                                                                                190
                                                                            " required prepend-icon="short_text"
                                                                                v-model="
                                                                                    alternative.alternative
                                                                                " :label="
                                                                                    $t(
                                                                                        'poll.alternative'
                                                                                    ).capitalize()
                                                                                " type="text"></v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs1>
                                                                            <v-btn fab dark small color="error" @click="
                                                                                removeAlternative(
                                                                                    question,
                                                                                    index
                                                                                )
                                                                            ">
                                                                                <v-icon dark>remove</v-icon>
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
                    <v-btn color="info" @click="newPolls = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="success" @click="updatePoll">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="newDatabase" width="90%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('newa').capitalize() + ' ' + $t('form.database').capitalize() }}
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="database" lazy-validation>
                        <v-container>
                            <v-row xs12>
                                <v-text-field v-validate="'required|max:60'" :data-vv-as="$t('form.name')"
                                    data-vv-name="databaseForm.name"
                                    :error-messages="errors.collect('databaseForm.name')" :counter="60" required
                                    prepend-icon="work" v-model="database.name" :label="$t('form.name').capitalize()"
                                    type="text"></v-text-field>
                            </v-row>
                            <v-row xs12>
                                <h4>
                                    {{ $t('database.fields').capitalize() }}
                                </h4>
                                <v-btn fab dark small color="success" @click="addField">
                                    <v-icon dark>add</v-icon>
                                </v-btn>
                            </v-row>
                            <v-row xs12 v-for="(field, index) in database.fields" :key="index">
                                <v-col xs2>
                                    <v-text-field :disabled="disableField(field.key)" v-validate="'required|max:200'"
                                        :data-vv-as="$t('form.key')" :data-vv-name="`databaseForm.key${index}`"
                                        :error-messages="
                                            errors.collect(`databaseForm.key${index}`)
                                        " :counter="200" required prepend-icon="short_text" v-model="field.key"
                                        :label="$t('form.key').capitalize()" type="text"></v-text-field>
                                </v-col>
                                <v-col xs3>
                                    <v-text-field v-validate="'required|max:500'" :data-vv-as="$t('form.field')"
                                        :data-vv-name="`databaseForm.field${index}`" :error-messages="
                                            errors.collect(`databaseForm.field${index}`)
                                        " :counter="500" required prepend-icon="short_text" v-model="field.name"
                                        :label="$t('form.field').capitalize()" type="text"></v-text-field>
                                </v-col>
                                <v-col xs2>
                                    <v-select :disabled="disableField(field.key)" v-model="field.type"
                                        :label="$t('form.type').capitalize()" v-validate="'required'"
                                        :data-vv-name="`databaseForm.type${index}`" :error-messages="
                                            errors.collect(`databaseForm.type${index}`)
                                        " :items="databaseTypes" outline required></v-select>
                                </v-col>
                                <v-col xs2>
                                    <v-checkbox :disabled="disableField(field.key)" v-validate="'required'"
                                        :data-vv-name="`databaseForm.required${index}`" :error-messages="
                                            errors.collect(`databaseForm.required${index}`)
                                        " required v-model="field.required" :label="$t('form.required').capitalize()"
                                        color="success" @change="
                                            field.required ? (field.in_form = true) : null
                                        "></v-checkbox>
                                </v-col>
                                <v-col xs2>
                                    <v-checkbox :disabled="disableField(field.key)" v-validate="'required'"
                                        :data-vv-name="`databaseForm.in_form${index}`" :error-messages="
                                            errors.collect(`databaseForm.in_form${index}`)
                                        " required v-model="field.in_form" :label="$t('form.in_form').capitalize()"
                                        color="success"></v-checkbox>
                                </v-col>
                                <v-col xs1>
                                    <v-btn v-if="field.type === 'choice'" small fab color="success" dark
                                        class="mt-2 mr-2" @click="addChoice(field)">
                                        <v-icon dark>mdi-playlist-plus</v-icon>
                                    </v-btn>
                                    <v-btn v-if="index > 0 && !disableField(field.key)" small fab color="error" dark
                                        class="mt-2" @click="database.fields.splice(index, 1)">
                                        <v-icon dark>remove</v-icon>
                                    </v-btn>
                                </v-col>
                                <template v-for="(choice, choiceIndex) in field.choices">
                                    <v-col xs8 offset-xs2 :key="index + '-' + choiceIndex">
                                        <v-text-field v-validate="'required'" :data-vv-as="$t('form.choice')"
                                            :data-vv-name="
                                                `databaseForm.field${index +
                                                    '-' +
                                                    choiceIndex}`
                                            " :error-messages="
                                                errors.collect(
                                                    `databaseForm.field${index +
                                                        '-' +
                                                        choiceIndex}`
                                                )
                                            " required prepend-icon="short_text" v-model="
                                                field.choices[field.choices.indexOf(choice)]
                                            " :label="$t('form.choice').capitalize()" type="text"></v-text-field>
                                    </v-col>
                                    <v-col xs1 :key="index + '-' + choiceIndex + '-b'">
                                        <v-btn small fab color="error" dark class="mt-2"
                                            @click="field.choices.splice(index, 1)">
                                            <v-icon dark>remove</v-icon>
                                        </v-btn>
                                    </v-col>
                                </template>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" elevation="2" @click="newDatabase = false">{{ $t('cancel') }}</v-btn>
                    <v-btn color="success" elevation="2" @click="createDatabase">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogEmailParticipants" persistent max-width="90%">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ $t(`mailings.${mailings.category}`) }}</span>
                </v-card-title>

                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field v-model="mailings.fromName" label="De"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field v-model="mailings.subject" label="Asunto"></v-text-field>
                        </v-col>

                        <v-col cols="12">Esta seguro que quiere enviar una invitación a
                            {{ selectedParticipants.length }} participantes?</v-col>
                        <v-col cols="12">
                            <v-data-table v-model="selectedParticipants" :headers="participantHeaders"
                                :items="participants" :footer-props="participantFooterOptions" show-select
                                loading="true"></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogEmailParticipants = false">{{ $t('cancel') }}
                    </v-btn>
                    <v-btn :disabled="selectedParticipants.length === 0" color="blue darken-1" text
                        @click="sendMultipleEmails">{{ $t('accept') }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { loadUrl } from '../app'
import * as Doka from '../plugins/doka/index'
import { forEach } from 'lodash'
import moment from 'moment-timezone'

import Ticket from '../components/Ticket.vue'

export default {
    name: 'Webinars',

    components: { Ticket },

    data: function () {
        return {
            reminderHeaders: [
                {
                    text: 'ID',
                    align: 'left',
                    value: 'id',
                },

                {
                    text: this.$t('form.date').capitalize(),
                    align: 'left',
                    value: 'scheduled_for',
                },

                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    align: 'center',
                    sortable: false,
                },
            ],
            reminder: {},
            reminders: [],
            dialogReminders: false,

            mailings: {},
            dialogEmailParticipants: false,
            participants: [],
            selectedParticipants: [],
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

            newWebinar: false,
            landing: 'primero',
            doka: Doka.create({
                cropAllowImageTurnRight: true,
                cropLimitToImageBounds: false,
            }),
            webinars: [],
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
            databases: [],

            newPolls: false,
            poll: { questions: [] },
            polls: [],
            selectedPolls: [],

            newSponsors: false,
            sponsor: {},
            sponsors: [],
            selectedSponsors: [],

            newSpeakers: false,
            actor: {},
            speakers: [],
            selectedSpeakers: [],
            webinarHeaders: [
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

                {
                    text: this.$t('form.start_time').capitalize(),
                    align: 'left',
                    value: 'start_time',
                },

                {
                    text: this.$t('actor.title').capitalize() + ' ' + this.$t('form.url'),
                    align: 'left',
                    value: 'webinar_link',
                },

                {
                    text: this.$t('actions').capitalize(),
                    value: 'action',
                    align: 'center',
                    sortable: false,
                },
            ],

            itemsPerPage: 10,
            loading: false,

            webinarTemplate: {
                landing: { which: 3 },
                profile: null,
                profile_id: null,
                estimate: { confirmation_form: false, ticket_sale: false },
            },
            webinar: {
                landing: { which: 3 },
                profile: null,
                profile_id: null,
                estimate: { confirmation_form: false, ticket_sale: false },
            },
            databaseTemplate: {
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
            database: null,
            selectedDatabase: null,

            startDateMenu: false,
            startTimeMenu: false,
            endTimeMenu: false,

            webinarStep: 1,
            newDatabase: false,
            newDatabaseName: null,

            emailsSlug: '',

            participantsEmailDialog: false,

            serverOptionsLogo: {
                process: {
                    url: './api/uploadLogoEvent',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.webinar.logo_event = responseJson.name
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
                        this.webinar.banner = responseJson.name
                    },
                },
                load: loadUrl,
            },
            serverOptions: {
                process: {
                    url: './api/uploadPhoto',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.actor.photo = responseJson.name
                    },
                },
                load: loadUrl,
            },

            serverSponsorOptions: {
                process: {
                    url: './api/uploadLogoSponsor',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.sponsor.logo = responseJson.name
                    },
                },
                load: loadUrl,
            },

            types: [
                { text: this.$t('poll.type.single'), value: 0 },
                { text: this.$t('poll.type.multiple'), value: 1 },
                { text: this.$t('poll.type.open'), value: 2 },
            ],

            timezoneSelectOptions: moment.tz.names()
                .reduce((memo, tz) => {
                    memo.push({
                        name: tz,
                        offset: moment.tz(tz).utcOffset()
                    });

                    return memo;
                }, [])
                .sort((a, b) => {
                    return a.offset - b.offset
                })
                .reduce((memo, tz) => {
                    const timezone = tz.offset ? moment.tz(tz.name).format('Z') : '';
                    memo.push({ text: `(GMT${timezone}) ${tz.name}`, value: tz.name })

                    return memo
                }, [])
        }
    },

    computed: {
        photo() {
            return this.actor.photo ? [{ source: this.actor.photo, options: { type: 'local' } }] : []
        },
        logo() {
            return this.webinar.logo_event
                ? [
                    {
                        source: this.webinar.logo_event,
                        options: { type: 'local' },
                    },
                ]
                : []
        },
        banner() {
            return this.webinar.banner ? [{ source: this.webinar.banner, options: { type: 'local' } }] : []
        },

        sponsorLogo() {
            return this.sponsor.logo ? [{ source: this.sponsor.logo, options: { type: 'local' } }] : []
        },
    },

    watch: {
        newWebinar(val) {
            if (val) {
                this.selectedSpeakers = this.webinar.actors
            }
        },
    },

    methods: {
        saveReminder() {
            this.$validator.validateAll('reminderForm').then((valid) => {
                if (valid) {
                    this.loading = true

                    this.axios
                        .post(`/webinars/${this.webinar.id}/reminder`, { ...this.reminder })
                        .then((response) => {
                            this.$snotify.success(this.$t('event.store.success'))
                            this.reminders = response.data.reminders
                            this.dialogReminders = false
                        })
                        .catch((error) => {
                            this.loading = false
                            let errors = ''
                            forEach(error.response.data.errors, (value, key) => {
                                forEach(value, (e, k) => {
                                    errors += e
                                })
                            })
                            this.$snotify.error(errors, this.$t('actor.store.failure'))
                        })
                        .finally(() => this.loading = false)


                }
            })
        },

        checkSwitches(value, category) {
            if (!value) return

            switch (category) {
                case 'confirmation':
                    this.webinar.estimate.confirmation_form = true
                    this.webinar.estimate.ticket_sale = false
                    break

                case 'tickets':
                    this.webinar.estimate.confirmation_form = false
                    this.webinar.estimate.ticket_sale = true
                    break
            }

            if (!this.webinar.landing || !this.webinar.landing.which) {
                this.webinar.landing = { which: 3 }
            }
        },

        addQuestion() {
            if (!this.poll.questions) {
                this.poll.questions = []
            }
            this.poll.questions.push({
                id: null,
                type: 0,
                alternatives: [],
            })

            this.$forceUpdate()
        },

        removeQuestion(index) {
            this.poll.questions.splice(index, 1)

            this.$forceUpdate()
        },

        addAlternative(question) {
            if (!question.alternatives) {
                question.alternatives = []
            }
            question.alternatives.push({ id: null })

            this.$forceUpdate()
        },

        removeAlternative(question, index) {
            question.alternatives.splice(index, 1)

            this.$forceUpdate()
        },

        addTicket() {
            this.webinar.tickets.push({ quantity: 0, price: 0, protection: null })
        },
        removeTicket(index) {
            this.webinar.tickets.splice(index, 1)
        },

        sendMultipleEmails() {
            switch (this.mailings.category) {
                case 'confirmation':
                    this.axios
                        .post(`/webinars/${this.webinar.id}/confirmation-email`, { ...this.mailings, participants: this.selectedParticipants.map(p => p.id) })
                        .then((response) => {
                            this.$snotify.success(this.$t('event.confirmation.success'))
                            this.dialogEmailParticipants = false
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
                    break
            }
        },

        async selectMailing() {
            let category = 'confirmation'
            this.mailings.category = category

            switch (category) {
                case 'confirmation':
                    this.mailings.fromName = `${this.$auth.user().name} ${this.$auth.user().lastname}`
                    this.mailings.subject =
                        (this.webinar.confirmation_form ? 'Confirmación ' : 'Invitación ') + this.webinar.name

                    try {
                        const response = await this.axios.get(`/events/${this.webinar.id}/participants/confirmation`)
                        this.participants = response.data.participants

                        if (this.participants.length > 0) {
                            this.selectedParticipants = Object.assign([], this.participants)
                            this.dialogEmailParticipants = true
                        } else {
                            this.$snotify.error(this.$t('database.empty'))
                        }
                    } catch (e) {
                        this.$snotify.error(this.$t('general_error'))
                    }

                    this.loading = false
                    break
            }
        },

        syncWebinarActors() {
            this.loading = true
            this.axios
                .post(`webinars/${this.webinar.id}/actors`, { actors: this.selectedSpeakers.map((actor) => actor.id) })
                .then((response) => {
                    this.loading = false
                    this.$snotify.success(this.$t('actor.update.success'))
                    this.selectedSpeakers = response.data.actors
                    this.webinar.actors = this.selectedSpeakers
                    this.webinarStep += 1
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('actor.update.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        syncWebinarSponsors() {
            this.loading = true
            this.axios
                .post(`webinars/${this.webinar.id}/sponsors`, {
                    sponsors: this.selectedSponsors.map((sponsor) => sponsor.id),
                })
                .then((response) => {
                    this.loading = false
                    this.$snotify.success(this.$t('sponsor.update.success'))
                    this.selectedSponsors = response.data.sponsors
                    this.webinar.sponsors = this.selectedSponsors
                    this.webinarStep += 1
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('actor.update.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        syncWebinarPolls() {
            this.loading = true
            this.axios
                .post(`webinars/${this.webinar.id}/polls`, {
                    polls: this.selectedPolls.map((sponsor) => sponsor.id),
                })
                .then((response) => {
                    this.loading = false
                    this.$snotify.success(this.$t('poll.update.success'))
                    this.selectedPolls = response.data.polls
                    this.webinar.polls = this.selectedPolls
                    this.webinarStep += 1
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('poll.update.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        updateActor() {
            this.$validator.validateAll('actorForm').then((valid) => {
                if (valid) {
                    this.loading = true

                    if (this.actor.id) {
                        this.axios
                            .put(`/actors/${this.actor.id}`, { ...this.actor, links: [] })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('actor.update.success'))
                                this.actors = response.data.actors
                                this.actor = {}
                                this.newSpeakers = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('actor.update.failure'))
                            })
                    } else {
                        this.axios
                            .post('/actors', { ...this.actor, links: [] })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('actor.store.success'))
                                this.actors = response.data.actors
                                this.actor = {}
                                this.newSpeakers = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('actor.store.failure'))
                            })
                    }
                }
            })
        },

        updateSponsor() {
            this.$validator.validateAll('sponsorForm').then((valid) => {
                if (valid) {
                    this.loading = true

                    if (this.sponsor.id) {
                        this.axios
                            .put(`/sponsors/${this.sponsor.id}`, { ...this.sponsor, link: null })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('sponsor.update.success'))
                                this.sponsors = response.data.sponsors
                                this.sponsor = {}
                                this.newSponsors = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('sponsor.update.failure'))
                            })
                    } else {
                        this.axios
                            .post('/sponsors', { ...this.sponsor, links: [] })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('sponsor.store.success'))
                                this.sponsors = response.data.sponsors
                                this.sponsor = {}
                                this.newSponsors = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('sponsor.store.failure'))
                            })
                    }
                }
            })
        },

        updatePoll() {
            this.$validator.validateAll('pollsForm').then((valid) => {
                if (valid) {
                    this.loading = true

                    if (this.poll.id) {
                        this.axios
                            .put(`/polls/${this.poll.id}`, { ...this.poll })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('poll.update.success'))
                                this.polls = response.data.polls
                                this.newPolls = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('poll.update.failure'))
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    } else {
                        this.axios
                            .post('/polls', { ...this.poll })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('poll.store.success'))
                                this.polls = response.data.polls
                                this.newPolls = false
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('poll.store.failure'))
                            })

                            .finally(() => {
                                this.loading = false
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

        addField() {
            this.database.fields.push({
                type: 'string',
                required: false,
                in_form: false,
            })
        },

        openNewDatabase() {
            this.database = Object.assign({}, this.databaseTemplate)
            this.newDatabase = true
        },

        disableField(fieldName) {
            return ['name', 'lastname', 'email'].includes(fieldName)
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text)
            this.$snotify.success('Copiado!')
        },

        open(url) {
            window.open(url)
        },

        createDatabase() {
            this.$validator.validateAll('database').then((valid) => {
                if (valid) {
                    this.loading = true

                    this.axios
                        .post('/databases', this.database)
                        .then((response) => {
                            this.$snotify.success(this.$t('databse.store.success'))
                            this.databases = response.data.databases
                            this.selectedDatabase = this.databases.find((db) => db.id === response.data.database_id)
                            this.webinar.profile_id = this.selectedDatabase.profiles[0].id
                            this.newDatabaseName = null
                            this.newDatabase = false
                        })
                        .catch((error) => {
                            let errors = ''
                            forEach(error.response.data.errors, (value, key) => {
                                forEach(value, (e, k) => {
                                    errors += e
                                })
                            })
                            this.$snotify.error(errors, this.$t('databse.store.failure'))
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            })
        },

        allowedDates(val) {
            const [year, month, day] = val.split('-')
            const date = new Date()
            date.setYear(year)
            date.setMonth(month - 1)
            date.setDate(day)
            date.setHours(0, 0, 0, 0)

            const today = new Date()
            today.setHours(0, 0, 0, 0)

            return date >= today
        },

        clearWebinar() {
            this.webinar = Object.assign({}, this.webinarTemplate)
        },

        onUpdateFilesLogo(files) {
            if (files.length == 0) {
                this.webinar.logo_event = null
            }
        },
        onUpdateFilesBanner(files) {
            if (files.length == 0) {
                this.webinar.banner = null
            }
        },

        onSponsorUpdateFiles(files) {
            if (files.length == 0) {
                this.sponsor.logo = null
            }
        },

        saveOrUpdate() {
            this.$validator.validateAll('event').then((valid) => {
                if (valid) {
                    this.loading = true

                    if (this.webinar.id) {
                        this.axios
                            .patch(`/webinars/${this.webinar.id}/user`, {
                                ...this.webinar,
                                profile_id: this.selectedDatabase.profiles[0].id,
                            })
                            .then((response) => {
                                this.$snotify.success(this.$t('event.store.success'))
                                this.webinar = response.data.webinar
                            })
                            .catch((error) => {
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('event.store.failure'))
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    } else {
                        this.axios
                            .post('/webinars/user', {
                                ...this.webinar, profile_id: this.selectedDatabase.profiles[0].id,
                                primary: this.webinar.primary.hexa,
                                secondary: this.webinar.secondary.hexa,
                                terciary: this.webinar.terciary.hexa,
                                landing: { which: -1 },
                            })
                            .then((response) => {
                                this.$snotify.success(this.$t('event.store.success'))
                                this.webinar = response.data.webinar
                            })
                            .catch((error) => {
                                let errors = ''
                                forEach(error.response.data.errors, (value, key) => {
                                    forEach(value, (e, k) => {
                                        errors += e
                                    })
                                })
                                this.$snotify.error(errors, this.$t('event.store.failure'))
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    }
                }
            })
        },

        saveLanding() {
            if (this.webinar.landing) {
                this.loading = true

                const landing = this.webinar.landing.which ? this.webinar.landing : null

                this.axios
                    .patch('/webinars/user/landing', {
                        id: this.webinar.id,
                        landing,
                        confirmation_form: this.webinar.estimate.confirmation_form,
                        ticket_sale: this.webinar.estimate.ticket_sale,
                        webpay_accepted: this.webinar.webpay_accepted,
                        paypal_accepted: this.webinar.paypal_accepted,
                        personalize_tickets: this.webinar.personalize_tickets,
                        tickets: this.webinar.tickets,
                        primary: this.webinar.primary,
                        secondary: this.webinar.secondary,
                        terciary: this.webinar.terciary,
                    })
                    .then((response) => {
                        this.$snotify.success(this.$t('event.store.success'))
                        this.webinar = response.data.webinar

                        if (!this.webinar.landing) this.webinar.landing = { which: null }

                        this.webinarStep += 1
                    })
                    .catch((error) => {
                        let errors = ''
                        forEach(error.response.data.errors, (value, key) => {
                            forEach(value, (e, k) => {
                                errors += e
                            })
                        })
                        this.$snotify.error(errors, this.$t('event.store.failure'))
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        },

        sendParticipantEmail() {
            this.loading = true

            this.axios
                .post('/webinars/' + this.webinar.id + '/participantMail')
                .then((response) => {
                    this.$snotify.success(this.$t('event.store.success'))
                    // this.webinar = response.data.event
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('event.store.failure'))
                })
                .finally(() => {
                    this.loading = false
                })
        },

        sendSpeakerEmails() {
            this.loading = true

            this.axios
                .post(`/webinars/${this.webinar.id}/speakerMail`, {
                    emails: this.selectedSpeakers.map((actor) => actor.email),
                })
                .then((response) => {
                    this.$snotify.success(this.$t('mailings.success').capitalize())
                    // this.webinar = response.data.event
                })
                .catch((error) => {
                    let errors = ''
                    forEach(error.response.data.errors, (value, key) => {
                        forEach(value, (e, k) => {
                            errors += e
                        })
                    })
                    this.$snotify.error(errors, this.$t('mailings.failure').capitalize())
                })
                .finally(() => {
                    this.loading = false
                })
        },
    },

    created() {
        this.database = Object.assign({}, this.databaseTemplate)

        this.loading = true

        this.clearWebinar()
        let webinars = this.axios
            .get('/webinars')
            .then((response) => {
                this.webinars = response.data
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        let actors = this.axios
            .get('/actors')
            .then((response) => {
                this.actors = response.data.actors
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        let sponsors = this.axios
            .get('/sponsors')
            .then((response) => {
                this.sponsors = response.data.sponsors
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        let polls = this.axios
            .get('/polls')
            .then((response) => {
                this.polls = response.data.polls
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })

        let databases = this.axios.get('/databases').then((response) => {
            this.databases = response.data.databases
            if (this.databases.length > 0 && !this.webinar.profile) {
                this.selectedDatabase = this.databases[0]
                this.webinar.profile_id = this.selectedDatabase.profiles[0].id
            } else if (this.webinar.profile) {
                this.selectedDatabase = this.databases.find((database) => {
                    return database.id === this.webinar.profile.database_id
                })
            }
        })

        Promise.all([webinars, actors, sponsors, polls, databases])
            .finally(() => {
                this.loading = false
            })
            .finally(() => {
                this.loading = false
            })
    },
}
</script>

<style>

</style>
