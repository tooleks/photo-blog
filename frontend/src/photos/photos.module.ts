import {NgModule} from '@angular/core';
import {InfiniteScrollModule} from 'angular2-infinite-scroll';
import {SharedModule} from '../shared/shared.module';
import {PhotoFormComponent, PhotosComponent, PhotosBySearchPhraseComponent, PhotosByTagComponent} from './components';
import {PhotoDataProviderService} from './services';
import {PhotosRouting, PhotosRoutingProviders} from './photos.routing';
import {NoticesModule} from '../common/notices'; 

@NgModule({
    imports: [
        InfiniteScrollModule,
        SharedModule,
        PhotosRouting,
        NoticesModule,
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
    ],
})
export class PhotosModule {
}
