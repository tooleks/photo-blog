export default class DummyReCaptcha {
    /**
     * @constructor
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
     * @return {Promise<void>}
     */
    async execute() {
        this._onVerified.call(this._onVerified);
    }

    /**
     * @return {Promise<void>}
     */
    async render() {
        //
    }

    /**
     * @return {Promise<void>}
     */
    async reset() {
        //
    }

    /**
     * @return {Promise<void>}
     */
    async load() {
        //
    }
}
