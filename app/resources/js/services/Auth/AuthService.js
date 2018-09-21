import moment from "moment";
import {isObject} from "tooleks";

/**
 * String key used to represent a user auth in the persistent storage.
 *
 * @type {string}
 */
export const DEFAULT_AUTH_KEY = "user";

/**
 * Class AuthService.
 */
export default class AuthService {
    /**
     * AuthService constructor.
     *
     * @param {EventEmitter} eventEmitter
     * @param {LocalStorageService} localStorageService
     * @param {string} [authKey="user"]
     */
    constructor(eventEmitter, localStorageService, authKey = DEFAULT_AUTH_KEY) {
        this._eventEmitter = eventEmitter;
        this._localStorageService = localStorageService;
        this._authKey = authKey;
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
            this._localStorageService.set(this._authKey, user);
            this._eventEmitter.emit(this._authKey, user);
        }
    }

    /**
     * Remove user from the persistent storage.
     *
     * @return {void}
     */
    removeUser() {
        this._localStorageService.remove(this._authKey);
        this._eventEmitter.emit(this._authKey, null);
    }

    /**
     * Get user from the persistent storage.
     *
     * @return {Object}
     */
    getUser() {
        return this._localStorageService.get(this._authKey);
    }

    /**
     * Determine if user is authenticated.
     *
     * @return {boolean}
     */
    authenticated() {
        return this._localStorageService.exists(this._authKey);
    }

    /**
     * Subscribe a listener on user change.
     *
     * @param {Function} listener
     * @return {void}
     */
    subscribe(listener) {
        this._eventEmitter.on(this._authKey, listener);
    }
}
