import {Component, Input, Output, Inject, EventEmitter, HostListener, SimpleChanges, ViewChild} from '@angular/core';
import {CallbackHandlerService, ScrollFreezerService} from '../../services';
import {GalleryGridComponent} from './gallery-grid.component';
import {GalleryImage} from './models';

@Component({
    selector: 'gallery',
    templateUrl: 'gallery.component.html',
    styleUrls: ['gallery.component.css'],
})
export class GalleryComponent {
    @ViewChild('galleryGridComponent') galleryGridComponent:GalleryGridComponent;
    @Input() galleryImages:Array<GalleryImage> = [];
    @Output() galleryImagesChange:EventEmitter<Array<GalleryImage>> = new EventEmitter<Array<GalleryImage>>();
    @Input() defaultImageId:string;
    @Input() onLoadMoreCallback:any;
    @Input() showCloseButton:boolean = true;
    @Input() showInfoButton:boolean = true;
    @Input() showEditButton:boolean = true;
    @Input() showDeleteButton:boolean = false;
    @Output() onOpenImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    @Output() onCloseImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    @Output() onEditImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    @Output() onDeleteImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    private isOpenedInfo:boolean = false;
    private openedImage:GalleryImage;
    private openedImageIndex:number;
    private openedImageIsLoaded:boolean;

    constructor(@Inject(CallbackHandlerService) private callbackHandler:CallbackHandlerService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.reset();
    }

    ngOnChanges(changes:SimpleChanges) {
        // We will view default image only on the fresh load of images.
        // This is a buggy piece of code. Be aware when making a changes.
        if (this.defaultImageId && changes['galleryImages'] && !changes['galleryImages'].previousValue.length) {
            this.viewImageById(this.defaultImageId);
        }
    }

    @HostListener('document:keydown', ['$event'])
    onDocumentKeyDown = (event:KeyboardEvent) => {
        if (this.openedImage) {
            switch (event.key) {
                case 'Escape':
                    return this.closeImage();
                case 'ArrowLeft':
                    return this.viewPrevImage();
                case 'ArrowRight':
                    return this.viewNextImage(true);
            }
        }
    };
    
    reset = () => {
        this.galleryImages = [];
        this.galleryImagesChange.emit(this.galleryImages);
        this.unsetOpenedImage();
        this.galleryGridComponent.reset();
    };

    setOpenedImage = (galleryImage:GalleryImage, index:number):void => {
        this.scrollFreezer.freezeBackgroundScroll();
        this.openedImage = galleryImage;
        this.openedImageIndex = index;
        const galleryImageLoader = new Image;
        let loaded = false;
        galleryImageLoader.onload = () => {
            this.openedImageIsLoaded = loaded = true;
            this.onOpenImage.emit(this.openedImage);
        };
        setTimeout(() => (this.openedImageIsLoaded = loaded), 400);
        galleryImageLoader.src = galleryImage.getLargeSizeUrl();
    };

    unsetOpenedImage = ():void => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.openedImage = null;
        this.openedImageIndex = null;
        this.openedImageIsLoaded = false;
    };

    viewImageById = (galleryImageId:any):void => {
        this.galleryImages.some((galleryImage:GalleryImage, index:number) => {
            if (galleryImage.getId() == galleryImageId && index != this.openedImageIndex) {
                this.setOpenedImage(galleryImage, index);
                return true;
            } else if (index === this.galleryImages.length - 1) {
                this.loadMoreImages().then(() => this.viewImageById(galleryImageId));
            }
            return false;
        });
    };

    viewImage = (galleryImage:GalleryImage):void => {
        const galleryImageId = galleryImage.getId();
        this.galleryImages.some((galleryImage:GalleryImage, index:number) => {
            if (galleryImage.getId() == galleryImageId && index != this.openedImageIndex) {
                this.setOpenedImage(this.galleryImages[index], index);
                return true;
            }
            return false;
        });
    };

    viewPrevImage = ():void => {
        const prevImageIndex = this.openedImageIndex - 1;
        if (this.galleryImages[prevImageIndex]) {
            this.viewImage(this.galleryImages[prevImageIndex]);
        }
    };

    viewNextImage = (loadMoreIfNotExist:boolean):void => {
        const nextImageIndex = this.openedImageIndex + 1;
        if (this.galleryImages[nextImageIndex]) {
            this.viewImage(this.galleryImages[nextImageIndex]);
        } else if (loadMoreIfNotExist) {
            this.loadMoreImages().then(() => this.viewNextImage(false));
        }
    };

    loadMoreImages = ():Promise<Array<GalleryImage>> => {
        return this.callbackHandler
            .resolveCallback(this.onLoadMoreCallback)
            .then((galleryImages:Array<GalleryImage>) => this.galleryImages = galleryImages);
    };

    closeImage = ():void => {
        this.onCloseImage.emit(this.openedImage);
        this.unsetOpenedImage();
    };

    toggleInfo = ():void => {
        this.isOpenedInfo = !this.isOpenedInfo;
    };

    editImage = ():void => {
        this.onEditImage.emit(this.openedImage);
    };

    deleteImage = ():void => {
        this.onDeleteImage.emit(this.openedImage);
    };
}
