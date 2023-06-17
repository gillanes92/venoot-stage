<template>
    <v-content>
        <v-container>
            <v-row class="title">
                <v-col>Configuracion de LandingPage</v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    data: function() {
        return {
            loading: false,
            landingDialog: false,
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
                free_tickets: true,
                netTotal: 0
            }
        }
    }
    // mounted() {

    //     this.loading = true

    //      this.axios
    //         .get(`/events/${this.$route.params.id}`)
    // }
}
</script>

<style>
</style>
