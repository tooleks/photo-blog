import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {
    ApiService,
    ApiErrorHandler as BaseApiErrorHandler,
    AuthService,
    AuthProviderService,
    EnvService,
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
import {ApiErrorHandler} from './services/api-error-handler'
import {
    FileSelectInputComponent,
    GalleryComponent,
    GalleryGridComponent,
    TagsSelectInputComponent,
} from './components';
import {SafeHtmlPipe} from './pipes';
import {NoticesModule} from '../common/notices';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        TagInputModule,
        NoticesModule,
    ],
    declarations: [
        FileSelectInputComponent,
        GalleryComponent,
        GalleryGridComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
    exports: [
        CommonModule,
        FormsModule,
        FileSelectInputComponent,
        GalleryComponent,
        GalleryGridComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
    providers: [
        ApiService,
        {provide: BaseApiErrorHandler, useClass: ApiErrorHandler},
        AuthService,
        AuthProviderService,
        EnvService,
        LocalStorageService,
        LockProcessServiceProvider,
        LockerServiceProvider,
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
