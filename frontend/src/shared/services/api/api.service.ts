import {Injectable, Inject} from '@angular/core';
import {Http, Headers, Response, URLSearchParams} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/Rx';
import {ApiErrorHandler} from './api-error-handler';
import {env} from '../../../../env';

@Injectable()
export class ApiService {
    apiUrl:string;
    debug:boolean;
    headers:Headers;
    searchParams:URLSearchParams;
    body:any;

    constructor(@Inject(Http) private http:Http,
                @Inject(ApiErrorHandler) private errorHandler:ApiErrorHandler) {
        this.apiUrl = env.apiUrl;
        this.debug = env.debug;
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

    private initializeOptions(options?:any) {
        options = options || {};
        return {
            headers: this.initializeHeaders(options.headers),
            search: this.initializeSearchParams(options.params),
        };
    }

    private initializeHeaders(headers?:any) {
        this.headers = this.getDefaultHeaders();
        headers = headers || {};
        for (var name in headers) {
            if (headers.hasOwnProperty(name)) {
                this.headers.append(name, headers[name]);
            }
        }
        return this.headers;
    }

    private getDefaultHeaders() {
        let headers = new Headers;
        headers.append('Accept', 'application/json');
        headers.append('Bearer', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImZlZWU2MmE2ZWZjMGIyMTQ5NjdkN2QwZmZjYmQ4OWY4MzA5N2EzOTgyNjIwZjk1OGQzYWUzZmI5NDRlNGI1NTRiODZmYjUwN2I3MGFkZWZkIn0.eyJhdWQiOiIxIiwianRpIjoiZmVlZTYyYTZlZmMwYjIxNDk2N2Q3ZDBmZmNiZDg5ZjgzMDk3YTM5ODI2MjBmOTU4ZDNhZTNmYjk0NGU0YjU1NGI4NmZiNTA3YjcwYWRlZmQiLCJpYXQiOjE0Nzc4MzEyMDQsIm5iZiI6MTQ3NzgzMTIwNCwiZXhwIjo0NjMzNTA0ODA0LCJzdWIiOiIxIiwic2NvcGVzIjpbImNyZWF0ZS1waG90b3MiXX0.EaP4T78cnf1rjurA3Vp4vY6_nOPLICn5hVARIzpLm9oz5gYT1cuD49c8Bsk0vBF_bb0sR9EXOkhIWRtuvT13-AvT3wEfihUDFO1ZofhLVSZ75fvp_dSl6dFhKrcVZuyu6VNf7I93kNPuIh8rXcmhQPcYLIdnOkkZkXVayTnrvQvpQMK1KjZEKW_OId1jdeDBJeIutkeeUa7XB9HVctFMeeGM8ifdWGvb03FtdqOaZ2tjSF41NSLrpad0MJDG1k1FoLTuf0QuaznWZMFeNohd_hke1aU593-pTXf-V-sarmH6D7rzfMWipGyBHHA_WGGeJqJXMwzDnd0F-3p5CQk1BHiwTle_lIL2oSszC5pctHBMoPlk7LHaHOMnHTvAluvCGLU2qmYrd1SI4GUNC7moT584vPYu-FeCMAYZYo0LTgEXtprNrzA-7GcRoNh6J837_P09DmQp8UDAbNEZi412elsMODeEb4ahYcSXH3iDkYeew_KnBD6LUpx1xg0tHZnno6JQWBMsQtWzdKcrDK16lMqEneVjPP8iHBalLlGTrsO2vKZ7ERuIDwPIgCU7AkQAuxfZ3vFp4YphN1KQQP5O73M8e4kMB4rdCWpNiIxh0-nfP8bSinmSd6Gmo5DL2cmMe33QTrNlSdAZcjf90pafWmdyFZOFFYFh9YGJv4Spqdk');
        return headers;
    }

    private initializeSearchParams(searchParams?:any) {
        this.searchParams = this.getDefaultSearchParams();
        searchParams = searchParams || {};
        for (var name in searchParams) {
            if (searchParams.hasOwnProperty(name)) {
                this.searchParams.set(name, searchParams[name]);
            }
        }
        return this.searchParams;
    }

    private getDefaultSearchParams() {
        let searchParams = new URLSearchParams;
        if (this.debug) {
            searchParams.set('XDEBUG_SESSION_START', 'START');
        }
        return searchParams;
    }

    private initializeBody(body?:any) {
        this.body = body || {};
        return this.body;
    }

    private getAbsoluteUrl(url:string) {
        return this.apiUrl + '/' + url;
    }

    private extractData(response:Response) {
        let body = response.json();
        return body.data || {};
    }

    private handleError(error:any) {
        this.errorHandler.handle(error);
        return Observable.throw(error.message);
    }
}
