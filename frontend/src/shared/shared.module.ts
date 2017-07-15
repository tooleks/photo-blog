import {NgModule} from '@angular/core';
import {HttpModule, JsonpModule, Response} from '@angular/http';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {Http} from '@angular/http';
import {Title} from '@angular/platform-browser';
import {CoreModule} from '../core';
import {GalleryModule, NoticesModule} from '../lib';
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
            deps: [Http, AppService, ApiErrorHandler, AuthProviderService],
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
            deps: [Http, AppService, AuthProviderService],
        },
    ],
})
export class SharedModule {
}

export function getApiService(http: Http, app: AppService, errorHandler: ApiErrorHandler, authProvider: AuthProviderService) {
    return new ApiService(
        http,
        app.getApiUrl(),
        function onResponseSuccess(response: Response) {
            return response.json() || {};
        },
        function onResponseError(response: Response) {
            return errorHandler.onResponseError(response);
        },
        function provideDefaultHeaders() {
            const headers = {'Accept': 'application/json'};
            if (authProvider.hasAuth()) {
                headers['Authorization'] = `${authProvider.getAuthTokenType()} ${authProvider.getAuthAccessToken()}`;
            }
            return headers;
        },
        function provideDefaultSearchParams() {
            return app.inDebugMode() ? {'XDEBUG_SESSION_START': 'START'} : {};
        }
    );
}

export function getTitleService(title: Title, app: AppService) {
    return new TitleService(title, app.getName());
}

export function getUserDataProviderService(http: Http, app: AppService, authProvider: AuthProviderService) {
    return new UserDataProviderService(http, app.getApiUrl(), authProvider);
}
