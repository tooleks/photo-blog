import {Component, OnInit, AfterViewInit, ViewChildren, ViewChild} from '@angular/core';
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
    selector: 'photos-by-search-phrase',
    templateUrl: 'photos-by-search-phrase.component.html',
})
export class PhotosBySearchPhraseComponent extends AbstractPhotosComponent implements OnInit, AfterViewInit {
    @ViewChildren('inputSearch') inputSearchComponent:any;
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
        super(router, route, app, title, metaTags, navigatorProvider, pagerProvider, processLockerProvider, environmentDetector, scrollFreezer);
        this.defaults['search_phrase'] = null;
        this.defaults['title'] = 'Search Photos';
    }

    ngOnInit():void {
        super.ngOnInit();
        this.queryParams['search_phrase'] = this.defaults.search_phrase;
        this.title.setPageNameSegment(this.defaults.title);
        this.metaTags.setTitle(this.title.getPageNameSegment());
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
            .map((queryParams:any) => queryParams['search_phrase'])
            .filter((searchPhrase:any) => searchPhrase && searchPhrase != this.queryParams['search_phrase'])
            .map((searchPhrase:any) => String(searchPhrase))
            .subscribe((searchPhrase:string) => this.onSearchPhraseChange(searchPhrase));
    }

    reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    initImages(fromPage:number, toPage:number, perPage:number, searchPhrase:string):void {
        if (fromPage <= toPage) {
            this.loadImages(fromPage, perPage, searchPhrase).then(() => this.initImages(++fromPage, toPage, perPage, searchPhrase));
        }
    }

    loadImages(page:number, perPage:number, searchPhrase:string):Promise<Array<GalleryImage>> {
        return searchPhrase
            ? this.processLoadImages(() => this.photoDataProvider.getBySearchPhrase(page, perPage, searchPhrase))
            : Promise.reject(new Error);
    }

    loadMoreImages():Promise<Array<GalleryImage>> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['search_phrase']);
    }

    onSearchPhraseChange(searchPhrase:string):void {
        this.reset();
        this.queryParams['search_phrase'] = searchPhrase;
        this.title.setPageNameSegment(`Search "${searchPhrase}"`);
        this.metaTags.setTitle(this.title.getPageNameSegment());
        this.initImages(this.defaults.page, this.queryParams['page'], this.defaults.perPage, searchPhrase);
    }

    navigateToSearchPhotos(searchPhrase:string):void {
        if (searchPhrase) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
        }
    }
}
