/**
 * First we call all the essential packages
 */
import Vue from 'vue'

import Vuetify from 'vuetify'
import en from 'vuetify/es5/locale/en'
import es from 'vuetify/es5/locale/es'

import VueAuth from '@websanova/vue-auth'
import auth from './auth'
import store from './store/index'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Snotify, { SnotifyPosition } from 'vue-snotify'
import VeeValidate from 'vee-validate'
import validationMessages from 'vee-validate/dist/locale/en'
import mensajesDeValidacion from 'vee-validate/dist/locale/es'
import VueI18n from 'vue-i18n'
import vueFilePond, { setOptions } from 'vue-filepond'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import FilePondPluginImageCrop from 'filepond-plugin-image-crop'
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'
import FilePondPluginImageTransform from 'filepond-plugin-image-transform'
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
import FilePondPluginImageEdit from 'filepond-plugin-image-edit'
import translations from './translations'
import DatetimePicker from 'vuetify-datetime-picker'
import 'vuetify-datetime-picker/src/stylus/main.styl'
import VueCurrencyFilter from 'vue-currency-filter'
import JsonExcel from 'vue-json-excel'
import browserDetect from 'vue-browser-detect-plugin'

/**
 * Then we load essential components
 */

import Web from './Web'

/**
 *  After we define Global Funcs
 */

String.prototype.capitalize = function () {
  return this.charAt(0).toUpperCase() + this.slice(1)
}

String.prototype.toHex = function () {
  var hash = 0
  if (this.length === 0) return hash
  for (var i = 0; i < this.length; i++) {
    hash = this.charCodeAt(i) + ((hash << 5) - hash)
    hash = hash & hash
  }
  var color = '#'
  for (i = 0; i < 3; i++) {
    var value = (hash >> (i * 8)) & 255
    color += ('00' + value.toString(16)).substr(-2)
  }
  return color
}

Vue.use(VueCurrencyFilter, {
  symbol: '$',
  thousandsSeparator: '.',
  fractionCount: 0,
  fractionSeparator: '',
  symbolPosition: 'front',
  symbolSpacing: true,
})
Vue.use(browserDetect)
Vue.component('downloadExcel', JsonExcel)

/**
 * Fileupload
 */

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginImageCrop,
  FilePondPluginImageTransform,
  FilePondPluginImageExifOrientation,
  FilePondPluginImageEdit,
  FilePondPluginFileValidateSize
)

// export const loadUrl =
// 'https://elasticbeanstalk-us-west-2-373134702977.s3-us-west-2.amazonaws.com/public/'
export const loadUrl = './public/images/'

/**
 * Now we configure all that is needed
 */

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: 'es',
  messages: translations,
  fallbackLocale: 'es',
})

Vue.use(VeeValidate, {
  i18n,
  dictionary: {
    en: {
      messages: validationMessages.messages,
      attributes: translations.en.form,
    },
    es: {
      messages: mensajesDeValidacion.messages,
      attributes: translations.es.form,
    },
  },
})

Vue.use(Snotify, {
  toast: {
    position: SnotifyPosition.rightTop,
  },
})

Vue.router = router
Vue.use(VueAxios, axios)
Vue.use(VueAuth, auth)
Vue.use(DatetimePicker)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const vuetifyOptions = {
  theme: {
    themes: {
      light: {
        primary: '#EE312F',
        secondary: '#441B2F',
        background: '#333333',
        accent: '#00AA50',
        error: '#E52322',
        info: '#211D26',
        success: '#00AA50',
        warning: '#F6922B',
      },
    },
  },
  lang: {
    locales: { en, es },
    current: 'es',
  },
  icons: {
    iconfont: 'mdi',
  },
}
Vue.use(Vuetify)

Vue.mixin({
  data: function () {
    return {
      BASE_URL: BASE_URL,
    }
  },
})

export const app = new Vue({
  store,
  router,
  i18n,
  vuetify: new Vuetify(vuetifyOptions),
  render: (h) => h(Web),
}).$mount('#app')

/**
 * Mail Editor
 */
export const claudio = axios.create({
  // baseURL: 'http://venoot-stage.us-west-2.elasticbeanstalk.com//tickets/grapesjs/services',
  baseURL: BASE_URL + '/mail/services',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
  },
})

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

/**
 * Now we will save the axios ajax information so we don't have to write it
 * all for every request
 */

Vue.axios.defaults.headers.common['Content-Type'] = 'application/json'
Vue.axios.defaults.baseURL = BASE_URL + '/api'
