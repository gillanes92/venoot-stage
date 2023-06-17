<template>
    <div id="builder">
        <v-container fluid fill-height>
            <v-content>
                <v-row>
                    <v-col>
                        <iframe
                            :src="`${this.BASE_URL}/templates/${
                                this.$route.params.id
                            }/builderjs?token=${this.$auth.token()}`"
                        ></iframe>
                    </v-col>
                </v-row>
            </v-content>
        </v-container>
    </div>
</template>

<script>
export default {
    name: 'Builder',
}
</script>

<style scoped>
#builder {
    height: 100%;
}

.v-content {
    height: 100%;
}

.v-content__wrap {
    height: 100%;
}

iframe {
    width: 100%;
    height: 100%;
}

.col {
    height: 100%;
}

.row {
    height: 100%;
}
</style>
