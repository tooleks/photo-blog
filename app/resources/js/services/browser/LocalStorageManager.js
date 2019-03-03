export default class LocalStorageManager {
    /**
     */
    constructor() {
        this.set = this.set.bind(this);
        this.remove = this.remove.bind(this);
        this.get = this.get.bind(this);
        this.exists = this.exists.bind(this);
    }

    /**
     * Set value by key into a local storage.
     *
     * @param {string} key
     * @param {*} value
     * @returns {LocalStorageManager}
     */
    set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
        return this;
    }

    /**
     * Remove value by key from a local storage.
     *
     * @param {string} key
     * @returns {void}
     * @returns {LocalStorageManager}
     */
    remove(key) {
        localStorage.removeItem(key);
        return this;
    }

    /**
     * Get value by key from a local storage.
     *
     * @param {string} key
     * @param {*} defaultValue
     * @returns {*}
     */
    get(key, defaultValue = null) {
        let value = defaultValue;
        if (this.exists(key)) {
            const rawValue = localStorage.getItem(key);
            if (typeof rawValue !== "undefined") {
                value = JSON.parse(rawValue);
            }
        }
        return value;
    }

    /**
     * Determine if value by key exists in local storage.
     *
     * @param {string} key
     * @returns {boolean}
     */
    exists(key) {
        return localStorage.getItem(key) !== null;
    }
}
