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
    templateUrl: './photos.component.html',
})
export class PhotosComponent {
    private photos:Array<PublishedPhoto> = [];
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
        this.pager = pagerProvider.getInstance(1, 20);
        this.navigator = navigatorProvider.getInstance();
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

        this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage());
    }

    private processLoadPhotos = (page:number, perPage:number):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getAll(page, perPage)
            .then((response:any) => {
                if (response.data.length) this.pager.setPage(response.current_page);
                this.appendPhotos(response.data);
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

    private appendPhotos = (photos:Array<PublishedPhoto>):void => {
        this.photos = this.photos.concat(photos);
    };

    private getPhotos = ():Array<PublishedPhoto> => {
        return this.photos;
    };

    loadMorePhotos = ():Promise<Array<PublishedPhoto>> => {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage());
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
