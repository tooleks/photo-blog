import {Component, OnInit, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService, EnvironmentDetectorService} from '../../../core'
import {GalleryComponent, GalleryImage} from '../../../lib';
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
    selector: 'photos-by-tag',
    templateUrl: 'photos-by-tag.component.html',
})
export class PhotosByTagComponent extends AbstractPhotosComponent implements OnInit {
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;

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
        super(
            router,
            route,
            app,
            title,
            metaTags,
            navigatorProvider,
            pagerProvider,
            processLockerProvider,
            environmentDetector,
            scrollFreezer
        );
        this.defaults['tag'] = null;
        this.defaults['title'] = 'Search By Tag';
    }

    ngOnInit():void {
        super.ngOnInit();
        this.queryParams['tag'] = this.defaults['tag'];
        this.title.setPageNameSegment(this.defaults['title']);
        this.metaTags.setTitle(this.title.getPageNameSegment());
    }

    protected initParamsSubscribers() {
        super.initParamsSubscribers();
        this.route.params
            .map((params) => params['tag'])
            .filter((tag) => tag && tag != this.queryParams['tag'])
            .map((tag) => String(tag))
            .subscribe((tag:string) => this.onTagChange(tag));
    }

    reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    initImages(fromPage:number, toPage:number, perPage:number, tag:string):void {
        if (fromPage <= toPage) {
            this.loadImages(fromPage, perPage, tag).then(() => this.initImages(++fromPage, toPage, perPage, tag));
        }
    }

    loadImages(page:number, perPage:number, tag:string):Promise<Array<GalleryImage>> {
        if (tag)
            return this.processLocker
                .lock(() => this.photoDataProvider.getByTag(page, perPage, tag))
                .then(response => this.onLoadImagesSuccess(response));
        else
            return Promise.reject(new Error);
    }

    loadMoreImages():Promise<Array<GalleryImage>> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['tag']);
    }

    onTagChange(tag:string):void {
        this.reset();
        this.queryParams['tag'] = tag;
        this.title.setPageNameSegment(`Tag #${tag}`);
        this.metaTags.setTitle(this.title.getPageNameSegment());
        this.initImages(this.defaults['page'], this.queryParams['page'], this.defaults['perPage'], tag);
    }
}
