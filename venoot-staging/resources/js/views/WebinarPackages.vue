<template>
    <div>
        <v-content fluid fill-height>
            <v-row >
                <v-col cols="10">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Star</th>
                                    <th class="text-center">Pro</th>
                                    <th class="text-center">Premium</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Duracion</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">4</td>
                                    <td class="text-center">6</td>
                                </tr>
                                <tr>
                                    <td>Expositores</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center">4</td>
                                    <td class="text-center">10</td>
                                </tr>
                                <tr>
                                    <td>Asistentes</td>
                                    <td class="text-center">100</td>
                                    <td class="text-center">1000</td>
                                    <td class="text-center">5000</td>
                                </tr>
                                <tr>
                                    <td>Correos</td>
                                    <td class="text-center">300</td>
                                    <td class="text-center">3000</td>
                                    <td class="text-center">15000</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center"><v-btn small :disabled="webinarPackage === 1" color="success" elevation="2" @click="buy(1)">Comprar</v-btn></td>
                                    <td class="text-center"><v-btn small :disabled="webinarPackage === 2" color="success" elevation="2" @click="buy(2)">Comprar</v-btn></td>
                                    <td class="text-center"><v-btn small :disabled="webinarPackage === 3" color="success" elevation="2" @click="buy(3)">Comprar</v-btn></td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-content>
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex'
export default {
    name: 'WebinarPackages',

    data: function () {
        return {}
    },

    computed: {
        ...mapState(['webinarPackage'])
    },

    methods: {
        ...mapMutations(['SET_WEBINAR_PACKAGE']),

        buy(webinarPackage) {
            if (!this.$auth.check()) {
                window.localStorage.setItem('goTo', 'webinarpackages')
                this.$router.push('/login')
                return
            }

            this.SET_WEBINAR_PACKAGE(webinarPackage)
            this.$router.push('/webinars')
        }
    },

    mounted() {
        if (this.webinarPackage) {
          this.$router.push('/webinars')
          return
        }
    },
}
</script>
