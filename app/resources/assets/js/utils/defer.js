export default class Defer {
    constructor() {
        this.resolved = false;
        this.callbacks = [];
    }

    resolve() {
        // Do not do anything if defer is already resolved.
        if (this.resolved) {
            return;
        }

        this.resolved = true;
        this.callbacks.forEach((callback) => callback.call(callback));
        this.callbacks = [];
    }

    then(callback) {
        if (this.resolved) {
            callback.call(callback);
        } else {
            this.callbacks.push(callback);
        }
    }
}
