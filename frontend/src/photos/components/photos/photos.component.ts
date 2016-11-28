import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TitleService} from '../../../shared/services/title';
import {SyncProcessService, SyncProcessServiceProvider} from '../../../shared/services/sync-process';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';

@Component({
    selector: 'photos',
    template: require('./photos.component.html'),
})
export class PhotosComponent {
    protected empty:boolean;
    protected queryParams:any = {};
    protected pagerService:PagerService;
    protected syncProcessService:SyncProcessService;
    protected navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) protected route:ActivatedRoute,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(PagerServiceProvider) protected pagerServiceProvider:PagerServiceProvider,
                @Inject(SyncProcessServiceProvider) protected syncProcessServiceProvider:SyncProcessServiceProvider,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(PhotoService) protected photoService:PhotoService) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
        this.pagerService = this.pagerServiceProvider.getInstance();
        this.syncProcessService = this.syncProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.titleService.setTitle('All Photos');

        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:string) => {
                this.pagerService.setPage(Number(page));
            });

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:string) => {
                this.queryParams.show = Number(show);
            });

        this.load(
            this.pagerService.getLimitForPage(this.pagerService.getPage()),
            this.pagerService.getOffset()
        ).then((photos:PhotoModel[]) => {
            this.empty = photos.length === 0;
        });
    }

    loadPhotos(take:number, skip:number) {
        return this.photoService.getAll(take, skip).toPromise().then((photos:PhotoModel[]) => {
            return this.pagerService.appendItems(photos);
        });
    }

    getPhotos() {
        return this.pagerService.getItems();
    }

    load(take:number, skip:number) {
        return this.syncProcessService.startProcess().then(() => {
            return this.loadPhotos(take, skip);
        }).then((result:any) => {
            this.syncProcessService.endProcess();
            this.setPageNumber();
            return result;
        }).catch((error:any) => {
            this.syncProcessService.endProcess();
            return error;
        });
    }

    loadMore() {
        return this.load(this.pagerService.getLimit(), this.pagerService.getOffset());
    }

    setPageNumber() {
        let page = this.pagerService.getPage();
        if (page > 1) this.navigatorService.setQueryParam('page', page);
    }

    isEmpty() {
        return !this.syncProcessService.isProcessing() && this.empty === true;
    }

    isLoading() {
        return this.syncProcessService.isProcessing();
    }

    onShowPhoto(photo:PhotoModel) {
        this.navigatorService.setQueryParam('show', photo.id);
    }

    onHidePhoto() {
        this.navigatorService.unsetQueryParam('show');
    }

    navigateToEditPhoto(photo:PhotoModel) {
        this.navigatorService.navigate(['photo/edit', photo.id]);
    }
}
