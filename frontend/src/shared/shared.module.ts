import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {GalleryComponent, GalleryGridComponent} from './components/gallery';
import {PagerServiceProvider} from './services/pager';
import {ApiService, ApiErrorHandler as BaseApiErrorHandler} from './services/api';
import {ApiErrorHandler} from './services/api-error-handler';
import {LockerServiceProvider} from './services/locker';
import {SyncProcessServiceProvider} from './services/sync-process';
import {NavigatorServiceProvider} from './services/navigator';
import {TitleService} from './services/title';
import {NotificatorService} from './services/notificator';
import {EnvService} from './services/env';
import {AuthService, AuthProviderService} from './services/auth';
import {FileSelectInputComponent} from './components/file-select-input/file-select-input.component';
import {TagsSelectInputComponent} from './components/tags-select-input/tags-select-input.component';
import {LocalStorageService} from './services/local-storage/local-storage.service';
import {UserService} from './services/user';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        TagInputModule,
    ],
    declarations: [
        GalleryComponent,
        GalleryGridComponent,
        FileSelectInputComponent,
        TagsSelectInputComponent,
    ],
    exports: [
        CommonModule,
        FormsModule,
        GalleryComponent,
        GalleryGridComponent,
        FileSelectInputComponent,
        TagsSelectInputComponent,
    ],
    providers: [
        TitleService,
        NotificatorService,
        AuthService,
        AuthProviderService,
        UserService,
        ApiService,
        ApiErrorHandler,
        {provide: BaseApiErrorHandler, useClass: ApiErrorHandler},
        LockerServiceProvider,
        SyncProcessServiceProvider,
        NavigatorServiceProvider,
        PagerServiceProvider,
        EnvService,
        LocalStorageService,
    ],
})
export class SharedModule {
}
