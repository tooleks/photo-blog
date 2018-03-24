import {Defer} from "tooleks";

const defer = new Defer;

export default class BrowserReCaptchaService {
    constructor(element, siteKey, onLoadFunctionName, onVerified) {
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
        window[onLoadFunctionName] = () => defer.resolve();
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
        if (!this._isEnabled()) {
            return;
        }

        defer.promisify().then(() => this._getReCaptcha().execute(this.widgetId));
    }

    render() {
        if (!this._isEnabled()) {
            this._emitOnVerified();
        }

        defer.promisify().then(() => {
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
        if (!this._isEnabled()) {
            return;
        }

        defer.promisify().then(() => this._getReCaptcha().reset(this.widgetId));
    }

    load() {
        if (typeof this._getReCaptcha() !== "undefined") {
            defer.resolve();
        }
    }
}
