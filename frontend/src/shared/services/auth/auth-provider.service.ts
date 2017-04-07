import {Injectable} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';

@Injectable()
export class AuthProviderService {
    constructor(private localStorage:LocalStorageService) {
    }

    setAuth = (auth:any):any => {
        this.localStorage.set('auth', auth);
        return auth;
    };

    getAuth = ():any => {
        return this.localStorage.get('auth');
    };

    hasAuth = ():boolean => {
        return this.getAuth() !== null;
    };

    getAuthApiToken = ():string => {
        let auth = this.getAuth();
        return auth ? auth.api_token : null;
    };

    setUser = (user:any):any => {
        this.localStorage.set('user', user);
        return user;
    };

    getUser = ():any => {
        return this.localStorage.get('user');
    };

    hasUser = ():boolean => {
        return this.getUser() !== null;
    };

    isAuthenticated = ():boolean => {
        return this.hasAuth() && this.hasUser();
    };
}
