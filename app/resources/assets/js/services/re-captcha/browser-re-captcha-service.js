import {Defer} from "../../utils";

const defer = new Defer;

export default class BrowserReCaptchaService {
    constructor(element, siteKey, onLoadFunctionName, onVerified) {
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
        window[onLoadFunctionName] = () => defer.resolve(this._getReCaptcha());
    }

    _isEnabled() {
        return Boolean(this.siteKey);
    }

    _emitOnVerified(response = undefined) {
        this.onVerified.call(this.onVerified, response);
    }

    _getReCaptcha() {
        return window["grecaptcha"];
    }

    execute() {
        // Do not do anything if reCAPTCHA service is not enabled.
        if (!this._isEnabled()) {
            return;
        }
        defer.then(() => this._getReCaptcha().execute(this.widgetId));
    }

    render() {
        // Emit `onVerified` event explicitly if reCAPTCHA service is not enabled.
        if (!this._isEnabled()) {
            this._emitOnVerified();
        }
        defer.then(() => {
            this.widgetId = this._getReCaptcha().render(this.element, {
                sitekey: this.siteKey,
                size: "invisible",
                callback: (response) => {
                    this._emitOnVerified(response);
                    this.reset();
                },
            });
        });
    }

    reset() {
        // Do not do anything if reCAPTCHA service is not enabled.
        if (!this._isEnabled()) {
            return;
        }
        defer.then(() => this._getReCaptcha().reset(this.widgetId));
    }

    load() {
        // Resolve defer explicitly if reCAPTCHA service is loaded.
        if (typeof this._getReCaptcha() !== "undefined") {
            defer.resolve(this._getReCaptcha());
        }
    }
}
