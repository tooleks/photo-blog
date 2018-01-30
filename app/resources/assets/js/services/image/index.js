import {isBrowserEnv, isServerEnv} from "../../utils";
import {browserPreloadImage} from "./browser";
import {dummyPreloadImage} from "./dummy";

export function preloadImage(url) {
    if (isBrowserEnv()) {
        return browserPreloadImage(url);
    }

    if (isServerEnv()) {
        return dummyPreloadImage(url);
    }

    throw new Error("Unknown environment.");
}
