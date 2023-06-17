<template>
    <div>
        <v-content>
            <v-container>
                <v-row>
                    <v-col cols="8">
                        <h1>Escritorio General</h1>
                        <v-spacer></v-spacer>
                    </v-col>
                    <v-col cols="2" class="h5">
                        <h3>{{ $auth.user().company.social_reason }}</h3>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="1" class="font-weight-bold"> Plan Valido Hasta: </v-col>
                    <v-col cols="2" class="font-weight-bold">
                        <v-icon>mdi-calendar</v-icon>
                        {{ (new Date($auth.user().expiration_date)).toLocaleDateString() }}
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-subtitle> Cuenta Tipo </v-card-subtitle>
                            <v-card-text class="text-center">
                                <h2>{{ $auth.user().package.capitalize() }}</h2>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card>
                            <v-card-subtitle> Correos Electronicos </v-card-subtitle>
                            <v-card-text class="text-center">
                                <h2>{{ total_emails }}/{{ max_emails }}</h2>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card>
                            <v-card-subtitle> Asistentes </v-card-subtitle>
                            <v-card-text class="text-center">
                                <h2>{{ total_participants }}/{{ max_participants }}</h2>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card>
                            <v-card-subtitle> Ventas CPL$ </v-card-subtitle>
                            <v-card-text class="text-center">
                                <h2>$3.000.000</h2>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="3">
                        <v-card>
                            <v-card-subtitle>Acceso por Dispositivo</v-card-subtitle>
                            <v-card-text>
                                <apexchart ref="chartDevices" width="100%" height="250" type="donut"
                                    :options="optionsDevices" :series="seriesDevices" />
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="5">
                        <v-card>
                            <v-card-subtitle>Accesos</v-card-subtitle>
                            <v-card-text>
                                <apexchart ref="chart" width="100%" height="250" type="area" :options="optionsAccess"
                                    :series="seriesAccess" />
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="4">
                        <v-card>
                            <v-card-subtitle>Asistentes Por Webinar</v-card-subtitle>
                            <v-card-text>
                                <apexchart ref="chartConfirmed" width="100%" height="250" type="bar"
                                    :options="optionsConfirmed" :series="seriesConfirmed" />
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row justify="center" class="mt-8">
                    <v-col cols="10">
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <!-- <th class="text-center">MES 1</th> -->
                                    <th class="text-center">Nombre</th>
                                    <!-- <th class="text-center">ID</th> -->
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Hora</th>
                                    <th class="text-center">Asistentes</th>
                                    <th class="text-center">Mail Enviados</th>
                                    <th class="text-center">Dashboard LINK</th>
                                </thead>
                                <tbody>
                                    <tr v-for="webinar of webinars" :key="webinar.id">
                                        <!-- <td class="text-center"></td> -->
                                        <td class="text-center">{{ webinar.name }}</td>
                                        <!-- <td class="text-center">{{ webinar.id }}</td> -->
                                        <td class="text-center">{{ webinar.start_date }}</td>
                                        <td class="text-center">{{ webinar.start_time }}</td>
                                        <td class="text-center">{{ webinar.registered_participants_count }}</td>
                                        <td class="text-center">{{ webinar.sent_mail_count }}</td>
                                        <td class="text-center">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon small class="mr-2" @click.stop="
                                                        $router.push({ name: 'webinarsingledashboard', params: { id: webinar.id } })
                                                        " v-on="on">mdi-view-dashboard-variant</v-icon>
                                                </template>
                                                <span>Dashboard del Webinar</span>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </div>
</template>

<script>
import { forEach } from 'lodash'
import { mapState } from 'vuex'
import VueApexCharts from 'vue-apexcharts'
export default {
    name: 'WebinarDashboard',

    components: {
        apexchart: VueApexCharts,
    },

    data: function () {
        return {
            total_emails: 0,
            max_emails: 0,

            total_participants: 0,
            max_participants: 0,

            loading: false,
            webinars: [],

            seriesDevices: [],
            seriesAccess: [],
            seriesConfirmed: [{ data: [] }],

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

            optionsAccess: {
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                dataLabels: {
                    enabled: false,
                },
                labels: []
            },

            optionsConfirmed: {
                dataLabels: {
                    enabled: false,
                },
                labels: []
            },
        }
    },

    computed: {
        ...mapState(['webinarPackage']),
    },

    created() {
        let webinars = this.axios
            .get('/webinars/data')
            .then((response) => {
                this.webinars = response.data.webinars
                this.webinars.sort((a, b) => new Date(b.start_date) - new Date(a.start_date))

                for (const webinar of this.webinars) {
                    forEach(webinar.devices, (value, key) => {
                        let index = this.optionsDevices.labels.indexOf(key)
                        if (index < 0) {
                            this.optionsDevices.labels.push(key)
                            this.seriesDevices.push(value)
                        } else {
                            this.seriesDevices[index] += value
                        }
                    })

                    this.total_emails += webinar.sent_mail_count
                    this.total_participants += webinar.registered_participants_count

                    this.optionsConfirmed.labels.push(webinar.name)
                    this.seriesConfirmed[0].data.push(webinar.registered_participants_count)
                }

                this.max_emails = response.data.package.mails
                this.max_participants = response.data.package.participants
            })
            .catch((error) => {
                console.log(error)
                this.$snotify.error(this.$t('general_error'))
                this.$router.push('/webinars')
            })

        Promise.all([webinars])
            .finally(() => {
                this.loading = false
            })
    },
}
</script>

<style>
.dashboard td {
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 10px;
    padding-bottom: 10px;
    border: 1px solid;
    margin: 0px 0px 0px 0px;
}
</style>
