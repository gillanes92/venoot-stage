<template>
    <div class="event">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <v-card>
                            <v-card-title>
                                <span class="headline">
                                    {{ $t('form.new').capitalize() + ' ' + $t('event.title') }}
                                </span>
                            </v-card-title>
                            <v-card-text>
                                <v-form ref="form" data-vv-scope="event" lazy-validation>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs4>
                                                <v-select
                                                    :disabled="!!event.id"
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required'"
                                                    data-vv-name="databases"
                                                    :items="databases"
                                                    item-text="name"
                                                    v-model.lazy="currentDatabase"
                                                    prepend-icon="mdi-database"
                                                    :label="$t('form.database').capitalize()"
                                                    @change="event.profile_id = currentDatabase.profiles[0].id"
                                                    return-object
                                                    required
                                                ></v-select>
                                            </v-flex>
                                            <v-flex xs6>
                                                <v-select
                                                    v-if="this.currentDatabase"
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required'"
                                                    data-vv-name="profiles"
                                                    :items="currentDatabase.profiles"
                                                    item-text="name"
                                                    item-value="id"
                                                    v-model.lazy="event.profile_id"
                                                    prepend-icon="mdi-chart-pie"
                                                    :label="$t('form.profiles').capitalize()"
                                                    required
                                                ></v-select>
                                            </v-flex>

                                            <v-flex xs5>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required|max:120'"
                                                    data-vv-name="name"
                                                    :error-messages="errors.collect('event.name')"
                                                    :counter="120"
                                                    required
                                                    prepend-icon="short_text"
                                                    v-model.lazy="event.name"
                                                    :label="$t('form.name').capitalize()"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs3 v-if="event.landing">
                                                <v-select
                                                    :items="categories"
                                                    :label="$t('form.category').capitalize()"
                                                    v-model.lazy="event.category"
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required'"
                                                ></v-select>
                                            </v-flex>
                                            <v-flex xs4>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required|email|max:60'"
                                                    data-vv-name="email"
                                                    :error-messages="errors.collect('event.email')"
                                                    :counter="60"
                                                    required
                                                    prepend-icon="mdi-at"
                                                    v-model.lazy="event.email"
                                                    :label="$t('form.mail').capitalize()"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 lg6 v-if="event.landing">
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="'required|max:60'"
                                                    data-vv-name="location"
                                                    :error-messages="errors.collect('event.location')"
                                                    :counter="60"
                                                    required
                                                    prepend-icon="mdi-city"
                                                    v-model.lazy="event.location"
                                                    :label="$t('form.location').capitalize()"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs4 lg2 v-if="event.landing">
                                                <v-menu
                                                    v-model.lazy="startDayMenu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                        }"
                                                    >
                                                        <v-text-field
                                                            v-validate="'required'"
                                                            data-vv-name="start_date"
                                                            :error-messages="errors.collect('event.start_date')"
                                                            v-model.lazy="event.start_date"
                                                            :label="$t('form.start_date').capitalize()"
                                                            prepend-icon="calendar_today"
                                                            readonly
                                                            v-on="on"
                                                            required
                                                            format="YYYY-MM-DD HH:mm"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="event.start_date"
                                                        no-title
                                                        scrollable
                                                        :locale="$i18n.locale"
                                                        @input="startDayMenu = false"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs2 lg1 v-if="event.landing">
                                                <v-menu
                                                    ref="startTimeMenu"
                                                    v-model.lazy="startTimeMenu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    :return-value.sync="event.start_time"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                        }"
                                                    >
                                                        <v-text-field
                                                            data-vv-validate-on="blur"
                                                            v-validate="'required'"
                                                            data-vv-name="start_time"
                                                            :error-messages="errors.collect('event.start_time')"
                                                            v-model.lazy="event.start_time"
                                                            :label="$t('form.start_time').capitalize()"
                                                            prepend-icon="mdi-clock"
                                                            readonly
                                                            v-on="on"
                                                            required
                                                        ></v-text-field>
                                                    </template>
                                                    <v-time-picker
                                                        format="24hr"
                                                        v-if="startTimeMenu"
                                                        v-model.lazy="event.start_time"
                                                        scrollable
                                                        :locale="$i18n.locale"
                                                        @click:minute="$refs.startTimeMenu.save(event.start_time)"
                                                    ></v-time-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs4 lg2 v-if="event.landing">
                                                <v-menu
                                                    v-model.lazy="endDateMenu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                        }"
                                                    >
                                                        <v-text-field
                                                            v-validate="'required'"
                                                            data-vv-name="end_date"
                                                            :error-messages="errors.collect('event.end_date')"
                                                            v-model.lazy="event.end_date"
                                                            :label="$t('form.end_date').capitalize()"
                                                            prepend-icon="calendar_today"
                                                            readonly
                                                            v-on="on"
                                                            required
                                                            format="YYYY-MM-DD HH:mm"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="event.end_date"
                                                        no-title
                                                        scrollable
                                                        :locale="$i18n.locale"
                                                        @input="endDateMenu = false"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs2 lg1 v-if="event.landing">
                                                <v-menu
                                                    ref="endTimeMenu"
                                                    v-model.lazy="endTimeMenu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    :return-value.sync="event.end_time"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                        }"
                                                    >
                                                        <v-text-field
                                                            data-vv-validate-on="blur"
                                                            v-validate="'required'"
                                                            data-vv-name="end_time"
                                                            :error-messages="errors.collect('event.end_time')"
                                                            v-model.lazy="event.end_time"
                                                            :label="$t('form.end_time').capitalize()"
                                                            prepend-icon="mdi-clock"
                                                            readonly
                                                            v-on="on"
                                                            required
                                                        ></v-text-field>
                                                    </template>
                                                    <v-time-picker
                                                        format="24hr"
                                                        v-if="endTimeMenu"
                                                        v-model.lazy="event.end_time"
                                                        scrollable
                                                        :locale="$i18n.locale"
                                                        @click:minute="$refs.endTimeMenu.save(event.end_time)"
                                                    ></v-time-picker>
                                                </v-menu>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-textarea
                                                    data-vv-validate-on="blur"
                                                    data-vv-name="description"
                                                    :error-messages="errors.collect('event.description')"
                                                    required
                                                    prepend-icon="notes"
                                                    v-model.lazy="event.description"
                                                    :label="$t('form.description').capitalize()"
                                                    type="text"
                                                    rows="5"
                                                ></v-textarea>
                                            </v-flex>
                                            <v-flex xs12>
                                                <!-- <v-expansion-panels :value="0">
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <h4
                                                                class="headline mb-0"
                                                            >{{ $t('event.images').capitalize() }}</h4>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content
                                                            eager
                                                >-->
                                                <v-container>
                                                    <v-layout wrap>
                                                        <v-flex xs9>
                                                            <v-card>
                                                                <v-card-title primary-title>
                                                                    <v-icon>image</v-icon>
                                                                    <h4 class="headline mb-0">
                                                                        {{ $t('form.banner').capitalize() }}
                                                                    </h4>
                                                                </v-card-title>
                                                                <v-card-text>
                                                                    <file-pond
                                                                        name="banner"
                                                                        :server="serverOptionsBanner"
                                                                        :label-idle="$t('form.upload.idle')"
                                                                        :files="banner"
                                                                        allow-multiple="false"
                                                                        accepted-file-types="image/jpeg, image/png"
                                                                        @updatefiles="onUpdateFilesBanner"
                                                                        required
                                                                        image-crop-aspect-ratio="19:9"
                                                                        :image-edit-instant-edit="true"
                                                                        :imageEditEditor="doka"
                                                                        max-file-size="1536KB"
                                                                        :label-max-file-size-exceeded="
                                                                            $t('file.uploadExcedeed')
                                                                        "
                                                                        :label-max-file-size="$t('file.uploadDetailed')"
                                                                    ></file-pond>
                                                                </v-card-text>
                                                            </v-card>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-card>
                                                                <v-card-title primary-title>
                                                                    <v-icon>image</v-icon>
                                                                    <h4 class="headline mb-0">
                                                                        {{ $t('form.logo').capitalize() }}
                                                                    </h4>
                                                                </v-card-title>
                                                                <v-card-text>
                                                                    <file-pond
                                                                        name="logo"
                                                                        :server="serverOptionsLogo"
                                                                        :label-idle="$t('form.upload.idle')"
                                                                        :files="logo"
                                                                        allow-multiple="false"
                                                                        accepted-file-types="image/jpeg, image/png"
                                                                        @updatefiles="onUpdateFilesLogo"
                                                                        required
                                                                        :crop-limit-to-image-bounds="true"
                                                                        :image-edit-instant-edit="true"
                                                                        :imageEditEditor="doka"
                                                                        max-file-size="1536KB"
                                                                        :label-max-file-size-exceeded="
                                                                            $t('file.uploadExcedeed')
                                                                        "
                                                                        :label-max-file-size="$t('file.uploadDetailed')"
                                                                    ></file-pond>
                                                                </v-card-text>
                                                            </v-card>
                                                        </v-flex>

                                                        <v-flex xs12>
                                                            <v-card>
                                                                <v-card-title primary-title>
                                                                    <v-icon>image</v-icon>
                                                                    <h4 class="headline mb-0">
                                                                        {{ $t('event.extra_images').capitalize() }}
                                                                    </h4>
                                                                </v-card-title>
                                                                <v-card-text class="filepond-grid">
                                                                    <file-pond
                                                                        name="image"
                                                                        :server="serverOptionsExtras"
                                                                        :label-idle="$t('form.upload.idle')"
                                                                        :files="extras"
                                                                        accepted-file-types="image/jpeg, image/png"
                                                                        @updatefiles="onUpdateFilesExtras"
                                                                        allowImageExifOrientation
                                                                        allow-multiple
                                                                        :max-files="3"
                                                                        :image-edit-instant-edit="true"
                                                                        :imageEditEditor="doka"
                                                                        max-file-size="1536KB"
                                                                        :label-max-file-size-exceeded="
                                                                            $t('file.uploadExcedeed')
                                                                        "
                                                                        :label-max-file-size="$t('file.uploadDetailed')"
                                                                    ></file-pond>
                                                                </v-card-text>
                                                            </v-card>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                                <!-- </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>-->
                                            </v-flex>
                                            <v-flex xs12 class="subtitle-1">
                                                {{ $t('ssnn.title') }}
                                            </v-flex>
                                            <v-flex sm12 lg6>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="{
                                                        url: {
                                                            require_protocol: true,
                                                        },
                                                    }"
                                                    data-vv-name="twitter"
                                                    :error-messages="errors.collect('event.twitter')"
                                                    prepend-icon="mdi-twitter"
                                                    v-model.lazy="event.twitter"
                                                    label="Twitter"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex sm12 lg6>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="{
                                                        url: {
                                                            require_protocol: true,
                                                        },
                                                    }"
                                                    data-vv-name="facebook"
                                                    :error-messages="errors.collect('event.facebook')"
                                                    prepend-icon="mdi-facebook"
                                                    v-model.lazy="event.facebook"
                                                    label="Facebook"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex sm12 lg6>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="{
                                                        url: {
                                                            require_protocol: true,
                                                        },
                                                    }"
                                                    data-vv-name="instagram"
                                                    :error-messages="errors.collect('event.instagram')"
                                                    prepend-icon="mdi-instagram"
                                                    v-model.lazy="event.instagram"
                                                    label="Instagram"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex sm12 lg6>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="{
                                                        url: {
                                                            require_protocol: true,
                                                        },
                                                    }"
                                                    data-vv-name="linkedin"
                                                    :error-messages="errors.collect('event.linkedin')"
                                                    prepend-icon="mdi-linkedin"
                                                    v-model.lazy="event.linkedin"
                                                    label="LinkedIn"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex sm12 lg6>
                                                <v-text-field
                                                    data-vv-validate-on="blur"
                                                    v-validate="{
                                                        url: {
                                                            require_protocol: true,
                                                        },
                                                    }"
                                                    data-vv-name="web"
                                                    :error-messages="errors.collect('event.web')"
                                                    prepend-icon="mdi-spider-web"
                                                    v-model.lazy="event.web"
                                                    label="Web"
                                                    type="text"
                                                ></v-text-field>
                                            </v-flex>

                                            <template v-if="event.landing">
                                                <v-flex xs12>
                                                    <!-- <v-expansion-panels>
                                                        <v-expansion-panel
                                                            :disabled="loading && $browserDetect.isChrome"
                                                        >
                                                            <v-expansion-panel-header>
                                                                <span
                                                                    class="headline"
                                                                >{{ $t('form.has_location').capitalize() }}</span>
                                                            </v-expansion-panel-header>
                                                            <v-expansion-panel-content
                                                                eager
                                                    >-->
                                                    <!-- Speakers -->
                                                    <v-layout row wrap>
                                                        <!-- Locations -->
                                                        <v-flex xs12>
                                                            <span class="headline">
                                                                {{ $t('form.has_location').capitalize() }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-switch
                                                                v-model.lazy="event.landing.has_location"
                                                                :label="$t('show_section_landing').capitalize()"
                                                                required
                                                            ></v-switch>
                                                        </v-flex>

                                                        <v-flex xs4>
                                                            <v-text-field
                                                                :disabled="!event.landing.has_location"
                                                                data-vv-validate-on="blur"
                                                                v-validate="'max:120'"
                                                                data-vv-as="location_title"
                                                                data-vv-name="location_title"
                                                                :error-messages="errors.collect(`event.location_title`)"
                                                                :counter="120"
                                                                prepend-icon="short_text"
                                                                v-model.lazy="event.landing.location_title"
                                                                :label="$t('form.location_title').capitalize()"
                                                            ></v-text-field>
                                                        </v-flex>

                                                        <v-flex xs6>
                                                            <v-textarea
                                                                :disabled="!event.landing.has_location"
                                                                data-vv-validate-on="blur"
                                                                v-validate="'max:180'"
                                                                :data-vv-as="$t('form.location_description')"
                                                                data-vv-name="location_description"
                                                                :error-messages="
                                                                    errors.collect(`event.location_description`)
                                                                "
                                                                :counter="180"
                                                                prepend-icon="mdi-text-subject"
                                                                v-model.lazy="event.landing.location_description"
                                                                :label="$t('form.location_description').capitalize()"
                                                                rows="3"
                                                            ></v-textarea>
                                                        </v-flex>

                                                        <v-flex xs12>
                                                            <v-card>
                                                                <v-card-title primary-title>
                                                                    <v-icon>image</v-icon>
                                                                    <h4 class="headline mb-0">
                                                                        {{ $t('form.location_photo').capitalize() }}
                                                                    </h4>
                                                                </v-card-title>
                                                                <v-card-text>
                                                                    <file-pond
                                                                        name="location"
                                                                        :server="serverOptionsLocation"
                                                                        :label-idle="$t('form.upload.idle')"
                                                                        :files="location"
                                                                        allow-multiple="false"
                                                                        accepted-file-types="image/jpeg, image/png"
                                                                        @updatefiles="onUpdateFilesLocation"
                                                                        required
                                                                        image-crop-aspect-ratio="9:5"
                                                                        :image-edit-instant-edit="true"
                                                                        :imageEditEditor="doka"
                                                                        max-file-size="1536KB"
                                                                        :label-max-file-size-exceeded="
                                                                            $t('file.uploadExcedeed')
                                                                        "
                                                                        :label-max-file-size="$t('file.uploadDetailed')"
                                                                    ></file-pond>
                                                                </v-card-text>
                                                            </v-card>
                                                        </v-flex>
                                                    </v-layout>
                                                    <!-- </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>-->
                                                </v-flex>

                                                <v-flex xs12>
                                                    <!-- <v-expansion-panels>
                                                        <v-expansion-panel
                                                            :disabled="loading && $browserDetect.isChrome"
                                                        >
                                                            <v-expansion-panel-header>
                                                                <span
                                                                    class="headline"
                                                                >{{ $t('landing.images').capitalize() }}</span>
                                                            </v-expansion-panel-header>
                                                            <v-expansion-panel-content
                                                                eager
                                                    >-->
                                                    <v-layout row wrap>
                                                        <!-- Images -->
                                                        <v-flex xs12>
                                                            <span class="headline">
                                                                {{ $t('landing.images').capitalize() }}
                                                            </span>
                                                        </v-flex>
                                                        <v-flex xs3 column>
                                                            <v-text-field
                                                                data-vv-validate-on="blur"
                                                                v-validate="'max:120'"
                                                                data-vv-as="images_title"
                                                                data-vv-name="images_title"
                                                                :error-messages="errors.collect(`event.images_title`)"
                                                                :counter="120"
                                                                prepend-icon="short_text"
                                                                v-model.lazy="event.landing.images_title"
                                                                :label="$t('form.images_title').capitalize()"
                                                            ></v-text-field>
                                                            <v-switch
                                                                v-model="event.landing.loop"
                                                                :label="$t('landing.loop').capitalize()"
                                                                required
                                                            ></v-switch>
                                                        </v-flex>

                                                        <v-flex xs8 offset-xs1>
                                                            <v-card>
                                                                <v-card-title>
                                                                    {{ $t('landing.images') }}
                                                                </v-card-title>
                                                                <v-card-text class="filepond-grid">
                                                                    <file-pond
                                                                        name="image"
                                                                        :server="serverOptionsLandingImages"
                                                                        :label-idle="$t('form.upload.idle')"
                                                                        :files="images"
                                                                        allow-multiple="true"
                                                                        accepted-file-types="image/jpeg, image/png"
                                                                        @updatefiles="onUpdateFilesLanding"
                                                                        required
                                                                        image-crop-aspect-ratio="4:3"
                                                                        :image-edit-instant-edit="true"
                                                                        :imageEditEditor="doka"
                                                                        max-file-size="1536KB"
                                                                        :label-max-file-size-exceeded="
                                                                            $t('file.uploadExcedeed')
                                                                        "
                                                                        :label-max-file-size="$t('file.uploadDetailed')"
                                                                    ></file-pond>
                                                                </v-card-text>
                                                            </v-card>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                            </template>

                                            <v-flex xs12>
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline mb-0 flex-grow-0">
                                                                {{ $t('setting.title').capitalize() }}
                                                            </span>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-container grid-list-md>
                                                                <v-layout wrap>
                                                                    <v-flex xs12 class="subtitle-1">
                                                                        Configuración Correos Automaticos
                                                                    </v-flex>
                                                                    <v-flex xs6>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            data-vv-name="from_name"
                                                                            :error-messages="errors.collect('event.from_name')"
                                                                            prepend-icon="mdi-text-short"
                                                                            v-model.lazy="event.from_name"
                                                                            :label="$t('form.name').capitalize()"
                                                                            type="text"
                                                                        ></v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs6>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'email'"
                                                                            data-vv-name="from_email"
                                                                            :error-messages="errors.collect('event.from_email')"
                                                                            prepend-icon="mdi-at"
                                                                            v-model.lazy="event.from_email"
                                                                            :label="$t('form.mail').capitalize()"
                                                                            type="text"
                                                                        ></v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            data-vv-name="from_subject"
                                                                            :error-messages="errors.collect(`event.from_subject`)"
                                                                            prepend-icon="mdi-text"
                                                                            v-model.lazy="event.from_subject"
                                                                            label="Asunto"
                                                                        ></v-text-field>
                                                                    </v-flex>
                                                                    <v-flex xs12>
                                                                        <v-alert type="info">
                                                                            {{ $t('info.invitees') }}
                                                                        </v-alert>
                                                                    </v-flex>
                                                                    <v-flex xs2 v-if="event.confirmation_form">
                                                                        <v-switch
                                                                            v-model="event.has_confirmation"
                                                                            :label="
                                                                                $t(
                                                                                    'event.has_confirmation'
                                                                                ).capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>
                                                                    <v-flex
                                                                        xs10
                                                                        v-if="event.confirmation_form"
                                                                    >
                                                                        <v-select
                                                                            :disabled="!event.has_confirmation"
                                                                            :items="database_keys"
                                                                            v-model="event.confirmation_key"
                                                                            :label="
                                                                                $t(
                                                                                    'event.confirmation_key'
                                                                                ).capitalize()
                                                                            "
                                                                        ></v-select>
                                                                    </v-flex>
                                                                    <v-flex xs6 v-if="event.confirmation_form">
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'integer|min:0'"
                                                                            data-vv-name="quota"
                                                                            :error-messages="
                                                                                errors.collect('event.quota')
                                                                            "
                                                                            prepend-icon="mdi-seat"
                                                                            v-model.lazy="event.quota"
                                                                            :label="$t('form.quota')"
                                                                            type="number"
                                                                            clearable
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs4 v-if="event.confirmation_form">
                                                                        <v-switch
                                                                            v-model.lazy="event.over_quota"
                                                                            :label="
                                                                                $t('allow').capitalize() +
                                                                                ' ' +
                                                                                $t('event.over_quota')
                                                                            "
                                                                            prepend-icon="mdi-account-arrow-right"
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs2>
                                                                        <v-select
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'required|integer|min_value:0'"
                                                                            data-vv-name="invitees"
                                                                            :error-messages="
                                                                                errors.collect('event.invitees')
                                                                            "
                                                                            v-model.lazy="event.invitees"
                                                                            :items="[0, 1, 2, 3, 4, 5]"
                                                                            :label="$t('form.invitees').capitalize()"
                                                                            prepend-icon="mdi-account-multiple-plus"
                                                                            required
                                                                            type="number"
                                                                        ></v-select>
                                                                    </v-flex>

                                                                    <v-flex
                                                                        xs12
                                                                        v-if="event.confirmation_form"
                                                                    >
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:180'"
                                                                            :counter="180"
                                                                            :data-vv-name="$t('event.apology')"
                                                                            :error-messages="
                                                                                errors.collect('event.apology')
                                                                            "
                                                                            prepend-icon="mdi-account-alert"
                                                                            v-model.lazy="event.apology"
                                                                            :label="$t('event.apology').capitalize()"
                                                                            type="text"
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12>
                                                                        <v-card>
                                                                            <v-card-title primary-title
                                                                                >Code</v-card-title
                                                                            >

                                                                            <v-card-text>
                                                                                <v-sheet color="grey lighten-2">
                                                                                    <v-container grid-list-md>
                                                                                        <v-layout wrap>
                                                                                            <v-flex
                                                                                                xs12
                                                                                                v-if="
                                                                                                    event.ticket_sale
                                                                                                "
                                                                                            >
                                                                                                <v-textarea
                                                                                                    readonly
                                                                                                    label="Carro / Cart - Code"
                                                                                                    rows="17"
                                                                                                    :value="cartCode"
                                                                                                ></v-textarea>
                                                                                            </v-flex>
                                                                                            <v-flex
                                                                                                xs12
                                                                                                v-if="
                                                                                                    event.polls_quantity >
                                                                                                    0
                                                                                                "
                                                                                            >
                                                                                                <v-textarea
                                                                                                    readonly
                                                                                                    label="Encuesta / Poll - Code"
                                                                                                    rows="17"
                                                                                                    :value="pollCode"
                                                                                                ></v-textarea>
                                                                                            </v-flex>
                                                                                        </v-layout>
                                                                                    </v-container>
                                                                                </v-sheet>
                                                                            </v-card-text>
                                                                        </v-card>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-container>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <template v-if="event.landing">
                                                <v-flex xs12>
                                                    <v-expansion-panels>
                                                        <v-expansion-panel>
                                                            <v-expansion-panel-header>
                                                                <span class="headline mr-2 flex-grow-0">
                                                                    {{ $t('landing.title').capitalize() }}
                                                                </span>
                                                            </v-expansion-panel-header>
                                                            <v-expansion-panel-content>
                                                                <v-container grid-list-md>
                                                                    <v-layout wrap>
                                                                        <v-flex xs12>
                                                                            <v-alert type="info">
                                                                                {{ $t('info.secondary_titles') }}
                                                                            </v-alert>
                                                                        </v-flex>

                                                                        <v-flex xs4>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:40'"
                                                                                data-vv-as="highlight"
                                                                                data-vv-name="highlight"
                                                                                :error-messages="
                                                                                    errors.collect(`event.highlight`)
                                                                                "
                                                                                :counter="40"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.landing.highlight"
                                                                                :label="
                                                                                    $t('form.highlight').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs4>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:120'"
                                                                                data-vv-as="second_title"
                                                                                data-vv-name="second_title"
                                                                                :error-messages="
                                                                                    errors.collect(`event.second_title`)
                                                                                "
                                                                                :counter="120"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="
                                                                                    event.landing.second_title
                                                                                "
                                                                                :label="
                                                                                    $t('form.second_title').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs4>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:190'"
                                                                                data-vv-as="video"
                                                                                data-vv-name="video"
                                                                                :error-messages="
                                                                                    errors.collect(`event.video`)
                                                                                "
                                                                                :counter="190"
                                                                                prepend-icon="video_library"
                                                                                v-model.lazy="event.landing.video_url"
                                                                                label="Video"
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                v-model.lazy="event.landing.has_date"
                                                                                :label="
                                                                                    $t('form.has_date').capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                v-model.lazy="
                                                                                    event.landing.has_add_to_calendar
                                                                                "
                                                                                :label="
                                                                                    $t(
                                                                                        'form.has_add_to_calendar'
                                                                                    ).capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <v-flex xs8></v-flex>

                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                v-model.lazy="event.secure_video"
                                                                                :label="
                                                                                    $t('form.secure_video').capitalize()
                                                                                "
                                                                                required
                                                                                @change="
                                                                                    event.secure_video_extras =
                                                                                        event.secure_video_extras &&
                                                                                        event.secure_video
                                                                                    event.shared_chat =
                                                                                        event.shared_chat &&
                                                                                        event.secure_video
                                                                                "
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-select
                                                                                :disabled="!event.secure_video"
                                                                                :items="['iframe', 'jitsi']"
                                                                                v-model.lazy="event.video_category"
                                                                                :label="
                                                                                    $t(
                                                                                        'form.video_category'
                                                                                    ).capitalize()
                                                                                "
                                                                            ></v-select>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                :disabled="!event.secure_video"
                                                                                v-model.lazy="event.secure_video_extras"
                                                                                :label="
                                                                                    $t(
                                                                                        'form.secure_video_extras'
                                                                                    ).capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                :disabled="!event.secure_video_extras"
                                                                                v-model.lazy="event.shared_chat"
                                                                                :label="
                                                                                    $t('form.shared_chat').capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                          <v-flex xs2>
                                                                            <v-switch
                                                                                :disabled="!event.secure_video_extras"
                                                                                v-model.lazy="event.one_to_one_chat"
                                                                                :label="
                                                                                    $t('form.one_to_one_chat').capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                          <v-flex xs2>
                                                                            <v-switch
                                                                                :disabled="!event.secure_video_extras"
                                                                                v-model.lazy="event.speaker_chat"
                                                                                :label="
                                                                                    $t('form.speaker_chat').capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <!-- Descripcion -->
                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                v-model.lazy="
                                                                                    event.landing.has_description
                                                                                "
                                                                                :label="
                                                                                    $t(
                                                                                        'form.has_description'
                                                                                    ).capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>
                                                                        <v-flex xs4>
                                                                            <v-text-field
                                                                                :disabled="
                                                                                    !event.landing.has_description
                                                                                "
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:120'"
                                                                                data-vv-as="description_title"
                                                                                data-vv-name="description_title"
                                                                                :error-messages="
                                                                                    errors.collect(
                                                                                        `event.description_title`
                                                                                    )
                                                                                "
                                                                                :counter="120"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="
                                                                                    event.landing.description_title
                                                                                "
                                                                                :label="
                                                                                    $t(
                                                                                        'form.description_title'
                                                                                    ).capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-text-field
                                                                                :disabled="
                                                                                    !event.landing.has_description
                                                                                "
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:40'"
                                                                                data-vv-as="where_title"
                                                                                data-vv-name="where_title"
                                                                                :error-messages="
                                                                                    errors.collect(`event.where_title`)
                                                                                "
                                                                                :counter="40"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.landing.where_title"
                                                                                :label="
                                                                                    $t('form.where_title').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:40'"
                                                                                :disabled="
                                                                                    !event.landing.has_description
                                                                                "
                                                                                data-vv-as="when_title"
                                                                                data-vv-name="when_title"
                                                                                :error-messages="
                                                                                    errors.collect(`event.when_title`)
                                                                                "
                                                                                :counter="40"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.landing.when_title"
                                                                                :label="
                                                                                    $t('form.when_title').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex xs2>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:40'"
                                                                                :disabled="
                                                                                    !event.landing.has_description
                                                                                "
                                                                                data-vv-as="hour_title"
                                                                                data-vv-name="hour_title"
                                                                                :error-messages="
                                                                                    errors.collect(`event.hour_title`)
                                                                                "
                                                                                :counter="40"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.landing.hour_title"
                                                                                :label="
                                                                                    $t('form.hour_title').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <!-- Description location --->
                                                                        <v-flex xs12>
                                                                            <v-textarea
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'required'"
                                                                                data-vv-name="description"
                                                                                :error-messages="
                                                                                    errors.collect('event.description')
                                                                                "
                                                                                required
                                                                                prepend-icon="notes"
                                                                                v-model.lazy="event.description"
                                                                                :label="
                                                                                    $t('form.description').capitalize()
                                                                                "
                                                                                type="text"
                                                                                rows="5"
                                                                            ></v-textarea>
                                                                        </v-flex>

                                                                        <br />
                                                                        <br />

                                                                        <!-- Confirm -->
                                                                        <v-flex
                                                                            xs3
                                                                            v-if="event.confirmation_form"
                                                                        >
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:120'"
                                                                                data-vv-as="confirm_title"
                                                                                data-vv-name="confirm_title"
                                                                                :error-messages="
                                                                                    errors.collect(
                                                                                        `event.confirm_title`
                                                                                    )
                                                                                "
                                                                                :counter="120"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="
                                                                                    event.landing.confirm_title
                                                                                "
                                                                                :label="
                                                                                    $t(
                                                                                        'form.confirm_title'
                                                                                    ).capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <v-flex
                                                                            xs3
                                                                            v-if="event.confirmation_form"
                                                                        >
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:120'"
                                                                                data-vv-as="confirm_subtitle"
                                                                                data-vv-name="confirm_subtitle"
                                                                                :error-messages="
                                                                                    errors.collect(
                                                                                        `event.confirm_subtitle`
                                                                                    )
                                                                                "
                                                                                :counter="120"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="
                                                                                    event.landing.confirm_subtitle
                                                                                "
                                                                                :label="
                                                                                    $t(
                                                                                        'form.confirm_subtitle'
                                                                                    ).capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                        <!-- Redes Sociales -->
                                                                        <v-flex xs2>
                                                                            <v-switch
                                                                                v-model.lazy="event.landing.has_ssnn"
                                                                                :label="
                                                                                    $t('form.has_ssnn').capitalize()
                                                                                "
                                                                                required
                                                                            ></v-switch>
                                                                        </v-flex>

                                                                        <v-flex xs4>
                                                                            <v-text-field
                                                                                :disabled="!event.landing.has_ssnn"
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'max:120'"
                                                                                data-vv-as="ssnn_title"
                                                                                data-vv-name="ssnn_title"
                                                                                :error-messages="
                                                                                    errors.collect(`event.ssnn_title`)
                                                                                "
                                                                                :counter="120"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.landing.ssnn_title"
                                                                                :label="
                                                                                    $t('form.ssnn_title').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>

                                                                         <v-flex xs12>
                                                                            <v-text-field
                                                                                data-vv-validate-on="blur"
                                                                                v-validate="'alpha_dash|max:120'"
                                                                                data-vv-as="pretty_url"
                                                                                data-vv-name="pretty_url"
                                                                                :error-messages="
                                                                                    errors.collect(`event.pretty_url`)
                                                                                "
                                                                                :counter="180"
                                                                                prepend-icon="short_text"
                                                                                v-model.lazy="event.pretty_url"
                                                                                :label="
                                                                                    $t('event.pretty_url').capitalize()
                                                                                "
                                                                            ></v-text-field>
                                                                        </v-flex>
                                                                    </v-layout>
                                                                </v-container>
                                                            </v-expansion-panel-content>
                                                        </v-expansion-panel>
                                                    </v-expansion-panels>
                                                </v-flex>
                                            </template>

                                            <v-flex xs12 v-if="event.landing">
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">
                                                                {{ $t('actor.titlePlural').capitalize() }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="showActorsUpload"
                                                                class="mr-4"
                                                            >
                                                                <v-icon small dark>mdi-file-excel</v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="dialogActor = true"
                                                                class="mr-4"
                                                            >
                                                                <v-icon small dark>add</v-icon>
                                                            </v-btn>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <!-- Speakers -->
                                                            <v-layout row wrap>
                                                                <v-flex xs2>
                                                                    <v-switch
                                                                        v-model.lazy="event.landing.has_speakers"
                                                                        :label="$t('show_section_landing').capitalize()"
                                                                        required
                                                                    ></v-switch>
                                                                </v-flex>

                                                                <v-flex xs2>
                                                                    <v-switch
                                                                        v-model.lazy="event.landing.show_speaker_title"
                                                                        :label="
                                                                            $t(
                                                                                'landing.show_sponsor_title'
                                                                            ).capitalize()
                                                                        "
                                                                        required
                                                                    ></v-switch>
                                                                </v-flex>

                                                                <v-flex xs8>
                                                                    <v-text-field
                                                                        :disabled="!event.landing.has_speakers"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'max:120'"
                                                                        data-vv-as="speakers_title"
                                                                        data-vv-name="speakers_title"
                                                                        :error-messages="
                                                                            errors.collect(`event.speakers_title`)
                                                                        "
                                                                        :counter="120"
                                                                        prepend-icon="short_text"
                                                                        v-model.lazy="event.landing.speakers_title"
                                                                        :label="$t('form.speakers_title').capitalize()"
                                                                    ></v-text-field>
                                                                </v-flex>

                                                                <v-flex xs12>
                                                                    <v-combobox
                                                                        v-model.lazy="event.actors"
                                                                        :items="actors"
                                                                        item-value="id"
                                                                        :label="$t('actor.titlePlural').capitalize()"
                                                                        prepend-icon="record_voice_over"
                                                                        multiple
                                                                        chips
                                                                    >
                                                                        <template slot="selection" slot-scope="data">
                                                                            <v-chip>
                                                                                {{ data.item.name }}
                                                                                {{ data.item.lastname }}
                                                                            </v-chip>
                                                                        </template>
                                                                        <template slot="item" slot-scope="data">
                                                                            {{ data.item.name }}
                                                                            {{ data.item.lastname }}
                                                                        </template>
                                                                    </v-combobox>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <v-flex xs12 v-if="event.landing">
                                                <v-expansion-panels v-model="activitiesPanel">
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">
                                                                {{ $t('form.activities').capitalize() }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="showActivitiesUpload"
                                                                class="mr-4"
                                                            >
                                                                <v-icon small dark>mdi-file-excel</v-icon>
                                                            </v-btn>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="newActivity"
                                                                class="mr-4"
                                                            >
                                                                <v-icon small dark>add</v-icon>
                                                            </v-btn>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-layout wrap>
                                                                <template v-if="event.estimate_landing">
                                                                    <!-- Activities -->
                                                                    <v-flex xs3>
                                                                        <v-switch
                                                                            v-model.lazy="event.landing.has_activities"
                                                                            :label="
                                                                                $t('show_section_landing').capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs8>
                                                                        <v-text-field
                                                                            :disabled="!event.landing.has_activities"
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            data-vv-as="activities_title"
                                                                            data-vv-name="activities_title"
                                                                            :error-messages="
                                                                                errors.collect(`event.activities_title`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="
                                                                                event.landing.activities_title
                                                                            "
                                                                            :label="
                                                                                $t('form.activities_title').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>
                                                                </template>
                                                                <v-flex xs12>
                                                                    <v-expansion-panels dark multiple>
                                                                        <v-expansion-panel
                                                                            v-for="(activity,
                                                                            index) in event.activities"
                                                                            :key="`expansion-panel-${index}`"
                                                                        >
                                                                            <v-expansion-panel-header>
                                                                                <span>
                                                                                    {{
                                                                                        $t(
                                                                                            'activity.title'
                                                                                        ).toUpperCase() +
                                                                                        ' ' +
                                                                                        (index + 1)
                                                                                    }}
                                                                                    - {{ activity.name }}
                                                                                </span>
                                                                                <v-spacer></v-spacer>
                                                                                <v-btn
                                                                                    mr-2
                                                                                    color="error"
                                                                                    @click="deleteActivity(index)"
                                                                                    width="100"
                                                                                >
                                                                                    {{ $t('delete') }}
                                                                                </v-btn>
                                                                            </v-expansion-panel-header>
                                                                            <v-expansion-panel-content>
                                                                                <v-container grid-list-md>
                                                                                    <v-layout wrap>
                                                                                        <v-flex xs3>
                                                                                            <v-text-field
                                                                                                data-vv-validate-on="blur"
                                                                                                v-validate="
                                                                                                    'required|max:200'
                                                                                                "
                                                                                                data-vv-as="name"
                                                                                                :data-vv-name="`name${index}`"
                                                                                                :error-messages="
                                                                                                    errors.collect(
                                                                                                        `event.name${index}`
                                                                                                    )
                                                                                                "
                                                                                                :counter="200"
                                                                                                required
                                                                                                prepend-icon="short_text"
                                                                                                v-model.lazy="
                                                                                                    activity.name
                                                                                                "
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'form.name'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                            ></v-text-field>
                                                                                        </v-flex>
                                                                                        <v-flex xs3>
                                                                                            <v-text-field
                                                                                                data-vv-validate-on="blur"
                                                                                                v-validate="
                                                                                                    'required|max:60'
                                                                                                "
                                                                                                :data-vv-name="`location${index}`"
                                                                                                data-vv-as="location"
                                                                                                :error-messages="
                                                                                                    errors.collect(
                                                                                                        `event.location${index}`
                                                                                                    )
                                                                                                "
                                                                                                :counter="60"
                                                                                                required
                                                                                                prepend-icon="my_location"
                                                                                                v-model.lazy="
                                                                                                    activity.location
                                                                                                "
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'form.location'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                            ></v-text-field>
                                                                                        </v-flex>
                                                                                        <v-flex xs2>
                                                                                            <v-menu
                                                                                                v-model.lazy="
                                                                                                    menu[index]
                                                                                                "
                                                                                                :close-on-content-click="
                                                                                                    false
                                                                                                "
                                                                                                :nudge-right="40"
                                                                                                transition="scale-transition"
                                                                                                offset-y
                                                                                                min-width="290px"
                                                                                            >
                                                                                                <template
                                                                                                    v-slot:activator="{
                                                                                                        on,
                                                                                                    }"
                                                                                                >
                                                                                                    <v-text-field
                                                                                                        v-model="
                                                                                                            activity.date
                                                                                                        "
                                                                                                        :label="
                                                                                                            $t(
                                                                                                                'form.date'
                                                                                                            ).capitalize()
                                                                                                        "
                                                                                                        v-validate="
                                                                                                            'required'
                                                                                                        "
                                                                                                        :data-vv-as="
                                                                                                            $t(
                                                                                                                'form.date'
                                                                                                            ).capitalize()
                                                                                                        "
                                                                                                        :data-vv-name="`date${index}`"
                                                                                                        :error-messages="
                                                                                                            errors.collect(
                                                                                                                `event.date${index}`
                                                                                                            )
                                                                                                        "
                                                                                                        prepend-icon="calendar_today"
                                                                                                        required
                                                                                                        readonly
                                                                                                        v-on="on"
                                                                                                    ></v-text-field>
                                                                                                </template>
                                                                                                <v-date-picker
                                                                                                    v-model="
                                                                                                        activity.date
                                                                                                    "
                                                                                                    no-title
                                                                                                    scrollable
                                                                                                    :locale="
                                                                                                        $i18n.locale
                                                                                                    "
                                                                                                    color="success"
                                                                                                    @input="
                                                                                                        menu[
                                                                                                            index
                                                                                                        ] = false
                                                                                                    "
                                                                                                ></v-date-picker>
                                                                                            </v-menu>
                                                                                        </v-flex>
                                                                                        <v-flex xs2>
                                                                                            <v-menu
                                                                                                :ref="`startTimeActivityRef-${index}`"
                                                                                                v-model="
                                                                                                    startTimeActivityMenu[
                                                                                                        index
                                                                                                    ]
                                                                                                "
                                                                                                :close-on-content-click="
                                                                                                    false
                                                                                                "
                                                                                                :nudge-right="40"
                                                                                                :return-value.sync="
                                                                                                    activity.start_time
                                                                                                "
                                                                                                transition="scale-transition"
                                                                                                offset-y
                                                                                                max-width="290px"
                                                                                                min-width="290px"
                                                                                            >
                                                                                                <template
                                                                                                    v-slot:activator="{
                                                                                                        on, attrs
                                                                                                    }"
                                                                                                >
                                                                                                    <v-text-field
                                                                                                        data-vv-name="start_time"
                                                                                                        v-model="
                                                                                                            activity.start_time
                                                                                                        "
                                                                                                        :error-messages="
                                                                                                            errors.collect(
                                                                                                                `event.start_time${index}`
                                                                                                            )
                                                                                                        "
                                                                                                        :label="
                                                                                                            $t(
                                                                                                                'form.start_time'
                                                                                                            ).capitalize()
                                                                                                        "
                                                                                                        prepend-icon="mdi-clock"
                                                                                                        readonly
                                                                                                        v-bind="attrs"
                                                                                                        v-on="on"
                                                                                                    ></v-text-field>
                                                                                                </template>
                                                                                                <v-time-picker
                                                                                                    format="24hr"
                                                                                                    v-if="
                                                                                                        startTimeActivityMenu[
                                                                                                            index
                                                                                                        ]
                                                                                                    "
                                                                                                    v-model="
                                                                                                        activity.start_time
                                                                                                    "
                                                                                                    scrollable
                                                                                                    :locale="
                                                                                                        $i18n.locale
                                                                                                    "
                                                                                                    @click:minute="
                                                                                                        $refs[`startTimeActivityRef-${index}`][0].save(
                                                                                                            activity.start_time
                                                                                                        )
                                                                                                    "
                                                                                                    :max="
                                                                                                        activity.end_time
                                                                                                    "
                                                                                                ></v-time-picker>
                                                                                            </v-menu>
                                                                                        </v-flex>
                                                                                        <v-flex xs2>
                                                                                            <v-menu
                                                                                                :ref="`endTimeActivityRef-${index}`"
                                                                                                v-model.lazy="
                                                                                                    endTimeActivityMenu[
                                                                                                        activity.id
                                                                                                    ]
                                                                                                "
                                                                                                :close-on-content-click="
                                                                                                    false
                                                                                                "
                                                                                                :nudge-right="40"
                                                                                                :return-value.sync="
                                                                                                    activity.end_time
                                                                                                "
                                                                                                transition="scale-transition"
                                                                                                offset-y
                                                                                                max-width="290px"
                                                                                                min-width="290px"
                                                                                            >
                                                                                                <template
                                                                                                    v-slot:activator="{
                                                                                                        on,
                                                                                                    }"
                                                                                                >
                                                                                                    <v-text-field
                                                                                                        v-model.lazy="
                                                                                                            activity.end_time
                                                                                                        "
                                                                                                        :error-messages="
                                                                                                            errors.collect(
                                                                                                                `event.end_time${index}`
                                                                                                            )
                                                                                                        "
                                                                                                        :label="
                                                                                                            $t(
                                                                                                                'form.end_time'
                                                                                                            ).capitalize()
                                                                                                        "
                                                                                                        prepend-icon="mdi-clock"
                                                                                                        readonly
                                                                                                        v-on="on"
                                                                                                    ></v-text-field>
                                                                                                </template>
                                                                                                <v-time-picker
                                                                                                    format="24hr"
                                                                                                    v-if="
                                                                                                        endTimeActivityMenu[
                                                                                                            activity.id
                                                                                                        ]
                                                                                                    "
                                                                                                    v-model.lazy="
                                                                                                        activity.end_time
                                                                                                    "
                                                                                                    scrollable
                                                                                                    :locale="
                                                                                                        $i18n.locale
                                                                                                    "
                                                                                                    @click:minute="
                                                                                                         $refs[`endTimeActivityRef-${index}`][0].save(
                                                                                                            activity.end_time
                                                                                                        )
                                                                                                    "
                                                                                                    :min="
                                                                                                        activity.start_time
                                                                                                    "
                                                                                                ></v-time-picker>
                                                                                            </v-menu>
                                                                                        </v-flex>
                                                                                        <v-flex xs10 :key="`descripcion-${index}`">
                                                                                            <v-textarea
                                                                                                data-vv-validate-on="blur"
                                                                                                v-validate="'max:1000'"
                                                                                                :data-vv-as="
                                                                                                    $t(
                                                                                                        'form.description'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                                :data-vv-name="`description${index}`"
                                                                                                :error-messages="
                                                                                                    errors.collect(
                                                                                                        `event.description${index}`
                                                                                                    )
                                                                                                "
                                                                                                :counter="1000"
                                                                                                prepend-icon="notes"
                                                                                                v-model.lazy="
                                                                                                    activity.description
                                                                                                "
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'form.description'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                                rows="3"
                                                                                            ></v-textarea>
                                                                                        </v-flex>

                                                                                        <v-flex xs2 :key="`order-${index}`">
                                                                                            <v-flex xs12>
                                                                                                 <v-select
                                                                                                    data-vv-validate-on="blur"
                                                                                                    v-validate="'required'"
                                                                                                    data-vv-name="Order"
                                                                                                    :items="[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]"
                                                                                                    v-model.lazy="activity.order"
                                                                                                    prepend-icon="mdi-order-numeric-ascending"
                                                                                                    :label="$t('activity.order').capitalize()"
                                                                                                    required
                                                                                                ></v-select>
                                                                                            </v-flex>
                                                                                            <v-flex xs12>
                                                                                                <v-select
                                                                                                    data-vv-validate-on="blur"
                                                                                                    v-validate="'required'"
                                                                                                    data-vv-name="Order"
                                                                                                    :items="['normal', 'secundario', 'acentuado', 'desacentuado']"
                                                                                                    v-model.lazy="activity.style"
                                                                                                    prepend-icon="mdi-palette-swatch-outline"
                                                                                                    :label="$t('activity.style').capitalize()"
                                                                                                    required
                                                                                                ></v-select>
                                                                                            </v-flex>
                                                                                        </v-flex>

                                                                                        <v-flex xs10 :key="`video-${index}`">
                                                                                            <v-text-field
                                                                                                data-vv-validate-on="blur"
                                                                                                v-validate="{
                                                                                                    url: {
                                                                                                        require_protocol: true,
                                                                                                    },
                                                                                                }"
                                                                                                :data-vv-as="
                                                                                                    $t(
                                                                                                        'activity.video_url'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                                :data-vv-name="`video_url${index}`"
                                                                                                :error-messages="
                                                                                                    errors.collect(
                                                                                                        `event.video_url${index}`
                                                                                                    )
                                                                                                "
                                                                                                prepend-icon="mdi-message-video"
                                                                                                v-model.lazy="
                                                                                                    activity.video_url
                                                                                                "
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'activity.video_url'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                            ></v-text-field>
                                                                                        </v-flex>
                                                                                        <v-flex xs2 :key="`show-${index}`">
                                                                                             <v-switch
                                                                                                color="primary"
                                                                                                v-model.lazy="activity.show_in_landing"
                                                                                                :label="
                                                                                                    $t('show_in_landing').capitalize()
                                                                                                "
                                                                                                required
                                                                                            ></v-switch>
                                                                                        </v-flex>
                                                                                        <v-flex xs12 :key="`link-${index}`">
                                                                                            <v-text-field
                                                                                                data-vv-validate-on="blur"
                                                                                                v-validate="{
                                                                                                    url: {
                                                                                                        require_protocol: true,
                                                                                                    },
                                                                                                }"
                                                                                                :data-vv-as="
                                                                                                    $t(
                                                                                                        'activity.extra_link'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                                :data-vv-name="`extra_link${index}`"
                                                                                                :error-messages="
                                                                                                    errors.collect(
                                                                                                        `event.extra_link${index}`
                                                                                                    )
                                                                                                "
                                                                                                prepend-icon="mdi-link-variant"
                                                                                                v-model.lazy="
                                                                                                    activity.extra_link
                                                                                                "
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'activity.extra_link'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                            ></v-text-field>
                                                                                        </v-flex>
                                                                                        <v-flex xs11>
                                                                                            <v-combobox
                                                                                                v-model.lazy="
                                                                                                    activity.actors
                                                                                                "
                                                                                                :items="actors"
                                                                                                item-value="id"
                                                                                                :label="
                                                                                                    $t(
                                                                                                        'actor.title'
                                                                                                    ).capitalize()
                                                                                                "
                                                                                                prepend-icon="record_voice_over"
                                                                                                multiple
                                                                                                chips
                                                                                            >
                                                                                                <template
                                                                                                    slot="selection"
                                                                                                    slot-scope="data"
                                                                                                >
                                                                                                    <v-chip>
                                                                                                        {{
                                                                                                            data.item
                                                                                                                .name
                                                                                                        }}
                                                                                                        {{
                                                                                                            data.item
                                                                                                                .lastname
                                                                                                        }}
                                                                                                    </v-chip>
                                                                                                </template>
                                                                                                <template
                                                                                                    slot="item"
                                                                                                    slot-scope="data"
                                                                                                >
                                                                                                    {{ data.item.name }}
                                                                                                    {{
                                                                                                        data.item
                                                                                                            .lastname
                                                                                                    }}
                                                                                                </template>
                                                                                            </v-combobox>
                                                                                        </v-flex>

                                                                                        <v-flex xs1>
                                                                                            <v-btn
                                                                                                fab
                                                                                                small
                                                                                                dark
                                                                                                color="success"
                                                                                                @click="
                                                                                                    dialogActor = true
                                                                                                "
                                                                                            >
                                                                                                <v-icon>add</v-icon>
                                                                                            </v-btn>
                                                                                        </v-flex>
                                                                                    </v-layout>
                                                                                </v-container>
                                                                            </v-expansion-panel-content>
                                                                        </v-expansion-panel>
                                                                    </v-expansion-panels>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <v-flex xs12 v-if="event.landing">
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">
                                                                {{ $t('form.sponsors').capitalize() }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="dialogSponsor = true"
                                                                class="mr-4"
                                                            >
                                                                <v-icon small dark>add</v-icon>
                                                            </v-btn>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-layout wrap>
                                                                <template v-if="event.landing">
                                                                    <!-- Sponsors -->
                                                                    <v-flex xs2>
                                                                        <v-switch
                                                                            v-model.lazy="event.landing.has_sponsors"
                                                                            :label="
                                                                                $t('show_section_landing').capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs2>
                                                                        <v-switch
                                                                            v-model.lazy="
                                                                                event.landing.show_sponsor_title
                                                                            "
                                                                            :label="
                                                                                $t(
                                                                                    'landing.show_sponsor_title'
                                                                                ).capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs8>
                                                                        <v-text-field
                                                                            :disabled="!event.landing.has_sponsors"
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            data-vv-as="sponsors_title"
                                                                            data-vv-name="sponsors_title"
                                                                            :error-messages="
                                                                                errors.collect(`event.sponsors_title`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.sponsors_title"
                                                                            :label="
                                                                                $t('form.sponsors_title').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>
                                                                </template>

                                                                <v-flex xs12>
                                                                    <v-combobox
                                                                        v-model.lazy="event.sponsors"
                                                                        :items="sponsors"
                                                                        item-text="name"
                                                                        item-value="id"
                                                                        :label="$t('form.sponsors').capitalize()"
                                                                        prepend-icon="record_voice_over"
                                                                        multiple
                                                                        chips
                                                                    ></v-combobox>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <v-flex xs12 v-if="event.landing">
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">
                                                                {{ $t('form.has_contact').capitalize() }}
                                                            </span>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-layout wrap>
                                                                <template v-if="event.landing">
                                                                    <!-- Sponsors -->
                                                                    <v-flex xs3>
                                                                        <v-switch
                                                                            v-model.lazy="event.landing.has_contact"
                                                                            :label="
                                                                                $t('show_section_landing').capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs4>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            data-vv-as="contact_title"
                                                                            data-vv-name="contact_title"
                                                                            :error-messages="
                                                                                errors.collect(`event.contact_title`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.contact_title"
                                                                            :label="
                                                                                $t('form.contact_title').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs5>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:180'"
                                                                            data-vv-as="contact_subtitle"
                                                                            data-vv-name="contact_subtitle"
                                                                            :error-messages="
                                                                                errors.collect(`event.contact_subtitle`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="
                                                                                event.landing.contact_subtitle
                                                                            "
                                                                            :label="
                                                                                $t('form.contact_subtitle').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs12>
                                                                        <v-alert type="info">
                                                                            {{ $t('info.contact_empty_fields') }}
                                                                        </v-alert>
                                                                    </v-flex>

                                                                    <v-flex xs4>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            :data-vv-as="$t('contact.address_title')"
                                                                            data-vv-name="address_title"
                                                                            :error-messages="
                                                                                errors.collect(`event.address_title`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.address_title"
                                                                            :label="
                                                                                $t('contact.address_title').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs8>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            :data-vv-as="$t('contact.address')"
                                                                            data-vv-name="address"
                                                                            :error-messages="
                                                                                errors.collect(`event.address`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.address"
                                                                            :label="$t('contact.address').capitalize()"
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs4>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            :data-vv-as="$t('contact.phone_title')"
                                                                            data-vv-name="phone_title"
                                                                            :error-messages="
                                                                                errors.collect(`event.phone_title`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.phone_title"
                                                                            :label="
                                                                                $t('contact.phone_title').capitalize()
                                                                            "
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs8>
                                                                        <v-text-field
                                                                            data-vv-validate-on="blur"
                                                                            v-validate="'max:120'"
                                                                            :data-vv-as="$t('contact.phone')"
                                                                            data-vv-name="addess"
                                                                            :error-messages="
                                                                                errors.collect(`event.phone`)
                                                                            "
                                                                            :counter="120"
                                                                            prepend-icon="short_text"
                                                                            v-model.lazy="event.landing.phone"
                                                                            :label="$t('contact.phone').capitalize()"
                                                                        ></v-text-field>
                                                                    </v-flex>

                                                                    <v-flex xs3>
                                                                        <v-switch
                                                                            v-model.lazy="event.landing.show_email"
                                                                            :label="
                                                                                $t('contact.show_email').capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>

                                                                    <v-flex xs3>
                                                                        <v-switch
                                                                            v-model.lazy="
                                                                                event.landing.show_contact_form
                                                                            "
                                                                            :label="
                                                                                $t(
                                                                                    'contact.show_contact_form'
                                                                                ).capitalize()
                                                                            "
                                                                            required
                                                                        ></v-switch>
                                                                    </v-flex>
                                                                </template>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <v-flex xs12 v-if="event.ticket_sale">
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">
                                                                {{ $t('form.tickets').capitalize() }}
                                                            </span>
                                                            <v-spacer></v-spacer>
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                fab
                                                                small
                                                                @click.native.stop="addTicket"
                                                            >
                                                                <v-icon small dark>add</v-icon>
                                                            </v-btn>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-layout row wrap>
                                                                 <v-flex xs6>
                                                                    <v-switch
                                                                        :label="$t('show_section_landing').capitalize()"
                                                                        v-model="event.landing.show_tickets"
                                                                    ></v-switch>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-switch
                                                                        :label="$t('tickets.personalized_tickets').capitalize()"
                                                                        v-model="event.personalize_tickets"
                                                                    ></v-switch>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-switch
                                                                        label="Webpay"
                                                                        v-model="event.webpay_accepted"
                                                                    ></v-switch>
                                                                </v-flex>
                                                                <v-flex xs6>
                                                                    <v-switch
                                                                        label="Paypal"
                                                                        v-model="event.paypal_accepted"
                                                                    ></v-switch>
                                                                </v-flex>
                                                                <v-flex xs6 v-if="event.landing">
                                                                    <v-text-field
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'max:120'"
                                                                        :data-vv-as="$t('tickets.title')"
                                                                        :data-vv-name="$t('tickets.title')"
                                                                        :error-messages="
                                                                            errors.collect(`tickets.title`)
                                                                        "
                                                                        :counter="120"
                                                                        prepend-icon="short_text"
                                                                        v-model.lazy="event.landing.tickets_title"
                                                                        :label="$t('tickets.title').capitalize()"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs6 v-if="event.landing">
                                                                    <v-text-field
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'max:300'"
                                                                        :data-vv-as="$t('tickets.subtitle')"
                                                                        :data-vv-name="$t('tickets.subtitle')"
                                                                        :error-messages="
                                                                            errors.collect(`tickets.subtitle`)
                                                                        "
                                                                        :counter="300"
                                                                        prepend-icon="short_text"
                                                                        v-model.lazy="event.landing.tickets_subtitle"
                                                                        :label="$t('tickets.subtitle').capitalize()"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs12>
                                                                    <v-alert type="info">
                                                                        {{ $t('info.tickets_discount') }}
                                                                    </v-alert>
                                                                </v-flex>
                                                                <v-flex xs12>
                                                                    <Ticket
                                                                        v-for="(ticket, index) in event.tickets"
                                                                        :key="`ticket-${index}`"
                                                                        :ticket="ticket"
                                                                        :index="index"
                                                                        :databases="databases"
                                                                        @remove-ticket="removeTicket"
                                                                    />
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>

                                            <v-flex xs12 v-if="event.estimate_app">
                                                <v-expansion-panels>
                                                    <v-expansion-panel>
                                                        <v-expansion-panel-header>
                                                            <span class="headline">App</span>
                                                        </v-expansion-panel-header>
                                                        <v-expansion-panel-content>
                                                            <v-layout row wrap>
                                                                <v-flex xs6>
                                                                    <v-text-field
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'max:30'"
                                                                        :data-vv-as="$t('app.producer')"
                                                                        :data-vv-name="$t('app.producer')"
                                                                        :error-messages="errors.collect(`app.producer`)"
                                                                        :counter="30"
                                                                        prepend-icon="short_text"
                                                                        v-model.lazy="event.producer"
                                                                        :label="$t('app.producer').capitalize()"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs5>
                                                                    <v-text-field
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'max:30'"
                                                                        :data-vv-as="$t('app.password')"
                                                                        :data-vv-name="$t('app.password')"
                                                                        :error-messages="errors.collect(`app.password`)"
                                                                        :counter="30"
                                                                        prepend-icon="short_text"
                                                                        v-model.lazy="event.producer_password"
                                                                        :label="$t('app.password').capitalize()"
                                                                        :type="showPassword ? 'text' : 'password'"
                                                                    ></v-text-field>
                                                                </v-flex>
                                                                <v-flex xs1>
                                                                    <v-btn
                                                                        icon
                                                                        color="orange"
                                                                        @click="showPassword = !showPassword"
                                                                        class="mt-4"
                                                                    >
                                                                        <v-icon v-if="showPassword">mdi-eye</v-icon>
                                                                        <v-icon v-else>mdi-eye-off</v-icon>
                                                                    </v-btn>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-expansion-panel-content>
                                                    </v-expansion-panel>
                                                </v-expansion-panels>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

        <v-btn
            v-if="!!event.id"
            class="preview"
            color="success"
            :loading="loading"
            dark
            fab
            fixed
            right
            bottom
            @click.stop="openPreview"
        >
            <v-icon dark>mdi-file-find</v-icon>
        </v-btn>

        <v-btn color="success" :loading="loading" dark fab fixed right bottom @click="update">
            <v-icon dark>mdi-content-save</v-icon>
        </v-btn>

        <v-dialog v-model.lazy="dialogSponsor" max-width="80%">
            <v-card>
                <v-card-title>
                    <span class="headline">
                        {{ $t('form.new').capitalize() + ' ' + $t('sponsor.title') }}
                    </span>
                </v-card-title>

                <v-card-text>
                    <v-form data-vv-scope="sponsorForm">
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-alert :value="true" type="info" color="infoBlue">{{
                                        $t('sponsor.info')
                                    }}</v-alert>
                                </v-flex>
                                <v-flex xs9>
                                    <v-text-field
                                        data-vv-validate-on="blur"
                                        v-validate="'required|max:30'"
                                        data-vv-name="sponsorForm.name"
                                        :error-messages="errors.collect('sponsorForm.name')"
                                        :counter="30"
                                        required
                                        prepend-icon="short_text"
                                        v-model.lazy="sponsor.name"
                                        :label="$t('form.name').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-text-field
                                        data-vv-validate-on="blur"
                                        v-validate="'required|max:30'"
                                        :data-vv-name="$t('form.category')"
                                        :error-messages="errors.collect('sponsorForm.category')"
                                        :counter="30"
                                        required
                                        prepend-icon="short_text"
                                        v-model.lazy="sponsor.category"
                                        :label="$t('form.category').capitalize()"
                                        type="text"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea
                                        data-vv-validate-on="blur"
                                        v-validate="'max:180'"
                                        data-vv-name="sponsorForm.description"
                                        :error-messages="errors.collect('sponsorForm.description')"
                                        :counter="180"
                                        prepend-icon="notes"
                                        v-model.lazy="sponsor.description"
                                        :label="$t('form.description').capitalize()"
                                        type="text"
                                        rows="5"
                                    ></v-textarea>
                                </v-flex>

                                <v-flex xs12>
                                    <v-card>
                                        <v-card-title primary-title>
                                            <v-icon>image</v-icon>
                                            <h4 class="headline mb-0">
                                                {{ $t('form.logo').capitalize() }}
                                            </h4>
                                        </v-card-title>
                                        <v-card-text>
                                            <file-pond
                                                name="logo"
                                                :server="serverOptionsSponsor"
                                                :label-idle="$t('form.upload.idle')"
                                                :files="sponsorLogo"
                                                allow-multiple="false"
                                                accepted-file-types="image/jpeg, image/png"
                                                @updatefiles="onUpdateFilesSponsor"
                                                required
                                                image-crop-aspect-ratio="1:1"
                                                :image-edit-instant-edit="true"
                                                :imageEditEditor="doka"
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
                    <v-btn color="blue darken-1" text @click="closeSponsor">
                        {{ $t('cancel') }}
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="updateSponsor">
                        {{ $t('accept') }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model.lazy="dialogActor" max-width="80%">
            <v-card>
                <v-card-title>
                    <span class="headline">
                        {{ $t('form.new').capitalize() + ' ' + $t('sponsor.title') }}
                    </span>
                </v-card-title>

                <v-card-text>
                    <Actor :actor="actor" :auth="$auth" />
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeActor">
                        {{ $t('cancel') }}
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="updateActor">
                        {{ $t('accept') }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model.lazy="configurarLandingDialog" max-width="95%">
            <v-card>
                <v-card-title primary-title>Configuración de LandingPage</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle" v-if="event.landing">
                                        <template v-if="event.landing.which === 0">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="400" @click="selectLanding(0)">
                                            <v-card-title primary-title>0</v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue">Previsualizar</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle" v-if="event.landing">
                                        <template v-if="event.landing.which === 1">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="400" @click="selectLanding(1)">
                                            <v-card-title primary-title>1</v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue">Previsualizar</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle" v-if="event.landing">
                                        <template v-if="event.landing.which === 2">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="400" @click="selectLanding(2)">
                                            <v-card-title primary-title>2</v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue">Previsualizar</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle" v-if="event.landing">
                                        <template v-if="event.landing.which === -1">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="300">
                                            <v-card-title primary-title>
                                                Quiero Construir mi Propio Landing
                                            </v-card-title>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model.lazy="configurarEnviosDialog" max-width="95%">
            <v-card>
                <v-card-title primary-title>Configuración de eMails</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.mailing_which === 0">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="400" @click="selectMailing(0)">
                                            <v-card-title primary-title>0</v-card-title>
                                        </v-card>
                                    </v-col>
                                    <v-col cols="12" justify="center">
                                        <v-btn color="blue">Previsualizar</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" xs="12" sm="6" md="4" lg="3" xl="2">
                                <v-row>
                                    <v-col cols="12" class="subtitle">
                                        <template v-if="event.mailing_which === -1">
                                            <v-icon color="success">mdi-check-bold</v-icon>Seleccionado
                                        </template>
                                        <template v-else>Disponible</template>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-card color="grey" width="300" height="300">
                                            <v-card-title primary-title> Quiero Construir mi eMail </v-card-title>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showActivitiesUploadDialog" max-width="30%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('upload').capitalize() + ' Excel' }}
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="openWindow('/files/venoot.xlsx')">{{ $t('example') }}</v-btn>
                </v-card-title>
                <v-card-text>
                    <file-pond
                        name="activities"
                        :server="serverOptionsActivities"
                        :label-idle="$t('form.upload.idle')"
                        :files="activitiesFiles"
                        allow-multiple="false"
                        accepted-file-types="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        required
                    ></file-pond>
                </v-card-text>
                <v-card-actions></v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showActorsUploadDialog" max-width="30%">
            <v-card>
                <v-card-title class="headline">
                    {{ $t('upload').capitalize() + ' Excel' }}
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="openWindow('/files/venoot.xlsx')">{{ $t('example') }}</v-btn>
                </v-card-title>
                <v-card-text>
                    <file-pond
                        name="actors"
                        :server="serverOptionsActors"
                        :label-idle="$t('form.upload.idle')"
                        :files="actorsFiles"
                        allow-multiple="false"
                        accepted-file-types="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        required
                    ></file-pond>
                </v-card-text>
                <v-card-actions></v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { loadUrl } from '../app'
import * as Doka from '../plugins/doka/index'
import { forEach } from 'lodash'
import { v4 as uuid } from 'uuid';

import Actor from '../components/Actor'
import Ticket from '../components/Ticket'

export default {
    components: {
        Actor,
        Ticket,
    },
    data: function () {
        return {
            panels: {},
            doka: Doka.create({
                cropAllowImageTurnRight: true,
                cropLimitToImageBounds: false,
            }),
            fab: false,
            showActivitiesUploadDialog: false,
            showActorsUploadDialog: false,
            configurarLandingDialog: false,
            configurarEnviosDialog: false,
            startDayMenu: false,
            endDateMenu: false,
            startTimeMenu: false,
            endTimeMenu: false,
            startTimeActivityMenu: {},
            endTimeActivityMenu: {},
            dialog: false,
            dialogActor: false,
            dialogSponsor: false,
            currentDatabase: null,
            showPassword: false,
            menu: {},
            actor: {},
            actors: [],
            sponsor: {
                category: this.$t('sponsor.title'),
            },
            sponsors: [],
            event: {
                category: 'recitales',
                invitees: 0,
                activities: [],
                estimate: {},
                tickets: [],
                over_quota: false,
                has_confirmation: false,
                secure_video: false,
                secure_video_extras: false,
                shared_chat: false,
                video_category: 'iframe',
                one_to_one_chat: false,
                speaker_chat: false,
                pretty_url: null,
                webpay_accepted: true,
                paypal_accepted: false,
                personalize_tickets: false
            },
            events: [],
            databases: [],
            templates: [],
            activitiesFiles: [],
            actorsFiles: [],
            eventsHeaders: [
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
                    text: this.$t('form.has_landing').capitalize(),
                    align: 'center',
                    value: 'has_landing',
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'name',
                    sortable: false,
                },
            ],
            categories: [
                {
                    text: this.$t('event.category.recital').capitalize(),
                    value: 'recitales',
                },
                {
                    text: this.$t('event.category.entertainment').capitalize(),
                    value: 'entretenimientos',
                },
                {
                    text: this.$t('event.category.sports').capitalize(),
                    value: 'deportes',
                },
                {
                    text: this.$t('event.category.courses').capitalize(),
                    value: 'cursos',
                },
                {
                    text: this.$t('event.category.corporativec').capitalize(),
                    value: 'corporativoc',
                },
                {
                    text: this.$t('event.category.corporativeo').capitalize(),
                    value: 'corporativoa',
                },
                {
                    text: this.$t('event.category.ceremony').capitalize(),
                    value: 'ceremonia',
                },
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
            serverOptionsLocation: {
                process: {
                    url: './api/uploadLocationEvent',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.event.landing.location_photo = responseJson.name
                    },
                },
                load: loadUrl,
            },
            serverOptionsSponsor: {
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
            serverOptionsLandingImages: {
                process: {
                    url: './api/uploadLandingImages',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        if (!this.event.landing) {
                            return []
                        }

                        if (Array.isArray(this.event.landing.images)) {
                            this.event.landing.images.push(responseJson.name)
                        } else {
                            this.event.landing.images = [responseJson.name]
                        }
                    },
                },
                load: loadUrl,
            },
            serverOptionsExtras: {
                process: {
                    url: './api/uploadExtraImages',
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        if (Array.isArray(this.event.extra_images)) {
                            this.event.extra_images.push(responseJson.name)
                            console.log(this.event.extra_images)
                        } else {
                            this.event.extra_images = [responseJson.name]
                        }
                        return responseJson.name
                    },
                },
                load: loadUrl,
            },

            serverOptionsActivities: {
                process: {
                    url: `./api/events/0/uploadActivities`,
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.$snotify.success(this.$t('participant.excel.success'))
                        this.showActivitiesUploadDialog = false
                        this.activitiesFiles = []

                        this.event.activities = responseJson.activities
                    },
                    onerror: (response) => {
                        let responseJson = JSON.parse(response)
                        console.log(responseJson)
                        this.$snotify.error(this.$t('participant.excel.failure'))
                        this.showActivitiesUploadDialog = false
                        this.activitiesFiles = []
                    },
                },
                load: loadUrl,
            },

            serverOptionsActors: {
                process: {
                    url: `./api/events/0/uploadActors`,
                    headers: {
                        Authorization: `Bearer ${this.$auth.token()}`,
                    },
                    onload: (response) => {
                        let responseJson = JSON.parse(response)
                        this.$snotify.success(this.$t('participant.excel.success'))
                        this.showActorsUploadDialog = false
                        this.actorsFiles = []

                        this.event.actors = responseJson.actors
                    },
                    onerror: (response) => {
                        let responseJson = JSON.parse(response)
                        console.log(responseJson)
                        this.$snotify.error(this.$t('participant.excel.failure'))
                        this.showActorsUploadDialog = false
                        this.actorsFiles = []
                    },
                },
                load: loadUrl,
            },
            loading: false,
            activitiesPanel: null,
        }
    },
    computed: {
        cartCode() {
            return `
            <html>
                <head>
                    ...
                    <link href="https://venoot.com/css/shopping-cart.css" rel="stylesheet" />
                    ...
                </head>

                <body>
                    ...
                    <div id="venoot-shopping-cart" data-id=${this.event.id}  data-env="production"></div>
                    ...
                    <script src="https://venoot.com/js/shopping-cart.js"><\/script>
                    ...
                </body>
            </html>
            `
        },

        pollCode() {
            return `
            <html>
                <head>
                    ...
                    <link href="https://venoot.com/css/poll.css" rel="stylesheet" />
                    ...
                </head>

                <body>
                    ...
                    <div id="venoot-poll" data-eventid=${this.event.id} data-pollid="ENCUESTA/POLL ID"  data-env="production"></div>
                    ...
                    <script src="https://venoot.com/js/poll.js"><\/script>
                    ...
                </body>
            </html>
            `
        },

        database_keys() {
            if (this.currentDatabase) {
                let result = []
                this.currentDatabase.fields.forEach((field) => {
                    if (field.type === 'choice' && !['name', 'lastname', 'email'].includes(field.key)) {
                        result.push({ text: field.name, value: field.key })
                    }
                })
                return result
            } else {
                return []
            }
        },
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
        sponsorLogo() {
            return this.sponsor.logo ? [{ source: this.sponsor.logo, options: { type: 'local' } }] : []
        },
        actorPhoto() {
            return this.actor.photo ? [{ source: this.actor.photo, options: { type: 'local' } }] : []
        },
        extras() {
            this.event.extra_images = this.event.extra_images && this.event.extra_images.length !== 0 ? JSON.parse(this.event.extra_images) : this.event.extra_images; //Necesario para convertir varchar [] en array js
            if (
                this.event.extra_images &&
                this.event.extra_images.length !== 0
            ) {
                return this.event.extra_images.map(image => {
                    return { source: image, options: { type: 'local' } }
                })
            } else {
                return []
            }
        },
        images() {
          if (this.event.landing && this.event.landing.images && this.event.landing.images.length !== 0) {
              return this.event.landing.images.map(image => {
                  return { source: image, options: { type: 'local' } }
              })
          } else {
              return []
          }
        },
        location() {
            return this.event.landing.location_photo
                ? [
                      {
                          source: this.event.landing.location_photo,
                          options: { type: 'local' },
                      },
                  ]
                : []
        },
        profiles() {
            if (this.currentDatabase) {
                return this.currentDatabase.profiles
            } else {
                return []
            }
        },
    },
    methods: {
        openPreview() {
            this.openWindow(
                `events/${this.event.id}/landing/preview/${this.event.landing.which}?token=${this.$auth.token()}`
            )
        },

        selectLanding(num) {
            this.event.landing.which = num
            this.configurarLandingDialog = false
        },
        selectMailing(num) {
            this.event.mailing_which = num
            this.configurarEnviosDialog = false
        },
        openWindow: function (link) {
            window.open(link)
        },
        addTicket() {
            this.event.tickets.push({ quantity: 0, price: 0, protection: null })
        },
        removeTicket(index) {
            this.event.tickets.splice(index, 1)
        },
        updateActor() {
            this.$validator.validateAll('actorForm').then((valid) => {
                if (valid && this.actorPhoto) {
                    this.loading = true

                    if (!this.actor.links) {
                        this.actor.links = []
                    }

                    this.axios
                        .post('/actors', { ...this.actor })
                        .then((response) => {
                            this.loading = false
                            this.$snotify.success(this.$t('actor.store.success'))
                            this.actors = response.data.actors
                            this.closeActor()
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
            })
        },
        closeActor() {
            this.dialogActor = false
            setTimeout(() => {
                this.actor = Object.assign({}, {})
            }, 300)
        },
        updateSponsor() {
            this.$validator.validateAll('sponsorForm').then((valid) => {
                if (valid && this.sponsorLogo) {
                    this.loading = true
                    this.axios
                        .post('/sponsors', { ...this.sponsor })
                        .then((response) => {
                            this.loading = false
                            this.$snotify.success(this.$t('sponsor.store.success'))
                            this.sponsors = response.data.sponsors
                            this.closeSponsor()
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
            })
        },
        closeSponsor() {
            this.dialogSponsor = false
            setTimeout(() => {
                this.sponsor = Object.assign({}, {})
            }, 300)
        },
        newActivity() {
            this.activitiesPanel = 0
            this.event.activities.push({ actors: [], show_in_landing: true, order: 0, style: 'normal' })
        },
        deleteActivity(index) {
            this.event.activities.splice(index, 1)
        },

        showActivitiesUpload() {
            this.serverOptionsActivities.process.url = `./api/events/${this.event.id}/uploadActivities`
            this.showActivitiesUploadDialog = true
        },

        showActorsUpload() {
            this.serverOptionsActors.process.url = `./api/events/${this.event.id}/uploadActors`
            this.showActorsUploadDialog = true
        },
        async getTickets() {
          this.loading = true;
          if(this.event.id){
               await this.axios
                   .get(`/ticketsByEvent/${this.event.id}`)
                   .then(response => {
                       console.log("tickets");
                       console.log(response);
                       this.event.tickets = response.data.tickets
                       console.log(this.event);
                   })
                   .catch(error => {
                       this.$snotify.error(this.$t('general_error'))
                       this.$router.push('/home')
                   })
                   .finally(() => {
                       this.loading = false
                   })
            }
        },
        async getActorsByEvent(){
            this.loading = true;
            if(this.event.id){
                 await this.axios
                     .get(`/actorsByEvent/${this.event.id}`)
                     .then(response => {
                         console.log("actors");
                         console.log(response);
                         this.event.actors = response.data.actors
                         console.log(this.event);
                     })
                     .catch(error => {
                         this.$snotify.error(this.$t('general_error'))
                         this.$router.push('/home')
                     })
                     .finally(() => {
                         this.loading = false
                     })
              }
       },
       async getActivitiesByEvent(){
           this.loading = true;
           if(this.event.id){
                 await this.axios
                     .get(`/activitiesByEvent/${this.event.id}`)
                     .then(response => {
                         console.log("activities");
                         console.log(response);
                         this.event.activities = response.data.activities
                         console.log(this.event);
                     })
                     .catch(error => {
                         this.$snotify.error(this.$t('general_error'))
                         this.$router.push('/home')
                     })
                     .finally(() => {
                         this.loading = false
                     })
              }
        },
        async getLanding(){
            this.loading = true

            if(this.event.id){
               await this.axios
                  .get(`/events/${this.event.id}/landing`)
                  .then(response => {
                      console.log("landing");
                      console.log(response);
                      this.event.landing = response.data.landing.length != 0 ? response.data.landing[0] : [];
                      console.log(this.event);

                      if (this.event.estimate_landing && !this.event.landing) {
                          this.event.landing = {
                              which: 0,
                              has_date: true,
                              has_add_to_calendar: true,
                              has_description: true,
                              has_speakers: true,
                              has_activities: true,
                              has_location: true,
                              has_sponsors: true,
                              has_contact: true,
                              has_ssnn: true,
                              show_speaker_title: true,
                              show_sponsor_title: true,
                              images: []
                          }
                      }
                  })
                  .catch(error => {
                      this.$snotify.error(this.$t('general_error'))
                      this.$router.push('/home')
                  })
                  .finally(() => {
                      console.log("finally getLanding");
                      this.loading = false
                  })
            }
        },
        update() {
            this.$validator.validateAll('event').then((valid) => {
                if (valid) {
                    this.loading = true
                    if (this.event.estimate_event) {
                        delete this.event.estimate_event
                    }

                    if (this.event.ticket_sale) {
                        if (!this.event.webpay_accepted && !this.event.paypal_accepted) {
                            this.$snotify.error('Se necesita al menos un medio de pago')
                            this.loading = false
                            return
                        }
                    }

                    if (this.event.id) {
                        this.axios
                            .put(`/events/${this.event.id}`, { ...this.event })
                            .then((response) => {
                                this.loading = false
                                this.$snotify.success(this.$t('event.update.success'))
                                this.event = response.data.event
                            })
                            .catch((error) => {
                                this.loading = false
                                let errors = ''
                                if (error.response) {
                                    forEach(error.response.data.errors, (value, key) => {
                                        forEach(value, (e, k) => {
                                            errors += e
                                        })
                                    })
                                    this.$snotify.error(errors, this.$t('event.update.failure'))
                                } else {
                                    console.log(error.message)
                                }
                            })
                            .finally(() => (this.loading = false))
                    } else {
                        this.axios
                            .post('/events', { ...this.event })
                            .then((response) => {
                                this.$snotify.success(this.$t('event.store.success'))
                                this.event = response.data.event
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
        onUpdateFilesLogo(files) {
            if (files.length == 0) {
                this.event.logo_event = null
            }
        },
        onUpdateFilesBanner(files) {
            if (files.length == 0) {
                this.event.banner = null
            }
        },
        onUpdateFilesLocation(files) {
            if (files.length == 0) {
                this.event.location_photo = null
            }
        },
        onUpdateFilesSponsor(files) {
            if (files.length == 0) {
                this.sponsor.logo = null
            }
        },
        onUpdateFilesActor(files) {
            if (files.length == 0) {
                this.actor.photo = null
            }
        },
        onUpdateFilesLanding(files) {
            if (files.length == 0) {
                this.event.landing.images = []
            }
        },
        onUpdateFilesExtras(files) {
            if (files.length == 0) {
                this.event.extra_images = []
            }
        },
    },
    async mounted() {
        if (this.$route.params.event) {
            this.event = this.$route.params.event
            this.event.database_id = this.$route.params.event.database_id
            this.event.mailing_which = 0
        } else if (this.$route.params.estimate) {
            this.event.estimate_id = this.$route.params.estimate.id
            this.event.estimate = this.$route.params.estimate
        } else {
            this.$router.push('/home')
        }

        await this.getLanding();
        await this.getActorsByEvent();
        await this.getActivitiesByEvent();
        await this.getTickets();


        this.loading = true
        this.axios
            .get('/databases')
            .then((response) => {
                this.databases = response.data.databases
                if (this.databases.length > 0 && !this.event.profile) {
                    this.currentDatabase = this.databases[0]
                    this.event.profile_id = this.currentDatabase.profiles[0].id
                } else if (this.event.profile) {
                    this.currentDatabase = this.databases.find((database) => {
                        return database.id === this.event.profile.database_id
                    })
                }
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                this.loading = false
            })

        this.axios
            .get('/sponsors')
            .then((response) => {
                this.sponsors = response.data.sponsors
            })
            .catch((error) => {
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/home')
            })
            .finally(() => {
                this.loading = false
            })

        this.axios
            .get('/actors')
            .then((response) => {
                this.actors = response.data.actors
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
.filepond--root {
    max-height: 380px;
}

.filepond-grid .filepond--item {
    width: calc(50% - 0.5em);
}

.filepond--list-scroller {
    z-index: 4 !important;
}

.filepond--drop-label {
    z-index: 3 !important;
}

.preview {
    margin-right: 60px;
}
</style>
