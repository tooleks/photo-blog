import {Injectable} from '@angular/core';
import {Http, Headers, Response, URLSearchParams} from '@angular/http';
import 'rxjs/Rx';
import {ApiErrorHandler} from './api-error-handler';
import {EnvService} from '../env';
import {AuthProviderService} from '../auth/auth-provider.service';

@Injectable()
export class ApiService {
    url:string;
    debug:boolean;

    constructor(public env:EnvService,
                public http:Http,
                public errorHandler:ApiErrorHandler,
                public authProvider:AuthProviderService) {
        this.url = this.env.get('apiUrl');
        this.debug = this.env.get('debug');
    }

    get = (url:string, options?:any):Promise<any> => {
        return this.http
            .get(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    post = (url:string, body?:any, options?:any):Promise<any> => {
        return this.http
            .post(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    put = (url:string, body?:any, options?:any):Promise<any> => {
        return this.http
            .put(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    delete = (url:string, options?:any):Promise<any> => {
        return this.http
            .delete(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .toPromise()
            .then(this.extractResponseData)
            .catch(this.errorHandler.handleResponse);
    };

    protected initializeOptions = (options?:any) => {
        options = options || {};
        return {
            headers: this.initializeHeaders(options.headers),
            search: this.initializeSearchParams(options.params),
        };
    };

    protected initializeHeaders = (headers?:any):Headers => {
        let initializedHeaders = this.getDefaultHeaders();
        headers = headers || {};
        for (var name in headers) {
            if (headers.hasOwnProperty(name)) {
                initializedHeaders.append(name, headers[name]);
            }
        }
        return initializedHeaders;
    };

    protected getDefaultHeaders = ():Headers => {
        let defaultHeaders = new Headers;
        defaultHeaders.append('Accept', 'application/json');
        if (this.authProvider.hasAuth()) {
            defaultHeaders.append('Authorization', 'Bearer ' + this.authProvider.getAuthApiToken());
        }
        return defaultHeaders;
    };

    protected initializeSearchParams = (searchParams?:any):URLSearchParams => {
        let initializedSearchParams = this.getDefaultSearchParams();
        searchParams = searchParams || {};
        for (var name in searchParams) {
            if (searchParams.hasOwnProperty(name)) {
                initializedSearchParams.set(name, searchParams[name]);
            }
        }
        return initializedSearchParams;
    };

    protected getDefaultSearchParams = ():URLSearchParams => {
        let defaultSearchParams = new URLSearchParams;
        if (this.debug) {
            defaultSearchParams.set('XDEBUG_SESSION_START', 'START');
        }
        return defaultSearchParams;
    };

    protected initializeBody = (body?:any) => {
        return body || {};
    };

    protected getAbsoluteUrl = (relativeUrl:string):string => {
        return this.url + relativeUrl;
    };

    public extractResponseData = (response:Response):any => {
        return response.json() || {};
    };
}
