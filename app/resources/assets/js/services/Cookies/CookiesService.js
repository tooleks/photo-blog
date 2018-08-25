/**
 * Class CookiesService.
 */
export default class CookiesService {
    /**
     * CookiesService constructor.
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
            .reduce((cookies, rawCookie) => {
                const [name, value] = rawCookie.trim().split("=");
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
