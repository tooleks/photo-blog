import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {LockerService, LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {PhotoService} from '../../services/photo.service';

@Component({
    selector: 'photos',
    template: require('./photos.component.html'),
})
export class PhotosComponent {
    private pagerService:PagerService;
    private lockerService:LockerService;
    private navigatorService:NavigatorService;
    queryParams:any = {};

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(PagerServiceProvider) private pagerServiceProvider:PagerServiceProvider,
                @Inject(LockerServiceProvider) private lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(PhotoService) private photoService:PhotoService) {
        this.pagerService = pagerServiceProvider.getInstance();
        this.lockerService = lockerServiceProvider.getInstance();
        this.navigatorService = navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page) => this.pagerService.setPage(parseInt(page)));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show) => this.queryParams.show = parseInt(show));

        this.loadPhotos(this.pagerService.getLimitForPage(this.pagerService.getPage()), this.pagerService.getOffset());
    }

    loadMorePhotos() {
        return this.loadPhotos(this.pagerService.getLimit(), this.pagerService.getOffset());
    }

    loadPhotos(take:number, skip:number) {
        if (this.lockerService.isLocked()) {
            return;
        } else {
            this.lockerService.lock();
        }
        return new Promise((resolve) => {
            this.photoService
                .getAll(take, skip)
                .subscribe((photos:Object[]) => {
                    this.setPhotos(photos);
                    this.lockerService.unlock();
                    resolve(this.pagerService.getItems());
                });
        });
    }

    getPhotos() {
        return this.pagerService.getItems();
    }

    setPhotos(photos:Object[]) {
        this.pagerService.addItems(photos);
        if (this.pagerService.getPage() > 1) {
            this.navigatorService.setQueryParam('page', this.pagerService.getPage());
        }
    }

    showPhotoCallback(photo:any) {
        this.navigatorService.setQueryParam('show', photo.id);
    }

    hidePhotoCallback() {
        this.navigatorService.unsetQueryParam('show');
    }
}
