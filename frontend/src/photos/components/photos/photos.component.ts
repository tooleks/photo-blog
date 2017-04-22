import {Component, OnInit, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService} from '../../../core'
import {GalleryImage} from '../../../lib';
import {
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
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                processLockerProvider:ProcessLockerServiceProvider,
                scrollFreezer:ScrollFreezerService) {
        super(router, route, title, metaTags, navigatorProvider, pagerProvider, processLockerProvider, scrollFreezer);
    }

    ngOnInit():void {
        super.ngOnInit();
        this.title.setTitle('All Photos');
        this.metaTags.setTitle(this.title.getPageName());
    }

    ngAfterViewInit():void {
        super.ngAfterViewInit();
        const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
        this.loadImages(this.defaults.page, perPageOffset);
    }

    protected loadImages(page:number, perPage:number):Promise<Array<GalleryImage>> {
        return this.processLoadImages(() => this.photoDataProvider.getAll(page, perPage));
    }

    protected loadMoreImages():Promise<Array<GalleryImage>> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage());
    }
}
