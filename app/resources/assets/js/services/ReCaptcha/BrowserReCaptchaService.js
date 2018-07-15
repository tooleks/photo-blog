import {waitUntil} from "tooleks";

/**
 * Class BrowserReCaptchaService.
 */
export default class BrowserReCaptchaService {
    /**
     * BrowserReCaptchaService constructor.
     *
     * @param {*} element
     * @param {string} siteKey
     * @param {Function} onVerified
     */
    constructor(element, siteKey, onVerified) {
        this.deffered = new Defer;
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
        this.execute = this.execute.bind(this);
        this.render = this.render.bind(this);
        this.reset = this.reset.bind(this);
        this.load = this.load.bind(this);
    }

    /**
     * @return {Promise}
     */
    async execute() {
        const reCaptcha = await this.deffered.promisify();
        reCaptcha.execute(this.widgetId);
    }

    /**
     * @return {Promise}
     */
    async render() {
        const reCaptcha = await this.deffered.promisify();
        this.widgetId = reCaptcha.render(this.element, {
            sitekey: this.siteKey,
            size: "invisible",
            callback: (response) => {
                this.onVerified.call(this.onVerified, response);
                this.reset();
            },
        });
    }

    /**
     * @return {Promise}
     */
    async reset() {
        const reCaptcha = await this.deffered.promisify();
        reCaptcha.reset(this.widgetId);
    }

    /**
     * @return {Promise}
     */
    load() {
        return waitUntil(() => window["grecaptcha"]);
    }
}
