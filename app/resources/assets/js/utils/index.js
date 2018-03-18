import Defer from "./defer";
import EventEmitter from "./event-emitter";

function optional(callback, defaultValue = undefined) {
    try {
        return callback.call(callback);
    } catch (error) {
        return defaultValue;
    }
}

function isBrowserEnv() {
    return typeof window !== "undefined";
}

function isServerEnv() {
    return !isBrowserEnv();
}

export {optional, isBrowserEnv, isServerEnv, Defer, EventEmitter};
