// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
require("./bootstrap");

import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import { ServerTable, ClientTable, Event } from "vue-tables-2";
// import * as VueGoogleMaps from "vue2-google-maps";
import VueTouch from "vue-touch";
// import Trend from "vuetrend";
// import Toasted from "vue-toasted";
// import VueApexCharts from "vue-apexcharts";

import store from "./store";
import router from "./Routes";
import App from "./App.vue";

Vue.use(BootstrapVue);
Vue.use(ClientTable, {}, false, "bootstrap4");
Vue.use(ServerTable, {}, false, "bootstrap4");
Vue.use(VueTouch);
// Vue.use(Trend);
// Vue.use(VueGoogleMaps, {
//     load: {
//         key: "AIzaSyB7OXmzfQYua_1LEhRdqsoYzyJOPh9hGLg"
//     }
// });
// Vue.use(Toasted, { duration: 10000 });
// Vue.component("apexchart", VueApexCharts);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: "#app",
  store,
  router,
  render: h => h(App)
});
