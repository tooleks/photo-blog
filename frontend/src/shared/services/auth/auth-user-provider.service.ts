import {Injectable, Inject} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';
import {UserModel} from '../../models/user-model';

@Injectable()
export class AuthUserProviderService {
    constructor(@Inject(LocalStorageService) protected localStorageService:LocalStorageService) {
    }

    setAuth = (auth:any) => {
        this.localStorageService.set('auth', auth);
        return Promise.resolve(auth);
    };

    getAuth = () => this.localStorageService.get('auth');

    hasAuth = () => this.getAuth() !== null;

    getAuthApiToken = () => {
        let auth = this.getAuth();
        return auth ? auth.api_token : null;
    };

    setUser = (user:UserModel) => {
        this.localStorageService.set('user', user);
        return new Promise((resolve, reject) => {
            resolve(user);
        });
    };

    getUser = () => this.localStorageService.get('user');

    hasUser = () => this.getUser() !== null;
}
