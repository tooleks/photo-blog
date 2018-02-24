import {isBrowserEnv} from "../../utils";
import BrowserReCaptchaService from "./browser-re-captcha-service";
import DummyReCaptchaService from "./dummy-re-captcha-service";

export default function (element, siteKey, onVerified) {
    if (isBrowserEnv() && siteKey) {
        return new BrowserReCaptchaService(element, siteKey, "vueReCaptchaOnLoad", onVerified);
    }

    return new DummyReCaptchaService(onVerified);
}
