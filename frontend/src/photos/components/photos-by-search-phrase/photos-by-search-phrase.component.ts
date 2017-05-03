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
    protected queryParams:any = {search_phrase: ''};

    constructor(protected environmentDetector:EnvironmentDetectorService,
                protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                router:Router,
                route:ActivatedRoute,
                app:AppService,
                title:TitleService,
                metaTags:MetaTagsService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                processLockerProvider:ProcessLockerServiceProvider,
                scrollFreezer:ScrollFreezerService) {
        super(router, route, app, title, metaTags, navigatorProvider, pagerProvider, processLockerProvider, scrollFreezer);
        this.defaults['title'] = 'Search Photos';
    }

    ngOnInit():void {
        super.ngOnInit();
        this.title.setPageNameSegment(this.defaults['title']);
        this.metaTags.setTitle(this.defaults['title']);
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

    loadImages(page:number, perPage:number, searchPhrase?:any):Promise<Array<GalleryImage>> {
        return this.processLoadImages(() => this.photoDataProvider.getBySearchPhrase(page, perPage, searchPhrase));
    }

    loadMoreImages():Promise<Array<GalleryImage>> {
        return this.loadImages(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['search_phrase']);
    }

    onSearchPhraseChange(searchPhrase:string):void {
        this.reset();
        this.queryParams['search_phrase'] = searchPhrase;
        this.title.setPageNameSegment(`Search "${searchPhrase}"`);
        this.metaTags.setTitle(this.title.getPageNameSegment());
        const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
        this.loadImages(this.defaults.page, perPageOffset, searchPhrase);
    }

    navigateToSearchPhotos(searchPhrase:string):void {
        if (searchPhrase) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
        }
    }
}
