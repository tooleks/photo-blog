import {toUser} from "../mapper/ApiEntity/transform";

export default class LoginManager {
    /**
     * LoginManager constructor.
     *
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
     * @return {Promise<Object>}
     */
    async signIn(credentials) {
        await this._api.createToken(credentials);
        const response = await this._api.getUser("me");
        const expiresIn = this._cookies.get("expires_in");
        const user = toUser({...response.data, expires_in: expiresIn});
        this._auth.setUser(user);
        return this._auth.getUser();
    }

    /**
     * Sign out a user.
     *
     * @return {Promise}
     */
    async signOut() {
        try {
            await this._api.deleteToken();
        } finally {
            this._auth.removeUser();
        }
    }
}
