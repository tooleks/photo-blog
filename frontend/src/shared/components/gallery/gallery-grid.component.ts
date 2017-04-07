import {
    Component,
    Input,
    Output,
    ElementRef,
    SimpleChanges,
    EventEmitter,
    OnChanges,
    AfterContentInit,
    OnDestroy
} from '@angular/core';
import {scaleImageSmallSizeToHeight, scaleImagesSmallSizeToWidth, sumImagesSmallSizeWidth} from './helpers';
import {GalleryImage} from './models';

@Component({
    selector: 'gallery-grid',
    templateUrl: 'gallery-grid.component.html',
    styleUrls: ['gallery-grid.component.css'],
})
export class GalleryGridComponent implements OnChanges, AfterContentInit, OnDestroy {
    private elementSize:any = {width: 0, height: 0};
    private elementSizeCheck:any = null;
    @Input() elementSizeCheckInterval:number = 250;

    @Input() rowHeight:number = 0;
    private gridRowMaxHeight:number = 0;
    private gridRowMaxWidth:number = 0;

    @Input() galleryImages:Array<GalleryImage> = [];
    @Output() onClickGridImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    private gridRows:Array<Array<GalleryImage>> = [];
    private activeRowImages:Array<GalleryImage> = [];

    constructor(private elementRef:ElementRef) {
        this.reset();
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.setGridRowMaxHeight(changes['rowHeight'].currentValue);
            this.renderGrid(this.galleryImages);
        }

        if (changes['galleryImages'] && changes['galleryImages'].currentValue.length) {
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.renderGrid(changes['galleryImages'].currentValue);
        }
    }

    ngAfterContentInit() {
        // Check if code is running in a browser.
        if (typeof (window) !== 'undefined') {
            this.elementSizeCheck = setInterval(this.elementSizeCheckCallback, this.elementSizeCheckInterval);
        }
    }

    ngOnDestroy() {
        if (this.elementSizeCheck !== null) {
            clearInterval(this.elementSizeCheck);
        }
    }

    elementSizeCheckCallback = ():void => {
        let height = this.elementRef.nativeElement.offsetHeight;
        let width = this.elementRef.nativeElement.offsetWidth;
        if (width !== this.getElementSize().width) {
            this.setElementSize(width, height);
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridRows([]);
            this.renderGrid(this.galleryImages);
        }
    };

    setElementSize = (width:number, height:number):void => {
        this.elementSize = {width: width, height: height};
    };

    getElementSize = ():{width:number, height:number} => {
        return this.elementSize;
    };

    setGridRowMaxHeight = (gridRowMaxHeight:number):void => {
        this.gridRowMaxHeight = gridRowMaxHeight;
    };

    getGridRowMaxHeight = ():number => {
        return this.gridRowMaxHeight;
    };

    setGridRowMaxWidth = (gridRowMaxWidth:number):void => {
        this.gridRowMaxWidth = gridRowMaxWidth;
    };

    getGridRowMaxWidth = ():number => {
        return this.gridRowMaxWidth;
    };

    setGridRows = (gridRows:Array<Array<GalleryImage>>):void => {
        this.gridRows = gridRows;
    };

    getGridRows = ():Array<Array<GalleryImage>> => {
        return this.gridRows;
    };

    setActiveRowImages = (activeRowImages:Array<GalleryImage>):void => {
        this.activeRowImages = activeRowImages;
    };

    getActiveRowImages = ():Array<GalleryImage> => {
        return this.activeRowImages;
    };

    reset = ():void => {
        this.setGridRows([]);
        this.setActiveRowImages([]);
    };

    renderGrid = (galleryImages:Array<GalleryImage>):void => {
        const newGalleryImages = galleryImages.filter((galleryImage:GalleryImage) => !this.existsInGrid(galleryImage));
        // Get the array of the new grid images concatenated with the array of the last row images.
        const processImages = this.getGridRows().length ? this.getGridRows().pop().concat(newGalleryImages) : newGalleryImages;
        processImages.forEach((galleryImage:GalleryImage, index:number) => {
            this.pushImageToActiveRow(galleryImage);
            this.renderActiveRowIfFilled(index == processImages.length - 1);
        });
    };

    private existsInGrid = (galleryImage:GalleryImage):boolean => {
        // Note: Convert multi-dimensional array (of rows of images) into single-dimensional array (of images).
        return [].concat.apply([], this.getGridRows())
            .some((gridGalleryImage:GalleryImage) => gridGalleryImage.getId() == galleryImage.getId());
    };

    private pushImageToActiveRow = (galleryImage:GalleryImage):void => {
        const scaledGalleryImage = scaleImageSmallSizeToHeight(galleryImage, this.getGridRowMaxHeight());
        this.getActiveRowImages().push(scaledGalleryImage);
    };

    private renderActiveRowIfFilled = (force:boolean = false):void => {
        let renderedImages:Array<GalleryImage> = [];
        // If the active row width is bigger than the max row width, scale the active row to the max row width.
        if (sumImagesSmallSizeWidth(this.getActiveRowImages()) > this.getGridRowMaxWidth()) {
            renderedImages = scaleImagesSmallSizeToWidth(this.getActiveRowImages(), this.getGridRowMaxWidth());
        }
        // If the force flag is enabled and there are no rendered images, set active row images as rendered images.
        if (force && !renderedImages.length) {
            renderedImages = this.getActiveRowImages();
        }
        // If there some rendered images, reset the active row images and append the images to to the grid.
        if (renderedImages.length) {
            this.setActiveRowImages([]);
            this.getGridRows().push(renderedImages);
        }
    };
}
