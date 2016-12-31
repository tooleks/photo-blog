import {Injectable, Inject} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';
import {Auth, User} from '../../models';

@Injectable()
export class AuthProviderService {
    constructor(@Inject(LocalStorageService) private localStorage:LocalStorageService) {
    }

    setAuth = (auth:Auth):Auth => {
        this.localStorage.set('auth', auth);
        return auth;
    };

    getAuth = ():Auth => {
        return this.localStorage.get('auth');
    };

    hasAuth = ():boolean => {
        return this.getAuth() !== null;
    };

    getAuthApiToken = ():string => {
        let auth = this.getAuth();
        return auth ? auth.api_token : null;
    };

    setUser = (user:User):User => {
        this.localStorage.set('user', user);
        return user;
    };

    getUser = ():User => {
        return this.localStorage.get('user');
    };

    hasUser = ():boolean => {
        return this.getUser() !== null;
    };

    isAuthenticated = ():boolean => {
        return this.hasAuth() && this.hasUser();
    };
}
