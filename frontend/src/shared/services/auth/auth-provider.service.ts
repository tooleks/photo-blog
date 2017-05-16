import {Injectable} from '@angular/core';
import {LocalStorageService} from '../local-storage';

@Injectable()
export class AuthProviderService {
    constructor(protected localStorage: LocalStorageService) {
    }

    setAuth(auth) {
        this.localStorage.set('auth', auth);
        return auth;
    }

    getAuth() {
        return this.localStorage.get('auth');
    }

    hasAuth(): boolean {
        return this.getAuth() !== null;
    }

    getAuthApiToken(): string {
        const auth = this.getAuth();
        return auth ? auth.api_token : null;
    }

    setUser(user) {
        this.localStorage.set('user', user);
        return user;
    }

    getUser() {
        return this.localStorage.get('user');
    }

    hasUser(): boolean {
        return this.getUser() !== null;
    }

    isAuthenticated(): boolean {
        return this.hasAuth() && this.hasUser();
    }
}
