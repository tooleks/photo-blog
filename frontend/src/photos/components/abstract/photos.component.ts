import {OnInit, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {MetaTagsService} from '../../../core'
import {GalleryImage} from '../../../lib';
import {
    TitleService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    ProcessLockerServiceProvider,
    ProcessLockerService,
    ScrollFreezerService,
} from '../../../shared';

export abstract class PhotosComponent implements OnInit, AfterViewInit {
    protected defaults:any = {page: 1, perPage: 20, show: null};
    protected queryParams:any = {};
    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected processLocker:ProcessLockerService;
    protected images:Array<GalleryImage> = [];
    protected hasMoreImages:boolean = true;

    constructor(protected router:Router,
                protected route:ActivatedRoute,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected navigatorProvider:NavigatorServiceProvider,
                protected pagerProvider:PagerServiceProvider,
                protected processLockerProvider:ProcessLockerServiceProvider,
                protected scrollFreezer:ScrollFreezerService) {
        this.pager = this.pagerProvider.getInstance(this.defaults.page, this.defaults.perPage);
        this.navigator = this.navigatorProvider.getInstance();
        this.processLocker = this.processLockerProvider.getInstance();
    }

    ngOnInit():void {
        this.queryParams['page'] = this.defaults.page;
        this.queryParams['show'] = this.defaults.show;
    }

    ngAfterViewInit():void {
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers():void {
        this.route.queryParams
            .map((queryParams:any) => queryParams['page'])
            .filter((page:any) => page)
            .subscribe((page:number) => this.queryParams['page'] = Number(page));

        this.route.queryParams
            .map((queryParams:any) => queryParams['show'])
            .filter((show:any) => show)
            .subscribe((show:number) => this.queryParams['show'] = Number(show));
    }

    protected reset():void {
        this.images = [];
    }

    protected processLoadImages(callback):Promise<Array<GalleryImage>> {
        return this.processLocker
            .lock(callback)
            .then(this.onLoadImagesSuccess.bind(this));
    }

    protected onLoadImagesSuccess(response:any):Array<GalleryImage> {
        const images = this.mapImages(response.data);
        this.hasMoreImages = !(response.data.length < this.defaults.perPage);
        if (response.data.length) {
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.images = this.images.concat(images);
        }
        return images;
    }

    protected mapImages(images:Array<any>):Array<GalleryImage> {
        return PhotoToGalleryImageMapper.map(images).map((image:GalleryImage) => {
            return image.setViewUrl(
                this.router.createUrlTree(['photos'], {queryParams: {show: image.getId()}}).toString()
            );
        });
    }

    protected isEmpty():boolean {
        return !this.images.length && !this.processLocker.isLocked();
    }

    protected isProcessing():boolean {
        return this.processLocker.isLocked();
    }

    protected onShowPhoto(image:GalleryImage):void {
        this.scrollFreezer.freeze();
        this.title.setDynamicTitle(image.getDescription());
        this.metaTags.setImage(image.getLargeSizeUrl());
        this.metaTags.setTitle(image.getDescription());
        this.navigator.setQueryParam('show', image.getId());
    }

    protected onHidePhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreeze();
        this.navigator.unsetQueryParam('show');
        this.title.unsetDynamicTitle();
    }

    protected onEditPhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreeze();
        this.navigator.navigate(['photo/edit', image.getId()]);
    }
}
