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
import {PublishedPhoto} from '../../../shared/models';
import {PhotoDataProviderService} from '../../services';

@Component({
    selector: 'photos',
    template: require('./photos-by-tag.component.html'),
})
export class PhotosByTagComponent {
    @ViewChild('galleryComponent') galleryComponent:any;

    private photos:Array<PublishedPhoto> = [];
    private initialized:boolean = false;
    private queryParams:Object = {};
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

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);
    }

    ngAfterViewInit() {
        this.route.params
            .map((params) => params['tag'])
            .subscribe((tag:string) => {
                this.photos = [];
                this.galleryComponent.reset();
                this.queryParams['tag'] = tag;
                this.title.setTitle(['Photos', '#' + tag]);
                this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage(), this.queryParams['tag']);
            });
    }

    private processLoadPhotos = (page:number, perPage:number, tag:string):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getByTag(page, perPage, tag)
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

    private loadPhotos = (page:number, perPage:number, tag:string):Promise<Array<PublishedPhoto>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [page, perPage, tag])
            .then((result:any) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return result;
            });
    };

    loadMorePhotos = () => {
        return this.loadPhotos(this.pager.getPage(), this.pager.getPerPage(), this.queryParams['tag']);
    };

    getLoadedPhotos = () => {
        return this.photos;
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
