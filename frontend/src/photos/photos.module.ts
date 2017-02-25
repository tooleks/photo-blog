import {NgModule} from '@angular/core';
import {InfiniteScrollModule} from 'angular2-infinite-scroll';
import {SharedModule} from '../shared/shared.module';
import {PhotoFormComponent, PhotosComponent, PhotosBySearchQueryComponent, PhotosByTagComponent} from './components';
import {PhotoDataProviderService} from './services';
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
