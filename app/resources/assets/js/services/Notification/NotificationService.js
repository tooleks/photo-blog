/**
 * Class NotificationService.
 */
export default class NotificationService {
    /**
     * NotificationService constructor.
     *
     * @param {*} handler
     * @param {Object} [options={}]
     */
    constructor(handler, options = {}) {
        this.handler = handler;
        this.options = {
            group: "main",
            duration: 2000,
            position: "center",
            ...options,
        };
    }

    /**
     * Show notification message.
     *
     * @param {string} title
     * @param {string} [message=""]
     * @param {string} [type=""]
     * @return {void}
     */
    notify(title, message = "", type = "") {
        const notification = {...this.options, title, text: message, type};
        this.handler(notification);
    }

    /**
     * Show success notification message.
     *
     * @param {string} title
     * @param {string} [message=""]
     * @return {void}
     */
    success(title, message = "") {
        this.notify(title, message, "success");
    }

    /**
     * Show warning notification message.
     *
     * @param {string} title
     * @param {string} [message=""]
     * @return {void}
     */
    warning(title, message = "") {
        this.notify(title, message, "warn");
    }

    /**
     * Show error notification message.
     *
     * @param {string} title
     * @param {string} [message=""]
     * @return {void}
     */
    error(title, message = "") {
        this.notify(title, message, "error");
    }
}
