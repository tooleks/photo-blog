import {OnInit, AfterViewInit, EventEmitter} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import 'rxjs/add/operator/map';
import {MetaTagsService, EnvironmentDetectorService} from '../../../core'
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
import {PhotoToGalleryImageMapper} from '../../mappers';

export abstract class PhotosComponent implements OnInit, AfterViewInit {
    protected defaults:any = {title: '', page: 1, perPage: 20, show: null};
    protected queryParams:any = {};
    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected processLocker:ProcessLockerService;
    protected originalImages:Array<any> = [];
    protected originalImagesChange:EventEmitter<Array<any>> = new EventEmitter<Array<any>>();
    protected images:Array<GalleryImage> = [];
    protected hasMoreImages:boolean = true;
    protected linkedData:Array<any> = [];

    constructor(protected router:Router,
                protected route:ActivatedRoute,
                protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected navigatorProvider:NavigatorServiceProvider,
                protected pagerProvider:PagerServiceProvider,
                protected processLockerProvider:ProcessLockerServiceProvider,
                protected environmentDetector:EnvironmentDetectorService,
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

        this.originalImagesChange
            .subscribe((originalImages:Array<any>) => this.onOriginalImagesChange(originalImages));
    }

    reset():void {
        this.images = [];
        this.originalImages = [];
        this.originalImagesChange.emit(this.originalImages);
    }

    protected processLoadImages(callback:any):Promise<Array<GalleryImage>> {
        if (typeof (callback) !== 'function') {
            throw new Error('Type of the "callback" parameter should be a function.');
        }
        return this.processLocker
            .lock(callback)
            .then((response:any) => this.onLoadImagesSuccess(response));
    }

    protected onLoadImagesSuccess(response:any):Array<GalleryImage> {
        const images:Array<GalleryImage> = PhotoToGalleryImageMapper.map(response.data);
        this.hasMoreImages = !(response.data.length < this.defaults.perPage);
        if (response.data.length) {
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.originalImages = this.originalImages.concat(response.data);
            this.originalImagesChange.emit(this.originalImages);
            this.images = this.images.concat(images);
        }
        return images;
    }

    isEmpty():boolean {
        return !this.images.length && !this.processLocker.isLocked();
    }

    isProcessing():boolean {
        return this.processLocker.isLocked();
    }

    onOriginalImagesChange(originalImages:Array<any>):void {
        if (this.environmentDetector.isServer()) {
            this.linkedData = originalImages.map((image:any) => {
                return {
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
                };
            });
        }
    }

    onShowPhoto(image:GalleryImage):void {
        this.scrollFreezer.freeze();
        this.title.setPageNameSegment(image.getDescription());
        this.metaTags.setTitle(image.getDescription()).setImage(image.getLargeSizeUrl());
        this.navigator.setQueryParam('show', image.getId());
    }

    onHidePhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreeze();
        this.navigator.unsetQueryParam('show');
        this.title.setPageNameSegment(this.defaults['title']);
    }

    onEditPhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreeze();
        this.navigator.navigate(['photo/edit', image.getId()]);
    }
}
