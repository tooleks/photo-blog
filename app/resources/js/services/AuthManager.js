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
        this.setUser = this.setUser.bind(this);
        this.getUser = this.getUser.bind(this);
        this.authenticated = this.authenticated.bind(this);
        this.subscribe = this.subscribe.bind(this);
    }

    /**
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
     * @return {void}
     */
    removeUser() {
        this._localStorage.remove(AUTH_KEY);
        this._eventEmitter.emit(AUTH_KEY, null);
    }

    /**
     * @return {User}
     */
    getUser() {
        const object = this._localStorage.get(AUTH_KEY);

        // Construct a new object of the user from the plain object.
        if (object !== null) {
            return User.fromObject(object);
        }

        return null;
    }

    /**
     * @return {boolean}
     */
    authenticated() {
        const user = this.getUser();

        // The user is valid only if the user object is an instance of User class and
        // session expiration datetime is in the future.
        const validUser = user instanceof User && moment.utc().isBefore(user.expiresAt);

        if (!validUser) {
            this.removeUser();
        }

        return validUser;
    }

    /**
     * @param {Function} listener
     * @return {Function}
     */
    subscribe(listener) {
        return this._eventEmitter.on(AUTH_KEY, listener);
    }
}
