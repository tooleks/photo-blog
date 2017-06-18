import {OnInit, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/filter';
import {MetaTagsService} from '../../../core'
import {GalleryImage} from '../../../lib';
import {
    AppService,
    TitleService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    ProcessLockerServiceProvider,
    ProcessLockerService,
    ScrollFreezerService,
} from '../../../shared';
import {PhotoToLinkedDataMapper, PhotoToGalleryImageMapper} from '../../mappers';

export abstract class PhotosComponent implements OnInit, AfterViewInit {
    protected pager: PagerService;
    protected navigator: NavigatorService;
    protected processLocker: ProcessLockerService;

    images: Array<GalleryImage> = [];
    hasMoreImages: boolean = true;

    linkedData: Array<any> = [];
    queryParams = {};

    protected defaults = {
        page: 1,
        perPage: 40,
        show: null,
        title: null,
    };

    constructor(protected router: Router,
                protected route: ActivatedRoute,
                protected app: AppService,
                protected title: TitleService,
                protected metaTags: MetaTagsService,
                protected navigatorProvider: NavigatorServiceProvider,
                protected pagerProvider: PagerServiceProvider,
                protected processLockerProvider: ProcessLockerServiceProvider,
                protected scrollFreezer: ScrollFreezerService,
                protected galleryImageMapper: PhotoToGalleryImageMapper,
                protected linkedDataMapper: PhotoToLinkedDataMapper) {
        this.pager = this.pagerProvider.getInstance(this.defaults['page'], this.defaults['perPage']);
        this.navigator = this.navigatorProvider.getInstance();
        this.processLocker = this.processLockerProvider.getInstance();
    }

    reset(): void {
        this.images = [];
        this.hasMoreImages = true;
        this.linkedData = [];
    }

    ngOnInit(): void {
        this.title.setPageNameSegment(this.defaults['title']);
        this.metaTags.setTitle(this.title.getPageNameSegment());
        this.queryParams['page'] = this.defaults['page'];
        this.queryParams['show'] = this.defaults['show'];
    }

    ngAfterViewInit(): void {
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers(): void {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .filter((page) => typeof (page) !== 'undefined')
            .map((page) => Number(page))
            .subscribe((page: number) => this.queryParams['page'] = page);

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .filter((show) => typeof (show) !== 'undefined')
            .map((show) => Number(show))
            .subscribe((show: number) => this.queryParams['show'] = show);
    }

    isEmpty(): boolean {
        return !this.images.length && !this.processLocker.isLocked();
    }

    isProcessing(): boolean {
        return this.processLocker.isLocked();
    }

    onLoadImagesSuccess(response) {
        if (response.data.length) {
            let images = response.data.map((item) => this.galleryImageMapper.map(item));
            this.images = this.images.concat(images);
        }

        if (response.data.length) {
            let linkedData = response.data.map((image) => this.linkedDataMapper.map(image));
            this.linkedData = this.linkedData.concat(linkedData);
        }

        if (response.data.length) {
            this.pager.setPage(response.current_page);
            if (this.pager.getPage() > 1) {
                this.navigator.setQueryParam('page', this.pager.getPage());
            }
        }

        this.hasMoreImages = !(response.data.length < this.defaults['perPage']);

        return response;
    }

    onShowPhoto(image: GalleryImage): void {
        this.scrollFreezer.freeze();
        this.title.setPageNameSegment(image.getDescription());
        this.metaTags.setTitle(image.getDescription()).setImage(image.getViewerSizeUrl());
        this.navigator.setQueryParam('show', image.getId());
    }

    onHidePhoto(image: GalleryImage): void {
        this.scrollFreezer.unfreeze();
        this.navigator.unsetQueryParam('show');
        this.queryParams['show'] = this.defaults['show'];
        this.title.setPageNameSegment(this.defaults['title']);
    }

    onEditPhoto(image: GalleryImage): void {
        this.scrollFreezer.unfreeze();
        this.navigator.navigate(['photo/edit', image.getId()]);
    }
}
