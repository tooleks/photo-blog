import moment from "moment";
import User from "../entities/User";

/** @type {string} */
export const AUTH_KEY = "currentUser";

export default class AuthManager {
    /**
     * @param {LocalStorageManager} localStorage
     */
    constructor(localStorage) {
        this._localStorage = localStorage;
        this._user = null;
        this.loadUser = this.loadUser.bind(this);
        this.setUser = this.setUser.bind(this);
        this.getUser = this.getUser.bind(this);
        //
        this.loadUser();
    }

    /**
     * @returns {void}
     */
    loadUser() {
        const object = this._localStorage.get(AUTH_KEY);
        if (object == null) {
            return;
        }

        // Construct a new object of the user from the plain object.
        const user = User.fromObject(object);

        // A user is valid only if the session expiration datetime is in the future.
        if (moment.utc().isAfter(user.expiresAt)) {
            this.removeUser();
            return;
        }

        this.setUser(user);
    }

    /**
     * @param {User} user
     * @returns {void}
     * @throws {TypeError}
     */
    setUser(user) {
        this._user = user;
        this._localStorage.set(AUTH_KEY, user);
    }

    /**
     * @returns {void}
     */
    removeUser() {
        this._user = null;
        this._localStorage.remove(AUTH_KEY);
    }

    /**
     * @returns {User|null}
     */
    getUser() {
        return this._user;
    }

    /**
     * @returns {boolean}
     */
    hasUser() {
        return Boolean(this._user);
    }
}
