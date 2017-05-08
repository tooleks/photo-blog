import {OnInit, AfterViewInit, EventEmitter} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/filter';
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
import {OriginalImageToGalleryImageMapper as Mapper} from '../../mappers';

export abstract class PhotosComponent implements OnInit, AfterViewInit {
    protected defaults:any = {page: 1, perPage: 40, show: null, title: null};
    protected queryParams:any = {};

    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected processLocker:ProcessLockerService;

    protected originalImages:Array<any> = [];
    protected originalImagesChange:EventEmitter<Array<any>> = new EventEmitter<Array<any>>();

    protected images:Array<GalleryImage> = [];
    protected imagesChange:EventEmitter<Array<GalleryImage>> = new EventEmitter<Array<GalleryImage>>();

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
        this.pager = this.pagerProvider.getInstance(this.defaults['page'], this.defaults['perPage']);
        this.navigator = this.navigatorProvider.getInstance();
        this.processLocker = this.processLockerProvider.getInstance();
    }

    setOriginalImages(originalImages:Array<any>):void {
        this.originalImages = originalImages;
        this.originalImagesChange.emit(this.originalImages);
    }

    getOriginalImages():Array<any> {
        return this.originalImages;
    }

    setImages(images:Array<GalleryImage>):void {
        this.images = images;
        this.imagesChange.emit(this.images);
    }

    getImages():Array<GalleryImage> {
        return this.images;
    }

    ngOnInit():void {
        this.queryParams['page'] = this.defaults['page'];
        this.queryParams['show'] = this.defaults['show'];
    }

    ngAfterViewInit():void {
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers():void {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .filter((page) => page)
            .map((page) => Number(page))
            .subscribe((page:number) => this.queryParams['page'] = page);

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .filter((show) => show)
            .map((show) => Number(show))
            .subscribe((show:number) => this.queryParams['show'] = show);

        this.originalImagesChange
            .subscribe((originalImages:Array<any>) => this.onOriginalImagesChange(originalImages));
    }

    reset():void {
        this.setImages([]);
        this.setOriginalImages([]);
    }

    isEmpty():boolean {
        return !this.images.length && !this.processLocker.isLocked();
    }

    isProcessing():boolean {
        return this.processLocker.isLocked();
    }

    protected onLoadImagesSuccess(response:any):Array<GalleryImage> {
        let images:Array<GalleryImage> = [];

        if (response.data.length) {
            // Map the response data into the gallery images.
            images = Mapper.map(response.data);

            // Concatenate loaded images with the existing ones.
            this.setOriginalImages(this.getOriginalImages().concat(response.data));
            this.setImages(this.getImages().concat(images));

            // Change the page query parameter value.
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
        }

        // Change the has more images flag.
        this.hasMoreImages = !(response.data.length < this.defaults['perPage']);

        // Return just loaded gallery images.
        return images;
    }

    protected onOriginalImagesChange(originalImages:Array<any>):void {
        if (this.environmentDetector.isServer()) {
            this.linkedData = originalImages.map((image) => {
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
        this.queryParams['show'] = this.defaults['show'];
        this.title.setPageNameSegment(this.defaults['title']);
    }

    onEditPhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreeze();
        this.navigator.navigate(['photo/edit', image.getId()]);
    }
}
