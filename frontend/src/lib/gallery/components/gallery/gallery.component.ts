import {Component, OnInit, OnChanges, Input, Output, EventEmitter, SimpleChanges, ViewChild} from '@angular/core';
import {GalleryImage} from '../../models';
import {GalleryGridComponent} from '../gallery-grid';
import {GalleryViewerComponent} from '../gallery-viewer';

@Component({
    selector: 'gallery',
    templateUrl: 'gallery.component.html',
    styleUrls: ['gallery.component.css'],
})
export class GalleryComponent implements OnInit, OnChanges {
    @ViewChild('galleryGridComponent') galleryGridComponent:GalleryGridComponent;
    @ViewChild('galleryViewerComponent') galleryViewerComponent:GalleryViewerComponent;

    @Input() images:Array<GalleryImage> = [];

    @Input() defaultImageId:any;

    @Input() loadMoreCallback:any = null;

    @Output() onOpenImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() enabledKeyboardEvents:boolean = true;

    @Input() showPrevImageButton:boolean = true;
    @Output() onPrevImage:EventEmitter<any> = new EventEmitter<any>();

    @Input() showNextImageButton:boolean = true;
    @Output() onNextImage:EventEmitter<any> = new EventEmitter<any>();

    @Input() showCloseImageButton:boolean = true;
    @Output() onCloseImage:EventEmitter<any> = new EventEmitter<any>();

    @Input() showEditImageButton:boolean = true;
    @Output() onEditImage:EventEmitter<any> = new EventEmitter<any>();

    @Input() showDeleteImageButton:boolean = true;
    @Output() onDeleteImage:EventEmitter<any> = new EventEmitter<any>();

    @Input() showImageInfoButton:boolean = true;
    @Output() onImageInfo:EventEmitter<any> = new EventEmitter<any>();
    @Input() visibleImageInfo:boolean = false;
    @Output() visibleImageInfoChange:EventEmitter<boolean> = new EventEmitter<boolean>();

    protected openedImage:GalleryImage;
    protected openedImageIndex:number;

    ngOnInit():void {
        this.reset();
    }

    ngOnChanges(changes:SimpleChanges):void {
        if (this.defaultImageId && changes['images'] && !changes['images'].firstChange &&
            changes['images'].previousValue.length < changes['images'].currentValue.length) {
            this.openImageById(this.defaultImageId);
        }

        if (this.openedImage && changes['images'] && !changes['images'].firstChange &&
            changes['images'].previousValue.length < changes['images'].currentValue.length) {
            this.openNextImage(false);
        }
    }

    reset = ():void => {
        this.images = [];
        this.unsetOpenedImage();
        this.galleryGridComponent.reset();
    };

    setOpenedImage = (image:GalleryImage, index:number):void => {
        this.openedImage = image;
        this.openedImageIndex = index;
    };

    unsetOpenedImage = ():void => {
        this.openedImage = null;
        this.openedImageIndex = null;
    };

    openImageById = (imageId:any):void => {
        this.images.some((image:GalleryImage, index:number) => {
            if (image.getId() == imageId) {
                this.setOpenedImage(image, index);
                return true;
            } else if (index === this.images.length - 1) {
                this.loadMoreImages();
            }
            return false;
        });
    };

    openImage = (image:GalleryImage):void => {
        const imageId = image.getId();
        this.images.some((image:GalleryImage, index:number) => {
            if (image.getId() == imageId) {
                this.setOpenedImage(this.images[index], index);
                return true;
            }
            return false;
        });
    };

    openPrevImage = ():void => {
        const prevImageIndex = this.openedImageIndex - 1;
        if (this.images[prevImageIndex]) {
            this.openImage(this.images[prevImageIndex]);
        }
    };

    openNextImage = (loadMoreImages:boolean):void => {
        const nextImageIndex = this.openedImageIndex + 1;
        if (this.images[nextImageIndex]) {
            this.openImage(this.images[nextImageIndex]);
        } else if (loadMoreImages) {
            this.loadMoreImages();
        }
    };

    loadMoreImages = ():Promise<any> => {
        return new Promise((resolve, reject) => {
            typeof (this.loadMoreCallback) === 'function'
                ? resolve(this.loadMoreCallback())
                : reject(new Error('The "loadMoreCallback" callback is not a function.'));
        });
    };

    onImageLoaded = (image:GalleryImage):void => {
        this.onOpenImage.emit(image);
    };

    clickPrevImage = (image:GalleryImage):void => {
        this.onPrevImage.emit(image);
        this.openPrevImage();
    };

    clickNextImage = (image:GalleryImage):void => {
        this.onNextImage.emit(image);
        this.openNextImage(true);
    };

    clickCloseImage = (image:GalleryImage):void => {
        this.onCloseImage.emit(image);
        this.unsetOpenedImage();
    };

    clickEditImage = (image:GalleryImage):void => {
        this.onEditImage.emit(image);
    };

    clickDeleteImage = (image:GalleryImage):void => {
        this.onDeleteImage.emit(image);
    };

    clickImageInfo = (image:GalleryImage):void => {
        this.visibleImageInfo = !this.visibleImageInfo;
        this.visibleImageInfoChange.emit(this.visibleImageInfo);
        this.onImageInfo.emit(image);
    };
}
