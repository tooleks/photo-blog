import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
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
import {NoticesModule} from '../lib/notices';

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
        ApiService,
        {provide: BaseApiErrorHandler, useClass: ApiErrorHandler},
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
    ],
})
export class SharedModule {
}
