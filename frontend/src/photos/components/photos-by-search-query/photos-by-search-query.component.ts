import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TitleService} from '../../../shared/services/title';
import {LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';
import {PhotosGrid} from '../photos-grid';

@Component({
    selector: 'photos',
    template: require('./photos-by-search-query.component.html'),
})
export class PhotosBySearchQueryComponent extends PhotosGrid {
    protected queryParams:any = {};
    protected pagerService:PagerService;

    constructor(@Inject(ActivatedRoute) protected route:ActivatedRoute,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(PagerServiceProvider) protected pagerServiceProvider:PagerServiceProvider,
                @Inject(LockerServiceProvider) protected lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(PhotoService) protected photoService:PhotoService) {
        super(lockerServiceProvider, navigatorServiceProvider);
        this.pagerService = this.pagerServiceProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page) => this.pagerService.setPage(Number(page)));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show) => this.queryParams.show = Number(show));

        this.route.queryParams
            .map((params) => params['query'])
            .subscribe((query) => {
                this.titleService.setTitle(['Photos', query]);
                this.queryParams.query = query;
                this.pagerService = this.pagerServiceProvider.getInstance();
                this
                    .loadPhotos(
                        this.pagerService.getLimitForPage(this.pagerService.getPage()),
                        this.pagerService.getOffset(),
                        this.queryParams.query
                    )
                    .then((photos:PhotoModel[]) => {
                        this.empty = photos.length === 0;
                    });
            });
    }

    loadMorePhotos() {
        return this.loadPhotos(
            this.pagerService.getLimit(),
            this.pagerService.getOffset(),
            this.queryParams.tag
        );
    }

    loadPhotos(take:number, skip:number, query:string) {
        if (this.lockerService.isLocked()) {
            return new Promise((resolve, reject) => reject());
        } else {
            this.lockerService.lock();
        }

        return this.photoService
            .getBySearchQuery(take, skip, query).toPromise()
            .then((photos:PhotoModel[]) => {
                return this.pagerService
                    .appendItems(photos)
                    .then((photos:PhotoModel[]) => {
                        this.navigatorService.setQueryParam('page', this.pagerService.getPage());
                        this.lockerService.unlock();
                        return photos;
                    });
            }).catch((error:any) => {
                this.lockerService.unlock();
                return error;
            });
    }

    getPhotos() {
        return this.pagerService.getItems();
    }
}
