export default class DummyReCaptcha {
    /**
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
     * @returns {Promise<void>}
     */
    async execute() {
        this._onVerified.call(this._onVerified);
    }

    /**
     * @returns {Promise<void>}
     */
    async render() {
        //
    }

    /**
     * @returns {Promise<void>}
     */
    async reset() {
        //
    }

    /**
     * @returns {Promise<void>}
     */
    async load() {
        //
    }
}
