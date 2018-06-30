import moment from "moment";

export default class AuthService {
    constructor(eventEmitter, localStorageService, key) {
        this.eventEmitter = eventEmitter;
        this.localStorageService = localStorageService;
        this.key = key;
        this._initialize();
    }

    _initialize() {
        const user = this.getUser();
        if (!this._isValidUser(user) || this._isExpiredAuth(user)) {
            this.setUser(null);
        }
    }

    _isValidUser(user) {
        return typeof user === "object" && user !== null;
    }

    _isExpiredAuth(user) {
        return moment.utc().isAfter(moment.utc(user.expires_at));
    }

    setUser(user) {
        if (this._isValidUser(user)) {
            this.localStorageService.set(this.key, user);
        } else {
            this.localStorageService.remove(this.key);
        }
        this.eventEmitter.emit(this.key, user);
    }

    getUser() {
        return this.localStorageService.get(this.key);
    }

    authenticated() {
        return this.localStorageService.exists(this.key);
    }

    onChange(callback) {
        this.eventEmitter.on(this.key, callback);
    }
}
