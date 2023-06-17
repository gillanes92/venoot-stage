<template>
  <div class="landing-creator">
    <v-content style="padding: 0px">
      <v-container fluid>
        <v-layout align-center justify-center>
          <v-flex xs12>
            <div id="gjsLanding">
            </div>
            <div id="blocksLanding"></div>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  },
  props: {
    event: {
      type: Object,
      default: {}
    }
  },
  mounted () {

  }
}
</script>

<style scoped>
</style>

