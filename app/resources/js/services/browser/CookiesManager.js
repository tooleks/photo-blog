export default class CookiesManager {
    /**
     * @constructor
     */
    constructor() {
        this.getAll = this.getAll.bind(this);
        this.get = this.get.bind(this);
    }

    /**
     * Get all cookies.
     *
     * @return {Object}
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
     * @return {string}
     */
    get(name) {
        return this.getAll()[name];
    }
}
