import Defer from "./defer";

function optional(callback) {
    try {
        return callback.call(callback);
    } catch (error) {
        return undefined;
    }
}

function isBrowserEnv() {
    return typeof window !== "undefined";
}

function isServerEnv() {
    return !isBrowserEnv();
}

export {optional, isBrowserEnv, isServerEnv, Defer};
