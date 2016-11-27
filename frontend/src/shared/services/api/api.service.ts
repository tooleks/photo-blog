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
    headers:Headers;
    searchParams:URLSearchParams;
    body:any;

    constructor(@Inject(EnvService) protected envService:EnvService,
                @Inject(Http) protected http:Http,
                @Inject(ApiErrorHandler) protected errorHandler:ApiErrorHandler,
                @Inject(AuthUserProviderService) protected authUserProviderService:AuthUserProviderService) {
        this.apiUrl = this.envService.get('apiUrl');
        this.debug = this.envService.get('debug');
    }

    get(url:string, options?:any) {
        return this.http
            .get(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .map(this.extractData.bind(this))
            .catch(this.handleError.bind(this));
    }

    post(url:string, body?:any, options?:any) {
        return this.http
            .post(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .map(this.extractData.bind(this))
            .catch(this.handleError.bind(this));
    }

    put(url:string, body?:any, options?:any) {
        return this.http
            .put(this.getAbsoluteUrl(url), this.initializeBody(body), this.initializeOptions(options))
            .map(this.extractData.bind(this))
            .catch(this.handleError.bind(this));
    }

    delete(url:string, options?:any) {
        return this.http
            .delete(this.getAbsoluteUrl(url), this.initializeOptions(options))
            .map(this.extractData.bind(this))
            .catch(this.handleError.bind(this));
    }

    protected initializeOptions(options?:any) {
        options = options || {};
        return {
            headers: this.initializeHeaders(options.headers),
            search: this.initializeSearchParams(options.params),
        };
    }

    protected initializeHeaders(headers?:any) {
        this.headers = this.getDefaultHeaders();
        headers = headers || {};
        for (var name in headers) {
            if (headers.hasOwnProperty(name)) {
                this.headers.append(name, headers[name]);
            }
        }
        return this.headers;
    }

    protected getDefaultHeaders() {
        let headers = new Headers;
        headers.append('Accept', 'application/json');
        if (this.authUserProviderService.hasAuth()) {
            headers.append('Authorization', 'Bearer ' + this.authUserProviderService.getAuthApiToken());
        }
        return headers;
    }

    protected initializeSearchParams(searchParams?:any) {
        this.searchParams = this.getDefaultSearchParams();
        searchParams = searchParams || {};
        for (var name in searchParams) {
            if (searchParams.hasOwnProperty(name)) {
                this.searchParams.set(name, searchParams[name]);
            }
        }
        return this.searchParams;
    }

    protected getDefaultSearchParams() {
        let searchParams = new URLSearchParams;
        if (this.debug) {
            searchParams.set('XDEBUG_SESSION_START', 'START');
        }
        return searchParams;
    }

    protected initializeBody(body?:any) {
        this.body = body || {};
        return this.body;
    }

    protected getAbsoluteUrl(relativeUrl:string) {
        return this.apiUrl + relativeUrl;
    }

    protected extractData(response:Response) {
        let body = response.json();
        return body.data || {};
    }

    protected handleError(error:any) {
        this.errorHandler.handle(error);
        return Observable.throw(error.message);
    }
}
