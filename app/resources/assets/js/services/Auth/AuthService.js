import moment from "moment";

/**
 * Class AuthService.
 */
export default class AuthService {
    /**
     * AuthService constructor.
     *
     * @param {EventEmitter} eventEmitter
     * @param {LocalStorageService} localStorageService
     * @param {string} key
     */
    constructor(eventEmitter, localStorageService, key) {
        this.eventEmitter = eventEmitter;
        this.localStorageService = localStorageService;
        this.key = key;
        this._initialize = this._initialize.bind(this);
        this._isValidUser = this._isValidUser.bind(this);
        this._isExpiredUserAuth = this._isExpiredUserAuth.bind(this);
        this.setUser = this.setUser.bind(this);
        this.getUser = this.getUser.bind(this);
        this.authenticated = this.authenticated.bind(this);
        this.onChange = this.onChange.bind(this);
        this._initialize();
    }

    /**
     * Initialize service.
     *
     * @return {void}
     * @private
     */
    _initialize() {
        const user = this.getUser();
        if (!this._isValidUser(user) || this._isExpiredUserAuth(user)) {
            this.setUser(null);
        }
    }

    /**
     * Determine if passed argument is valid user.
     *
     * @param {Object} user
     * @return {boolean}
     * @private
     */
    _isValidUser(user) {
        return typeof user === "object" && user !== null;
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
     * Set user to persistent storage.
     *
     * @param {Object} user
     * @return {void}
     */
    setUser(user) {
        if (this._isValidUser(user)) {
            this.localStorageService.set(this.key, user);
        } else {
            this.localStorageService.remove(this.key);
        }
        this.eventEmitter.emit(this.key, user);
    }

    /**
     * Get user from persistent storage.
     *
     * @return {Object}
     */
    getUser() {
        return this.localStorageService.get(this.key);
    }

    /**
     * Determine if user is authenticated.
     *
     * @return {boolean}
     */
    authenticated() {
        return this.localStorageService.exists(this.key);
    }

    /**
     * Register listener on user change.
     *
     * @param {Function} listener
     * @return {void}
     */
    onChange(listener) {
        this.eventEmitter.on(this.key, listener);
    }
}
