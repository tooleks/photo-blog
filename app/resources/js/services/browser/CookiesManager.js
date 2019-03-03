export default class CookiesManager {
    /**
     */
    constructor() {
        this.getAll = this.getAll.bind(this);
        this.get = this.get.bind(this);
    }

    /**
     * Get all cookies.
     *
     * @returns {Object}
     */
    getAll() {
        return document.cookie
            .split(";")
            .filter((item) => item.length > 0)
            .reduce((cookies, item) => {
                const [name, value = ""] = item.trim().split("=");
                cookies[name] = value;
                return cookies;
            }, {});
    }

    /**
     * Get cookie by name.
     *
     * @param {string} name
     * @returns {string}
     */
    get(name) {
        return this.getAll()[name];
    }
}
