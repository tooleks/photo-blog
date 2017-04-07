import {Component, OnInit, AfterViewInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    PagerServiceProvider,
    PagerService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {PhotoDataProviderService} from '../../services';
import {PhotoToGalleryImageMapper} from '../../mappers';
import {GalleryImage} from '../../../shared/components/gallery';

@Component({
    selector: 'photos',
    templateUrl: 'photos.component.html',
})
export class PhotosComponent implements OnInit, AfterViewInit {
    private defaults:any = {page: 1, perPage: 20};
    private queryParams:Object = {};
    private pager:PagerService;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;
    private galleryImages:Array<GalleryImage> = [];
    private hasMoreGalleryImages:boolean = true;

    constructor(private route:ActivatedRoute,
                private title:TitleService,
                private authProvider:AuthProviderService,
                private photoDataProvider:PhotoDataProviderService,
                navigatorProvider:NavigatorServiceProvider,
                pagerProvider:PagerServiceProvider,
                lockProcessProvider:LockProcessServiceProvider) {
        this.pager = pagerProvider.getInstance(this.defaults.page, this.defaults.perPage);
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessProvider.getInstance();
    }

    ngOnInit():void {
        this.title.setTitle('All Photos');
        this.initQueryParams();
    }

    ngAfterViewInit():void {
        const perPageOffset = this.queryParams['page'] * this.pager.getPerPage();
        this.loadPhotos(this.defaults.page, perPageOffset);
    }

    private initQueryParams = ():void => {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.queryParams['page'] = page ? Number(page) : this.defaults.page);

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = Number(show));
    };

    private loadPhotos = (page:number, perPage:number):Promise<Array<GalleryImage>> => {
        return this.lockProcess
            .process(this.photoDataProvider.getAll, [page, perPage])
            .then(this.handleLoadPhotos);
    };

    private handleLoadPhotos = (response:any):Array<GalleryImage> => {
        const galleryImages = PhotoToGalleryImageMapper.map(response.data);
        this.hasMoreGalleryImages = !(response.data.length < this.defaults.perPage);
        if (response.data.length) {
            this.pager.setPage(response.current_page);
            this.navigator.setQueryParam('page', this.pager.getPage());
            this.galleryImages = this.galleryImages.concat(galleryImages);
        }
        return galleryImages;
    };

    loadMorePhotos = ():Promise<Array<GalleryImage>> => {
        return this.loadPhotos(this.pager.getNextPage(), this.pager.getPerPage());
    };

    isEmpty = ():boolean => {
        return !this.galleryImages.length && !this.lockProcess.isProcessing();
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    onShowPhoto = (galleryImage:GalleryImage):void => {
        this.navigator.setQueryParam('show', galleryImage.getId());
    };

    onHidePhoto = (galleryImage:GalleryImage):void => {
        this.navigator.unsetQueryParam('show');
    };

    onEditPhoto = (galleryImage:GalleryImage):void => {
        this.navigator.navigate(['photo/edit', galleryImage.getId()]);
    };
}
