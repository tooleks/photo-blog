import "./polyfills";
import "./bootstrap";

import Vue from "vue";
import VueAnalytics from "vue-analytics";
import VueNotifications from "vue-notification";
import dc from "./dc";
import router from "./router";

// Register VueAnalytics plugin if tracking ID is configured.
if (dc.get("config").credentials.googleAnalytics.trackingId) {
    Vue.use(VueAnalytics, {
        id: dc.get("config").credentials.googleAnalytics.trackingId,
        checkDuplicatedScript: true,
        router,
    });
}

// Register VueNotifications plugins.
Vue.use(VueNotifications);

// Register application-wide directives and components.
require("./directives");
Vue.component("app", require("./components/App"));
Vue.component("app-header", require("./components/layout/Header"));
Vue.component("app-footer", require("./components/layout/Footer"));

// Register dependency container in the Vue prototype,
// so it can be accessed from all components through `this.$dc` call.
Vue.prototype.$dc = dc;

new Vue({
    el: "#app",
    router,
});
