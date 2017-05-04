import {Component, OnInit, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService, EnvironmentDetectorService} from '../../../core'
import {GalleryImage} from '../../../lib';
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
import {PhotosComponent as AbstractPhotosComponent} from '../abstract';

@Component({
    selector: 'photos',
    templateUrl: 'photos.component.html',
})
export class PhotosComponent extends AbstractPhotosComponent implements OnInit, AfterViewInit {
    constructor(protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                router:Router,
                route:ActivatedRoute,
                app:AppService,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                processLockerProvider:ProcessLockerServiceProvider,
                environmentDetector:EnvironmentDetectorService,
                scrollFreezer:ScrollFreezerService) {
        super(router, route, app, title, metaTags, navigatorProvider, pagerProvider, processLockerProvider, environmentDetector, scrollFreezer);
        this.defaults['title'] = 'All Photos';
    }

    ngOnInit():void {
        super.ngOnInit();
        this.title.setPageNameSegment(this.defaults.title);
        this.metaTags.setTitle(this.title.getPageNameSegment());
    }

    ngAfterViewInit():void {
        super.ngAfterViewInit();
        this.initImages(this.defaults.page, this.queryParams['page'], this.defaults.perPage);
    }

    initImages(fromPage:number, toPage:number, perPage:number):void {
        if (fromPage <= toPage) {
            this.loadImages(fromPage, perPage).then(() => this.initImages(++fromPage, toPage, perPage));
        }
    }

    loadImages(page:number, perPage:number):Promise<Array<GalleryImage>> {
        return this.processLoadImages(() => this.photoDataProvider.getAll(page, perPage));
    }

    loadMoreImages():Promise<Array<GalleryImage>> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage());
    }
}
