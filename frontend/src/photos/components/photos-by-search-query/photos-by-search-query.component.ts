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
import {PhotoDataProviderService} from '../../services';

@Component({
    selector: 'photos',
    template: require('./photos-by-search-query.component.html'),
})
export class PhotosBySearchQueryComponent {
    @ViewChildren('inputSearch') inputSearchComponent:any;
    @ViewChild('galleryComponent') galleryComponent:any;

    private photos:Array<PublishedPhoto> = [];
    private initialized:boolean = false;
    private queryParams:Object = {search_phrase: ''};
    private searchPhrase:string = null;
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
        this.navigator = navigatorProvider.getInstance();
        this.pager = pagerProvider.getInstance(1, 20);
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
    }

    ngAfterViewInit() {
        this.inputSearchComponent.first.nativeElement.focus();

        this.route.queryParams
            .map((queryParams) => queryParams['search_phrase'])
            .subscribe((searchPhrase:string) => {
                if (!searchPhrase || searchPhrase == this.searchPhrase) {
                    return;
                }
                this.photos = [];
                this.galleryComponent.reset();
                this.queryParams['search_phrase'] = this.searchPhrase = searchPhrase;
                this.title.setTitle(['Photos', this.queryParams['search_phrase']]);
                this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage(), this.queryParams['search_phrase']);
            });
    }

    private processLoadPhotos = (page:number, perPage:number, searchPhrase:string):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getBySearchPhrase(page, perPage, searchPhrase)
            .then((response:any) => {
                // If the response has data, set pager page to current page.
                response.data.length && this.pager.setPage(response.current_page);
                // Concatenate already loaded photos with just loaded photos and set initialized flag.
                this.photos = this.photos.concat(response.data);
                this.initialized = true;
                // Return new photos.
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

    getLoadedPhotos = ():Array<PublishedPhoto> => {
        return this.photos;
    };

    searchPhotos = () => {
        if (this.queryParams['search_phrase'].length) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: this.queryParams['search_phrase']}});
        }
    };

    isEmpty = ():boolean => {
        return this.initialized && !this.photos.length && !this.lockProcess.isProcessing();
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
