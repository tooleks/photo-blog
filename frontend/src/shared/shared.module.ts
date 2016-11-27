import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {GalleryComponent} from './components/gallery/gallery.component';
import {PagerServiceProvider} from './services/pager';
import {ApiService, ApiErrorHandler} from './services/api';
import {LockerServiceProvider} from './services/locker';
import {NavigatorServiceProvider} from './services/navigator';
import {TitleService} from './services/title';
import {NotificatorService} from './services/notificator';
import {EnvService} from './services/env';
import {AuthService, AuthUserProviderService} from './services/auth';
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
        FileSelectInputComponent,
        TagsSelectInputComponent,
    ],
    exports: [
        CommonModule,
        FormsModule,
        GalleryComponent,
        FileSelectInputComponent,
        TagsSelectInputComponent,
    ],
    providers: [
        TitleService,
        NotificatorService,
        AuthService,
        AuthUserProviderService,
        UserService,
        ApiService,
        ApiErrorHandler,
        LockerServiceProvider,
        NavigatorServiceProvider,
        PagerServiceProvider,
        EnvService,
        LocalStorageService,
    ],
})
export class SharedModule {
}
