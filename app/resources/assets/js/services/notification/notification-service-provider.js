import Vue from "vue";
import VueNotifications from "vue-notification";

Vue.use(VueNotifications);

import NotificationService from "./notification-service";

export default function () {
    return new NotificationService(Vue.prototype.$notify);
}
