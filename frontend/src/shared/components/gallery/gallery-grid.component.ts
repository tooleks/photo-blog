import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';
import {GalleryItem} from './gallery-item';

/**
 * Class GalleryGridComponent.
 *
 * The class provides gallery grid component that builds images into the structure like this:
 * _________________________
 * |____|___|___|___|___|__|
 * |__|___|___|___|___|____|
 * |____|___|___|___|___|__|
 * |__|___|___|___|___|____|
 * |____|___|___|___|___|__|
 * |__|___|___|___|___|____|
 */
@Component({
    selector: 'gallery-grid',
    templateUrl: './gallery-grid.component.html',
    styles: [String(require('./gallery-grid.component.css'))],
})
export class GalleryGridComponent {
    @Input() rowHeight:number = 0;
    @Input() galleryItems:Array<GalleryItem> = [];
    @Input() updateInterval:number;
    @Output() onClickGridItem:EventEmitter<GalleryItem> = new EventEmitter<GalleryItem>();
    private elementRefProperties:any = {width: 0, height: 0};
    private elementSizeCheckInterval:any = null;
    private gridRowMaxHeight:number;
    private gridRowMaxWidth:number;
    private gridRowItems:Array<Array<GalleryItem>> = [];
    private activeRowWidth:number = 0;
    private activeRowItems:Array<GalleryItem> = [];

    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
        this.reset()
            .setGridRowMaxHeight(0)
            .setGridRowMaxWidth(0);
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.setGridRowMaxHeight(changes['rowHeight'].currentValue);
            this.setGridItems(this.galleryItems);
        }
        if (changes['galleryItems'] && changes['galleryItems'].currentValue.length) {
            this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridItems(changes['galleryItems'].currentValue);
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
            this.resetGridRowItems();
            this.setGridItems(this.galleryItems);
        }
    };

    setElementRefProperties = (width:number, height:number):this => {
        this.elementRefProperties = {
            width: width,
            height: height,
        };
        return this;
    };

    getElementRefProperties = ():{width:number, height:number} => {
        return this.elementRefProperties;
    };

    setGridRowMaxHeight = (gridRowMaxHeight:number):this => {
        this.gridRowMaxHeight = gridRowMaxHeight;
        return this;
    };

    getGridRowMaxHeight = ():number => {
        return this.gridRowMaxHeight;
    };

    setGridRowMaxWidth = (gridRowMaxWidth:number):this => {
        this.gridRowMaxWidth = gridRowMaxWidth;
        return this;
    };

    getGridRowMaxWidth = ():number => {
        return this.gridRowMaxWidth;
    };

    setActiveRowWidth = (activeRowWidth:number):this => {
        this.activeRowWidth = activeRowWidth;
        return this;
    };

    getActiveRowWidth = ():number => {
        return this.activeRowWidth;
    };

    setActiveRowItems = (activeRowItems:Array<GalleryItem>):this => {
        this.activeRowItems = activeRowItems;
        return this;
    };

    getActiveRowItems = ():Array<GalleryItem> => {
        return this.activeRowItems;
    };

    setGridRowItems = (gridRowItems:Array<Array<GalleryItem>>):this => {
        this.gridRowItems = gridRowItems;
        return this;
    };

    getGridRowItems = ():Array<Array<GalleryItem>> => {
        return this.gridRowItems;
    };

    reset = ():this => {
        this.resetGridRowItems()
            .resetActiveRow();
        return this;
    };

    resetGridRowItems = ():this => {
        this.setGridRowItems([]);
        return this;
    };

    private resetActiveRow = ():this => {
        this.setActiveRowWidth(0)
            .setActiveRowItems([]);
        return this;
    };

    setGridItems = (items:Array<GalleryItem>):void => {
        let newGridItems = this.filterNewGridItems(items);
        // Get the array of the new grid items concatenated with the array of the last row items.
        let gridItems = this.getGridRowItems().length ? this.getGridRowItems().pop().concat(newGridItems) : newGridItems;
        if (!gridItems.length) return;
        gridItems.forEach((item:GalleryItem, index:number) => {
            this.appendItemToActiveRow(item, this.getGridRowMaxHeight());
            let rowItems = this.releaseRowItems(index == gridItems.length - 1);
            if (rowItems.length) this.getGridRowItems().push(rowItems);
        });
    };

    private filterNewGridItems = (items:Array<GalleryItem>):Array<GalleryItem> => {
        return items.filter((item:GalleryItem) => !this.existsInGrid(item.getId()));
    };

    private existsInGrid = (id:number):boolean => {
        if (!this.getGridRowItems().length) return false;
        // Convert multi-dimensional array (of rows of items) into single-dimensional array (of items).
        let gridItems = [].concat.apply([], this.getGridRowItems());
        return gridItems.some((item:GalleryItem) => item.getId() == id);
    };

    private appendItemToActiveRow = (item:GalleryItem, maxHeight:number):void => {
        this.scaleItemToHeight(item, maxHeight);
        let predictedRowWidth = this.predictRowWidth(this.getActiveRowItems(), item.getSmallSizeWidth());
        this.setActiveRowWidth(predictedRowWidth);
        this.getActiveRowItems().push(item);
    };

    private releaseRowItems = (force:boolean = false):Array<GalleryItem> => {
        let rowItems:Array<GalleryItem> = [];
        if (this.getActiveRowWidth() > this.getGridRowMaxWidth()) {
            let gridRowMaxWidth = this.getGridRowMaxWidth();
            this.scaleActiveRowItemsToWidth(gridRowMaxWidth);
            rowItems = this.getActiveRowItems();
            let rowWidth = this.getActiveRowWidth();
            let diffWidth = this.getGridRowMaxWidth() - rowWidth;
            rowItems[rowItems.length - 1].setSmallSizeWidth(rowItems[rowItems.length - 1].getSmallSizeWidth() + diffWidth);
        }
        if (force && !rowItems.length) {
            rowItems = this.getActiveRowItems();
        }
        if (rowItems.length) {
            this.resetActiveRow();
        }
        return rowItems;
    };

    private calculateRowWidth = (rowItems:Array<GalleryItem>):number => {
        let rowWidth = 0;
        rowItems.forEach((item:GalleryItem) => rowWidth += item.getSmallSizeWidth());
        return rowWidth;
    };

    private predictRowWidth = (rowItems:Array<GalleryItem>, newItemWidth:number):number => {
        return this.calculateRowWidth(rowItems) + newItemWidth;
    };

    private scaleItemToHeight = (item:GalleryItem, height:number):void => {
        let scaleRate = item.getSmallSizeHeight() * 100 / height;
        let scaledWidth = Math.floor(item.getSmallSizeWidth() * 100 / scaleRate);
        let scaledHeight = Math.floor(height);
        item.setSmallSizeWidth(scaledWidth);
        item.setSmallSizeHeight(scaledHeight);
    };

    private scaleActiveRowItemsToWidth = (width:number):void => {
        let scaleRate = this.getActiveRowWidth() * 100 / width;
        let scaledActiveRowItems = this.getActiveRowItems().map((item:GalleryItem) => {
            let scaledWidth = Math.floor(item.getSmallSizeWidth() * 100 / scaleRate);
            let scaledHeight = Math.floor(item.getSmallSizeHeight() * 100 / scaleRate);
            item.setSmallSizeWidth(scaledWidth);
            item.setSmallSizeHeight(scaledHeight);
            return item;
        });
        let scaledActiveRowWidth = this.calculateRowWidth(scaledActiveRowItems);
        this.setActiveRowItems(scaledActiveRowItems);
        this.setActiveRowWidth(scaledActiveRowWidth);
    };
}
