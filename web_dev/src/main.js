// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import rcnb from 'rcnb'
import App from './App'
import router from './router'
import utils from '@/../static/js/utils'
import '@/../static/css/global.css'

Vue.config.productionTip = false
Vue.rcnb = Vue.prototype.$rcnb = rcnb

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
