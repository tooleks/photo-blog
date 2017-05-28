import {Component, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService} from '../../../core'
import {
    AppService,
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    PagerServiceProvider,
    ProcessLockerServiceProvider,
    ScrollFreezerService,
} from '../../../shared';
import {PhotoDataProviderService} from '../../services';
import {PhotoToLinkedDataMapper, PhotoToGalleryImageMapper} from '../../mappers';
import {PhotosComponent as AbstractPhotosComponent} from '../abstract';

@Component({
    selector: 'photos',
    templateUrl: 'photos.component.html',
})
export class PhotosComponent extends AbstractPhotosComponent implements AfterViewInit {
    constructor(public authProvider: AuthProviderService,
                protected photoDataProvider: PhotoDataProviderService,
                router: Router,
                route: ActivatedRoute,
                app: AppService,
                title: TitleService,
                metaTags: MetaTagsService,
                navigatorProvider: NavigatorServiceProvider,
                pagerProvider: PagerServiceProvider,
                processLockerProvider: ProcessLockerServiceProvider,
                scrollFreezer: ScrollFreezerService,
                galleryImageMapper: PhotoToGalleryImageMapper,
                linkedDataMapper: PhotoToLinkedDataMapper) {
        super(
            router,
            route,
            app,
            title,
            metaTags,
            navigatorProvider,
            pagerProvider,
            processLockerProvider,
            scrollFreezer,
            galleryImageMapper,
            linkedDataMapper
        );
        this.defaults['title'] = 'All Photos';
    }

    ngAfterViewInit(): void {
        super.ngAfterViewInit();
        this.initImages(this.defaults['page'], this.queryParams['page'], this.defaults['perPage']);
    }

    initImages(fromPage: number, toPage: number, perPage: number): void {
        if (fromPage <= toPage) {
            this.loadImages(fromPage, perPage).then(() => this.initImages(++fromPage, toPage, perPage));
        }
    }

    loadImages(page: number, perPage: number): Promise<any> {
        return this.processLocker
            .lock(() => this.photoDataProvider.getAll(page, perPage))
            .then((response) => this.onLoadImagesSuccess(response));
    }

    loadMoreImages(): Promise<any> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage());
    }
}
