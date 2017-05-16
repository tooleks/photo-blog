import {Injectable} from '@angular/core';
import {UserDataProviderService} from '../user-data-provider';
import {AuthProviderService} from './auth-provider.service';

@Injectable()
export class AuthService {
    constructor(protected userDataProvider: UserDataProviderService, protected authProvider: AuthProviderService) {
    }

    signIn(email: string, password: string): Promise<any> {
        return this.userDataProvider
            .getAuthByCredentials(email, password)
            .then((auth) => this.authProvider.setAuth(auth))
            .then((auth) => this.userDataProvider.getById(auth.user_id))
            .then((user) => this.authProvider.setUser(user));
    }

    signOut(): Promise<any> {
        return new Promise((resolve, reject) => {
            if (this.authProvider.isAuthenticated()) {
                let user = this.authProvider.getUser();
                this.authProvider.setAuth(null);
                this.authProvider.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    }
}
