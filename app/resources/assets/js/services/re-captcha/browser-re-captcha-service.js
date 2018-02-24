import {Defer} from "../../utils";

const defer = new Defer;

export default class BrowserReCaptchaService {
    constructor(element, siteKey, onLoadFunctionName, onVerified) {
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
        window[onLoadFunctionName] = () => defer.resolve(window["grecaptcha"]);
    }

    _isEnabled() {
        return Boolean(this.siteKey);
    }

    _emitOnVerified(response = undefined) {
        this.onVerified.call(this.onVerified, response);
    }

    execute() {
        // Do not do anything if reCAPTCHA service is not enabled.
        if (!this._isEnabled()) {
            return;
        }

        defer.then((gReCaptcha) => gReCaptcha.execute(this.widgetId));
    }

    render() {
        // Emit `onVerified` event explicitly if reCAPTCHA service is not enabled.
        if (!this._isEnabled()) {
            this._emitOnVerified();
        }
        defer.then((gReCaptcha) => {
            this.widgetId = gReCaptcha.render(this.element, {
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
        defer.then((gReCaptcha) => gReCaptcha.reset(this.widgetId));
    }

    load() {
        // Resolve defer explicitly if reCAPTCHA service is loaded.
        if (typeof window["grecaptcha"] !== "undefined") {
            defer.resolve(window["grecaptcha"]);
        }
    }
}
