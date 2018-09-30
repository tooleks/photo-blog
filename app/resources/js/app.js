import "./polyfills";
import "./bootstrap";

import Vue from "vue";
import VueAnalytics from "vue-analytics";
import router from "./router";
import "./directives";
import * as services from "./services/factory";
import store from "./store";
import App from "./components/App";

Vue.prototype.$services = services;

if (services.getConfig().credentials.googleAnalytics.trackingId) {
    Vue.use(VueAnalytics, {
        id: services.getConfig().credentials.googleAnalytics.trackingId,
        checkDuplicatedScript: true,
        router,
    });
}

Vue.component("app", App);

new Vue({
    el: "#app",
    store,
    router,
});
