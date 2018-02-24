export default class Defer {
    constructor() {
        this._resolved = false;
        this._callbacks = [];
    }

    _callCallback(callback) {
        callback.call(callback, this._value);
    }

    resolve(value) {
        // Do not do anything if defer is already resolved.
        if (this._resolved) {
            return;
        }
        this._value = value;
        this._resolved = true;
        this._callbacks.forEach((callback) => this._callCallback(callback));
        this._callbacks = [];
    }

    then(callback) {
        if (this._resolved) {
            this._callCallback(callback);
        } else {
            this._callbacks.push(callback);
        }
    }
}
