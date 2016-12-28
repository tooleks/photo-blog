import {Injectable, Inject} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';
import {AuthModel, UserModel} from '../../models';

@Injectable()
export class AuthProviderService {
    constructor(@Inject(LocalStorageService) protected localStorageService:LocalStorageService) {
    }

    setAuth = (auth:AuthModel):AuthModel => {
        this.localStorageService.set('auth', auth);
        return auth;
    };

    getAuth = () => this.localStorageService.get('auth');

    hasAuth = () => this.getAuth() !== null;

    getAuthApiToken = () => {
        let auth = this.getAuth();
        return auth ? auth.api_token : null;
    };

    setUser = (user:UserModel):UserModel => {
        this.localStorageService.set('user', user);
        return user;
    };

    getUser = () => this.localStorageService.get('user');

    hasUser = () => this.getUser() !== null;

    isAuthenticated = () => this.hasAuth() && this.hasUser();
}
