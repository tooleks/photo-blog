import {ActivatedRoute} from '@angular/router';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {
    TitleService,
    MetaTagsService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    LockProcessServiceProvider,
    LockProcessService,
    ScrollFreezerService,
} from '../../../shared';
import {GalleryImage} from '../../../lib/gallery';

export abstract class PhotosGalleryComponent {
    protected defaults:any = {page: 1, perPage: 20, show: null};
    protected queryParams:any = {};
    protected pager:PagerService;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;
    protected images:Array<GalleryImage> = [];
    protected hasMoreImages:boolean = true;

    constructor(protected route:ActivatedRoute,
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

    protected init():void {
        this.initTitle();
        this.initMeta();
        this.initParamsSubscribers();
    }

    protected abstract initTitle():void;

    protected initMeta():void {
        this.metaTags.setTitle(this.title.getPageName());
    }

    protected initParamsSubscribers():void {
        this.route.queryParams
            .map((queryParams:any) => queryParams['page'])
            .subscribe((page:number) => this.queryParams['page'] = page ? Number(page) : this.defaults.page);

        this.route.queryParams
            .map((queryParams:any) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show ? Number(show) : this.defaults.show);
    }

    protected reset():void {
        this.images = [];
    }

    protected abstract loadPhotos(page:number, perPage:number, parameters?:any):Promise<Array<GalleryImage>>;

    protected abstract loadMorePhotos():Promise<Array<GalleryImage>>;

    protected handleLoadPhotos(response:any):Array<GalleryImage> {
        const images = PhotoToGalleryImageMapper.map(response.data);
        this.hasMoreImages = !(response.data.length < this.defaults.perPage);
        if (response.data.length) {
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
        this.scrollFreezer.freezeBackgroundScroll();
        this.navigator.setQueryParam('show', image.getId());
        this.metaTags.setImage(image.getLargeSizeUrl());
        this.metaTags.setTitle(image.getDescription());
    }

    protected onHidePhoto(image:GalleryImage):void {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.navigator.unsetQueryParam('show');
    }

    protected onEditPhoto(image:GalleryImage):void {
        this.navigator.navigate(['photo/edit', image.getId()]);
    }
}
