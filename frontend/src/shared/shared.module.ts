import {NgModule} from '@angular/core';
import {HttpModule, JsonpModule} from '@angular/http';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {Http} from '@angular/http';
import {Title} from '@angular/platform-browser';
import {EnvModule, EnvService, HtmlModule, SeoModule} from '../core';
import {GalleryModule, NoticesModule} from '../lib';
import {
    ApiService,
    ApiErrorHandler as BaseApiErrorHandler,
    AppService,
    AuthService,
    AuthProviderService,
    EnvironmentDetectorService,
    LocalStorageService,
    LockProcessServiceProvider,
    LockerServiceProvider,
    NavigatorServiceProvider,
    PagerServiceProvider,
    ScreenDetectorService,
    ScrollFreezerService,
    TitleService,
    UserDataProviderService,
} from './services';
import {ApiErrorHandler} from './services/api-error-handler';

@NgModule({
    imports: [
        HttpModule,
        JsonpModule,
        CommonModule,
        FormsModule,
        EnvModule,
        HtmlModule,
        GalleryModule,
        NoticesModule,
        SeoModule,
    ],
    exports: [
        HttpModule,
        JsonpModule,
        CommonModule,
        FormsModule,
        EnvModule,
        HtmlModule,
        GalleryModule,
        NoticesModule,
        SeoModule,
    ],
    providers: [
        {
            provide: ApiService,
            deps: [Http, BaseApiErrorHandler, AppService, AuthProviderService],
            useFactory(http:Http, errorHandler:BaseApiErrorHandler, app:AppService, authProvider:AuthProviderService) {
                return new ApiService(http, errorHandler, app.getApiUrl(), () => {
                    const headers = {'Accept': 'application/json'};
                    if (authProvider.hasAuth()) {
                        headers['Authorization'] = `Bearer ${authProvider.getAuthApiToken()}`;
                    }
                    return headers;
                }, () => {
                    return app.inDebugMode() ? {'XDEBUG_SESSION_START': 'START'} : {};
                });
            },
        },
        {
            provide: BaseApiErrorHandler,
            useClass: ApiErrorHandler,
        },
        {
            provide: AppService,
            deps: [EnvService],
            useFactory(env:EnvService) {
                return new AppService(env);
            },
        },
        AuthService,
        AuthProviderService,
        EnvironmentDetectorService,
        LocalStorageService,
        LockProcessServiceProvider,
        LockerServiceProvider,
        NavigatorServiceProvider,
        PagerServiceProvider,
        ScreenDetectorService,
        ScrollFreezerService,
        {
            provide: TitleService,
            deps: [AppService, Title],
            useFactory(app:AppService, title:Title) {
                return new TitleService(title, ' / ', app.getName());
            },
        },
        UserDataProviderService,
    ],
})
export class SharedModule {
}
