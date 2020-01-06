/* eslint-disable no-unused-vars */
import Vue from 'vue'
import App from './App.vue'
import router from './router/router'
import vuetify from './plugins/vuetify';
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vuetify from 'vuetify';
import store from './store/store'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.config.productionTip = false

Vue.use(Vuetify)
Vue.use(VueAxios, axios)


export default new Vuetify({
  icons: {
    iconfont: 'md',
  }
})

new Vue({
  store,
  router,
  vuetify,
  axios,
  render: h => h(App)
}).$mount('#app')
