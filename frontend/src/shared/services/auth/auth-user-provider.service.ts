import {Injectable, Inject} from '@angular/core';
import {LocalStorageService} from '../local-storage/local-storage.service';
import {UserModel} from '../../models/user-model';

@Injectable()
export class AuthUserProviderService {
    constructor(@Inject(LocalStorageService) private localStorageService:LocalStorageService) {
    }

    getUser() {
        return this.localStorageService.get('user');
    }

    getUserApiToken() {
        let user:UserModel = this.getUser();
        return user ? user.api_token : null;
    }

    isAuthenticatedUser() {
        return this.getUser();
    }
}
