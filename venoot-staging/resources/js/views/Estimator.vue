<template>
    <v-content>
        <v-container>
            <v-row class="title">
                <v-col>Cotizador de Servicios</v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-alert
                        type="info"
                        icon="mdi-alert-circle"
                    >Selecciona el servicio que gustes y obten en linea la información del precio asociado al uso de este, podrás modificar tu selección inicial, agregando servicios; Podrás guardar la cotizacion o ir de inmediato a crear tu actividad.</v-alert>
                </v-col>
            </v-row>
            <v-row justify="space-around">
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('full')"
                    >
                        <v-container>
                            <v-row wrap>
                                <v-img
                                    max-height="100"
                                    src="../storage/iconos/evento-completo.svg"
                                    aspect-ratio="1"
                                    contain
                                ></v-img>
                            </v-row>
                            <v-row>
                                <v-col class="text-center">Crear Evento Completo</v-col>
                            </v-row>
                        </v-container>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('webinar')"
                    >
                        <v-card-title primary-title>
                            <v-container>
                                <v-row wrap>
                                    <v-img
                                        max-height="100"
                                        src="../storage/iconos/email.svg"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col class="text-center">Crear Webinar</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('emailing')"
                    >
                        <v-card-title primary-title>
                            <v-container>
                                <v-row wrap>
                                    <v-img
                                        max-height="100"
                                        src="../storage/iconos/email.svg"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col class="text-center">Crear eMailings</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('form')"
                    >
                        <v-card-title primary-title>
                            <v-container>
                                <v-row wrap>
                                    <v-img
                                        max-height="100"
                                        src="../storage/iconos/formulario.svg"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col class="text-center">Crear Formulario</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('poll')"
                    >
                        <v-card-title primary-title>
                            <v-container fluid>
                                <v-row wrap>
                                    <v-img
                                        max-height="100"
                                        src="../storage/iconos/encuesta.svg"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col class="text-center">Crear Encuesta</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('cart')"
                    >
                        <v-card-title primary-title>
                            <v-container fluid>
                                <v-row wrap>
                                    <v-img
                                        max-height="80"
                                        src="../storage/iconos/carro.png"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col
                                        class="text-center"
                                    >Crear Carro Compra</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="6" md="4" class="d-flex justify-center">
                    <v-card
                        max-width="300"
                        width="100%"
                        height="200"
                        @click="openDialog('app')"
                    >
                        <v-card-title primary-title>
                            <v-container fluid>
                                <v-row wrap>
                                    <v-img
                                        max-height="100"
                                        src="../storage/iconos/app.svg"
                                        aspect-ratio="1"
                                        contain
                                    ></v-img>
                                </v-row>
                                <v-row>
                                    <v-col class="text-center">Crear App</v-col>
                                </v-row>
                            </v-container>
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <v-dialog v-model="landingDialog" persistent scrollable max-width="90%">
            <v-card>
                <v-card-title primary-title>Configuración de Servicios</v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-simple-table>
                                    <template v-slot:default>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <v-alert
                                                        type="info"
                                                    >Todos los servicios pueden ser agregados posteriormente al servicio.</v-alert>
                                                </td>
                                                <td class="title">$ CLP</td>
                                            </tr>
                                            <tr
                                                v-if="['full'].includes(category)"
                                            >
                                                <td class="title">Landing</td>
                                                <td>
                                                    <v-switch
                                                        v-model="estimate.landing"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.landing"
                                                    >{{ 55000 | currency }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'form'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Confirmación de Asistencía</td>
                                                <td>
                                                    <v-switch
                                                        :disabled="estimate.ticket_sale"
                                                        v-model="estimate.confirmation_form"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.confirmation_form"
                                                    >{{ 0 | currency }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'cart'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Venta de eTickets</td>
                                                <td>
                                                    <v-switch
                                                        :disabled="estimate.confirmation_form"
                                                        v-model="estimate.ticket_sale"
                                                    ></v-switch>
                                                </td>
                                                <td
                                                    class="justify-center subtitle"
                                                >Webpay 9% | Paypal 15%</td>
                                                <td class="subtitle"></td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'emailing', 'form', 'poll', 'app', 'cart'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Envios de eMails</td>
                                                <td>
                                                    <v-switch
                                                        v-model="estimate.mailings"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field
                                                        v-if="estimate.mailings"
                                                        class="inputQuantity"
                                                        v-model="estimate.mailings_quantity"
                                                        hide-details
                                                        single-line
                                                        type="number"
                                                    ></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.mailings"
                                                    >{{ estimate.mailings_quantity * 5 | currency }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'poll', 'app'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Creación de Encuestas</td>
                                                <td>
                                                    <v-switch
                                                        v-model="estimate.polls"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field
                                                        v-if="estimate.polls"
                                                        class="inputQuantity"
                                                        v-model="estimate.polls_quantity"
                                                        hide-details
                                                        single-line
                                                        type="number"
                                                    ></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.polls"
                                                    >{{ estimate.polls_quantity * 10000 | currency }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'cart'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Llaves de Acreditación QR</td>
                                                <td>
                                                    <v-switch
                                                        v-model="estimate.register_keys"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle">
                                                    <v-text-field
                                                        v-if="estimate.register_keys"
                                                        class="inputQuantity"
                                                        v-model="estimate.register_keys_quantity"
                                                        hide-details
                                                        single-line
                                                        type="number"
                                                    ></v-text-field>
                                                </td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.register_keys"
                                                    >{{ estimate.register_keys_quantity * 20000 | currency }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="['full', 'app'].includes(category)"
                                            >
                                                <td
                                                    class="title"
                                                >Aplicación Telefono</td>
                                                <td>
                                                    <v-switch
                                                        v-model="estimate.app"
                                                    ></v-switch>
                                                </td>
                                                <td class="subtitle"></td>
                                                <td class="subtitle">
                                                    <span
                                                        v-if="estimate.app"
                                                    >Te contactaremos</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                                <v-expansion-panels>
                                    <v-expansion-panel>
                                        <v-expansion-panel-header>Otros Servicios</v-expansion-panel-header>
                                        <v-expansion-panel-content>
                                            <v-simple-table>
                                                <template v-slot:default>
                                                    <tbody>
                                                        <tr
                                                            v-if="['full', 'emailing', 'form', 'poll', 'app', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title title-columm"
                                                                style="max-width: 200px"
                                                            >Administración</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.administration"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            ></td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.administration"
                                                                >{{ 45000 | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'emailing', 'form', 'poll', 'app', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >DISEÑO GRAFICO ( ADECUAR LOGOS, UN BANNER, HASTA DIEZ FOTOS, PALETA LANDING )</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.graphical_design"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            ></td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.graphical_design"
                                                                >{{ 55000 | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >ACREDITACION ( POR PERSONA ACREDITADA )</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.registering"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <v-text-field
                                                                    v-if="estimate.registering"
                                                                    class="inputQuantity"
                                                                    v-model="estimate.confirmed_amount"
                                                                    hide-details
                                                                    single-line
                                                                    type="number"
                                                                ></v-text-field>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.registering"
                                                                >{{ 1000 * estimate.confirmed_amount | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >LANYAR SIN LOGO</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.lanyards"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <v-text-field
                                                                    v-if="estimate.lanyards"
                                                                    class="inputQuantity"
                                                                    v-model="estimate.lanyard_amount"
                                                                    hide-details
                                                                    single-line
                                                                    type="number"
                                                                ></v-text-field>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.lanyards"
                                                                >{{ 800 * estimate.lanyard_amount | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >CREDENCIALES 4X0</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.credentials"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <v-text-field
                                                                    v-if="estimate.credentials"
                                                                    class="inputQuantity"
                                                                    v-model="estimate.credential_amount"
                                                                    hide-details
                                                                    single-line
                                                                    type="number"
                                                                ></v-text-field>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.credentials"
                                                                >{{ 800 * estimate.credential_amount | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >ACREDITADOR MEDIA JORNADA</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.collectors_half"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <v-text-field
                                                                    v-if="estimate.collectors_half"
                                                                    class="inputQuantity"
                                                                    v-model="estimate.collector_half_amount"
                                                                    hide-details
                                                                    single-line
                                                                    type="number"
                                                                ></v-text-field>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.collectors_half"
                                                                >{{ 25000 * estimate.collector_half_amount | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >ACREDITADOR JORNADA COMPLETA</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.collectors_full"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <v-text-field
                                                                    v-if="estimate.collectors_full"
                                                                    class="inputQuantity"
                                                                    v-model="estimate.collector_full_amount"
                                                                    hide-details
                                                                    single-line
                                                                    type="number"
                                                                ></v-text-field>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.collectors_full"
                                                                >{{ 50000 * estimate.collector_full_amount | currency }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            v-if="['full', 'form', 'poll', 'cart'].includes(category)"
                                                        >
                                                            <td
                                                                class="title"
                                                            >DESARROLLO</td>
                                                            <td>
                                                                <v-switch
                                                                    v-model="estimate.development"
                                                                ></v-switch>
                                                            </td>
                                                            <td
                                                                class="subtitle"
                                                            ></td>
                                                            <td
                                                                class="subtitle"
                                                            >
                                                                <span
                                                                    v-if="estimate.development"
                                                                >Te contactaremos</span>
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
                                                <td
                                                    colspan="3"
                                                    class="title text-right"
                                                >Total Neto</td>
                                                <td
                                                    class="subtitle"
                                                >{{ netTotal | currency }}</td>
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
                    <v-btn
                        color="blue"
                        class="white--text"
                        :loading="loading"
                        @click="save(false)"
                    >Guardar Cotizacion</v-btn>
                    <v-btn
                        color="success"
                        :loading="loading"
                        @click="save(true)"
                    >Ir a Crear Evento</v-btn>
                    <v-btn
                        color="black"
                        class="white--text"
                        :loading="loading"
                        @click="landingDialog = false"
                    >Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="webinarDialog" persistent scrollable max-width="90%">
            <v-card>
                <v-card-text>
                    <v-container>
                        <v-row class="text-center">
                          <v-col cols="4">
                            <v-card color="#EE312F" dark>
                                <v-card-title class="display-1">WEBINAR 1</v-card-title>
                                <v-card-text>
                                  <v-simple-table light>
                                    <template v-slot:default>
                                      <tbody class="body-2">
                                        <tr><td>4 EXPOSITORES</td></tr>
                                        <tr><td>50 ESPECTADORES</td></tr>
                                        <tr><td>FORMULARIO DE INSCRIPCION</td></tr>
                                        <tr><td>TRASMISION</td></tr>
                                        <tr><td>200 INVITACION EMAIL</td></tr>
                                        <tr><td>CONFIGURACION ACCESO</td></tr>
                                        <tr><td>CHAT</td></tr>
                                        <tr><td>ESTADISTICAS</td></tr>
                                        <tr class="black white--text"><td class="title" @click="$emit('open-webinar', 1)" style="cursor: pointer;">LO QUIERO</td></tr>
                                      </tbody>
                                    </template>
                                  </v-simple-table>
                                </v-card-text>
                            </v-card>
                          </v-col>

                          <v-col cols="4" >
                            <v-card color="#EE312F" dark>
                                <v-card-title class="display-1">WEBINAR 2</v-card-title>
                                <v-card-text>
                                  <v-simple-table light>
                                    <template v-slot:default>
                                      <tbody class="body-2">
                                        <tr><td>6 EXPOSITORES</td></tr>
                                        <tr><td>100 ESPECTADORES</td></tr>
                                        <tr><td>FORMULARIO DE INSCRIPCION</td></tr>
                                        <tr><td>TRASMISION / 4 MULTISALAS</td></tr>
                                        <tr><td>400 INVITACION EMAIL</td></tr>
                                        <tr><td>CONFIGURACION ACCESO</td></tr>
                                        <tr><td>CHAT</td></tr>
                                        <tr><td>ESTADISTICAS</td></tr>
                                        <tr class="black white--text"><td class="title" @click="$emit('open-webinar', 2)" style="cursor: pointer;">LO QUIERO</td></tr>
                                      </tbody>
                                    </template>
                                  </v-simple-table>
                                </v-card-text>
                            </v-card>
                          </v-col>

                          <v-col cols="4" >
                            <v-card color="#EE312F" dark>
                                <v-card-title class="display-1">WEBINAR 3</v-card-title>
                                <v-card-text>
                                  <v-simple-table light>
                                    <template v-slot:default>
                                      <tbody class="body-2">
                                        <tr><td>10 EXPOSITORES</td></tr>
                                        <tr><td>200 ESPECTADORES</td></tr>
                                        <tr><td>FORMULARIO DE INSCRIPCION</td></tr>
                                        <tr><td>TRASMISION / 8 MULTISALAS</td></tr>
                                        <tr><td>800 INVITACION EMAIL</td></tr>
                                        <tr><td>CONFIGURACION ACCESO</td></tr>
                                        <tr><td>CHAT</td></tr>
                                        <tr><td>ESTADISTICAS</td></tr>
                                        <tr class="black white--text"><td class="title" @click="$emit('open-webinar', 3)" style="cursor: pointer;">LO QUIERO</td></tr>
                                      </tbody>
                                    </template>
                                  </v-simple-table>
                                </v-card-text>
                            </v-card>
                          </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
          </v-card>
        </v-dialog>
    </v-content>
</template>

<script>
export default {
    data: function() {
        return {
            loading: false,
            landingDialog: false,
            webinarDialog: false,
            category: 'full',
            databases: [],
            estimate: {
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
                confirmed_amount: 0,
                lanyards: false,
                lanyard_amount: 0,
                credentials: false,
                credential_amount: 0,
                collectors_half: false,
                collectors_full: false,
                collector_half_amount: 0,
                collector_full_amount: 0,
                development: false
            }
        }
    },
    computed: {
        netTotal: function() {
            return (
                (this.estimate.landing ? 55000 : 0) +
                // (this.estimate.confirmation_form ? 15000 : 0) +
                (this.estimate.mailings
                    ? this.estimate.mailings_quantity * 5
                    : 0) +
                (this.estimate.polls
                    ? this.estimate.polls_quantity * 10000
                    : 0) +
                (this.estimate.register_keys
                    ? this.estimate.register_keys_quantity * 20000
                    : 0) +
                (this.estimate.administration ? 45000 : 0) +
                (this.estimate.graphical_design ? 55000 : 0) +
                (this.estimate.registering
                    ? this.estimate.confirmed_amount * 1000
                    : 0) +
                (this.estimate.lanyards
                    ? this.estimate.lanyard_amount * 800
                    : 0) +
                (this.estimate.credentials
                    ? this.estimate.credential_amount * 800
                    : 0) +
                (this.estimate.collectors_half
                    ? this.estimate.collector_half_amount * 25000
                    : 0) +
                (this.estimate.collectors_full
                    ? this.estimate.collector_full_amount * 50000
                    : 0)
            )
        }
    },
    methods: {
        openDialog(category) {
            this.category = category
            switch (category) {
                case 'full':
                    this.estimate = {
                        landing: true,
                        webinar:false,
                        webinar_category: null,
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
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break

                case 'webinar':
                  this.estimate = {
                      landing: false,
                      webinar:true,
                      webinar_category: 'basic',
                      confirmation_form: false,
                      mailings: true,
                      mailings_quantity: 1,
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
                      confirmed_amount: 0,
                      lanyard_amount: 0,
                      credential_amount: 0,
                      collector_half_amount: 0,
                      collector_full_amount: 0
                  }
                  break

                case 'emailing':
                    this.estimate = {
                        landing: false,
                        webinar:false,
                        webinar_category: null,
                        confirmation_form: false,
                        mailings: true,
                        mailings_quantity: 1,
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
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break

                case 'form':
                    this.estimate = {
                        landing: false,
                        webinar:false,
                        webinar_category: null,
                        confirmation_form: true,
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
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break

                case 'poll':
                    this.estimate = {
                        landing: false,
                        webinar:false,
                        webinar_category: null,
                        confirmation_form: false,
                        mailings: false,
                        mailings_quantity: 0,
                        polls: true,
                        polls_quantity: 1,
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
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break

                case 'cart':
                    this.estimate = {
                        landing: false,
                        webinar:false,
                        webinar_category: null,
                        confirmation_form: false,
                        mailings: false,
                        mailings_quantity: 0,
                        polls: false,
                        polls_quantity: 0,
                        register_keys: false,
                        register_keys_quantity: 0,
                        ticket_sale: true,
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
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break

                case 'app':
                    this.estimate = {
                        landing: false,
                        webinar:false,
                        webinar_category: null,
                        confirmation_form: false,
                        mailings: false,
                        mailings_quantity: 0,
                        polls: false,
                        polls_quantity: 0,
                        register_keys: false,
                        register_keys_quantity: 0,
                        ticket_sale: false,
                        app: true,
                        free_tickets: true,
                        administration: false,
                        graphical_design: false,
                        registering: false,
                        lanyards: false,
                        credentials: false,
                        collectors_half: false,
                        collectors_full: false,
                        development: false,
                        confirmed_amount: 0,
                        lanyard_amount: 0,
                        credential_amount: 0,
                        collector_half_amount: 0,
                        collector_full_amount: 0
                    }
                    break
            }
            if (category !== 'webinar') {
                this.landingDialog = true
            } else {
              this.webinarDialog = true
            }

        },
        save(goToEvent) {
            this.loading = true
            this.axios
                .post('/estimates', { ...this.estimate })
                .then(response => {
                    this.estimate = response.data.estimate

                    if (goToEvent) {
                        if (this.databases.length === 0) {
                            this.$router.push({
                                name: 'databases'
                            })
                        } else {
                            this.$router.push({
                                name: 'event',
                                params: { estimate: this.estimate }
                            })
                        }
                    } else {
                        this.$router.push('/dashboard')
                    }
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

    created() {
        this.axios.get('databases').then(response => {
            this.databases = response.data.databases
        })
    }
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

.title-columm {
    max-width: 200px;
}
</style>
