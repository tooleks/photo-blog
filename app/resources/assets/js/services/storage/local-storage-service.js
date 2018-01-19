export default class LocalStorage {
    set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    }

    remove(key) {
        localStorage.removeItem(key);
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
        return localStorage.getItem(key) !== null;
    }
}
