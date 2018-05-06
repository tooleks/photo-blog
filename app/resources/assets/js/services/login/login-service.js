export default class LoginService {
    constructor(apiService, cookiesService, authService, mapperService) {
        this.apiService = apiService;
        this.cookiesService = cookiesService;
        this.authService = authService;
        this.mapperService = mapperService;
    }

    /**
     * Sign in the user.
     *
     * @param {Object} credentials
     * @return {Promise<Object>}
     */
    async signIn(credentials) {
        await this.apiService.createToken(credentials);
        const {data} = await this.apiService.getUser("me");
        const expiresIn = this.cookiesService.get("expires_in");
        const user = this.mapperService.map({...data, expires_in: expiresIn}, "Api.User", "User");
        this.authService.setUser(user);
        return this.authService.getUser();
    }

    /**
     * Sign out the user.
     *
     * @return {Promise<void>}
     */
    async signOut() {
        try {
            await this.apiService.deleteToken();
        } finally {
            this.authService.setUser(null);
        }
    }
}
