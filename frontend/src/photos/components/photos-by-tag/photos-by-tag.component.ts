import {Component, Inject, ViewChild} from '@angular/core';
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
import {PhotoDataProviderService} from '../../services';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {GalleryImage} from '../../../shared/components/gallery';

@Component({
    selector: 'photos-by-tag',
    templateUrl: 'photos-by-tag.component.html',
})
export class PhotosByTagComponent {
    @ViewChild('galleryComponent') galleryComponent:any;
    private defaults:any = {page: 1, perPage: 20};
    private queryParams:Object = {search_phrase: ''};
    private pager:PagerService;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;
    private galleryImages:Array<GalleryImage> = [];

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
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
        this.title.setTitle(['Search By Tag']);
        this.scroller.scrollToTop();

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.queryParams['page'] = page ? Number(page) : this.defaults.page);

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = Number(show));
    }

    ngAfterViewInit() {
        this.route.params
            .map((params) => params['tag'])
            .subscribe(this.searchByTag);
    }

    private loadPhotos = (page:number, perPage:number, tag:string):Promise<Array<GalleryImage>> => {
        return this.lockProcess
            .process(this.photoDataProvider.getByTag, [page, perPage, tag])
            .then(this.handleLoadPhotos);
    };

    private handleLoadPhotos = (response:any):Array<GalleryImage> => {
        const galleryImages = PhotoToGalleryImageMapper.map(response.data);
        if (response.data.length) {
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.galleryImages = this.galleryImages.concat(galleryImages);
        }
        return galleryImages;
    };

    loadMorePhotos = ():Promise<Array<GalleryImage>> => {
        return this.loadPhotos(this.pager.getPage(), this.pager.getPerPage(), this.queryParams['tag']);
    };

    searchByTag = (tag:string):void => {
        if (tag && tag != this.queryParams['tag']) {
            this.galleryComponent.reset();
            this.queryParams['tag'] = tag;
            this.title.setTitle(['Photos', 'Tag #' + tag]);
            const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
            this.loadPhotos(this.defaults.page, perPageOffset, this.queryParams['tag']);
        }
    };

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
