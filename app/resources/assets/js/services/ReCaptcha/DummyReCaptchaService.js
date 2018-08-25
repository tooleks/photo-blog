/**
 * Class DummyReCaptchaService.
 */
export default class DummyReCaptchaService {
    /**
     * DummyReCaptchaService constructor.
     *
     * @param {Function} onVerified
     */
    constructor(onVerified) {
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
        this._onVerified.call(this._onVerified);
    }

    /**
     * @return {Promise}
     */
    async render() {
        //
    }

    /**
     * @return {Promise}
     */
    async reset() {
        //
    }

    /**
     * @return {Promise}
     */
    async load() {
        //
    }
}
