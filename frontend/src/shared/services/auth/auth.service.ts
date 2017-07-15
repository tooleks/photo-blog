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
            .catch((error) => this.onGetError(error));
    }

    refreshAuth(): Promise<any> {
        return this.userDataProvider
            .getAuthByRefreshToken(this.authProvider.getAuthRefreshToken())
            .then((auth) => this.onGetAuthSuccess(auth))
            .catch((error) => this.onGetError(error));
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
            .then((user) => this.authProvider.setUser(user))
            .catch((error) => this.onGetError(error));
    }

    protected onGetError(response): Promise<any> {
        const body = response.json() || {};

        switch (response.status) {
            case 401: {
                this.notices.warning(body.message);
                break;
            }
            case 422: {
                for (let attribute in body.errors) {
                    if (body.errors.hasOwnProperty(attribute)) {
                        body.errors[attribute].forEach((message: string) => this.notices.warning(message));
                    }
                }
                break;
            }
            default: {
                this.notices.error('Unknown error.');
                break;
            }
        }

        this.authProvider.setAuth(null);
        this.authProvider.setUser(null);

        return Promise.reject(response);
    }
}
