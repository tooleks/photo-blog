import Vue from "vue";
import VueNotification from "vue-notification";

Vue.use(VueNotification);

// Note: Don't forget to add <notifications group="main"></notifications> component to the app component.

/** @type {Function} */
const notify = Vue.prototype.$notify;

export default class AlertService {
    /**
     * AlertService constructor.
     *
     * @param {Object} [options]
     */
    constructor(options = {}) {
        this._options = {
            group: "main",
            duration: 2000,
            position: "center",
            ...options,
        };
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
    notify(title, text = "", type = "") {
        notify({...this._options, title, text, type});
    }

    /**
     * Show success alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    success(title, text = "") {
        this.notify(title, text, "success");
    }

    /**
     * Show warning alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    warning(title, text = "") {
        this.notify(title, text, "warn");
    }

    /**
     * Show error alert message.
     *
     * @param {string} title
     * @param {string} [text]
     * @return {void}
     */
    error(title, text = "") {
        this.notify(title, text, "error");
    }
}
