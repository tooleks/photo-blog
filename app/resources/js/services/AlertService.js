import Vue from "vue";
import VueNotification from "vue-notification";

Vue.use(VueNotification);

// Note: Don't forget to add <notifications group="main"></notifications> component to the app component.

export const GROUP = "main";
export const POSITION = "center";
export const DURATION_SHORT = 3 * 1000;
export const DURATION_LONG = 5 * 1000;
export const TYPE_SUCCESS = "success";
export const TYPE_WARN = "warn";
export const TYPE_ERROR = "error";

export default class AlertService {
    /**
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
     * @returns {void}
     */
    notify(title, text = "", type = TYPE_SUCCESS) {
        Vue.prototype.$notify({
            group: GROUP,
            duration: type === TYPE_ERROR
                ? DURATION_LONG
                : DURATION_SHORT,
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
     * @returns {void}
     */
    success(title, text) {
        this.notify(title, text, TYPE_SUCCESS);
    }

    /**
     * Show warning alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @returns {void}
     */
    warning(title, text) {
        this.notify(title, text, TYPE_WARN);
    }

    /**
     * Show error alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @returns {void}
     */
    error(title, text) {
        this.notify(title, text, TYPE_ERROR);
    }
}
