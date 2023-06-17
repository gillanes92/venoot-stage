<template>
  <div id="welcome">
    <v-img
      src="../images/conference.jpg"
    >
      <v-content>
        <v-container fluid>
          <h1 class="white--text">PAGINA DE BIENVENIDA</h1>
        </v-container>
      </v-content>
    </v-img>
  </div>
</template>

<script>
  export default {
    data: ()  => ({

    }),

    created() {
      if (this.$auth.user().category === 'webinar') {
          this.$router.push('/webinardashboard')
      }
    }
  }
</script>

<style>

</style>
