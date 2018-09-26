import Vue from "vue";
import VueNotification from "vue-notification";

Vue.use(VueNotification);

// Note: Don't forget to add <notifications group="main"></notifications> component to the app component.

/** @type {Function} */
const notify = Vue.prototype.$notify;

export const GROUP = "main";
export const POSITION = "center";
export const DURATION_INFINITE = -1;
export const DURATION_THREE_SECONDS = 3 * 1000;
export const TYPE_SUCCESS = "success";
export const TYPE_WARN = "warn";
export const TYPE_ERROR = "error";

export default class AlertService {
    /**
     * AlertService constructor.
     */
    constructor() {
        this.notify = this.notify.bind(this);
        this.success = this.success.bind(this);
        this.warning = this.warning.bind(this);
        this.error = this.error.bind(this);
    }

    /**
     * Show alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @param {string} [type]
     * @return {void}
     */
    notify(title, text = "", type = TYPE_SUCCESS) {
        notify({
            group: GROUP,
            duration: type === TYPE_ERROR
                ? DURATION_INFINITE
                : DURATION_THREE_SECONDS,
            position: POSITION,
            title,
            text,
            type,
        });
    }

    /**
     * Show success alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    success(title, text) {
        this.notify(title, text, TYPE_SUCCESS);
    }

    /**
     * Show warning alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    warning(title, text) {
        this.notify(title, text, TYPE_WARN);
    }

    /**
     * Show error alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    error(title, text) {
        this.notify(title, text, TYPE_ERROR);
    }
}
