import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';

@Injectable()
export class UserDataProviderService {
    constructor(protected http: Http,
                protected apiUrl: string,
                protected onResponseSuccess,
                protected onResponseError,
                protected provideDefaultHeaders) {
    }

    getUser(): Promise<any> {
        return this.http
            .get(this.getApiAbsoluteUrl('/users/me'), this.getDefaultOptions())
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((response) => this.onResponseError(response));
    }

    createAuthByCredentials(email: string, password: string): Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl('/auth/token'), {email: email, password: password}, this.getDefaultOptions())
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((response) => this.onResponseError(response));
    }

    deleteAuth(): Promise<any> {
        return this.http
            .delete(this.getApiAbsoluteUrl('/auth/token'), this.getDefaultOptions())
            .toPromise()
            .then((response) => this.onResponseSuccess(response))
            .catch((response) => this.onResponseError(response));
    }

    protected getApiAbsoluteUrl(relativeUrl: string): string {
        return this.apiUrl + relativeUrl;
    }

    protected getDefaultOptions() {
        return {
            withCredentials: true,
            headers: this.getDefaultHeaders(),
        };
    }

    protected getDefaultHeaders(): Headers {
        const defaultHeaders = new Headers;
        const rawDefaultHeaders = this.getRawDefaultHeaders();
        for (let name in rawDefaultHeaders) {
            if (rawDefaultHeaders.hasOwnProperty(name)) {
                defaultHeaders.append(name, rawDefaultHeaders[name]);
            }
        }
        return defaultHeaders;
    }

    protected getRawDefaultHeaders() {
        return typeof (this.provideDefaultHeaders) === 'function'
            ? this.provideDefaultHeaders()
            : {};
    }
}
