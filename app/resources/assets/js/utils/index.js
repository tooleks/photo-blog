export function value(callback) {
    try {
        return callback.call(callback);
    } catch (error) {
        return undefined;
    }
}

export function isBrowserEnv() {
    return typeof window !== "undefined";
}

export function isServerEnv() {
    return !isBrowserEnv();
}
