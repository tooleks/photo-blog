import Vue from "vue";
import VueNotifications from "vue-notification";
import axios from "axios";
import moment from "moment";
import {DependencyContainer, EventEmitter} from "tooleks";

// Application-wide services.
import config from "../config";
import {mapperProvider} from "../services/mapper";
import {LocalStorageService} from "../services/local-storage";
import {CookiesService} from "../services/cookies";
import {BrowserReCaptchaService, DummyReCaptchaService} from "../services/re-captcha";
import {NotificationService} from "../services/notification";
import {DateService} from "../services/date";
import {AuthService} from "../services/auth";
import {ApiService, ErrorHandlerService, HTTP_STATUS_NO_CONTENT} from "../services/api";
import {LoginService} from "../services/data-providers/login";
import {PhotoService} from "../services/data-providers/photo";
import {PhotoMapService} from "../services/data-providers/photo-map";
import {TagService} from "../services/data-providers/tag";

const dc = new DependencyContainer;

dc.registerInstance("dc", dc);

dc.registerInstance("config", config);

dc.registerBinding("mapper", mapperProvider, {
    dependencies: ["date"],
    factory: true,
});

dc.registerBinding("localStorage", () => new LocalStorageService(localStorage), {
    factory: true,
});

dc.registerBinding("cookies", () => new CookiesService(document.cookie), {
    factory: true,
});

dc.registerBinding(
    "reCaptchaProvider",
    () => {
        return (element, siteKey, onVerified) => {
            if (!Vue.$isServer && siteKey) {
                return new BrowserReCaptchaService(element, siteKey, "vueReCaptchaOnLoad", onVerified);
            }
            return new DummyReCaptchaService(onVerified);
        };
    },
    {
        factory: true,
    },
);

Vue.use(VueNotifications);
dc.registerBinding("notification", () => new NotificationService(Vue.prototype.$notify), {
    factory: true,
    singleton: true,
});

dc.registerBinding("date", () => new DateService(moment), {
    factory: true,
    singleton: true,
});

dc.registerBinding("auth", (localStorageService) => new AuthService(new EventEmitter, localStorageService, "user"), {
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
            // onSuccess
            (response) => {
                if (response.status === HTTP_STATUS_NO_CONTENT || typeof response.data === "object") {
                    return Promise.resolve(response);
                } else {
                    return Promise.reject(new SyntaxError);
                }
            },
            // onError
            (error, options) => {
                return errorHandler.handle(error, options);
            });
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

dc.registerBinding("photo", PhotoService, {
    dependencies: ["api", "mapper"],
});

dc.registerBinding("photoMap", PhotoMapService, {
    dependencies: ["api", "mapper"],
});

dc.registerBinding("tag", TagService, {
    dependencies: ["api", "mapper"],
});

export default dc;
