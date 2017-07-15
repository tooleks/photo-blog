import {Injectable} from '@angular/core';
import {UserDataProviderService} from '../user-data-provider';
import {AuthProviderService} from './auth-provider.service';
import {NoticesService} from '../../../lib';

@Injectable()
export class AuthService {
    constructor(protected userDataProvider: UserDataProviderService, protected authProvider: AuthProviderService, protected notices: NoticesService) {
    }

    signIn(email: string, password: string): Promise<any> {
        return this.userDataProvider
            .getAuthByCredentials(email, password)
            .then((auth) => this.onGetAuthSuccess(auth))
            .catch((error) => this.onGetAuthError(error));
    }

    refreshAuth(): Promise<any> {
        return this.userDataProvider
            .getAuthByRefreshToken(this.authProvider.getAuthRefreshToken())
            .then((auth) => this.onGetAuthSuccess(auth))
            .catch((error) => this.onGetAuthError(error));
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

    protected onGetAuthSuccess(auth): Promise<any> {
        this.authProvider.setAuth(auth);
        return this.userDataProvider.getUser()
            .then((user) => this.authProvider.setUser(user));
    }

    protected onGetAuthError(error): Promise<any> {
        this.notices.warning(error.message);
        this.authProvider.setAuth(null);
        this.authProvider.setUser(null);
        return Promise.reject(error);
    }
}
