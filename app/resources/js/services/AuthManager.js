import moment from "moment";
import {isObject} from "tooleks";
import {toUser} from "../mapper/ApiDomain/transform";

/** @type {string} */
export const AUTH_KEY = "user";

export default class AuthManager {
    /**
     * AuthManager constructor.
     *
     * @param {EventEmitter} eventEmitter
     * @param {LocalStorageManager} localStorage
     */
    constructor(eventEmitter, localStorage) {
        this._eventEmitter = eventEmitter;
        this._localStorage = localStorage;
        this._init = this._init.bind(this);
        this._isExpiredUserAuth = this._isExpiredUserAuth.bind(this);
        this.setUser = this.setUser.bind(this);
        this.getUser = this.getUser.bind(this);
        this.authenticated = this.authenticated.bind(this);
        this.subscribe = this.subscribe.bind(this);
        this._init();
    }

    /**
     * Initialize service default state.
     *
     * @return {void}
     * @private
     */
    _init() {
        const user = this.getUser();
        if (!isObject(user) || this._isExpiredUserAuth(user)) {
            this.removeUser();
        }
    }

    /**
     * Determine if a user session is already expired.
     *
     * @param {Object} user
     * @return {boolean}
     * @private
     */
    _isExpiredUserAuth(user) {
        return moment.utc().isAfter(moment.utc(user.expires_at));
    }

    /**
     * Set user to the persistent storage.
     *
     * @param {Object} user
     * @return {void}
     * @throws {TypeError}
     */
    setUser(user) {
        if (!isObject(user)) {
            throw new TypeError;
        } else {
            this._localStorage.set(AUTH_KEY, user);
            this._eventEmitter.emit(AUTH_KEY, user);
        }
    }

    /**
     * Remove user from the persistent storage.
     *
     * @return {void}
     */
    removeUser() {
        this._localStorage.remove(AUTH_KEY);
        this._eventEmitter.emit(AUTH_KEY, null);
    }

    /**
     * Get user from the persistent storage.
     *
     * @return {User}
     */
    getUser() {
        if (this._localStorage.exists(AUTH_KEY)) {
            const object = this._localStorage.get(AUTH_KEY);
            return toUser(object);
        } else {
            return null;
        }
    }

    /**
     * Determine if user is authenticated.
     *
     * @return {boolean}
     */
    authenticated() {
        return this._localStorage.exists(AUTH_KEY);
    }

    /**
     * Subscribe a listener on user change.
     *
     * @param {Function} listener
     * @return {void}
     */
    subscribe(listener) {
        this._eventEmitter.on(AUTH_KEY, listener);
    }
}
