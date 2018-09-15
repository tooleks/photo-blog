/**
 * Class LoginService.
 */
export default class LoginService {
    /**
     * LoginService constructor.
     *
     * @param {ApiService} apiService
     * @param {CookiesService} cookiesService
     * @param {AuthService} authService
     * @param {Mapper} mapperService
     */
    constructor(apiService, cookiesService, authService, mapperService) {
        this._apiService = apiService;
        this._cookiesService = cookiesService;
        this._authService = authService;
        this._mapperService = mapperService;
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
        await this._apiService.createToken(credentials);
        const {data} = await this._apiService.getUser("me");
        const expiresIn = this._cookiesService.get("expires_in");
        const user = this._mapperService.map({...data, expires_in: expiresIn}, "Api.User", "User");
        this._authService.setUser(user);
        return this._authService.getUser();
    }

    /**
     * Sign out a user.
     *
     * @return {Promise}
     */
    async signOut() {
        try {
            await this._apiService.deleteToken();
        } finally {
            this._authService.removeUser();
        }
    }
}
