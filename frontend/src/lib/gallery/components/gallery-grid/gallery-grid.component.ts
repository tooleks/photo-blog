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
import {scaleImageSmallSizeToHeight, scaleImagesSmallSizeToWidth, sumImagesSmallSizeWidth} from '../../helpers';
import {GalleryImage} from '../../models';

@Component({
    selector: 'gallery-grid',
    templateUrl: 'gallery-grid.component.html',
    styleUrls: ['gallery-grid.component.css'],
})
export class GalleryGridComponent implements OnChanges, AfterContentInit, OnDestroy {
    protected elementSize:any = {width: 0, height: 0};
    protected elementSizeCheck:any = null;

    @Input() elementSizeCheckInterval:number = 250;

    @Input() rowHeight:number = 0;

    protected gridRowMaxHeight:number = 0;
    protected gridRowMaxWidth:number = 0;

    @Input() images:Array<GalleryImage> = [];

    @Output() onClickGridImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    protected gridRows:Array<Array<GalleryImage>> = [];
    protected activeRowImages:Array<GalleryImage> = [];

    constructor(protected elementRef:ElementRef) {
        this.reset();
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.setGridRowMaxHeight(changes['rowHeight'].currentValue);
            this.renderGrid(this.images);
        }

        if (changes['images'] && changes['images'].currentValue.length) {
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.renderGrid(changes['images'].currentValue);
        }
    }

    ngAfterContentInit() {
        // #browser-specific
        if (typeof (window) !== 'undefined') {
            this.elementSizeCheck = setInterval(() => this.elementSizeCheckCallback(), this.elementSizeCheckInterval);
        }
    }

    ngOnDestroy() {
        if (this.elementSizeCheck !== null) {
            clearInterval(this.elementSizeCheck);
        }
    }

    elementSizeCheckCallback():void {
        let height = this.elementRef.nativeElement.offsetHeight;
        let width = this.elementRef.nativeElement.offsetWidth;
        if (width !== this.getElementSize().width) {
            this.setElementSize(width, height);
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridRows([]);
            this.renderGrid(this.images);
        }
    }

    setElementSize(width:number, height:number):void {
        this.elementSize = {width: width, height: height};
    }

    getElementSize():{width:number, height:number} {
        return this.elementSize;
    }

    setGridRowMaxHeight(gridRowMaxHeight:number):void {
        this.gridRowMaxHeight = gridRowMaxHeight;
    }

    getGridRowMaxHeight():number {
        return this.gridRowMaxHeight;
    }

    setGridRowMaxWidth(gridRowMaxWidth:number):void {
        this.gridRowMaxWidth = gridRowMaxWidth;
    }

    getGridRowMaxWidth():number {
        return this.gridRowMaxWidth;
    }

    setGridRows(gridRows:Array<Array<GalleryImage>>):void {
        this.gridRows = gridRows;
    }

    getGridRows():Array<Array<GalleryImage>> {
        return this.gridRows;
    }

    setActiveRowImages(activeRowImages:Array<GalleryImage>):void {
        this.activeRowImages = activeRowImages;
    }

    getActiveRowImages():Array<GalleryImage> {
        return this.activeRowImages;
    }

    reset():void {
        this.gridRows = [];
        this.activeRowImages = [];
        this.images = [];
    }

    renderGrid(images:Array<GalleryImage>):void {
        const newImages = images.filter((image:GalleryImage) => !this.existsInGrid(image));
        // Get the array of the new grid images concatenated with the array of the last row images.
        const processImages = this.getGridRows().length ? this.getGridRows().pop().concat(newImages) : newImages;
        processImages.forEach((image:GalleryImage, index:number) => {
            this.pushImageToActiveRow(image);
            this.renderActiveRowIfFilled(index == processImages.length - 1);
        });
    }

    existsInGrid(image:GalleryImage):boolean {
        // Note: Convert multi-dimensional array (of rows of images) into single-dimensional array (of images).
        return [].concat.apply([], this.getGridRows())
            .some((gridImage:GalleryImage) => gridImage.getId() == image.getId());
    }

    pushImageToActiveRow(image:GalleryImage):void {
        const scaledImage = scaleImageSmallSizeToHeight(image, this.getGridRowMaxHeight());
        this.getActiveRowImages().push(scaledImage);
    }

    renderActiveRowIfFilled(force:boolean = false):void {
        let renderedImages:Array<GalleryImage> = [];
        // If the active row width is bigger than the max row width, scale the active row to the max row width.
        if (sumImagesSmallSizeWidth(this.getActiveRowImages()) > this.getGridRowMaxWidth()) {
            renderedImages = scaleImagesSmallSizeToWidth(this.getActiveRowImages(), this.getGridRowMaxWidth());
        }
        // If the force flag is enabled and there are no rendered images, set active row images as rendered images.
        if (force && !renderedImages.length) {
            renderedImages = this.getActiveRowImages();
        }
        // If there some rendered images, reset the active row images and append the images to the grid.
        if (renderedImages.length) {
            this.setActiveRowImages([]);
            this.getGridRows().push(renderedImages);
        }
    }
}
