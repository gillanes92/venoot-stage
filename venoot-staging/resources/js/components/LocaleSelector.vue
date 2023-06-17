<template>
    <v-menu bottom left>
        <template v-slot:activator="{ on }">
            <v-btn class="white--text" icon v-on="on">
                <v-icon class="white--text">language</v-icon>
                {{ $i18n.locale }}
            </v-btn>
        </template>

        <v-list>
            <v-list-item
                v-for="(lang, i) in langs"
                :key="i"
                @click="changeLocale(lang)"
            >
                <v-list-item-title>{{ lang.toUpperCase() }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
export default {
    data: () => ({
        langs: ['es', 'en']
    }),
    methods: {
        async changeLocale(lang) {
            try {
                await this.axios.post('locale', { locale: lang })
                this.$i18n.locale = lang
                this.$vuetify.lang.current = lang
            } catch (e) {
                console.log(e)
            }
        }
    }
}
</script>

<style>
</style>
