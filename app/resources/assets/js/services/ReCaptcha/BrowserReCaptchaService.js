import {Defer} from "tooleks";

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
        this._getReCaptcha = this._getReCaptcha.bind(this);
        this.execute = this.execute.bind(this);
        this.render = this.render.bind(this);
        this.reset = this.reset.bind(this);
        this.load = this.load.bind(this);
    }

    /**
     * @return {*}
     * @private
     */
    _getReCaptcha() {
        return window["grecaptcha"];
    }

    /**
     * @return {Promise}
     */
    async execute() {
        await this.deffered.promisify();
        this._getReCaptcha().execute(this.widgetId);
    }

    /**
     * @return {Promise}
     */
    async render() {
        await this.deffered.promisify();
        this.widgetId = this._getReCaptcha().render(this.element, {
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
        await this.deffered.promisify();
        this._getReCaptcha().reset(this.widgetId);
    }

    /**
     * @return {Promise}
     */
    load() {
        // Resolve deferred when reCaptcha will be loaded.
        const timerId = setInterval(() => {
            if (typeof this._getReCaptcha() !== "undefined") {
                this.deffered.resolve();
                clearInterval(timerId);
            }
        });
        return this.deffered.promisify();
    }
}
