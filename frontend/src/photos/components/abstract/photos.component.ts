import {OnInit, AfterViewInit} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {LinkedDataService, MetaTagsService} from '../../../core'
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

export abstract class PhotosComponent implements OnInit, AfterViewInit {
    protected defaults:any = {page: 1, perPage: 20, show: null};
    protected queryParams:any = {};
    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected processLocker:ProcessLockerService;
    protected originalImages:Array<any> = [];
    protected images:Array<GalleryImage> = [];
    protected hasMoreImages:boolean = true;

    constructor(protected router:Router,
                protected route:ActivatedRoute,
                protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected linkedData:LinkedDataService,
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
            .subscribe((page:any) => this.queryParams['page'] = page ? Number(page) : this.defaults.page);

        this.route.queryParams
            .map((queryParams:any) => queryParams['show'])
            .subscribe((show:any) => this.queryParams['show'] = show ? Number(show) : this.defaults.show);
    }

    protected initLinkedData():void {
        this.originalImages.forEach((image:any) => {
            this.linkedData.pushItem({
                '@context': 'http://schema.org',
                '@type': 'Article',
                'mainEntityOfPage': {
                    '@type': 'WebPage',
                    '@id': `${this.app.getUrl()}/photos?show=${image.id}`,
                },
                'headline': image.description,
                'image': {
                    '@type': 'ImageObject',
                    'url': image.thumbnails.large.url,
                    'height': image.thumbnails.large.height,
                    'width': image.thumbnails.large.width,
                },
                'datePublished': image.created_at,
                'dateModified': image.updated_at,
                'author': {
                    '@type': 'Person',
                    'name': this.app.getAuthor(),
                },
                "publisher": {
                    "@type": "Organization",
                    "name": this.app.getName(),
                    "logo": {
                        "@type": "ImageObject",
                        "url": this.app.getImage(),
                    }
                },
                'description': image.description,
            });
        });
    }

    protected reset():void {
        this.images = [];
        this.originalImages = [];
    }

    protected processLoadImages(callback:any):Promise<Array<GalleryImage>> {
        if (typeof (callback) !== 'function') {
            throw new Error('Type of the "callback" parameter should be a function.');
        }
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
            this.originalImages = this.originalImages.concat(response.data);
            this.initLinkedData();
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
