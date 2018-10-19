import Vue from "vue";
import axios from "axios";
import {EventEmitter} from "tooleks";
import config from "../config";
import store from "../store";
import CookiesManager from "./CookiesManager";
import LocalStorageManager from "./LocalStorageManager";
import AlertService from "./AlertService";
import ApiHandler from "./api/ApiHandler";
import ApiService from "./api/ApiService";
import AuthManager from "./AuthManager";
import LoginManager from "./LoginManager";
import BrowserReCaptcha from "./recaptcha/BrowserReCaptcha";
import DummyReCaptcha from "./recaptcha/DummyReCaptcha";
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

/** @type {EventEmitter} */
const eventEmitter = new EventEmitter;

/** @return {EventEmitter} */
export function getEventEmitter() {
    return eventEmitter;
}

/** @return {CookiesManager} */
export function getCookies() {
    return new CookiesManager;
}

/** @return {LocalStorageManager} */
export function getLocalStorage() {
    return new LocalStorageManager;
}

/** @return {AlertService} */
export function getAlert() {
    return new AlertService;
}

/** @return {ApiService} */
export function getApi() {
    const apiHandler = new ApiHandler(getAlert());
    return new ApiService(axios, config.url.api, apiHandler.onData, apiHandler.onError);
}

/** @type {AuthManager} */
const auth = new AuthManager(getStore(), getLocalStorage());

/** @return {AuthManager} */
export function getAuth() {
    return auth;
}

/** @return {LoginManager} */
export function getLogin() {
    return new LoginManager(getApi(), getCookies(), getAuth());
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

/** @return {PhotoManager} */
export function getPhotoManager() {
    return new PhotoManager(getApi());
}

/** @return {SubscriptionManager} */
export function getSubscriptionManager() {
    return new SubscriptionManager(getApi());
}

/** @return {TagManager} */
export function getTagManager() {
    return new TagManager(getApi());
}
