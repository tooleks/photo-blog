import {isBrowserEnv, isServerEnv} from "../../utils";
import BrowserReCaptcha from "./browser-re-captcha-service";
import DummyReCaptcha from "./dummy-re-captcha-service";

export default function (element, siteKey, onVerified) {
    if (isBrowserEnv()) {
        const reCaptcha = new BrowserReCaptcha(element, siteKey, onVerified);
        window.vueReCaptchaOnLoad = () => reCaptcha.render();
        return reCaptcha;
    }

    if (isServerEnv()) {
        return new DummyReCaptcha(element, siteKey, onVerified);
    }

    throw new Error("Unknown environment.");
}
