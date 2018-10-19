import waitUntil from "../../utils/waitUntil";
import getOrCreateHeadElement from "../../utils/getOrCreateHeadElement";

export default class BrowserReCaptcha {
    /**
     * BrowserRecaptcha constructor.
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
     * @return {Promise<void>}
     */
    async execute() {
        const reCaptcha = await this.load();
        reCaptcha.execute(this._widgetId);
    }

    /**
     * @return {Promise<void>}
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
     * @return {Promise<void>}
     */
    async reset() {
        const reCaptcha = await this.load();
        reCaptcha.reset(this._widgetId);
    }

    /**
     * @return {Promise<void>}
     */
    async load() {
        getOrCreateHeadElement("script", {src: "https://www.google.com/recaptcha/api.js"});
        const reCaptcha = await waitUntil(() => window["grecaptcha"]);
        await waitUntil(() => reCaptcha.render && reCaptcha.execute && reCaptcha.reset);
        return reCaptcha;
    }
}
