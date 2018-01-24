import {preloadImage as browserPreloadImage} from "./browser";
import {preloadImage as serverPreloadImage} from "./server";

export function value(callback) {
    try {
        return callback.call(callback);
    } catch (error) {
        return undefined;
    }
}

export function preloadImage(url) {
    return isBrowserEnv() ? browserPreloadImage(url) : serverPreloadImage(url);
}

export function isBrowserEnv() {
    return typeof window !== "undefined";
}

export function isServerEnv() {
    return !isBrowserEnv();
}
