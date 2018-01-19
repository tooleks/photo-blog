import "./polyfills";
import "./bootstrap";

import Vue from "vue";
import VueAnalytics from "vue-analytics";

window.Vue = Vue;

import config from "./config";
import router from "./router";
import store from "./store";

if (config.credentials.googleAnalytics.trackingId) {
    Vue.use(VueAnalytics, {
        id: config.credentials.googleAnalytics.trackingId,
        checkDuplicatedScript: true,
        router,
    });
}

require("./directives");
Vue.component("app", require("./components/app"));
Vue.component("app-header", require("./components/layout/header"));
Vue.component("app-footer", require("./components/layout/footer"));

new Vue({
    el: "#app",
    store,
    router,
});
