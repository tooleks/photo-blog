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
            .then((response) => response.json() || {});
    }

    getAuthByCredentials(email: string, password: string): Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl('/auth/token'), {email: email, password: password})
            .toPromise()
            .then((response) => response.json() || {});
    }

    getAuthByRefreshToken(refreshToken: string): Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl('/auth/refresh-token'), {refresh_token: refreshToken})
            .toPromise()
            .then((response) => response.json() || {});
    }
}
