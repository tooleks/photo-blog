import Vue from "vue";

export default class EventBus {
    /**
     */
    constructor() {
        this._vue = new Vue;
        this.emit = this.emit.bind(this);
        this.on = this.on.bind(this);
        this.once = this.once.bind(this);
        this.off = this.off.bind(this);
    }

    /**
     * Trigger an event. Any additional arguments will be passed into the listener’s callback function.
     *
     * @param {string} event
     * @param {...*} [args]
     * @returns {EventBus}
     */
    emit(event, ...args) {
        this._vue.$emit(event, ...args);
        return this;
    }

    /**
     * Listen for a custom event. The callback will receive all the additional arguments passed into these event-triggering methods.
     *
     * @param {string|Array<string>} event
     * @param {function} callback
     * @returns {EventBus}
     */
    on(event, callback) {
        this._vue.$on(event, callback);
        return this;
    }

    /**
     * Listen for a custom event, but only once. The listener will be removed once it triggers for the first time.
     *
     * @param {string|Array<string>} event
     * @param {function} callback
     * @returns {EventBus}
     */
    once(event, callback) {
        this._vue.$once(event, callback);
        return this;
    }

    /**
     * Remove custom event listener(s).
     *
     * @param {string|Array<string>} event
     * @param {function} [callback]
     * @returns {EventBus}
     */
    off(event, callback) {
        this._vue.$off(event, callback);
        return this;
    }
}
