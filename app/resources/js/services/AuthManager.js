import moment from "moment";
import User from "../entities/User";

/** @type {string} */
export const AUTH_KEY = "currentUser";

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
        const validUser = user instanceof User && moment.utc().isBefore(user.expiresAt);

        if (!validUser) {
            this.removeUser();
        }
    }

    /**
     * Set user to the persistent storage.
     *
     * @param {User} user
     * @return {void}
     * @throws {TypeError}
     */
    setUser(user) {
        if (!(user instanceof User)) {
            throw new TypeError(`A user should be an instance of User class. ${user} is given.`);
        }

        this._localStorage.set(AUTH_KEY, user);
        this._eventEmitter.emit(AUTH_KEY, user);
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
        const object = this._localStorage.get(AUTH_KEY);
        if (object !== null) {
            return User.fromObject(object);
        }

        return null;
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
     * Register a listener on user change.
     *
     * @param {Function} listener
     * @return {void}
     */
    subscribe(listener) {
        this._eventEmitter.on(AUTH_KEY, listener);
    }
}
