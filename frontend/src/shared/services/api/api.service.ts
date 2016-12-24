import {Injectable, Inject} from '@angular/core';
import {Http, Headers, Response, URLSearchParams} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/Rx';
import {ApiErrorHandler} from './api-error-handler';
import {EnvService} from '../env';
import {AuthUserProviderService} from '../auth/auth-user-provider.service';

@Injectable()
export class ApiService {
    apiUrl:string;
    debug:boolean;

    constructor(@Inject(EnvService) protected envService:EnvService,
                @Inject(Http) protected http:Http,
                @Inject(ApiErrorHandler) protected errorHandler:ApiErrorHandler,
                @Inject(AuthUserProviderService) protected authUserProviderService:AuthUserProviderService) {
        this.apiUrl = this.envService.get('apiUrl');
        this.debug = this.envService.get('debug');
    }

    get = (url:string, options?:any) => {
        return this.http
            .get(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .map(this.extractData)
            .catch(this.handleError);
    };

    post = (url:string, body?:any, options?:any) => {
        return this.http
            .post(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .map(this.extractData)
            .catch(this.handleError);
    };

    put = (url:string, body?:any, options?:any) => {
        return this.http
            .put(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .map(this.extractData)
            .catch(this.handleError);
    };

    delete = (url:string, options?:any) => {
        return this.http
            .delete(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .map(this.extractData)
            .catch(this.handleError);
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
        if (this.authUserProviderService.hasAuth()) {
            defaultHeaders.append('Authorization', 'Bearer ' + this.authUserProviderService.getAuthApiToken());
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
        return this.apiUrl + relativeUrl;
    };

    protected extractData = (response:Response) => {
        let body = response.json();
        return body.data || {};
    };

    protected handleError = (error:any) => {
        this.errorHandler.handle(error);
        return Observable.throw(error.message);
    };
}
