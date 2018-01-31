export default class NotificationService {
    constructor(handler, options = {}) {
        this.handler = handler;
        const defaultOptions = {
            group: "main",
            duration: 2000,
            position: "center",
        };
        this.options = Object.assign({}, defaultOptions, options);
    }

    notify(title, message = "", type = "") {
        const notification = Object.assign({}, this.options, {title: title, text: message, type});
        this.handler(notification);
    }

    success(title, message = "") {
        this.notify(title, message, "success");
    }

    warning(title, message = "") {
        this.notify(title, message, "warn");
    }

    error(title, message = "") {
        this.notify(title, message, "error");
    }
}
