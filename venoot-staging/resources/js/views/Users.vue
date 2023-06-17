<template>
  <div id="dashboard">
    <v-content>
      <v-container grid-list-md>
        <v-layout align-center justify-center wrap>
          <v-flex xs12 shrink>
            <v-card
              elevation='12'
            >
              <v-toolbar color="primary" >
                <v-card-title class="white--text title">{{ $t('users.companyUsers').toUpperCase() }}</v-card-title>
              </v-toolbar>
              <v-card-text>
                <v-data-table
                  :headers="companyUsersHeaders"
                  :items="companyUsers"
                  :loading="companyUsersLoading"
                >
                  <template v-slot:items="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.lastname }}</td>
                    <td>{{ props.item.email }}</td>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-flex>
          <v-flex xs12 md12>
            <v-card
              elevation='12'
              tile
            >
              <v-toolbar color="primary" >
                <v-card-title class="white--text title">{{ $t('users.usersRequested').toUpperCase() }}</v-card-title>
              </v-toolbar>
              <v-card-text>
                <v-data-table
                  :headers="usersRequestedHeaders"
                  :items="usersRequested"
                  :loading="userRequestedLoading"
                >
                  <template v-slot:items="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.lastname }}</td>
                    <td>{{ props.item.email }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td class="justify-center px-0">
                      <v-icon
                        small
                        class="mr-2"
                        @click="acceptUser(props.item)"
                      >
                        check
                      </v-icon>
                      <v-icon
                        small
                        class="mr-2"
                        @click="rejectUser(props.item)"
                      >
                        clear
                      </v-icon>
                    </td>
                  </template>
                </v-data-table>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </div>
</template>

<script>
export default {
  data () {
    return {
      companyUsersLoading: true,
      userRequestedLoading: true,
      companyUsers: [],
      usersRequested: [],
      companyUsersHeaders: [
        {
          text: this.$t('form.name').capitalize(),
          align: 'left',
          value: 'name'
        },
        {
          text: this.$t('form.lastname').capitalize(),
          align: 'left',
          value: 'lastname'
        },
        {
          text: this.$t('form.mail').capitalize(),
          value: 'email'
        }
      ],
      usersRequestedHeaders: [
        {
          text: this.$t('form.name').capitalize(),
          align: 'left',
          value: 'name'
        },
        {
          text: this.$t('form.lastname').capitalize(),
          align: 'left',
          value: 'lastname'
        },
        {
          text: this.$t('form.mail').capitalize(),
          value: 'email'
        },
        {
          text: this.$t('form.created_at').capitalize(),
          value: 'created_at'
        },
        { text: this.$t('actions').capitalize(), value: 'name', sortable: false }
      ]
    }
  },
  methods: {
    acceptUser (user) {
      this.userRequestedLoading = true
      this.axios.get(`/users/${user.id}/accept`)
        .then((response) => {
          this.usersRequested = this.usersRequested.filter(u => u.id != user.id)
          this.companyUsersLoading = true
          this.axios.get('/users')
            .then((response) => {
              this.companyUsers = response.data.users
            })
            .catch (error => {
              this.$snotify.error(this.$t('general_error'))
            })
            .finally(() => {
              this.companyUsersLoading = false
            })
        })
        .catch (error => {
          this.$snotify.error(this.$t('general_error'))
        })
        .finally(() => {
          this.userRequestedLoading = false
        })
    },
    rejectUser (user) {
       this.userRequestedLoading = true
      this.axios.get(`/users/${user.id}/reject`)
        .then((response) => {
          this.usersRequested = this.usersRequested.filter(u => u.id != user.id)
        })
        .catch (error => {
          this.$snotify.error(this.$t('general_error'))
        })
        .finally(() => {
          this.userRequestedLoading = false
        })
    }
  },
  mounted () {
    this.companyUsersLoading = true
    this.axios.get('/users')
      .then((response) => {
        this.companyUsers = response.data.users
      })
      .catch (error => {
        this.$snotify.error(this.$t('general_error'))
      })
      .finally(() => {
        this.companyUsersLoading = false
      })

    this.userRequestedLoading = true
    this.axios.get('/users/requested')
      .then((response) => {
        this.usersRequested = response.data.usersRequested
      })
      .catch (error => {
        this.$snotify.error(this.$t('general_error'))
      })
      .finally(() => {
        this.userRequestedLoading = false
      })
  }
}
</script>

<style>

</style>
