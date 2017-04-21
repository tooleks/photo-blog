import {OnInit} from '@angular/core';
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
    LockProcessServiceProvider,
    LockProcessService,
    ScrollFreezerService,
} from '../../../shared';

export abstract class BasePhotosComponent implements OnInit {
    protected defaults:any = {page: 1, perPage: 20, show: null};
    protected queryParams:any = {};
    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;
    protected images:Array<GalleryImage> = [];
    protected hasMoreImages:boolean = true;

    constructor(protected router:Router,
                protected route:ActivatedRoute,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected navigatorProvider:NavigatorServiceProvider,
                protected pagerProvider:PagerServiceProvider,
                protected lockProcessProvider:LockProcessServiceProvider,
                protected scrollFreezer:ScrollFreezerService) {
        this.pager = this.pagerProvider.getInstance(this.defaults.page, this.defaults.perPage);
        this.navigator = this.navigatorProvider.getInstance();
        this.lockProcess = this.lockProcessProvider.getInstance();
    }

    ngOnInit():void {
        this.queryParams['page'] = this.defaults.page;
        this.queryParams['show'] = this.defaults.show;
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

    protected abstract loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>>;

    protected abstract loadMorePhotos():Promise<Array<GalleryImage>>;

    protected onLoadPhotosSuccess(response:any):Array<GalleryImage> {
        let images = [];
        this.hasMoreImages = !(response.data.length < this.defaults.perPage);
        if (response.data.length) {
            images = PhotoToGalleryImageMapper.map(response.data).map((image:GalleryImage) => image.setViewUrl(
                this.router.createUrlTree(['photos'], {queryParams: {show: image.getId()}}).toString()
            ));
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.images = this.images.concat(images);
        }
        return images;
    }

    protected isEmpty():boolean {
        return !this.images.length && !this.lockProcess.isProcessing();
    }

    protected isProcessing():boolean {
        return this.lockProcess.isProcessing();
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
