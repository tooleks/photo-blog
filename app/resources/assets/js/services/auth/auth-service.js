export default class AuthService {
    constructor(eventEmitter, storageService, key) {
        this.eventEmitter = eventEmitter;
        this.storageService = storageService;
        this.key = key;
    }

    _isValidUser(user) {
        return user !== null && typeof user !== "undefined";
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
