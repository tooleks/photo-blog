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
import {Photo} from '../../../shared/models';
import {PhotoDataProviderService} from '../../services/photo-data-provider';

@Component({
    selector: 'photos',
    template: require('./photos.component.html'),
})
export class PhotosComponent {
    private loaded:boolean = false;
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

        this.title.setTitle('All Photos');

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);
    }

    ngAfterViewInit() {
        this.loadPhotos(this.pager.calculateLimitForPage(this.pager.getPage()), this.pager.getOffset());
    }

    private processLoadPhotos = (take:number, skip:number):Promise<Array<Photo>> => {
        return this.photoDataProvider
            .getAll(take, skip)
            .then((photos:Array<Photo>) => this.pager.appendItems(photos))
            .then((photos:Array<Photo>) => {
                this.loaded = true;
                return photos;
            });
    };

    private loadPhotos = (take:number, skip:number):Promise<Array<Photo>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [take, skip])
            .then((photos:Array<Photo>) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return photos;
            });
    };

    getLoadedPhotos = ():Array<Photo> => {
        return this.pager.getItems();
    };

    loadMorePhotos = ():Promise<Array<Photo>> => {
        return this.loadPhotos(this.pager.getLimit(), this.pager.getOffset());
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    isEmpty = ():boolean => {
        return !this.pager.getItems().length && !this.lockProcess.isProcessing() && !this.loaded;
    };

    onShowPhoto = (photo:Photo):void => {
        this.navigator.setQueryParam('show', photo.id);
    };

    onHidePhoto = (photo:Photo):void => {
        this.navigator.unsetQueryParam('show');
    };

    onEditPhoto = (photo:Photo):void => {
        this.navigator.navigate(['photo/edit', photo.id]);
    };
}
