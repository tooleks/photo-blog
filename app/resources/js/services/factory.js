import Vue from "vue";
import config from "../config";
import store from "../store";
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
import PhotoManager from "./PhotoManager";
import SubscriptionManager from "./SubscriptionManager";
import TagManager from "./TagManager";

/** @type {Object} */
export function getConfig() {
    return config;
}

export function getStore() {
    return store;
}

/** @type {EventBus} */
const eventBus = new EventBus;

/** @return {EventBus} */
export function getEventBus() {
    return eventBus;
}

/** @return {CookiesManager} */
export function getCookies() {
    return new CookiesManager;
}

/** @return {FullScreenManager} */
export function getFullScreen() {
    return new FullScreenManager;
}

/** @return {Localization} */
export function getLocalization(locale = "en") {
    return new Localization(lang, locale);
}

/** @type {Localization} */
const localization = getLocalization();

/** @return {string} */
export function getLang(key, ...params) {
    return localization.get(key, ...params);
}

/** @return {LocalStorageManager} */
export function getLocalStorage() {
    return new LocalStorageManager;
}

/** @type {AlertService} */
const alertService = new AlertService;

/** @return {AlertService} */
export function getAlert() {
    return alertService;
}

/** @type {ApiHandler} */
const apiHandler = new ApiHandler(getAlert());
/** @type {ApiService} */
const apiService = new ApiService(config.url.api, apiHandler.onData, apiHandler.onError);

/** @return {ApiService} */
export function getApi() {
    return apiService;
}

/** @type {AuthManager} */
const authManager = new AuthManager(getStore(), getLocalStorage());

/** @return {AuthManager} */
export function getAuth() {
    return authManager;
}

/** @type {LoginManager} */
const loginManager = new LoginManager(getApi(), getCookies(), getAuth());

/** @return {LoginManager} */
export function getLogin() {
    return loginManager;
}

/** @return {BrowserReCaptcha|DummyReCaptcha} */
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

/** @return {PhotoManager} */
export function getPhotoManager() {
    return photoManager;
}

/** @type {SubscriptionManager} */
const subscriptionManager = new SubscriptionManager(getApi());

/** @return {SubscriptionManager} */
export function getSubscriptionManager() {
    return subscriptionManager;
}

/** @type {TagManager} */
const tagManager = new TagManager(getApi());

/** @return {TagManager} */
export function getTagManager() {
    return tagManager;
}
