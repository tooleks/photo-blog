export default class EventEmitter {
    constructor() {
        this.events = {};
    }

    emit(eventName, payload) {
        const event = this.events[eventName];
        if (event) {
            event.forEach(callback => callback.call(null, payload));
        }
    }

    subscribe(eventName, callback) {
        if (!this.events[eventName]) {
            this.events[eventName] = [];
        }

        this.events[eventName].push(callback);
        return () => {
            this.events[eventName] = this.events[eventName].filter(eventCallback => callback !== eventCallback);
        };
    }
}
