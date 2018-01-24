export default class AuthService {
    constructor(storage, key) {
        this.storage = storage;
        this.key = key;
    }

    _isValidUser(user) {
        return user !== null && typeof user !== "undefined";
    }

    set(user) {
        if (this._isValidUser(user)) {
            this.storage.set(this.key, user);
        } else {
            this.storage.remove(this.key);
        }
    }

    get() {
        return this.storage.get(this.key);
    }

    exists() {
        return this.storage.exists(this.key);
    }
}
