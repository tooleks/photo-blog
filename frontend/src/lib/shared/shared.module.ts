import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {Http} from '@angular/http';
import {Title} from '@angular/platform-browser';
import {TagInputModule} from 'ng2-tag-input';
import {
    ApiService,
    ApiErrorHandler as BaseApiErrorHandler,
    AppService,
    AuthService,
    AuthProviderService,
    EnvService,
    EnvironmentDetectorService,
    LocalStorageService,
    LockProcessServiceProvider,
    LockerServiceProvider,
    MetaTagsService,
    NavigatorServiceProvider,
    PagerServiceProvider,
    ScreenDetectorService,
    ScrollFreezerService,
    TitleService,
    UserDataProviderService,
} from './services';
import {ApiErrorHandler} from './services/api-error-handler'
import {FileSelectInputComponent, TagsSelectInputComponent} from './components';
import {SafeHtmlPipe} from './pipes';
import {NoticesModule} from '../notices';
import {env} from '../../../env'

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        TagInputModule,
        NoticesModule,
    ],
    declarations: [
        FileSelectInputComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
    exports: [
        CommonModule,
        FormsModule,
        FileSelectInputComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
    providers: [
        {
            provide: ApiService,
            deps: [Http, BaseApiErrorHandler, AuthProviderService],
            useFactory(http:Http, errorHandler:BaseApiErrorHandler, authProvider:AuthProviderService) {
                return new ApiService(
                    http,
                    errorHandler,
                    env.apiUrl,
                    () => {
                        const headers = {'Accept': 'application/json'};
                        if (authProvider.hasAuth()) {
                            headers['Authorization'] = `Bearer ${authProvider.getAuthApiToken()}`;
                        }
                        return headers;
                    },
                    () => {
                        return env.debug ? {'XDEBUG_SESSION_START': 'START'} : {};
                    }
                );
            },
        },
        {
            provide: BaseApiErrorHandler,
            useClass: ApiErrorHandler,
        },
        AppService,
        AuthService,
        AuthProviderService,
        EnvService,
        EnvironmentDetectorService,
        LocalStorageService,
        LockProcessServiceProvider,
        LockerServiceProvider,
        MetaTagsService,
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
