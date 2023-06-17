<template>
    <v-app v-if="$auth.ready()">
        <vue-snotify></vue-snotify>
        <v-navigation-drawer :mini-variant.sync="drawerMini" v-model="drawer" class="background" app dark>
            <v-list-item>
                <v-list-item-action>
                    <v-img src="../images/icon-logo.svg" :height="50" :width="50" aspect-ratio="1" contain></v-img>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="title white--text">Venoot</v-list-item-title>
                    <v-list-item-subtitle class="white--text"
                        >{{ $auth.user().name }} {{ $auth.user().lastname }}</v-list-item-subtitle
                    >
                </v-list-item-content>
            </v-list-item>

            <v-list v-if="$auth.check()" class="nav-list" dark>
                <v-list-item v-if="$auth.user().category === 'webinar'" @click="$router.push('/webinardashboard')">
                    <v-list-item-action>
                        <v-icon dark>home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Webinars Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-else @click="$router.push('/home')">
                    <v-list-item-action>
                        <v-icon dark>home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.home').capitalize() }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="$auth.user().category !== 'webinar'" @click="$router.push('/dashboard')">
                    <v-list-item-action>
                        <v-icon dark>dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('estimate.titlePlural').capitalize() }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="$router.push('/webinars')">
                    <v-list-item-action>
                        <v-icon dark>event</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.webinars') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="$router.push('/databases')">
                    <v-list-item-action>
                        <v-icon dark>storage</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.databases') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="$auth.user().category !== 'webinar'" @click="$router.push('/events')">
                    <v-list-item-action>
                        <v-icon dark>event</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.events') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="$router.push('/actors')">
                    <v-list-item-action>
                        <v-icon dark>record_voice_over</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.actors') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="$router.push('/sponsors')">
                    <v-list-item-action>
                        <v-icon dark>mdi-coffee</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.sponsors') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="$auth.user().category !== 'webinar'" @click="$router.push('/discounts')">
                    <v-list-item-action>
                        <v-icon dark>mdi-ticket-percent</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.discounts') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="$router.push('/polls')">
                    <v-list-item-action>
                        <v-icon dark>poll</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.polls') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="$auth.user().category !== 'webinar'" @click="$router.push('/templates_index')">
                    <v-list-item-action>
                        <v-icon dark>mdi-floor-plan</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.templates') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="$auth.user().category === 'webinar'" @click="$router.push('/tutorials')">
                    <v-list-item-action>
                        <v-icon dark>mdi-school</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.tutorials') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <template v-if="isSuperAdmin">
                    <v-divider inset dark></v-divider>

                    <v-subheader class="white--text">ADMIN</v-subheader>

                    <v-list-item @click="$router.push('/webinar-admin')">
                        <v-list-item-action>
                            <v-icon dark>mdi-video</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Webinars</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
            <v-list v-else class="nav-list">
                <v-list-item @click="$router.push('/login')">
                    <v-list-item-action>
                        <v-icon dark>lock</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.login') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click="$router.push('/signup')">
                    <v-list-item-action>
                        <v-icon dark>person_add</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t('link.signup') }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar color="primary" app elevate-on-scroll>
            <v-app-bar-nav-icon
                class="hidden-md-and-down"
                color="white"
                @click="drawerMini = !drawerMini"
            ></v-app-bar-nav-icon>
            <v-app-bar-nav-icon class="hidden-lg-and-up" color="white" @click="drawer = !drawer"></v-app-bar-nav-icon>

            <v-toolbar-title class="white--text">{{ $t(`link.${$route.name}`).toUpperCase() }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn v-if="false" class="white--text" to="/webinarpackages" text>{{ $t('webinar.packages') }}</v-btn>
                <v-btn v-if="!$auth.check()" class="white--text" to="/signup" text>{{ $t('link.signup') }}</v-btn>

                <v-btn v-if="$auth.user().category !== 'webinar'" class="white--text" to="/estimator" text>{{ $t('link.estimate') }}</v-btn>
                <v-menu offset-y v-if="$auth.check() && $auth.user().category !== 'webinar'">
                    <template v-slot:activator="{ on }">
                        <v-btn class="white--text" text v-on="on">
                            <v-icon color="white" left>account_circle</v-icon>
                            {{ $t('link.profiles') }}
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="$router.push(`/users/${$auth.user().id}`)">
                            <v-list-item-content>
                                <v-list-item-title>{{ $t('link.profile') }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-icon dark>person</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                        <v-list-item
                            v-if="$auth.user().company_id"
                            @click="$router.push(`/companies/${$auth.user().company_id}`)"
                        >
                            <v-list-item-content>
                                <v-list-item-title>{{ $t('link.companyPatch') }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-icon dark>business</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                        <v-list-item v-else @click="$router.push('/companies')">
                            <v-list-item-content>
                                <v-list-item-title>{{ $t('link.companyPost') }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-icon dark>business</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                        <v-list-item @click="$router.push('/users')">
                            <v-list-item-content>
                                <v-list-item-title>{{ $t('link.users') }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-icon dark>people</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                        <v-list-item @click="$router.push('/permissions')">
                            <v-list-item-content>
                                <v-list-item-title>{{ $t('link.permissions') }}</v-list-item-title>
                            </v-list-item-content>

                            <v-list-item-action>
                                <v-icon dark>people</v-icon>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>
                </v-menu>

                <v-btn v-if="$auth.check()" class="white--text" @click="logout" text>{{ $t('link.logout') }}</v-btn>
                <LocaleSelector />
            </v-toolbar-items>
        </v-app-bar>
        <router-view @open-webinar="openWebinar"></router-view>
        <v-footer app class="pl-3">
            Copyright ©️ 2018
            <a class="blue--text text--darken-2 mx-2" href="https://venoot.com">Venoot</a>
            - Todos los Derechos Reservados.
        </v-footer>

        <v-dialog v-model="webinarDialog" fullscreen>
            <v-card>
                <v-toolbar dark color="primary">
                    <v-toolbar-title>WEBINARS</v-toolbar-title>
                </v-toolbar>

                <v-container class="mx-0 pl-0 py-0" fluid>
                    <v-layout wrap>
                        <v-flex>
                            <v-navigation-drawer dark permanent>
                                <v-list-item>
                                    <v-list-item-action>
                                        <v-img
                                            src="../images/icon-logo.svg"
                                            :height="50"
                                            :width="50"
                                            aspect-ratio="1"
                                            contain
                                        ></v-img>
                                    </v-list-item-action>
                                    <v-list-item-content>
                                        <v-list-item-title class="title white--text">Venoot</v-list-item-title>
                                        <v-list-item-subtitle class="white--text"
                                            >{{ $auth.user().name }} {{ $auth.user().lastname }}</v-list-item-subtitle
                                        >
                                    </v-list-item-content>
                                </v-list-item>

                                <v-divider></v-divider>

                                <v-list v-if="$auth.check()" class="nav-list">
                                    <v-list-item
                                        @click="
                                            $router.push({
                                                query: { nav: 'webinars' },
                                                hash: 'databases',
                                            })
                                        "
                                    >
                                        <v-list-item-action>
                                            <v-icon dark>storage</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>{{ $t('link.databases') }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item @click="$router.push({ query: { nav: 'webinars' }, hash: 'events' })">
                                        <v-list-item-action>
                                            <v-icon dark>event</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>{{ $t('link.events') }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>

                                    <v-list-item
                                        @click="$router.push({ query: { nav: 'webinars' }, hash: 'delivery' })"
                                    >
                                        <v-list-item-action>
                                            <v-icon dark>mail</v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>{{
                                                $t('form.mailings').capitalize()
                                            }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-navigation-drawer>
                        </v-flex>
                        <v-flex xs9>
                            <v-list three-line>
                                <v-list-item id="databases">
                                    <v-list-item-content class="px-4">
                                        <v-list-item-title>{{ $t('database.title') }}</v-list-item-title>
                                        <v-radio-group v-model="databaseModel" row dense hide-details>
                                            <v-radio
                                                :disabled="hasDatabase"
                                                :label="$t('form.new').capitalize()"
                                                value="new"
                                            ></v-radio>
                                            <v-radio
                                                :disabled="hasDatabase"
                                                :label="$t('existent').capitalize()"
                                                value="existent"
                                            ></v-radio
                                            ><v-select
                                                :disabled="databaseModel !== 'existent' || hasDatabase"
                                                :items="['Database I', 'Database II', 'Database III']"
                                                class="ml-4"
                                                style="width: 400px"
                                                v-model="selectedDatabase"
                                            ></v-select>

                                            <v-btn
                                                @click="showExcelUpload"
                                                fab
                                                small
                                                color="success"
                                                dark
                                                class="ml-4"
                                                v-if="hasDatabase"
                                            >
                                                <v-icon>mdi-file-excel</v-icon>
                                            </v-btn>
                                        </v-radio-group>

                                        <v-form
                                            v-show="databaseModel === 'new' && show"
                                            ref="form"
                                            data-vv-scope="databaseForm"
                                            onsubmit="return false;"
                                        >
                                            <v-container grid-list-md class="mx-0 py-0">
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
                                                            :disabled="hasDatabase"
                                                        ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <h4>
                                                            {{ $t('database.fields').capitalize() }}
                                                            <v-btn
                                                                fab
                                                                dark
                                                                small
                                                                color="success"
                                                                @click="addField"
                                                                class="ml-4"
                                                                :disabled="hasDatabase"
                                                            >
                                                                <v-icon dark>add</v-icon>
                                                            </v-btn>
                                                        </h4>
                                                    </v-flex>
                                                    <v-flex xs12 v-for="(field, index) in database.fields" :key="index">
                                                        <v-layout row wrap>
                                                            <v-flex xs2>
                                                                <v-text-field
                                                                    :disabled="disableField(field.key) || hasDatabase"
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
                                                                    :disabled="hasDatabase"
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
                                                                    :disabled="disableField(field.key) || hasDatabase"
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
                                                                    :disabled="disableField(field.key) || hasDatabase"
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
                                                                    :disabled="disableField(field.key) || hasDatabase"
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
                                                                    :disabled="hasDatabase"
                                                                >
                                                                    <v-icon dark>mdi-playlist-plus</v-icon>
                                                                </v-btn>
                                                                <v-btn
                                                                    v-if="
                                                                        index > 0 &&
                                                                        !disableField(field.key) &&
                                                                        !hasDatabase
                                                                    "
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
                                                                        :disabled="hasDatabase"
                                                                        v-validate="'required'"
                                                                        :data-vv-as="$t('form.choice')"
                                                                        :data-vv-name="`databaseForm.field${
                                                                            index + '-' + choiceIndex
                                                                        }`"
                                                                        :error-messages="
                                                                            errors.collect(
                                                                                `databaseForm.field${
                                                                                    index + '-' + choiceIndex
                                                                                }`
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
                                                                        :disabled="hasDatabase"
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
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-btn
                                            v-if="databaseModel === 'new' && hasDatabase && !show"
                                            dark
                                            large
                                            color="blue"
                                            @click="toggleDatabase"
                                        >
                                            {{ $t('show') }}
                                        </v-btn>
                                        <v-btn
                                            v-if="databaseModel === 'new' && hasDatabase && show"
                                            dark
                                            large
                                            color="blue"
                                            @click="toggleDatabase"
                                        >
                                            {{ $t('hide') }}
                                        </v-btn>
                                    </v-list-item-action>
                                    <v-list-item-action>
                                        <v-btn
                                            v-if="!hasDatabase"
                                            dark
                                            large
                                            color="success"
                                            @click="saveDatabase"
                                            class="ml-4"
                                        >
                                            {{ $t('save') }}
                                        </v-btn>
                                        <v-btn v-else large color="blue" @click="saveDatabase" class="ml-4" disabled>
                                            {{ $t('saved') }}
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>

                                <v-divider></v-divider>

                                <v-list-item id="events">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ $t('event.title').capitalize() }}</v-list-item-title>
                                        <v-form ref="form" data-vv-scope="event" lazy-validation>
                                            <v-container grid-list-md class="ma-0 px-8">
                                                <v-layout wrap>
                                                    <v-flex xs5>
                                                        <v-text-field
                                                            :disabled="!hasDatabase || hasEvent"
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
                                                        ></v-text-field> </v-flex
                                                    ><v-flex xs4>
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
                                                                    :disabled="!hasDatabase || hasEvent"
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
                                                    <v-flex xs2>
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
                                                            <template v-slot:activator="{ on }">
                                                                <v-text-field
                                                                    :disabled="!hasDatabase || hasEvent"
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
                                                                @click:minute="
                                                                    $refs.startTimeMenu.save(event.start_time)
                                                                "
                                                            ></v-time-picker>
                                                        </v-menu>
                                                    </v-flex>
                                                    <v-flex xs12>
                                                        <v-textarea
                                                            :disabled="!hasDatabase || hasEvent"
                                                            data-vv-validate-on="blur"
                                                            data-vv-name="description"
                                                            :error-messages="errors.collect('event.description')"
                                                            required
                                                            prepend-icon="notes"
                                                            v-model.lazy="event.description"
                                                            :label="$t('form.description').capitalize()"
                                                            type="text"
                                                            rows="3"
                                                        ></v-textarea>
                                                    </v-flex>

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
                                                                    :disabled="!hasDatabase || hasEvent"
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
                                                                    :disabled="!hasDatabase || hasEvent"
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

                                                    <v-flex xs12 class="mt-4" v-if="webinarCategory > 1">
                                                        Multi-Salas
                                                    </v-flex>

                                                    <template v-if="webinarCategory >= 2">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>
                                                    <template v-if="webinarCategory >= 2">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>
                                                    <template v-if="webinarCategory >= 2">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>

                                                    <template v-if="webinarCategory >= 3">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>
                                                    <template v-if="webinarCategory >= 3">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>

                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>
                                                    <template v-if="webinarCategory >= 3">
                                                        <v-flex xs5>
                                                            <v-text-field
                                                                :disabled="!hasDatabase || hasEvent"
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
                                                            ></v-text-field> </v-flex
                                                        ><v-flex xs4>
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
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_date"
                                                                        :error-messages="
                                                                            errors.collect('event.start_date')
                                                                        "
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
                                                        <v-flex xs2>
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
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field
                                                                        :disabled="!hasDatabase || hasEvent"
                                                                        data-vv-validate-on="blur"
                                                                        v-validate="'required'"
                                                                        data-vv-name="start_time"
                                                                        :error-messages="
                                                                            errors.collect('event.start_time')
                                                                        "
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
                                                                    @click:minute="
                                                                        $refs.startTimeMenu.save(event.start_time)
                                                                    "
                                                                ></v-time-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </template>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-btn v-if="!hasDatabase" large disabled>
                                            {{ $t('$vuetify.noDataText') }}
                                        </v-btn>
                                        <v-btn
                                            v-if="hasDatabase && !hasEvent"
                                            dark
                                            large
                                            color="success"
                                            @click="saveEvent"
                                            class="ml-4"
                                        >
                                            {{ $t('save') }}
                                        </v-btn>
                                        <v-btn v-if="hasDatabase && hasEvent" large class="ml-4" disabled>
                                            {{ $t('saved') }}
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>

                                <v-divider></v-divider>

                                <v-list-item id="delivery">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ $t('form.mailings').capitalize() }}</v-list-item-title>

                                        <v-form ref="form" data-vv-scope="event" lazy-validation>
                                            <v-container grid-list-md class="ma-0 px-8">
                                                <v-layout wrap>
                                                    <v-flex xs6>
                                                        <v-text-field
                                                            :disabled="!hasEvent"
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
                                                            :disabled="!hasEvent"
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
                                                            :disabled="!hasEvent"
                                                            data-vv-validate-on="blur"
                                                            data-vv-name="from_subject"
                                                            :error-messages="errors.collect(`event.from_subject`)"
                                                            prepend-icon="mdi-text"
                                                            v-model.lazy="event.from_subject"
                                                            label="Asunto"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-form>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-btn v-if="!hasEvent" large @click="sendMails" disabled>
                                            {{ $t('$vuetify.noDataText') }}
                                        </v-btn>
                                        <v-btn
                                            v-if="hasEvent && !mailsSent"
                                            dark
                                            large
                                            color="success"
                                            @click="sendMails"
                                        >
                                            {{ $t('send') }}
                                        </v-btn>
                                        <v-btn v-if="hasEvent && mailsSent" large class="ml-4" disabled>
                                            {{ $t('sent') }}
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>
                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-dialog>

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
    </v-app>
</template>

<script>
import { loadUrl } from './app'
import * as Doka from './plugins/doka/index'
import LocaleSelector from './components/LocaleSelector'
export default {
    components: {
        LocaleSelector,
    },
    data: function () {
        return {
            doka: Doka.create({
                cropAllowImageTurnRight: true,
                cropLimitToImageBounds: false,
            }),

            drawerMini: false,
            drawer: true,

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
                        this.axios
                            .post(`/databases/${this.database.id}/verify`)
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

            webinarDialog: false,
            databaseModel: 'new',
            show: true,
            database: {
                name: 'New Database',
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
            },
            selectedDatabase: 'Database I',
            hasDatabase: false,

            event: {},
            startDayMenu: false,
            startTimeMenu: false,
            hasEvent: false,

            mailsSent: false,

            webinarCategory: null,

            showExcelUploadDialog: false,
            excelFiles: [],
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

        isSuperAdmin() {
            return this.$auth.user().roles.some((role) => role.name === 'super-admin')
        },
    },
    methods: {
        openWebinar(webinarCategory) {
            this.webinarCategory = webinarCategory
            this.webinarDialog = true
        },

        showExcelUpload() {
            this.serverOptionsDatabase.process.url = `./api/databases/${this.database.id}/uploadDatabase`
            this.showExcelUploadDialog = true
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

        disableField(fieldName) {
            return ['name', 'lastname', 'email'].includes(fieldName)
        },
        addField() {
            this.database.fields.push({
                type: 'string',
                required: false,
                in_form: false,
            })
        },
        saveDatabase() {
            this.hasDatabase = true
            this.show = false
        },
        toggleDatabase() {
            this.show = !this.show
        },

        saveEvent() {
            this.hasEvent = true
        },
        sendMails() {
            this.mailsSent = true
        },
        goToEstimate(item) {
            $router.push({ path: '/estimate/' + item })
        },
        logout() {
            this.$auth.logout({
                makeRequest: true,
                success: function () {
                    this.$snotify.success(this.$t('logout.success'))
                },
                error: function () {
                    this.$snotify.success(this.$t('logout.error'))
                },
                redirect: '/',
            })
        },
    },
}
</script>

<style>
.toolbar__logo .v-toolbar__content {
    padding: 0;
}

.toolbar__logo .v-image__image {
    width: 80px;
}
</style>
