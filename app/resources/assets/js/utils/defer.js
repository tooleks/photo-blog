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

        for (let index = 0, length = this.callStack.length; index < length; index++) {
            let callback = this.callStack[index];
            callback.call(callback);
        }

        this.resolved = true;
    }

    subscribe(callback) {
        if (this.resolved) {
            callback.call(callback);
        } else {
            this.callStack.push(callback);
        }
    }
}
