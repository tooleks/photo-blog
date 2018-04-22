export default class LocalStorageService {
    constructor(driver) {
        this.driver = driver;
    }

    set(key, value) {
        this.driver.setItem(key, JSON.stringify(value));
    }

    remove(key) {
        this.driver.removeItem(key);
    }

    get(key, defaultValue = null) {
        let value = defaultValue;
        if (this.exists(key)) {
            const rawValue = localStorage.getItem(key);
            value = typeof rawValue !== "undefined" ? JSON.parse(rawValue) : undefined;
        }
        return value;
    }

    exists(key) {
        return this.driver.getItem(key) !== null;
    }
}
