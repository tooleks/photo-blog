import {Component, OnInit, AfterViewInit, ViewChildren, ViewChild} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {MetaTagsService, EnvironmentDetectorService} from '../../../core'
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
import {PhotosGalleryComponent} from '../abstract';

@Component({
    selector: 'photos-by-search-phrase',
    templateUrl: 'photos-by-search-phrase.component.html',
})
export class PhotosBySearchPhraseComponent extends PhotosGalleryComponent implements OnInit, AfterViewInit {
    @ViewChildren('inputSearch') inputSearchComponent:any;
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;
    protected queryParams:any = {search_phrase: ''};

    constructor(protected environmentDetector:EnvironmentDetectorService,
                protected authProvider:AuthProviderService,
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
        this.init();
    }

    ngAfterViewInit():void {
        this.focusOnSearchInput();
    }

    protected focusOnSearchInput() {
        if (this.environmentDetector.isBrowser()) {
            this.inputSearchComponent.first.nativeElement.focus();
        }
    }

    protected initParamsSubscribers() {
        super.initParamsSubscribers();
        this.route.queryParams
            .map((queryParams:any) => queryParams['search_phrase'])
            .subscribe(this.searchPhotosByPhrase.bind(this));
    }

    protected initTitle():void {
        this.title.setTitle('Search Photos');
    }
    
    protected reset():void {
        super.reset();
        this.galleryComponent.reset();
    }

    protected loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>> {
        return this.lockProcess
            .process(() => this.photoDataProvider.getBySearchPhrase(page, perPage, parameters['searchPhrase']))
            .then(this.handleLoadPhotos.bind(this));
    }

    protected loadMorePhotos():Promise<Array<GalleryImage>> {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage(), {
            searchPhrase: this.queryParams['search_phrase'],
        });
    }

    protected searchPhotosByPhrase(searchPhrase:string):void {
        if (searchPhrase && searchPhrase != this.queryParams['search_phrase']) {
            this.reset();
            this.queryParams['search_phrase'] = String(searchPhrase);
            this.title.setTitle(['Photos', `Search "${this.queryParams['search_phrase']}"`]);
            this.metaTags.setTitle(this.title.getPageName());
            const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
            this.loadPhotos(this.defaults.page, perPageOffset, {
                searchPhrase: this.queryParams['search_phrase'],
            });
        }
    }

    protected navigateToSearchPhotos(searchPhrase:string):void {
        if (searchPhrase) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
        }
    }
}
