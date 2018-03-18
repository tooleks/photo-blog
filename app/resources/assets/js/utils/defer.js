export default class Defer {
    constructor() {
        this.resolved = false;
        this.callbacks = [];
    }

    _callCallback(callback) {
        callback.call(callback, this.value);
    }

    resolve(value) {
        if (this.resolved) {
            return;
        }
        this.value = value;
        this.resolved = true;
        this.callbacks.forEach((callback) => this._callCallback(callback));
        this.callbacks = [];
    }

    afterResolve(callback) {
        if (this.resolved) {
            this._callCallback(callback);
        } else {
            this.callbacks.push(callback);
        }
    }

    promisify() {
        return new Promise((resolve) => {
            this.afterResolve(resolve);
        });
    }
}
