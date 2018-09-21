import {waitUntil} from "tooleks";

/**
 * Class BrowserReCaptchaService.
 */
export default class BrowserReCaptchaService {
    /**
     * BrowserReCaptchaService constructor.
     *
     * @param {HTMLElement} element
     * @param {string} siteKey
     * @param {Function} onVerified
     */
    constructor(element, siteKey, onVerified) {
        this._element = element;
        this._siteKey = siteKey;
        this._onVerified = onVerified;
        this.execute = this.execute.bind(this);
        this.render = this.render.bind(this);
        this.reset = this.reset.bind(this);
        this.load = this.load.bind(this);
    }

    /**
     * @return {Promise}
     */
    async execute() {
        const reCaptcha = await this.load();
        reCaptcha.execute(this._widgetId);
    }

    /**
     * @return {Promise}
     */
    async render() {
        const reCaptcha = await this.load();
        this._widgetId = reCaptcha.render(this._element, {
            sitekey: this._siteKey,
            size: "invisible",
            callback: (response) => {
                this._onVerified.call(this._onVerified, response);
                this.reset();
            },
        });
    }

    /**
     * @return {Promise}
     */
    async reset() {
        const reCaptcha = await this.load();
        reCaptcha.reset(this._widgetId);
    }

    /**
     * @return {Promise}
     */
    load() {
        return waitUntil(() => window["grecaptcha"]);
    }
}
