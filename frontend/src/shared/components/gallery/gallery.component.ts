import {Component, Input, Output, EventEmitter, HostListener, SimpleChanges, ViewChild} from '@angular/core';
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
    private isLoadingMore:boolean = false;

    @Output() onOpenImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showCloseButton:boolean = true;
    @Output() onCloseImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showInfoButton:boolean = true;
    @Input() isOpenedInfo:boolean = false;
    @Output() onToggleInfo:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showEditButton:boolean = true;
    @Output() onEditImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showDeleteButton:boolean = false;
    @Output() onDeleteImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    private openedImage:GalleryImage;
    private openedImageIndex:number;
    private openedImageIsLoaded:boolean;

    ngOnInit() {
        this.reset();
    }

    ngOnChanges(changes:SimpleChanges) {
        // We will view default image only on the fresh load of images.
        // This is a buggy piece of code. Be aware when making a changes.
        if (this.defaultImageId && changes['galleryImages'] && !changes['galleryImages'].previousValue.length) {
            this.viewImageById(this.defaultImageId);
        }

        if (changes['galleryImages'] && this.openedImage && this.isLoadingMore) {
            this.viewNextImage(false);
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

    reset = ():void => {
        this.galleryImages = [];
        this.galleryImagesChange.emit(this.galleryImages);
        this.unsetOpenedImage();
        this.galleryGridComponent.reset();
    };

    setOpenedImage = (galleryImage:GalleryImage, index:number):void => {
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
            this.loadMoreImages();
        }
    };

    loadMoreImages = ():Promise<any> => {
        if (typeof this.onLoadMoreCallback === 'function') {
            this.isLoadingMore = true;
            return this.onLoadMoreCallback();
        } else {
            return Promise.reject(new Error('The "Load more" callback is not a function.'));
        }
    };

    closeImage = ():void => {
        this.onCloseImage.emit(this.openedImage);
        this.unsetOpenedImage();
    };

    toggleInfo = ():void => {
        this.isOpenedInfo = !this.isOpenedInfo;
        this.onToggleInfo.emit(this.openedImage);
    };

    editImage = ():void => {
        this.onEditImage.emit(this.openedImage);
    };

    deleteImage = ():void => {
        this.onDeleteImage.emit(this.openedImage);
    };
}
