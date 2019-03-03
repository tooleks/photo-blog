import "./polyfills";
import "./bootstrap";

import Vue from "vue";
import VueAnalytics from "vue-analytics";
import router from "./router";
import * as services from "./services/factory";
import App from "./components/App";
import AppPlugin from "./AppPlugin";

Vue.use(AppPlugin);

if (services.getConfig().credentials.googleAnalytics.trackingId) {
    Vue.use(VueAnalytics, {
        id: services.getConfig().credentials.googleAnalytics.trackingId,
        checkDuplicatedScript: true,
        router,
    });
}

export default new Vue({
    el: "#app",
    render(createElement) {
        return createElement(App);
    },
    router,
});
