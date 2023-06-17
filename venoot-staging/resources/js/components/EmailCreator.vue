<template>
  <div class="email-creator">
    <v-content style="padding: 0px">
      <v-container fluid>
        <v-layout align-center justify-center>
          <v-flex xs12>
            <div id="gjsEmail">
              <h1>Hello World Component!</h1>
            </div>
            <div id="blocksEmail"></div>
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
  mounted () {

  }
}
</script>

<style>
/* Let's highlight canvas boundaries */
#gjs {
  border: 3px solid #444;
}

/* Reset some default styling */
.gjs-cv-canvas {
  top: 0;
  width: 100%;
  height: 100%;
}
</style>

