import Vue from "vue";
import axios from "axios";
import {DependencyContainer, EventEmitter} from "tooleks";

// Application-wide services.
import config from "../config";
import {makeMapper} from "../services/Mapper";
import {LocalStorageService} from "../services/LocalStorage";
import {CookiesService} from "../services/Cookies";
import {BrowserReCaptchaService, DummyReCaptchaService} from "../services/ReCaptcha";
import {NotificationService} from "../services/Notification";
import {DateService} from "../services/Date";
import {AuthService} from "../services/Auth";
import {ApiService, ErrorHandlerService, HTTP_STATUS_NO_CONTENT} from "../services/Api";
import {LoginService} from "../services/Login";
import {PhotoMapService} from "../services/PhotoMap";

const dc = new DependencyContainer;

dc.registerInstance("dc", dc);

dc.registerInstance("config", config);

dc.registerBinding("mapper", makeMapper, {
    dependencies: ["date"],
    factory: true,
});

dc.registerBinding("localStorage", LocalStorageService);

dc.registerBinding("cookies", CookiesService);

dc.registerBinding(
    "reCaptchaProvider",
    () => {
        return (element, siteKey, onVerified) => {
            if (!Vue.$isServer && siteKey) {
                return new BrowserReCaptchaService(element, siteKey, onVerified);
            }
            return new DummyReCaptchaService(onVerified);
        };
    },
    {
        factory: true,
    },
);

dc.registerBinding("notification", () => new NotificationService(Vue.prototype.$notify), {
    factory: true,
    singleton: true,
});

dc.registerBinding("date", DateService);

dc.registerBinding("auth", (localStorageService) => new AuthService(new EventEmitter, localStorageService), {
    dependencies: ["localStorage"],
    factory: true,
    singleton: true,
});

dc.registerBinding(
    "api",
    (config, notificationService) => {
        const errorHandler = new ErrorHandlerService(notificationService);
        return new ApiService(
            // httpClient
            axios,
            // apiEndpoint
            config.url.api,
            // onData
            async (response) => {
                if (response.status === HTTP_STATUS_NO_CONTENT || typeof response.data === "object") {
                    return response;
                } else {
                    throw new SyntaxError;
                }
            },
            // onError
            errorHandler.handle,
        )
    },
    {
        dependencies: ["config", "notification"],
        singleton: true,
        factory: true,
    },
);

dc.registerBinding("login", LoginService, {
    dependencies: ["api", "cookies", "auth", "mapper"],
});

dc.registerBinding("photoMap", PhotoMapService, {
    dependencies: ["api", "mapper"],
});

export default dc;
