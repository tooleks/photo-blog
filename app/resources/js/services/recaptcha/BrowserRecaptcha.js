import waitUntil from "../../utils/waitUntil";
import getOrCreateHeadElement from "../../utils/getOrCreateHeadElement";

export default class BrowserRecaptcha {
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
        const recaptcha = await this.load();
        recaptcha.execute(this._widgetId);
    }

    /**
     * @return {Promise<void>}
     */
    async render() {
        const recaptcha = await this.load();
        this._widgetId = recaptcha.render(this._element, {
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
        const recaptcha = await this.load();
        recaptcha.reset(this._widgetId);
    }

    /**
     * @return {Promise<void>}
     */
    load() {
        getOrCreateHeadElement("script", {src: "https://www.google.com/recaptcha/api.js"});
        return waitUntil(() => window["grecaptcha"]);
    }
}
