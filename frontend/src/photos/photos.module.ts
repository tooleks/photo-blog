import {NgModule} from '@angular/core';
import {InfiniteScrollModule} from 'angular2-infinite-scroll';
import {SharedModule} from '../shared/shared.module';
import {PhotosComponent} from './components/photos/photos.component';
import {PhotoDataProviderService} from './services/photo-data-provider';
import {PhotoFormComponent} from './components/photo-form/photo-form.component';
import {PhotosBySearchQueryComponent} from './components/photos-by-search-query/photos-by-search-query.component';
import {PhotosByTagComponent} from './components/photos-by-tag/photos-by-tag.component';
import {PhotosRouting, PhotosRoutingProviders} from './photos.routing';

@NgModule({
    imports: [
        InfiniteScrollModule,
        SharedModule,
        PhotosRouting,
    ],
    declarations: [
        PhotoFormComponent,
        PhotosComponent,
        PhotosBySearchQueryComponent,
        PhotosByTagComponent,
    ],
    providers: [
        PhotoDataProviderService,
        PhotosRoutingProviders,
    ],
})
export class PhotosModule {
}
