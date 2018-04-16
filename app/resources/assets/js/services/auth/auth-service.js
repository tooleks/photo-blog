export default class AuthService {
    constructor(eventEmitter, storageService, key) {
        this.eventEmitter = eventEmitter;
        this.storageService = storageService;
        this.key = key;
        this._initialize();
    }

    _initialize() {
        const user = this.getUser();
        if (this._isExpiredAuth(user)) {
            this.setUser(null);
        }
    }

    _isValidUser(user) {
        return typeof user === "object" && user !== null;
    }

    _isExpiredAuth(user) {
        return !this._isValidUser(user) || (new Date).valueOf() > user.expires_at;
    }

    setUser(user) {
        if (this._isValidUser(user)) {
            this.storageService.set(this.key, user);
        } else {
            this.storageService.remove(this.key);
        }
        this.eventEmitter.emit(this.key, user);
    }

    getUser() {
        return this.storageService.get(this.key);
    }

    authenticated() {
        return this.storageService.exists(this.key);
    }

    onChange(callback) {
        this.eventEmitter.on(this.key, callback);
    }
}
