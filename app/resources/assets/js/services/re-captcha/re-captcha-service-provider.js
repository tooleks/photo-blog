import {isBrowserEnv, isServerEnv} from "../../utils";
import BrowserReCaptchaService from "./browser-re-captcha-service";
import DummyReCaptchaService from "./dummy-re-captcha-service";

export default function (element, siteKey, onVerified) {
    if (isBrowserEnv()) {
        return new BrowserReCaptchaService(element, siteKey, "vueReCaptchaOnLoad", onVerified);
    }

    if (isServerEnv()) {
        return new DummyReCaptchaService(onVerified);
    }

    throw new Error("Unknown environment.");
}
