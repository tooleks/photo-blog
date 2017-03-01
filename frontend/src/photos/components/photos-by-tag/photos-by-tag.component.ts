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

    private loaded:boolean;
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
        this.pager = pagerProvider.getInstance();
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
                this.galleryComponent.reset();
                this.queryParams['tag'] = tag;
                this.title.setTitle(['Photos', '#' + tag]);
                this.pager.reset();
                this.loadPhotos(this.pager.calculateLimitForPage(this.pager.getPage()),
                    this.pager.getOffset(), this.queryParams['tag']);
            });
    }

    private processLoadPhotos = (take:number, skip:number, tag:string):Promise<Array<PublishedPhoto>> => {
        return this.photoDataProvider
            .getByTag(take, skip, tag)
            .then((photos:Array<PublishedPhoto>) => this.pager.appendItems(photos));
    };

    private loadPhotos = (take:number, skip:number, tag:string):Promise<Array<PublishedPhoto>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [take, skip, tag])
            .then((result:any) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return result;
            });
    };

    getLoadedPhotos = () => {
        return this.pager.getItems();
    };

    loadMorePhotos = () => {
        return this.loadPhotos(this.pager.getLimit(), this.pager.getOffset(), this.queryParams['tag']);
    };

    isEmpty = ():boolean => {
        return !this.pager.getItems().length && !this.lockProcess.isProcessing() && !this.loaded;
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
