import {Injectable} from '@angular/core';
import {UserDataProviderService} from '../user-data-provider';
import {AuthProviderService} from './auth-provider.service';

@Injectable()
export class AuthService {
    constructor(protected userDataProvider: UserDataProviderService, protected authProvider: AuthProviderService) {
    }

    signIn(email: string, password: string): Promise<any> {
        return this.userDataProvider
            .createAuthByCredentials(email, password)
            .then((auth) => this.userDataProvider.getUser())
            .then((user) => this.authProvider.setUser(user));
    }

    signOut(): Promise<any> {
        if (this.authProvider.isAuthenticated()) {
            const user = this.authProvider.getUser();
            this.authProvider.setUser(null);
            return this.userDataProvider
                .deleteAuth()
                .then(() => user);
        } else {
            return Promise.reject(new Error);
        }
    }
}
