export default class Defer {
    constructor() {
        this.resolved = false;
        this.callbacks = [];
    }

    _callCallback(callback) {
        callback.call(callback, this.value);
    }

    resolve(value) {
        // Do not do anything if defer is already resolved.
        if (this.resolved) {
            return;
        }
        this.value = value;
        this.resolved = true;
        this.callbacks.forEach((callback) => this._callCallback(callback));
        this.callbacks = [];
    }

    then(callback) {
        if (this.resolved) {
            this._callCallback(callback);
        } else {
            this.callbacks.push(callback);
        }
    }
}
