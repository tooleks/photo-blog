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
        this.onVerified = onVerified;
    }

    /**
     * @return {Promise}
     */
    async execute() {
        this.onVerified.call(this.onVerified);
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
