import {Injectable} from '@angular/core';
import {Http, Headers, Response, URLSearchParams} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import {ApiErrorHandler} from './api-error-handler';

@Injectable()
export class ApiService {
    constructor(public http:Http,
                public errorHandler:ApiErrorHandler,
                public apiUrl:string,
                public defaultHeadersCallback:any = null,
                public defaultSearchParamsCallback:any = null) {
    }

    get = (relativeUrl:string, options?:any):Promise<any> => {
        return this.http
            .get(this.getApiAbsoluteUrl(relativeUrl), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    post = (relativeUrl:string, body?:any, options?:any):Promise<any> => {
        return this.http
            .post(this.getApiAbsoluteUrl(relativeUrl), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    put = (relativeUrl:string, body?:any, options?:any):Promise<any> => {
        return this.http
            .put(this.getApiAbsoluteUrl(relativeUrl), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    delete = (relativeUrl:string, options?:any):Promise<any> => {
        return this.http
            .delete(this.getApiAbsoluteUrl(relativeUrl), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    protected getApiAbsoluteUrl = (relativeUrl:string):string => {
        return this.apiUrl + relativeUrl;
    };

    protected initializeOptions = (options?:any) => {
        options = options || {};
        return {
            headers: this.initializeHeaders(options.headers),
            search: this.initializeSearchParams(options.params),
        };
    };

    protected initializeHeaders = (headers?:any):Headers => {
        const initializedHeaders = this.getDefaultHeaders();
        headers = headers || {};
        for (let name in headers) {
            if (headers.hasOwnProperty(name)) {
                initializedHeaders.append(name, headers[name]);
            }
        }
        return initializedHeaders;
    };

    protected getDefaultHeaders = ():Headers => {
        const defaultHeaders = new Headers;
        const rawDefaultHeaders = this.getRawDefaultHeaders();
        for (let name in rawDefaultHeaders) {
            if (rawDefaultHeaders.hasOwnProperty(name)) {
                defaultHeaders.append(name, rawDefaultHeaders[name]);
            }
        }
        return defaultHeaders;
    };

    protected getRawDefaultHeaders = ():any => {
        return typeof (this.defaultHeadersCallback) === 'function'
            ? this.defaultHeadersCallback()
            : {};
    };

    protected initializeSearchParams = (searchParams?:any):URLSearchParams => {
        const initializedSearchParams = this.getDefaultSearchParams();
        searchParams = searchParams || {};
        for (let name in searchParams) {
            if (searchParams.hasOwnProperty(name)) {
                initializedSearchParams.set(name, searchParams[name]);
            }
        }
        return initializedSearchParams;
    };

    protected getDefaultSearchParams = ():URLSearchParams => {
        const defaultSearchParams = new URLSearchParams;
        const rawDefaultSearchParams = this.getRawDefaultSearchParams();
        for (let name in rawDefaultSearchParams) {
            if (rawDefaultSearchParams.hasOwnProperty(name)) {
                defaultSearchParams.set(name, rawDefaultSearchParams[name]);
            }
        }
        return defaultSearchParams;
    };

    protected getRawDefaultSearchParams = ():any => {
        return typeof (this.defaultSearchParamsCallback) === 'function'
            ? this.defaultSearchParamsCallback()
            : {};
    };

    protected initializeBody = (body?:any) => {
        return body || {};
    };

    public extractResponseData = (response:Response):any => {
        return response.json() || {};
    };
}
