import {Component, Input, Output, OnChanges, SimpleChanges, EventEmitter, HostListener} from '@angular/core';
import {GalleryImage} from '../../models';
import {loadImage} from '../../helpers';

@Component({
    selector: 'gallery-viewer',
    templateUrl: 'gallery-viewer.component.html',
    styleUrls: ['gallery-viewer.component.css'],
})
export class GalleryViewerComponent implements OnChanges {
    @Input() enabledKeyboardEvents:boolean = true;
    @Input() loaderDelay:number = 400;

    @Input() image:GalleryImage = null;

    protected loadedImage:boolean = false;

    @Output() onImageLoaded:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showPrevImageButton:boolean = true;
    @Output() onPrevImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showNextImageButton:boolean = true;
    @Output() onNextImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showCloseImageButton:boolean = true;
    @Output() onCloseImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showEditImageButton:boolean = true;
    @Output() onEditImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showDeleteImageButton:boolean = true;
    @Output() onDeleteImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    @Input() showImageInfoButton:boolean = true;
    @Output() onImageInfo:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    @Input() visibleImageInfo:boolean = false;
    @Output() visibleImageInfoChange:EventEmitter<boolean> = new EventEmitter<boolean>();

    ngOnChanges(changes:SimpleChanges) {
        if (changes['image'] && changes['image'].currentValue) {
            this.processImageLoading();
        }
    }

    @HostListener('document:keydown', ['$event'])
    onDocumentKeyDown = (event:KeyboardEvent) => {
        if (this.enabledKeyboardEvents && this.image) {
            switch (event.key) {
                case 'Escape':
                    return this.clickCloseImage();
                case 'ArrowLeft':
                    return this.clickPrevImage();
                case 'ArrowRight':
                    return this.clickNextImage();
            }
        }
    };

    protected processImageLoading():void {
        var loadedImage;
        this.loadedImage = loadedImage = false;
        loadImage(this.image.getLargeSizeUrl(), () => {
            this.loadedImage = loadedImage = true;
            this.onImageLoaded.emit(this.image);
        });
        // #browser-specific
        if (typeof (window) !== 'undefined') {
            setTimeout(() => (this.loadedImage = loadedImage), this.loaderDelay);
        }
    }

    clickPrevImage():void {
        this.onPrevImage.emit(this.image);
    }

    clickNextImage():void {
        this.onNextImage.emit(this.image);
    }

    clickCloseImage():void {
        this.onCloseImage.emit(this.image);
    }

    clickEditImage():void {
        this.onEditImage.emit(this.image);
    }

    clickDeleteImage():void {
        this.onDeleteImage.emit(this.image);
    }

    clickImageInfo():void {
        this.visibleImageInfo = !this.visibleImageInfo;
        this.visibleImageInfoChange.emit(this.visibleImageInfo);
        this.onImageInfo.emit(this.image);
    };

    isVisibleImageInfo = ():boolean => {
        // #browser-specific
        if (typeof (window) !== 'undefined') {
            return this.loadedImage && this.visibleImageInfo;
        } else {
            return true;
        }
    };

    isLoadedImage():boolean {
        return this.loadedImage;
    };
}
