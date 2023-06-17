<template>
    <v-content>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card class="elevation-12">
                        <v-toolbar color="primary">
                            <v-card-title
                                class="white--text title"
                            >{{ $t('link.permissions').toUpperCase() }}</v-card-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-data-table
                                :headers="headers"
                                :items="users"
                                hide-default-footer
                                class="elevation-1"
                                :loading="loading"
                                item-key="id"
                            >
                                <template
                                    v-slot:item.name="{ item }"
                                >{{ `${item.name} ${item.lastname}` }}</template>

                                <template v-slot:item.databases="{ item }">
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('database-manager')"
                                            @change="toggleRole(item, 'database-manager')"
                                        ></v-switch>
                                        <v-chip
                                            v-else
                                            color="success"
                                            text-color="white"
                                        >Manager</v-chip>
                                    </v-row>
                                </template>

                                <template
                                    v-slot:item.events="{ item }"
                                    class="text-xs-center"
                                >
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('event-manager')"
                                            @change="toggleRole(item, 'event-manager')"
                                            :loading="loading"
                                        ></v-switch>
                                    </v-row>
                                </template>

                                <template
                                    v-slot:item.actors="{ item }"
                                    class="text-xs-center"
                                >
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('actor-manager')"
                                            @change="toggleRole(item, 'actor-manager')"
                                            :loading="loading"
                                        ></v-switch>
                                    </v-row>
                                </template>

                                <template
                                    v-slot:item.sponsors="{ item }"
                                    class="text-xs-center"
                                >
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('sponsor-manager')"
                                            @change="toggleRole(item, 'sponsor-manager')"
                                            :loading="loading"
                                        ></v-switch>
                                    </v-row>
                                </template>

                                <template
                                    v-slot:item.polls="{ item }"
                                    class="text-xs-center"
                                >
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('poll-manager')"
                                            @change="toggleRole(item, 'poll-manager')"
                                            :loading="loading"
                                        ></v-switch>
                                    </v-row>
                                </template>

                                <template
                                    v-slot:item.products="{ item }"
                                    class="text-xs-center"
                                >
                                    <v-row justify="center">
                                        <v-switch
                                            v-if="!isCompanyAdmin(item)"
                                            :input-value="item.roles.includes('product-manager')"
                                            @change="toggleRole(item, 'product-manager')"
                                            :loading="loading"
                                        ></v-switch>
                                    </v-row>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12">
                    <v-card class="elevation-12">
                        <v-toolbar color="primary">
                            <v-card-title
                                class="white--text title"
                            >{{ $t('link.collectors').toUpperCase() }}</v-card-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <v-data-table
                                        :headers="eventHeaders"
                                        :items="events"
                                        hide-default-footer
                                        class="elevation-1"
                                        :loading="loading"
                                        item-key="id"
                                    >
                                        <template
                                            v-slot:item.collectors="{ item }"
                                        >
                                            <v-combobox
                                                v-model="select[item.id]"
                                                :items="users"
                                                :item-text="(user) => `${user.name} ${user.lastname}`"
                                                :item-value="(user) => user.id"
                                                chips
                                                multiple
                                            ></v-combobox>
                                        </template>

                                        <template
                                            v-slot:item.actions="{ item }"
                                        >
                                            <v-row justify="center">
                                                <v-btn
                                                    text
                                                    icon
                                                    :loading="loading"
                                                    @click="syncCollectors(item.id)"
                                                >
                                                    <v-icon>mdi-content-save</v-icon>
                                                </v-btn>
                                            </v-row>
                                        </template>
                                    </v-data-table>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            users: [],
            events: [],
            key: 0,
            select: {},
            headers: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$t('link.databases').capitalize(),
                    value: 'databases',
                    align: 'center'
                },
                {
                    text: this.$t('link.events').capitalize(),
                    value: 'events',
                    align: 'center'
                },
                {
                    text: this.$t('link.actors').capitalize(),
                    value: 'actors',
                    align: 'center'
                },
                {
                    text: this.$t('link.sponsors').capitalize(),
                    value: 'sponsors',
                    align: 'center'
                },
                {
                    text: this.$t('link.polls').capitalize(),
                    value: 'polls',
                    align: 'center'
                },
                {
                    text: this.$t('product.title').capitalize(),
                    value: 'products',
                    align: 'center'
                }
            ],
            eventHeaders: [
                {
                    text: this.$t('form.name').capitalize(),
                    align: 'left',
                    value: 'name',
                    width: '30%'
                },
                {
                    text: this.$t('link.collectors').capitalize(),
                    value: 'collectors'
                },
                {
                    text: this.$t('actions').capitalize(),
                    value: 'actions',
                    width: '10%'
                }
            ]
        }
    },

    methods: {
        isCompanyAdmin(user) {
            return user.roles.includes('company-admin')
        },
        syncCollectors(eventId) {
            this.loading = true
            this.axios
                .post(`events/${eventId}/collectors`, {
                    collectors: this.select[eventId].map(event => event.id)
                })
                .then(response => {
                    this.$snotify.success(this.$t('saved').capitalize())
                })
                .catch(error => {
                    this.$snotify.error(this.$t('general_error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
        toggleRole(user, role) {
            this.loading = true
            if (user.roles.includes(role)) {
                this.axios
                    .post(`users/${user.id}/removeRole`, { role })
                    .then(response => {
                        const index = user.roles.indexOf(role)
                        if (index > -1) {
                            user.roles.splice(index, 1)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                        this.$snotify.error(this.$t('general_error'))
                    })
                    .finally(() => {
                        this.loading = false
                        console.log(user.roles)
                    })
            } else {
                this.axios
                    .post(`users/${user.id}/addRole`, { role })
                    .then(response => {
                        user.roles.push(role)
                    })
                    .catch(error => {
                        this.$snotify.error(this.$t('general_error'))
                    })
                    .finally(() => {
                        this.loading = false
                        console.log(user.roles)
                    })
            }
        }
    },
    mounted() {
        this.loading = true
        const users = this.axios
            .get('/users/roles')
            .then(response => {
                this.users = response.data.users
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
            })

        const events = this.axios
            .get('/events')
            .then(response => {
                this.events = response.data.events
                for (event of this.events) {
                    this.select[event.id] = event.collectors
                }
            })
            .catch(error => {
                this.$snotify.error(this.$t('general_error'))
            })

        Promise.all([users, events]).finally(() => {
            this.loading = false
        })
    }
}
</script>

<style>
</style>
