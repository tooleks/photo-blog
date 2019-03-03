import Vue from "vue";
import config from "../config";
import lang from "../resources/lang";
import ApiHandler from "./api/ApiHandler";
import ApiService from "./api/ApiService";
import CookiesManager from "./browser/CookiesManager";
import FullScreenManager from "./browser/FullScreenManager";
import LocalStorageManager from "./browser/LocalStorageManager";
import BrowserReCaptcha from "./recaptcha/BrowserReCaptcha";
import DummyReCaptcha from "./recaptcha/DummyReCaptcha";
import AlertService from "./AlertService";
import AuthManager from "./AuthManager";
import EventBus from "./EventBus";
import Localization from "./Localization";
import LoginManager from "./LoginManager";
import MetaManager from "./MetaManager";
import PhotoManager from "./PhotoManager";
import SubscriptionManager from "./SubscriptionManager";
import TagManager from "./TagManager";

/** @type {Object} */
export function getConfig() {
    return config;
}

/** @type {EventBus} */
const eventBus = new EventBus;

/** @returns {EventBus} */
export function getEventBus() {
    return eventBus;
}

/** @returns {CookiesManager} */
export function getCookies() {
    return new CookiesManager;
}

/** @returns {FullScreenManager} */
export function getFullScreen() {
    return new FullScreenManager;
}

/** @returns {Localization} */
export function getLocalization(locale = "en") {
    return new Localization(lang, locale);
}

/** @type {Localization} */
const localization = getLocalization();

/** @returns {string} */
export function getLang(key, ...params) {
    return localization.get(key, ...params);
}

/** @returns {LocalStorageManager} */
export function getLocalStorage() {
    return new LocalStorageManager;
}

/** @type {AlertService} */
const alertService = new AlertService;

/** @returns {AlertService} */
export function getAlert() {
    return alertService;
}

/** @type {ApiHandler} */
const apiHandler = new ApiHandler(getAlert());
/** @type {ApiService} */
const apiService = new ApiService(config.url.api, apiHandler.onData, apiHandler.onError);

/** @returns {ApiService} */
export function getApi() {
    return apiService;
}

/** @type {AuthManager} */
const authManager = Vue.observable(new AuthManager(getLocalStorage()));

/** @returns {AuthManager} */
export function getAuth() {
    return authManager;
}

/** @type {LoginManager} */
const loginManager = new LoginManager(getApi(), getCookies(), getAuth());

/** @returns {LoginManager} */
export function getLogin() {
    return loginManager;
}

/** @type {MetaManager} */
const metaManager = Vue.observable(new MetaManager());

/** @returns {MetaManager} */
export function getMeta() {
    return metaManager;
}

/** @returns {BrowserReCaptcha|DummyReCaptcha} */
export function getReCaptcha(element, siteKey, onVerified) {
    // Provide Recaptcha service only in a browser environment,
    // and if the site key value is provided.
    if (!Vue.$isServer && siteKey) {
        return new BrowserReCaptcha(element, siteKey, onVerified);
    }
    return new DummyReCaptcha(onVerified);
}

/** @type {PhotoManager} */
const photoManager = new PhotoManager(getApi());

/** @returns {PhotoManager} */
export function getPhotoManager() {
    return photoManager;
}

/** @type {SubscriptionManager} */
const subscriptionManager = new SubscriptionManager(getApi());

/** @returns {SubscriptionManager} */
export function getSubscriptionManager() {
    return subscriptionManager;
}

/** @type {TagManager} */
const tagManager = new TagManager(getApi());

/** @returns {TagManager} */
export function getTagManager() {
    return tagManager;
}
