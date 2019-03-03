import waitUntil from "../../utils/waitUntil";
import getOrCreateHeadElement from "../../utils/getOrCreateHeadElement";

export default class BrowserReCaptcha {
    /**
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
     * @returns {Promise<void>}
     */
    async execute() {
        const grecaptcha = await this.load();
        grecaptcha.execute(this._widgetId);
    }

    /**
     * @returns {Promise<void>}
     */
    async render() {
        const grecaptcha = await this.load();
        this._widgetId = grecaptcha.render(this._element, {
            sitekey: this._siteKey,
            size: "invisible",
            callback: (response) => {
                this._onVerified.call(this._onVerified, response);
                this.reset();
            },
        });
    }

    /**
     * @returns {Promise<void>}
     */
    async reset() {
        const grecaptcha = await this.load();
        grecaptcha.reset(this._widgetId);
    }

    /**
     * @returns {Promise<void>}
     */
    async load() {
        getOrCreateHeadElement("script", {src: "https://www.google.com/recaptcha/api.js"});
        return waitUntil(() => {
            // Verify that reCAPTCHA library API has been loaded.
            if (window.grecaptcha && window.grecaptcha.render && window.grecaptcha.execute && window.grecaptcha.reset) {
                return window.grecaptcha;
            }

            return false;
        });
    }
}
