<template>
    <v-form data-vv-scope="actorForm">
        <v-container grid-list-md class="actor">
            <v-layout wrap>
                <v-flex xs1>
                    <v-text-field
                        v-validate="'max:30'"
                        data-vv-name="prefix"
                        :error-messages="errors.collect('actorForm.prefix')"
                        :counter="30"
                        required
                        prepend-icon="work"
                        v-model="actor.prefix"
                        :label="$t('actor.prefix').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:30'"
                        data-vv-name="name"
                        :error-messages="errors.collect('actorForm.name')"
                        :counter="30"
                        required
                        prepend-icon="work"
                        v-model="actor.name"
                        :label="$t('form.name').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                 <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:30'"
                        data-vv-name="lastname"
                        :error-messages="errors.collect('actorForm.lastname')"
                        :counter="30"
                        required
                        prepend-icon="work"
                        v-model="actor.lastname"
                        :label="$t('form.lastname').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs3>
                    <v-text-field
                        v-validate="'required|email'"
                        data-vv-name="email"
                        :error-messages="errors.collect('actorForm.email')"
                        required
                        prepend-icon="email"
                        v-model="actor.email"
                        :label="$t('svv.email').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:20'"
                        data-vv-name="category"
                        :error-messages="errors.collect('actorForm.category')"
                        :counter="20"
                        required
                        prepend-icon="work"
                        v-model="actor.category"
                        :label="$t('form.category').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:30'"
                        data-vv-name="job"
                        :error-messages="errors.collect('job')"
                        :counter="30"
                        required
                        prepend-icon="work"
                        v-model="actor.job"
                        :label="$t('form.job').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs4>
                    <v-text-field
                        v-validate="'required|max:30'"
                        data-vv-name="organization"
                        :error-messages="
                            errors.collect('actorForm.organization')
                        "
                        :counter="30"
                        required
                        prepend-icon="work"
                        v-model="actor.organization"
                        :label="$t('form.organization').capitalize()"
                        type="text"
                    ></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-textarea
                        v-validate="'required'"
                        data-vv-name="description"
                        :error-messages="
                            errors.collect('actorForm.description')
                        "
                        required
                        prepend-icon="work"
                        v-model="actor.description"
                        :label="$t('form.description').capitalize()"
                        type="text"
                        rows="5"
                    ></v-textarea>
                </v-flex>

                <v-flex xs12>
                    <v-card>
                        <v-card-title primary-title>
                            <v-icon>image</v-icon>
                            <h4 class="headline mb-0">{{ $t('form.photo') }}</h4>
                        </v-card-title>
                        <v-card-text>
                            <file-pond
                                name="photo"
                                :server="serverOptions"
                                :label-idle="$t('form.upload.idle')"
                                :files="photo"
                                allow-multiple="false"
                                accepted-file-types="image/jpeg, image/png"
                                @updatefiles="onUpdateFiles"
                                required
                                image-crop-aspect-ratio="1:1"
                                image-resize-target-width="400"
                                :image-edit-instant-edit="true"
                                :imageEditEditor="doka"
                                max-file-size="1536KB"
                                :label-max-file-size-exceeded="
                                    $t('file.uploadExcedeed')
                                "
                                :label-max-file-size="$t('file.uploadDetailed')"
                            ></file-pond>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12>
                    <h4>
                        {{ $t('actor.links').capitalize() }}
                        <v-btn fab dark small color="success" @click="addLink">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </h4>
                </v-flex>
                <v-flex xs12 v-for="(link, index) in actor.links" :key="index">
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-select
                                    :items="links"
                                    v-model="link.name"
                                    :label="$t('ssnn.title')"
                                    prepend-icon="mdi-spider-web"
                                ></v-select>
                            </v-flex>
                            <v-flex xs7>
                                <v-text-field
                                    v-validate="{
                                        required: true,
                                        url: { require_protocol: true }
                                    }"
                                    data-vv-as="link"
                                    :data-vv-name="`link${index}`"
                                    :error-messages="
                                        errors.collect(`actorForm.link${index}`)
                                    "
                                    :counter="180"
                                    required
                                    prepend-icon="link"
                                    v-model="link.http"
                                    :label="$t('actor.link').capitalize()"
                                    type="text"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-btn
                                    fab
                                    dark
                                    small
                                    color="error"
                                    @click="removeLink(index)"
                                >
                                    <v-icon dark>remove</v-icon>
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>

<script>
import { FilePond, loadUrl } from '../app'
import * as Doka from '../plugins/doka/index'

export default {
    props: {
        actor: {
            default: { links: [] }
        },
        auth: {
            default: ''
        }
    },
    data() {
        return {
            aspectRatio: '4:3',
            doka: Doka.create({
                cropAllowImageTurnRight: true,
                cropLimitToImageBounds: false
            }),
            serverOptions: {
                process: {
                    url: './api/uploadPhoto',
                    headers: {
                        Authorization: `Bearer ${this.auth.token()}`
                    },
                    onload: response => {
                        let responseJson = JSON.parse(response)
                        this.actor.photo = responseJson.name
                    }
                },
                load: loadUrl
            },
            links: [
                { text: 'Twitter', value: 'twitter' },
                { text: 'Facebook', value: 'facebook' },
                { text: 'Instagram', value: 'instagram' },
                { text: 'Linkedin', value: 'linkedin' },
                { text: 'Web', value: 'globe' }
            ]
        }
    },
    computed: {
        photo() {
            return this.actor.photo
                ? [{ source: this.actor.photo, options: { type: 'local' } }]
                : []
        }
    },
    methods: {
        addLink() {
            this.actor.links.push({})
        },
        removeLink(index) {
            this.actor.links.splice(index, 1)
        },
        onUpdateFiles(files) {
            if (files.length == 0) {
                this.actor.photo = null
            }
        }
    }
}
</script>

<style></style>
