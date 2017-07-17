import {Injectable} from '@angular/core';
import {LocalStorageService} from '../local-storage';

@Injectable()
export class AuthProviderService {
    constructor(protected localStorage: LocalStorageService) {
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
        return this.hasUser();
    }
}
