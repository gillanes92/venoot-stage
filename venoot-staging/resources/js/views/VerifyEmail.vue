<template>
  <div id="verify-mail">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar color="primary">
                <v-card-title class="white--text title">{{ $t('link.verify').toUpperCase() }}</v-card-title>
              </v-toolbar>
              <v-card-text>
                <v-container grid-list-md text-xs-center>
                  <v-layout align-center justify-center row wrap>
                    <v-flex xs12 mb-4>
                      {{ $t('verify.verifiying_email') }}
                    </v-flex>
                    <v-flex xs12>
                      <v-progress-circular
                        indeterminate
                        color="primary"
                      ></v-progress-circular>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  mounted () {
    if (this.$route.query.verifyLink) {
      axios.get(this.$route.query.verifyLink)
        .then(response => {
          this.$snotify.info(this.$t('verify.success'))
          this.$router.push('login')
        })
        .catch((error) => {
        })
    } else {
      this.$router.push('signup')
    }
  }
}
</script>

<style>

</style>
