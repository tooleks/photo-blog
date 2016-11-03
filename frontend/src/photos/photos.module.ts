import {NgModule} from '@angular/core';
import {SharedModule} from '../shared/shared.module';
import {PhotosComponent} from './components/photos/photos.component';
import {PhotoService} from './services/photo.service';
import {PhotoFormComponent} from './components/photo-form/photo-form.component';
import {PhotosByTagComponent} from './components/photos-by-tag/photos-by-tag.component';
import {PhotosRouting, PhotosRoutingProviders} from './photos.routing';

@NgModule({
    imports: [
        SharedModule,
        PhotosRouting,
    ],
    declarations: [
        PhotoFormComponent,
        PhotosComponent,
        PhotosByTagComponent,
    ],
    providers: [
        PhotoService,
        PhotosRoutingProviders,
    ],
})
export class PhotosModule {
}
