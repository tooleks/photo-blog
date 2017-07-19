import {NgModule} from '@angular/core';
import {HttpModule, JsonpModule, Response} from '@angular/http';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {Http} from '@angular/http';
import {Title} from '@angular/platform-browser';
import {CoreModule} from '../core';
import {GalleryModule, NoticesModule, NoticesService} from '../lib';
import {
    ApiService,
    ApiErrorHandler,
    AppService,
    AuthService,
    AuthProviderService,
    LocalStorageService,
    ProcessLockerServiceProvider,
    LockerServiceProvider,
    NavigatorServiceProvider,
    PagerServiceProvider,
    ScrollFreezerService,
    TitleService,
    UserDataProviderService,
} from './services';

@NgModule({
    imports: [
        HttpModule,
        JsonpModule,
        CommonModule,
        FormsModule,
        CoreModule,
        GalleryModule,
        NoticesModule,
    ],
    exports: [
        HttpModule,
        JsonpModule,
        CommonModule,
        FormsModule,
        CoreModule,
        GalleryModule,
        NoticesModule,
    ],
    providers: [
        {
            provide: ApiService,
            useFactory: getApiService,
            deps: [Http, AppService, ApiErrorHandler],
        },
        ApiErrorHandler,
        AppService,
        AuthService,
        AuthProviderService,
        LocalStorageService,
        ProcessLockerServiceProvider,
        LockerServiceProvider,
        NavigatorServiceProvider,
        PagerServiceProvider,
        ScrollFreezerService,
        {
            provide: TitleService,
            useFactory: getTitleService,
            deps: [Title, AppService],
        },
        {
            provide: UserDataProviderService,
            useFactory: getUserDataProviderService,
            deps: [Http, AppService, ApiErrorHandler],
        },
    ],
})
export class SharedModule {
}

export function getApiService(http: Http, app: AppService, errorHandler: ApiErrorHandler) {
    return new ApiService(
        http,
        app.getApiUrl(),
        getOnResponseSuccessCallback(),
        getOnResponseErrorCallback(errorHandler),
        getDefaultHeadersCallback(),
        getDefaultSearchParamsCallback(app.inDebugMode())
    );
}

export function getTitleService(title: Title, app: AppService) {
    return new TitleService(title, app.getName());
}

export function getUserDataProviderService(http: Http, app: AppService, errorHandler: ApiErrorHandler) {
    return new UserDataProviderService(
        http,
        app.getApiUrl(),
        getOnResponseSuccessCallback(),
        getOnResponseErrorCallback(errorHandler),
        getDefaultHeadersCallback()
    );
}

function getOnResponseSuccessCallback() {
    return function (response: Response) {
        return response.json() || {};
    };
}

function getOnResponseErrorCallback(errorHandler: ApiErrorHandler) {
    return function (response: Response) {
        return errorHandler.onResponseError(response);
    };
}

function getDefaultHeadersCallback() {
    return function () {
        const headers = {};
        headers['X-Requested-With'] = 'XMLHttpRequest';
        headers['Accept'] = 'application/json';
        return headers;
    };
}

function getDefaultSearchParamsCallback(inDebugMode: boolean) {
    return function () {
        const searchParams = {};
        if (inDebugMode) {
            searchParams['XDEBUG_SESSION_START'] = 'START';
        }
        return searchParams;
    };
}
