import '@babel/polyfill'
import 'mutationobserver-shim'
import Vue from 'vue'
import './plugins/fontawesome'
import './plugins/bootstrap-vue'
import App from './App.vue'
import i18n from './i18n'
import router from './router'
import BootstrapVue from 'bootstrap-vue'


Vue.config.productionTip = false
Vue.use(BootstrapVue)

new Vue({
  i18n,
  router,
  render: h => h(App)
}).$mount('#app')
