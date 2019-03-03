import moment from "moment";
import * as apiEntityMapper from "../mapper/apiEntity";

export default class LoginManager {
    /**
     * @param {ApiService} api
     * @param {CookiesManager} cookies
     * @param {AuthManager} auth
     */
    constructor(api, cookies, auth) {
        this._api = api;
        this._cookies = cookies;
        this._auth = auth;
        this.signIn = this.signIn.bind(this);
        this.signOut = this.signOut.bind(this);
    }

    /**
     * Sign in a user.
     *
     * @param {Object} credentials
     * @returns {Promise<Object>}
     */
    async signIn(credentials) {
        await this._api.createToken(credentials);
        const response = await this._api.getUser("me");
        const user = apiEntityMapper.toUser(response.data);
        const expiresIn = this._cookies.get("expires_in");
        user.expiresAt = moment.utc().add(expiresIn, "seconds");
        this._auth.setUser(user);
        return this._auth.getUser();
    }

    /**
     * Sign out a user.
     *
     * @returns {Promise}
     */
    async signOut() {
        try {
            await this._api.deleteToken();
        } finally {
            this._auth.removeUser();
        }
    }
}
