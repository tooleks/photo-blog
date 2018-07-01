/**
 * Class LocalStorageService.
 */
export default class LocalStorageService {
    /**
     * LocalStorageService constructor.
     *
     * @param {*} driver
     */
    constructor(driver) {
        this.driver = driver;
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
     * @return {void}
     */
    set(key, value) {
        this.driver.setItem(key, JSON.stringify(value));
    }

    /**
     * Remove value by key from a local storage.
     *
     * @param {string} key
     * @return {void}
     */
    remove(key) {
        this.driver.removeItem(key);
    }

    /**
     * Get value by key from a local storage.
     *
     * @param {string} key
     * @param {*} defaultValue
     * @return {*}
     */
    get(key, defaultValue = null) {
        let value = defaultValue;
        if (this.exists(key)) {
            const rawValue = this.driver.getItem(key);
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
     * @return {boolean}
     */
    exists(key) {
        return this.driver.getItem(key) !== null;
    }
}
