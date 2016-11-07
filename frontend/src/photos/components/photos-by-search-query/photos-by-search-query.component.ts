import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {LockerService, LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {PhotoService} from '../../services/photo.service';

@Component({
    selector: 'photos',
    template: require('./photos-by-search-query.component.html'),
})
export class PhotosBySearchQueryComponent {
    private pager:PagerService;
    private locker:LockerService;
    private navigator:NavigatorService;
    queryParams:any = {};

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(PagerServiceProvider) private pagerProvider:PagerServiceProvider,
                @Inject(LockerServiceProvider) private lockerProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) private navigatorProvider:NavigatorServiceProvider,
                @Inject(PhotoService) private photoService:PhotoService) {
        this.pager = this.pagerProvider.getInstance();
        this.locker = this.lockerProvider.getInstance();
        this.navigator = this.navigatorProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page) => this.pager.setPage(parseInt(page)));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show) => this.queryParams.show = parseInt(show));

        this.route.params
            .map((params) => params['query'])
            .subscribe((query) => {
                console.log('PhotosBySearchQueryComponent');
                console.log(query);
                this.queryParams.query = query;
                this.pager = this.pagerProvider.getInstance();
                this.loadPhotos(this.pager.getLimitForPage(this.pager.getPage()), this.pager.getOffset(), this.queryParams.query);
            });
    }

    loadMorePhotos() {
        return this.loadPhotos(this.pager.getLimit(), this.pager.getOffset(), this.queryParams.tag);
    }

    loadPhotos(take:number, skip:number, tag:string) {
        if (this.locker.isLocked()) {
            return;
        } else {
            this.locker.lock();
        }
        return new Promise((resolve) => {
            this.photoService
                .getBySearchQuery(take, skip, tag)
                .subscribe((items:Object[]) => {
                    this.setPhotos(items);
                    this.locker.unlock();
                    resolve(this.pager.getItems());
                });
        });
    }

    setPhotos(photos:Object[]) {
        this.pager.addItems(photos);
        if (this.pager.getPage() > 1) {
            this.navigator.setQueryParam('page', this.pager.getPage());
        }
    }

    getPhotos() {
        return this.pager.getItems();
    }

    showPhotoCallback(photo:any) {
        this.navigator.setQueryParam('show', photo.id);
    }

    hidePhotoCallback() {
        this.navigator.unsetQueryParam('show');
    }
}
