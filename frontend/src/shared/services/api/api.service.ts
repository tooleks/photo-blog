import {Injectable} from '@angular/core';
import {Http, Headers, URLSearchParams} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import {ApiServiceInterface} from './interfaces';

@Injectable()
export class ApiService implements ApiServiceInterface {
    constructor(protected http:Http,
                protected apiUrl:string,
                protected onResponseSuccess,
                protected onResponseError,
                protected provideDefaultHeaders = null,
                protected provideDefaultSearchParams = null) {
    }

    get(relativeUrl:string, options?):Promise<any> {
        return this.http
            .get(this.getApiAbsoluteUrl(relativeUrl), this.initializeOptions(options))
            .toPromise()
            .then(this.onResponseSuccess)
            .catch(this.onResponseError);
    }

    post(relativeUrl:string, body?, options?):Promise<any> {
        return this.http
            .post(this.getApiAbsoluteUrl(relativeUrl), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.onResponseSuccess)
            .catch(this.onResponseError);
    }

    put(relativeUrl:string, body?, options?):Promise<any> {
        return this.http
            .put(this.getApiAbsoluteUrl(relativeUrl), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.onResponseSuccess)
            .catch(this.onResponseError);
    }

    delete(relativeUrl:string, options?):Promise<any> {
        return this.http
            .delete(this.getApiAbsoluteUrl(relativeUrl), this.initializeOptions(options))
            .toPromise()
            .then(this.onResponseSuccess)
            .catch(this.onResponseError);
    }

    protected getApiAbsoluteUrl(relativeUrl:string):string {
        return this.apiUrl + relativeUrl;
    }

    protected initializeOptions(options?) {
        options = options || {};
        return {
            headers: this.initializeHeaders(options.headers),
            search: this.initializeSearchParams(options.params),
        };
    }

    protected initializeHeaders(headers?):Headers {
        const initializedHeaders = this.getDefaultHeaders();
        headers = headers || {};
        for (let name in headers) {
            if (headers.hasOwnProperty(name)) {
                initializedHeaders.append(name, headers[name]);
            }
        }
        return initializedHeaders;
    }

    protected getDefaultHeaders():Headers {
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

    protected initializeSearchParams(searchParams?):URLSearchParams {
        const initializedSearchParams = this.getDefaultSearchParams();
        searchParams = searchParams || {};
        for (let name in searchParams) {
            if (searchParams.hasOwnProperty(name)) {
                initializedSearchParams.set(name, searchParams[name]);
            }
        }
        return initializedSearchParams;
    }

    protected getDefaultSearchParams():URLSearchParams {
        const defaultSearchParams = new URLSearchParams;
        const rawDefaultSearchParams = this.getRawDefaultSearchParams();
        for (let name in rawDefaultSearchParams) {
            if (rawDefaultSearchParams.hasOwnProperty(name)) {
                defaultSearchParams.set(name, rawDefaultSearchParams[name]);
            }
        }
        return defaultSearchParams;
    }

    protected getRawDefaultSearchParams() {
        return typeof (this.provideDefaultSearchParams) === 'function'
            ? this.provideDefaultSearchParams()
            : {};
    }

    protected initializeBody(body?) {
        return body || {};
    }
}
