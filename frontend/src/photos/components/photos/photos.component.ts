import {Component, OnInit, AfterViewInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {MetaTagsService} from '../../../core'
import {GalleryImage} from '../../../lib';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    PagerServiceProvider,
    LockProcessServiceProvider,
    ScrollFreezerService,
} from '../../../shared';
import {PhotoDataProviderService} from '../../services';
import {PhotosGalleryComponent} from '../abstract';

@Component({
    selector: 'photos',
    templateUrl: 'photos.component.html',
})
export class PhotosComponent extends PhotosGalleryComponent implements OnInit, AfterViewInit {
    constructor(protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                route:ActivatedRoute,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                lockProcessProvider:LockProcessServiceProvider,
                scrollFreezer:ScrollFreezerService) {
        super(route, title, metaTags, navigatorProvider, pagerProvider, lockProcessProvider, scrollFreezer);
    }

    ngOnInit():void {
        this.init();
    }

    ngAfterViewInit():void {
        const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
        this.loadPhotos(this.defaults.page, perPageOffset);
    }

    protected initTitle():void {
        this.title.setTitle('All Photos');
    }

    protected loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>> {
        return this.lockProcess
            .process(() => this.photoDataProvider.getAll(page, perPage))
            .then(this.handleLoadPhotos.bind(this));
    }

    protected loadMorePhotos():Promise<Array<GalleryImage>> {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage());
    }
}
