export default class Defer {
    constructor() {
        this.resolved = false;
        this.callStack = [];
    }

    resolve() {
        // Do not do anything if defer is already resolved.
        if (this.resolved) {
            return;
        }

        this.resolved = true;

        for (let index = 0, length = this.callStack.length; index < length; index++) {
            let callback = this.callStack[index];
            callback.call(callback);
        }
    }

    subscribe(callback) {
        if (this.resolved) {
            callback.call(callback);
        } else {
            this.callStack.push(callback);
        }
    }
}
