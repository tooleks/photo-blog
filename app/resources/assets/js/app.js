import "./polyfills";
import "./bootstrap";

import Vue from "vue";

// Register dependency container in the Vue prototype,
// so it can be accessed from all components through `this.$dc` call.
import dc from "./dependency-container";
Vue.prototype.$dc = dc;

// Add Vue to the global window object.
window.Vue = Vue;

import router from "./router";

// Register Google Analytics plugin if tracking ID is configured.
import VueAnalytics from "vue-analytics";
if (dc.get('config').credentials.googleAnalytics.trackingId) {
    Vue.use(VueAnalytics, {
        id: dc.get('config').credentials.googleAnalytics.trackingId,
        checkDuplicatedScript: true,
        router,
    });
}

// Register application-wide directives and components.
require("./directives");
Vue.component("app", require("./components/app"));
Vue.component("app-header", require("./components/layout/header"));
Vue.component("app-footer", require("./components/layout/footer"));

new Vue({
    el: "#app",
    router,
});
