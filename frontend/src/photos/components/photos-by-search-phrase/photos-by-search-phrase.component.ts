import {Component, Inject, ViewChildren, ViewChild} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {PhotoDataProviderService} from '../../services';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {GalleryImage, GalleryComponent} from '../../../shared/components/gallery';

@Component({
    selector: 'photos-by-search-phrase',
    templateUrl: 'photos-by-search-phrase.component.html',
})
export class PhotosBySearchPhraseComponent {
    @ViewChildren('inputSearch') inputSearchComponent:any;
    @ViewChild('galleryComponent') galleryComponent:GalleryComponent;
    private defaults:any = {page: 1, perPage: 20};
    private queryParams:Object = {search_phrase: ''};
    private pager:PagerService;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;
    private galleryImages:Array<GalleryImage> = [];
    private hasMoreGalleryImages:boolean = true;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(PagerServiceProvider) pagerProvider:PagerServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessProvider:LockProcessServiceProvider) {
        this.pager = pagerProvider.getInstance(this.defaults.page, this.defaults.perPage);
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessProvider.getInstance();
    }

    ngOnInit() {
        this.title.setTitle(['Search Photos']);
        this.initQueryParams();
    }

    ngAfterViewInit() {
        this.inputSearchComponent.first.nativeElement.focus();

        this.route.queryParams
            .map((queryParams) => queryParams['search_phrase'])
            .subscribe(this.searchPhotosByPhrase);
    }

    private initQueryParams = ():void => {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.queryParams['page'] = page ? Number(page) : this.defaults.page);

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = Number(show));
    };

    private loadPhotos = (page:number, perPage:number, searchPhrase:string):Promise<Array<GalleryImage>> => {
        return this.lockProcess
            .process(this.photoDataProvider.getBySearchPhrase, [page, perPage, searchPhrase])
            .then(this.handleLoadPhotos);
    };

    private handleLoadPhotos = (response:any):Array<GalleryImage> => {
        const galleryImages = PhotoToGalleryImageMapper.map(response.data);
        this.hasMoreGalleryImages = Boolean(response.data.length) || response.data.lenth < this.defaults.perPage;
        if (response.data.length) {
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.galleryImages = this.galleryImages.concat(galleryImages);
        }
        return galleryImages;
    };

    loadMorePhotos = ():Promise<Array<GalleryImage>> => {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage(), this.queryParams['search_phrase']);
    };

    searchPhotosByPhrase = (searchPhrase:string):void => {
        if (searchPhrase && searchPhrase != this.queryParams['search_phrase']) {
            this.galleryComponent.reset();
            this.queryParams['search_phrase'] = String(searchPhrase);
            this.title.setTitle(['Photos', 'Search "' + this.queryParams['search_phrase'] + '"']);
            const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
            this.loadPhotos(this.defaults.page, perPageOffset, this.queryParams['search_phrase']);
        }
    };

    navigateToSearchPhotos(searchPhrase:string):void {
        if (searchPhrase) {
            this.navigator.navigate(['photos/search'], {queryParams: {search_phrase: searchPhrase}});
        }
    }

    isEmpty = ():boolean => {
        return !this.galleryImages.length && !this.lockProcess.isProcessing();
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    onShowPhoto = (galleryImage:GalleryImage):void => {
        this.navigator.setQueryParam('show', galleryImage.getId());
    };

    onHidePhoto = (galleryImage:GalleryImage):void => {
        this.navigator.unsetQueryParam('show');
    };

    onEditPhoto = (galleryImage:GalleryImage):void => {
        this.navigator.navigate(['photo/edit', galleryImage.getId()]);
    };
}
