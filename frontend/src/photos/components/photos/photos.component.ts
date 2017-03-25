import {Component, Inject} from '@angular/core';
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
import {PhotoDataProviderService} from '../../services/photo-data-provider';

@Component({
    selector: 'photos',
    template: require('./photos.component.html'),
})
export class PhotosComponent {
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

        this.title.setTitle('All Photos');

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);
    }

    ngAfterViewInit() {
        this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage());
    }

    private processLoadPhotos = (page:number, perPage:number):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getAll(page, perPage)
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

    private loadPhotos = (page:number, perPage:number):Promise<Array<PublishedPhoto>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [page, perPage])
            .then((photos:Array<PublishedPhoto>) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return photos;
            });
    };

    loadMorePhotos = ():Promise<Array<PublishedPhoto>> => {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage());
    };

    getLoadedPhotos = ():Array<PublishedPhoto> => {
        return this.photos;
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    isEmpty = ():boolean => {
        return this.initialized && !this.photos.length && !this.lockProcess.isProcessing();
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
