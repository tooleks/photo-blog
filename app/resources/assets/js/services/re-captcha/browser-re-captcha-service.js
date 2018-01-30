export default class BrowserReCaptcha {
    constructor(element, siteKey, onVerified) {
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
    }

    _isEnabled() {
        return this.siteKey;
    }

    _callOnVerified(response = undefined) {
        this.onVerified.call(this.onVerified, response);
    }

    isReady() {
        return typeof window.grecaptcha !== "undefined";
    }

    execute() {
        if (this._isEnabled()) {
            window.grecaptcha.execute(this.widgetId);
        }
    }

    render() {
        if (this._isEnabled()) {
            this.widgetId = window.grecaptcha.render(this.element, {
                sitekey: this.siteKey,
                size: "invisible",
                callback: (response) => {
                    this._callOnVerified(response);
                    this.reset();
                },
            });
        } else {
            this._callOnVerified();
        }
    }

    reset() {
        if (this._isEnabled()) {
            window.grecaptcha.reset(this.widgetId);
        }
    }
}
