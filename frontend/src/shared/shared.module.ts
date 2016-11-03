import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {GalleryComponent} from './components/gallery/gallery.component';
import {PagerServiceProvider} from './services/pager';
import {ApiService, ApiErrorHandler} from './services/api';
import {LockerServiceProvider} from './services/locker';
import {NavigatorServiceProvider} from './services/navigator';
import {NotificatorService} from './services/notificator';
import {FileSelectInputComponent} from './components/file-select-input/file-select-input.component';
import {TagsSelectInputComponent} from './components/tags-select-input/tags-select-input.component';

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
        NotificatorService,
        ApiService,
        ApiErrorHandler,
        LockerServiceProvider,
        NavigatorServiceProvider,
        PagerServiceProvider,
    ],
})
export class SharedModule {
}
