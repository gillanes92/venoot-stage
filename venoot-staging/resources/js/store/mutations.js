import * as types from './mutation-types'

const mutations = {
  [types.SET_RETURN_LINK] (state, returnLink) {
    state.returnLink = returnLink
  },

  [types.SET_WEBINAR_PACKAGE] (state, webinarPackage) {
    state.webinarPackage = webinarPackage
  }
}

export default mutations