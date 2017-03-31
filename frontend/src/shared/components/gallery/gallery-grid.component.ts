import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';
import {GalleryImage} from './models';

@Component({
    selector: 'gallery-grid',
    templateUrl: 'gallery-grid.component.html',
    styleUrls: ['gallery-grid.component.css'],
})
export class GalleryGridComponent {
    @Input() rowHeight:number = 0;
    @Input() galleryImages:Array<GalleryImage> = [];
    @Input() updateInterval:number;
    @Output() onClickGridImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();
    private elementRefProperties:any = {width: 0, height: 0};
    private elementSizeCheckInterval:any = null;
    private gridRowMaxHeight:number;
    private gridRowMaxWidth:number;
    private gridRowImages:Array<Array<GalleryImage>> = [];
    private activeRowWidth:number = 0;
    private activeRowImages:Array<GalleryImage> = [];

    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
        this.reset();
        this.setGridRowMaxHeight(0);
        this.setGridRowMaxWidth(0);
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.setGridRowMaxHeight(changes['rowHeight'].currentValue);
            this.setGridImages(this.galleryImages);
        }
        if (changes['galleryImages'] && changes['galleryImages'].currentValue.length) {
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridImages(changes['galleryImages'].currentValue);
        }
    }

    ngAfterContentInit() {
        this.elementSizeCheckInterval = setInterval(this.elementSizeCheckCallback, this.updateInterval);
    }

    ngOnDestroy() {
        if (this.elementSizeCheckInterval !== null) clearInterval(this.elementSizeCheckInterval);
    }

    elementSizeCheckCallback = ():void => {
        let height = this.elementRef.nativeElement.offsetHeight;
        let width = this.elementRef.nativeElement.offsetWidth;
        if (width !== this.getElementRefProperties().width) {
            this.setElementRefProperties(width, height);
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.resetGridRowImages();
            this.setGridImages(this.galleryImages);
        }
    };

    setElementRefProperties = (width:number, height:number):void => {
        this.elementRefProperties = {
            width: width,
            height: height,
        };
    };

    getElementRefProperties = ():{width:number, height:number} => {
        return this.elementRefProperties;
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

    setActiveRowWidth = (activeRowWidth:number):void => {
        this.activeRowWidth = activeRowWidth;
    };

    getActiveRowWidth = ():number => {
        return this.activeRowWidth;
    };

    setActiveRowImages = (activeRowImages:Array<GalleryImage>):void => {
        this.activeRowImages = activeRowImages;
    };

    getActiveRowImages = ():Array<GalleryImage> => {
        return this.activeRowImages;
    };

    setGridRowImages = (gridRowImages:Array<Array<GalleryImage>>):void => {
        this.gridRowImages = gridRowImages;
    };

    getGridRowImages = ():Array<Array<GalleryImage>> => {
        return this.gridRowImages;
    };

    reset = ():void => {
        this.resetGridRowImages();
        this.resetActiveRow();
    };

    resetGridRowImages = ():void => {
        this.setGridRowImages([]);
    };

    private resetActiveRow = ():void => {
        this.setActiveRowWidth(0);
        this.setActiveRowImages([]);
    };

    setGridImages = (galleryImages:Array<GalleryImage>):void => {
        let newGridImages = this.filterNewGridImages(galleryImages);
        // Get the array of the new grid images concatenated with the array of the last row images.
        let gridImages = this.getGridRowImages().length ? this.getGridRowImages().pop().concat(newGridImages) : newGridImages;
        if (gridImages.length) {
            gridImages.forEach((galleryImage:GalleryImage, index:number) => {
                this.appendImageToActiveRow(galleryImage, this.getGridRowMaxHeight());
                let scaledActiveRowImages = this.releaseActiveRowImages(index == gridImages.length - 1);
                if (scaledActiveRowImages.length) this.getGridRowImages().push(scaledActiveRowImages);
            });
        }
    };

    private filterNewGridImages = (galleryImages:Array<GalleryImage>):Array<GalleryImage> => {
        return galleryImages.filter((galleryImage:GalleryImage) => !this.existsInGrid(galleryImage.getId()));
    };

    private existsInGrid = (id:number):boolean => {
        if (!this.getGridRowImages().length) return false;
        // Convert multi-dimensional array (of rows of images) into single-dimensional array (of images).
        let gridImages = [].concat.apply([], this.getGridRowImages());
        return gridImages.some((galleryImage:GalleryImage) => galleryImage.getId() == id);
    };

    private appendImageToActiveRow = (galleryImage:GalleryImage, maxHeight:number):void => {
        this.scaleImageToHeight(galleryImage, maxHeight);
        let predictedRowWidth = this.predictRowWidth(this.getActiveRowImages(), galleryImage.getSmallSizeWidth());
        this.setActiveRowWidth(predictedRowWidth);
        this.getActiveRowImages().push(galleryImage);
    };

    private releaseActiveRowImages = (force:boolean = false):Array<GalleryImage> => {
        let activeRowImages:Array<GalleryImage> = [];

        if (this.getActiveRowWidth() > this.getGridRowMaxWidth()) {
            let gridRowMaxWidth = this.getGridRowMaxWidth();
            this.scaleActiveRowImagesToWidth(gridRowMaxWidth);
            activeRowImages = this.getActiveRowImages();
        }

        if (force && !activeRowImages.length) {
            activeRowImages = this.getActiveRowImages();
        }

        if (activeRowImages.length) {
            this.resetActiveRow();
        }

        return activeRowImages;
    };

    private calculateRowWidth = (scaledActiveRowImages:Array<GalleryImage>):number => {
        let rowWidth = 0;
        scaledActiveRowImages.forEach((galleryImage:GalleryImage) => rowWidth += galleryImage.getSmallSizeWidth());
        return rowWidth;
    };

    private predictRowWidth = (scaledActiveRowImages:Array<GalleryImage>, newImageWidth:number):number => {
        return this.calculateRowWidth(scaledActiveRowImages) + newImageWidth;
    };

    private scaleImageToHeight = (galleryImage:GalleryImage, height:number):void => {
        let scaleRate = galleryImage.getSmallSizeHeight() * 100 / height;
        let scaledWidth = Math.floor(galleryImage.getSmallSizeWidth() * 100 / scaleRate);
        let scaledHeight = Math.floor(height);

        galleryImage.setSmallSizeWidth(scaledWidth);
        galleryImage.setSmallSizeHeight(scaledHeight);
    };

    private scaleActiveRowImagesToWidth = (width:number):void => {
        let scaleRate = this.getActiveRowWidth() * 100 / width;
        let scaledActiveRowImages = this.getActiveRowImages().map((galleryImage:GalleryImage) => {
            let scaledWidth = Math.floor(galleryImage.getSmallSizeWidth() * 100 / scaleRate);
            let scaledHeight = Math.floor(galleryImage.getSmallSizeHeight() * 100 / scaleRate);
            galleryImage.setSmallSizeWidth(scaledWidth)
                .setSmallSizeHeight(scaledHeight);
            return galleryImage;
        });
        // Note: After scaling the active row images may be a situation when the scaled row width will be not equal to the grid width.
        // The following lines of code fix this issue.
        let diffWidth = this.getGridRowMaxWidth() - this.calculateRowWidth(scaledActiveRowImages);
        if (diffWidth != 0) {
            let lastImageWidth = scaledActiveRowImages[scaledActiveRowImages.length - 1].getSmallSizeWidth() + diffWidth;
            scaledActiveRowImages[scaledActiveRowImages.length - 1].setSmallSizeWidth(lastImageWidth);
        }
        let scaledActiveRowWidth = this.calculateRowWidth(scaledActiveRowImages);

        this.setActiveRowImages(scaledActiveRowImages);
        this.setActiveRowWidth(scaledActiveRowWidth);
    };
}
