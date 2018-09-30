import moment from "moment";
import User from "../entities/User";

/** @type {string} */
export const AUTH_KEY = "currentUser";

export default class AuthManager {
    /**
     * AuthManager constructor.
     *
     * @param store
     * @param {LocalStorageManager} localStorage
     */
    constructor(store, localStorage) {
        this._store = store;
        this._localStorage = localStorage;
        this.loadUser = this.loadUser.bind(this);
        this.setUser = this.setUser.bind(this);
        this.getUser = this.getUser.bind(this);

        this.loadUser();
    }

    /**
     * @return {void}
     */
    loadUser() {
        const object = this._localStorage.get(AUTH_KEY);

        if (object === null) {
            return;
        }

        // Construct a new object of the user from the plain object.
        const user = User.fromObject(object);

        // The user is valid only if the session expiration datetime is in the future.
        if (moment.utc().isAfter(user.expiresAt)) {
            this.removeUser();
            return;
        }

        this.setUser(user);
    }

    /**
     * @param {User} user
     * @return {void}
     * @throws {TypeError}
     */
    setUser(user) {
        this._store.dispatch("auth/setUser", user);
        this._localStorage.set(AUTH_KEY, user);
    }

    /**
     * @return {void}
     */
    removeUser() {
        this._store.dispatch("auth/removeUser");
        this._localStorage.remove(AUTH_KEY);
    }

    /**
     * @return {User}
     */
    getUser() {
        return this._store.state.auth.user;
    }
}
