export function isBrowserEnv() {
    return typeof window !== "undefined";
}

export function isServerEnv() {
    return !isBrowserEnv();
}
