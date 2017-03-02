import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {
    ApiService,
    ApiErrorHandler as BaseApiErrorHandler,
    AuthService,
    AuthProviderService,
    CallbackHandlerService,
    EnvService,
    LocalStorageService,
    LockProcessServiceProvider,
    LockerServiceProvider,
    NavigatorServiceProvider,
    NotificatorService,
    PagerServiceProvider,
    ScreenDetectorService,
    ScrollFreezerService,
    ScrollerService,
    TitleService,
    UserDataProviderService,
} from './services';
import {ApiErrorHandler} from './services/api-error-handler'
import {
    FileSelectInputComponent,
    GalleryComponent,
    GalleryGridComponent,
    TagsSelectInputComponent,
} from './components';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        TagInputModule,
    ],
    declarations: [
        FileSelectInputComponent,
        GalleryComponent,
        GalleryGridComponent,
        TagsSelectInputComponent,
    ],
    exports: [
        CommonModule,
        FormsModule,
        FileSelectInputComponent,
        GalleryComponent,
        GalleryGridComponent,
        TagsSelectInputComponent,
    ],
    providers: [
        ApiService,
        {provide: BaseApiErrorHandler, useClass: ApiErrorHandler},
        AuthService,
        AuthProviderService,
        CallbackHandlerService,
        EnvService,
        LocalStorageService,
        LockProcessServiceProvider,
        LockerServiceProvider,
        NavigatorServiceProvider,
        NotificatorService,
        PagerServiceProvider,
        ScreenDetectorService,
        ScrollFreezerService,
        ScrollerService,
        TitleService,
        UserDataProviderService,
    ],
})
export class SharedModule {
}
