import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService} from '../../../core'
import {GalleryComponent, GalleryImage} from '../../../lib';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    PagerServiceProvider,
    LockProcessServiceProvider,
    ScrollFreezerService,
} from '../../../shared';
import {PhotoDataProviderService} from '../../services';
import {BasePhotosComponent} from '../abstract';

@Component({
    selector: 'photos-by-tag',
    templateUrl: 'photos-by-tag.component.html',
})
export class PhotosByTagComponent extends BasePhotosComponent implements OnInit {
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;
    protected queryParams:any = {tag: ''};

    constructor(protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                router:Router,
                route:ActivatedRoute,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                lockProcessProvider:LockProcessServiceProvider,
                scrollFreezer:ScrollFreezerService) {
        super(router, route, title, metaTags, navigatorProvider, pagerProvider, lockProcessProvider, scrollFreezer);
    }

    ngOnInit():void {
        super.ngOnInit();
        this.title.setTitle('Search By Tag');
        this.metaTags.setTitle(this.title.getPageName());
    }

    protected initParamsSubscribers() {
        super.initParamsSubscribers();
        this.route.params
            .map((params:any) => params['tag'])
            .filter((tag:any) => tag && tag != this.queryParams['tag'])
            .subscribe(this.searchByTag.bind(this));
    }

    protected reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    protected loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>> {
        return this.lockProcess
            .process(() => this.photoDataProvider.getByTag(page, perPage, parameters['tag']))
            .then(this.onLoadPhotosSuccess.bind(this));
    }

    protected loadMorePhotos():Promise<Array<GalleryImage>> {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage(), {
            tag: this.queryParams['tag'],
        });
    }

    protected searchByTag(tag:string):void {
        this.reset();
        this.queryParams['tag'] = String(tag);
        this.title.setTitle(['Photos', `Tag #${this.queryParams['tag']}`]);
        this.metaTags.setTitle(this.title.getPageName());
        const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
        this.loadPhotos(this.defaults.page, perPageOffset, {tag: this.queryParams['tag']});
    }
}
