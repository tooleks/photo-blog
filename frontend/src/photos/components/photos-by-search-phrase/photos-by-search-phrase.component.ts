import {Component, Inject, ViewChildren, ViewChild} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    TitleService,
    ScrollerService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {PublishedPhoto} from '../../../shared/models';
import {PhotoDataProviderService, PhotoMapper} from '../../services';

@Component({
    selector: 'photos-by-search-phrase',
    templateUrl: './photos-by-search-phrase.component.html',
})
export class PhotosBySearchPhraseComponent {
    @ViewChildren('inputSearch') inputSearchComponent:any;
    @ViewChild('galleryComponent') galleryComponent:any;
    private photos:Array<PublishedPhoto> = [];
    private queryParams:Object = {search_phrase: ''};
    private activeSearchPhrase:string = null;
    private pager:PagerService;
    private lockProcess:LockProcessService;
    private navigator:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(PagerServiceProvider) pagerProvider:PagerServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessProvider:LockProcessServiceProvider) {
        this.pager = pagerProvider.getInstance(1, 20);
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();

        this.title.setTitle(['Search Photos']);

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);

        this.route.queryParams
            .map((queryParams) => queryParams['search_phrase'])
            .subscribe((searchPhrase:string) => {
                if (!searchPhrase || searchPhrase == this.activeSearchPhrase) return;
                this.photos = [];
                this.galleryComponent.reset();
                this.queryParams['search_phrase'] = this.activeSearchPhrase = searchPhrase;
                this.title.setTitle(['Photos', 'Search "' + this.queryParams['search_phrase'] + '"']);
                this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage(), this.queryParams['search_phrase']);
            });
    }

    ngAfterViewInit() {
        this.inputSearchComponent.first.nativeElement.focus();
    }

    private processLoadPhotos = (page:number, perPage:number, searchPhrase:string):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getBySearchPhrase(page, perPage, searchPhrase)
            .then((response:any) => {
                if (response.data.length) this.pager.setPage(response.current_page);
                this.appendPhotos(response.data);
                return response.data;
            });
    };

    private loadPhotos = (page:number, perPage:number, searchPhrase:string):Promise<Array<PublishedPhoto>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [page, perPage, searchPhrase])
            .then((result:any) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return result;
            });
    };

    loadMorePhotos = ():Promise<Array<PublishedPhoto>> => {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['search_phrase']);
    };

    private appendPhotos = (photos:Array<PublishedPhoto>):void => {
        this.photos = this.photos.concat(photos.map(PhotoMapper.mapToPublishedPhoto));
    };

    private getPhotos = ():Array<PublishedPhoto> => {
        return this.photos;
    };

    searchPhotos = (searchPhrase:string):void => {
        if (searchPhrase) this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
    };

    isEmpty = ():boolean => {
        return !this.getPhotos().length && !this.lockProcess.isProcessing();
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    onShowPhoto = (photo:PublishedPhoto):void => {
        this.navigator.setQueryParam('show', photo.id);
    };

    onHidePhoto = (photo:PublishedPhoto):void => {
        this.navigator.unsetQueryParam('show');
    };

    onEditPhoto = (photo:PublishedPhoto):void => {
        this.navigator.navigate(['photo/edit', photo.id]);
    };
}
