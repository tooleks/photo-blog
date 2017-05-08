import {NgModule} from '@angular/core';
import {InfiniteScrollModule} from 'angular2-infinite-scroll';
import {GalleryModule} from '../lib';
import {SharedModule} from '../shared';
import {PhotoDataProviderService} from './services';
import {PhotosRouting, PhotosRoutingProviders} from './photos.routing';
import {PhotoFormComponent, PhotosComponent, PhotosBySearchPhraseComponent, PhotosByTagComponent} from './components';
import {ExifToStringMapper, PhotoToGalleryImageMapper, PhotoToLinkedDataMapper} from './mappers';

@NgModule({
    imports: [
        InfiniteScrollModule,
        GalleryModule,
        SharedModule,
        PhotosRouting,
    ],
    declarations: [
        PhotoFormComponent,
        PhotosComponent,
        PhotosBySearchPhraseComponent,
        PhotosByTagComponent,
    ],
    providers: [
        PhotoDataProviderService,
        PhotosRoutingProviders,
        ExifToStringMapper,
        PhotoToGalleryImageMapper,
        PhotoToLinkedDataMapper,
    ],
})
export class PhotosModule {
}
