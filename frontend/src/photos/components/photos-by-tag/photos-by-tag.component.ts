import {Component, OnInit, ViewChild} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {PhotosGalleryComponent} from '../abstract';
import {
    TitleService,
    AuthProviderService,
    MetaTagsService,
    NavigatorServiceProvider,
    PagerServiceProvider,
    LockProcessServiceProvider,
} from '../../../shared/services';
import {PhotoDataProviderService} from '../../services';
import {GalleryImage, GalleryComponent} from '../../../lib/gallery';

@Component({
    selector: 'photos-by-tag',
    templateUrl: 'photos-by-tag.component.html',
})
export class PhotosByTagComponent extends PhotosGalleryComponent implements OnInit {
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;
    protected queryParams:any = {tag: ''};

    constructor(protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                route:ActivatedRoute,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                lockProcessProvider:LockProcessServiceProvider) {
        super(route, title, metaTags, navigatorProvider, pagerProvider, lockProcessProvider);
    }

    ngOnInit():void {
        this.init();
    }

    protected initTitle():void {
        this.title.setTitle('Search By Tag');
    }

    protected initQueryParamsSubscribers() {
        super.initQueryParamsSubscribers();
        this.route.params
            .map((params:any) => params['tag'])
            .subscribe(this.searchByTag.bind(this));
    }

    protected reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    protected loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>> {
        return this.lockProcess
            .process(() => this.photoDataProvider.getByTag(page, perPage, parameters['tag']))
            .then(this.handleLoadPhotos.bind(this));
    }

    protected loadMorePhotos():Promise<Array<GalleryImage>> {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage(), {
            tag: this.queryParams['tag'],
        });
    }

    protected searchByTag(tag:string):void {
        if (tag && tag != this.queryParams['tag']) {
            this.reset();
            this.queryParams['tag'] = String(tag);
            this.title.setTitle(['Photos', `Tag #${this.queryParams['tag']}`]);
            this.metaTags.setTitle(this.title.getPageName());
            const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
            this.loadPhotos(this.defaults.page, perPageOffset, {
                tag: this.queryParams['tag'],
            });
        }
    }
}
