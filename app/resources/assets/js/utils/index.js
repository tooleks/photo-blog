function isBrowserEnv() {
    return typeof window !== "undefined";
}

function isServerEnv() {
    return !isBrowserEnv();
}

export {isBrowserEnv, isServerEnv};
