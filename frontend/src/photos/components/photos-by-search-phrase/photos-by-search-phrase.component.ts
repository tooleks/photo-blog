import {Component, OnInit, AfterViewInit, ViewChildren, ViewChild, QueryList, ElementRef} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/filter';
import {MetaTagsService, EnvironmentDetectorService} from '../../../core'
import {GalleryComponent} from '../../../lib';
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
    selector: 'photos-by-search-phrase',
    templateUrl: 'photos-by-search-phrase.component.html',
})
export class PhotosBySearchPhraseComponent extends AbstractPhotosComponent implements OnInit, AfterViewInit {
    @ViewChildren('inputSearch') inputSearchComponent:QueryList<ElementRef>;
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;

    constructor(protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                protected environmentDetector:EnvironmentDetectorService,
                router:Router,
                route:ActivatedRoute,
                app:AppService,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                processLockerProvider:ProcessLockerServiceProvider,
                scrollFreezer:ScrollFreezerService,
                galleryImageMapper:PhotoToGalleryImageMapper,
                linkedDataMapper:PhotoToLinkedDataMapper) {
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
        this.defaults['title'] = 'Search Photos';
        this.defaults['search_phrase'] = null;
    }

    reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    ngOnInit():void {
        super.ngOnInit();
        this.queryParams['search_phrase'] = this.defaults['search_phrase'];
    }

    ngAfterViewInit():void {
        super.ngAfterViewInit();
        this.focusOnSearchInput();
    }

    focusOnSearchInput() {
        if (this.environmentDetector.isBrowser()) {
            this.inputSearchComponent.first.nativeElement.focus();
        }
    }

    protected initParamsSubscribers() {
        super.initParamsSubscribers();
        this.route.queryParams
            .map((queryParams) => queryParams['search_phrase'])
            .filter((searchPhrase) => typeof (searchPhrase) !== 'undefined')
            .map((searchPhrase) => String(searchPhrase))
            .filter((searchPhrase:string) => searchPhrase !== this.queryParams['search_phrase'])
            .subscribe((searchPhrase:string) => this.onSearchPhraseChange(searchPhrase));
    }

    initImages(fromPage:number, toPage:number, perPage:number, searchPhrase:string):void {
        if (fromPage <= toPage) {
            this.loadImages(fromPage, perPage, searchPhrase).then(() => this.initImages(++fromPage, toPage, perPage, searchPhrase));
        }
    }

    loadImages(page:number, perPage:number, searchPhrase:string):Promise<any> {
        if (searchPhrase) {
            return this.processLocker
                .lock(() => this.photoDataProvider.getBySearchPhrase(page, perPage, searchPhrase))
                .then((response) => this.onLoadImagesSuccess(response));
        } else {
            return Promise.reject(new Error('Invalid value of a search phrase parameter.'));
        }
    }

    loadMoreImages():Promise<any> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['search_phrase']);
    }

    onSearchPhraseChange(searchPhrase:string):void {
        this.reset();
        this.queryParams['search_phrase'] = searchPhrase;
        this.defaults['title'] = `Search "${searchPhrase}"`;
        this.title.setPageNameSegment(this.defaults['title']);
        this.metaTags.setTitle(this.title.getPageNameSegment());
        this.initImages(this.defaults['page'], this.queryParams['page'], this.defaults['perPage'], searchPhrase);
    }

    navigateToSearchPhotos(searchPhrase:string):void {
        if (searchPhrase) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
        }
    }
}
