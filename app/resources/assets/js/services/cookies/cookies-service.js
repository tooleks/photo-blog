export default class CookiesService {
    constructor(cookies) {
        this.cookies = cookies;
    }

    getAll() {
        return this.cookies
            .split(";")
            .reduce((cookies, rawCookie) => {
                const [name, value] = rawCookie.trim().split("=");
                cookies[name] = value;
                return cookies;
            }, {});
    }

    get(name) {
        return this.getAll()[name];
    }
}
