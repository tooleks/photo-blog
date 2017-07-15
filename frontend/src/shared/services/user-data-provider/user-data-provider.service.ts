import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import {AuthProviderService} from '../auth';

@Injectable()
export class UserDataProviderService {
    constructor(protected http: Http, protected apiUrl: string, protected authProvider: AuthProviderService) {
    }

    protected getApiAbsoluteUrl(relativeUrl: string): string {
        return this.apiUrl + relativeUrl;
    }

    getUser(): Promise<any> {
        const headers = new Headers;
        headers.append('Accept', 'application/json');
        if (this.authProvider.hasAuth()) {
            headers.append('Authorization', `${this.authProvider.getAuthTokenType()} ${this.authProvider.getAuthAccessToken()}`);
        }
        return this.http
            .get(this.getApiAbsoluteUrl('/users/me'), {headers: headers})
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((error) => this.onResponseError(error));
    }

    getAuthByCredentials(email: string, password: string): Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl('/oauth/token'), {
                grant_type: 'password',
                client_id: process.env.API_OAUTH_CLIENT_ID,
                client_secret: process.env.API_OAUTH_CLIENT_SECRET,
                username: email,
                password: password,
            })
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((error) => this.onResponseError(error));
    }

    getAuthByRefreshToken(refreshToken: string): Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl('/oauth/token'), {
                grant_type: 'refresh_token',
                client_id: process.env.API_OAUTH_CLIENT_ID,
                client_secret: process.env.API_OAUTH_CLIENT_SECRET,
                refresh_token: refreshToken,
            })
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((error) => this.onResponseError(error));
    }

    protected onResponseSuccess(response) {
        return response.json() || {};
    }

    protected onResponseError(response) {
        return Promise.reject(response.json() || {});
    }
}
