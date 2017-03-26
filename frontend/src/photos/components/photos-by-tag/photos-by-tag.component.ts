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
    selector: 'photos-by-tag',
    templateUrl: './photos-by-tag.component.html',
})
export class PhotosByTagComponent {
    @ViewChild('galleryComponent') galleryComponent:any;
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

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);

        this.route.params
            .map((params) => params['tag'])
            .subscribe((tag:string) => {
                this.photos = [];
                this.galleryComponent.reset();
                this.queryParams['tag'] = tag;
                this.title.setTitle(['Photos', 'Tag #' + tag]);
                this.loadPhotos(1, this.pager.getPerPage() * this.pager.getPage(), this.queryParams['tag']);
            });
    }

    private processLoadPhotos = (page:number, perPage:number, tag:string):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getByTag(page, perPage, tag)
            .then((response:any) => {
                if (response.data.length) this.pager.setPage(response.current_page);
                this.appendPhotos(response.data);
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

    private appendPhotos = (photos:Array<PublishedPhoto>):void => {
        this.photos = this.photos.concat(photos);
    };

    private getPhotos = ():Array<PublishedPhoto> => {
        return this.photos;
    };

    loadMorePhotos = () => {
        return this.loadPhotos(this.pager.getPage(), this.pager.getPerPage(), this.queryParams['tag']);
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
