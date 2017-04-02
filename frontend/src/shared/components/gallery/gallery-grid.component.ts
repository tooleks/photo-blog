import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';
import {scaleImageSmallSizeToHeight, scaleImagesSmallSizeToWidth, sumImagesSmallSizeWidth} from './helpers';
import {GalleryImage} from './models';

@Component({
    selector: 'gallery-grid',
    templateUrl: 'gallery-grid.component.html',
    styleUrls: ['gallery-grid.component.css'],
})
export class GalleryGridComponent {
    /**
     * Grid row height in pixels.
     *
     * @type {number}
     */
    @Input() rowHeight:number = 0;

    /**
     * Gallery images.
     *
     * @type {Array<GalleryImage>}
     */
    @Input() galleryImages:Array<GalleryImage> = [];

    /**
     * Grid image 'onclick' event emitter.
     *
     * @type {EventEmitter<GalleryImage>}
     */
    @Output() onClickGridImage:EventEmitter<GalleryImage> = new EventEmitter<GalleryImage>();

    /**
     * Host element size.
     *
     * @type {{width: number, height: number}}
     */
    private elementSize:any = {width: 0, height: 0};

    /**
     * Host element size check function.
     *
     * @type {function|null}
     */
    private elementSizeCheck:any = null;

    /**
     * Host element size check interval in miliseconds.
     *
     * @type {number}
     */
    @Input() elementSizeCheckInterval:number = 250;

    /**
     * Grid row max height in pixels.
     *
     * @type {number}
     */
    private gridRowMaxHeight:number = 0;

    /**
     * Grid row max width in pixels.
     *
     * @type {number}
     */
    private gridRowMaxWidth:number = 0;

    /**
     * The grid rows property contains array of rows of images.
     *
     * @type {Array<Array<GalleryImage>>}
     */
    private gridRows:Array<Array<GalleryImage>> = [];

    /**
     * @type {Array}
     */
    private activeRowImages:Array<GalleryImage> = [];

    /**
     * GalleryGridComponent constructor.
     *
     * @param {ElementRef} elementRef
     */
    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
        this.reset();
    }

    /**
     * Handle component state changes.
     *
     * @param {SimpleChanges} changes
     */
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

    /**
     * Handle component component init state.
     */
    ngAfterContentInit() {
        this.elementSizeCheck = setInterval(this.elementSizeCheckCallback, this.elementSizeCheckInterval);
    }

    /**
     * Handle component destroy state.
     */
    ngOnDestroy() {
        if (this.elementSizeCheck !== null) {
            clearInterval(this.elementSizeCheck);
        }
    }

    /**
     * Callback function that re-renders grid images after host element size has changed.
     */
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

    /**
     * Set host element size.
     *
     * @param {number} width
     * @param {number} height
     */
    setElementSize = (width:number, height:number):void => {
        this.elementSize = {width: width, height: height};
    };

    /**
     * Get host element size.
     *
     * @return {any}
     */
    getElementSize = ():{width:number, height:number} => {
        return this.elementSize;
    };

    /**
     * Set grid row max height in pixels.
     *
     * @param {number} gridRowMaxHeight
     */
    setGridRowMaxHeight = (gridRowMaxHeight:number):void => {
        this.gridRowMaxHeight = gridRowMaxHeight;
    };

    /**
     * Get grid row max height in pixels
     *
     * @return {number}
     */
    getGridRowMaxHeight = ():number => {
        return this.gridRowMaxHeight;
    };

    /**
     * Set grid row max width in pixels.
     *
     * @param {number} gridRowMaxWidth
     */
    setGridRowMaxWidth = (gridRowMaxWidth:number):void => {
        this.gridRowMaxWidth = gridRowMaxWidth;
    };

    /**
     * Get grid row max width in pixels.
     *
     * @return {number}
     */
    getGridRowMaxWidth = ():number => {
        return this.gridRowMaxWidth;
    };

    /**
     * Set grid rows.
     *
     * @param {Array<Array<GalleryImage>>} gridRows
     */
    setGridRows = (gridRows:Array<Array<GalleryImage>>):void => {
        this.gridRows = gridRows;
    };

    /**
     * Get grid rows.
     *
     * @return {Array<Array<GalleryImage>>}
     */
    getGridRows = ():Array<Array<GalleryImage>> => {
        return this.gridRows;
    };

    /**
     * Set active row images.
     *
     * @param {Array<GalleryImage>} activeRowImages
     */
    setActiveRowImages = (activeRowImages:Array<GalleryImage>):void => {
        this.activeRowImages = activeRowImages;
    };

    /**
     * Get active row images.
     *
     * @return {Array<GalleryImage>}
     */
    getActiveRowImages = ():Array<GalleryImage> => {
        return this.activeRowImages;
    };

    /**
     * Reset component state.
     */
    reset = ():void => {
        this.setGridRows([]);
        this.setActiveRowImages([]);
    };

    /**
     * Render grid.
     *
     * @param {Array<GalleryImage>} galleryImages
     */
    renderGrid = (galleryImages:Array<GalleryImage>):void => {
        const newGalleryImages = galleryImages.filter((galleryImage:GalleryImage) => !this.existsInGrid(galleryImage));
        // Get the array of the new grid images concatenated with the array of the last row images.
        const processImages = this.getGridRows().length ? this.getGridRows().pop().concat(newGalleryImages) : newGalleryImages;
        processImages.forEach((galleryImage:GalleryImage, index:number) => {
            const scaledGalleryImage = scaleImageSmallSizeToHeight(galleryImage, this.getGridRowMaxHeight());
            this.getActiveRowImages().push(scaledGalleryImage);
            this.renderActiveRowIfFilled(index == processImages.length - 1);
        });
    };

    /**
     * Check if image exists in grid.
     *
     * @param {GalleryImage} galleryImage
     * @return {boolean}
     */
    private existsInGrid = (galleryImage:GalleryImage):boolean => {
        // Note: Convert multi-dimensional array (of rows of images) into single-dimensional array (of images).
        return [].concat.apply([], this.getGridRows())
            .some((gridGalleryImage:GalleryImage) => gridGalleryImage.getId() == galleryImage.getId());
    };

    /**
     * Render active row if it is filled.
     *
     * @param {boolean} force
     */
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
