import {Injectable, Inject} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';
import {UserModel} from '../../models/user-model';

@Injectable()
export class AuthUserProviderService {
    constructor(@Inject(LocalStorageService) private localStorageService:LocalStorageService) {
    }

    setAuth(auth:any) {
        this.localStorageService.set('auth', auth);
        return new Promise((resolve, reject) => {
            resolve(auth);
        });
    }

    getAuth() {
        return this.localStorageService.get('auth');
    }

    hasAuth() {
        return this.getAuth() !== null;
    }

    getAuthApiToken() {
        let auth = this.getAuth();
        return auth ? auth.api_token : null;
    }

    setUser(user:UserModel) {
        this.localStorageService.set('user', user);
        return new Promise((resolve, reject) => {
            resolve(user);
        });
    }

    getUser() {
        return this.localStorageService.get('user');
    }

    hasUser() {
        return this.getUser() !== null;
    }
}
