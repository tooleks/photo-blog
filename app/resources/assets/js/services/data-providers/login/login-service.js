export default class LoginService {
    constructor(apiService, authService, mapperService) {
        this.apiService = apiService;
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
        const user = this.mapperService.map(data, "Api.V1.User", "App.User");
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
