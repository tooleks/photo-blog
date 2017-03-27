import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';

// @TODO: Create grid row item class and map items to it.

@Component({
    selector: 'gallery-grid',
    templateUrl: './gallery-grid.component.html',
    styles: [String(require('./gallery-grid.component.css'))],
})
export class GalleryGridComponent {
    @Input() rowHeight:number = 0;
    @Input() galleryItems:Array<any> = [];
    @Input() updateInterval:number;
    @Output() onClickGridItem:EventEmitter<any> = new EventEmitter<any>();
    private elementRefProperties:any = {width: 0, height: 0};
    private elementSizeCheckInterval:any = null;
    private gridRowMaxHeight:number;
    private gridRowMaxWidth:number;
    private gridRowItems:Array<any> = [];
    private activeRowWidth:number = 0;
    private activeRowItems:Array<any> = [];

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
        this.elementSizeCheckInterval = setInterval(() => {
            let height = this.elementRef.nativeElement.offsetHeight;
            let width = this.elementRef.nativeElement.offsetWidth;
            if (width !== this.getElementRefProperties().width) {
                this.setElementRefProperties(width, height);
                this.setGridRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
                this.resetGridRowItems();
                this.setGridItems(this.galleryItems);
            }
        }, this.updateInterval);
    }

    ngOnDestroy() {
        if (this.elementSizeCheckInterval !== null) clearInterval(this.elementSizeCheckInterval);
    }

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

    setActiveRowItems = (activeRowItems:Array<any>):this => {
        this.activeRowItems = activeRowItems;
        return this;
    };

    getActiveRowItems = ():Array<any> => {
        return this.activeRowItems;
    };

    setGridRowItems = (gridRowItems:Array<any>):this => {
        this.gridRowItems = gridRowItems;
        return this;
    };

    getGridRowItems = ():Array<any> => {
        return this.gridRowItems;
    };

    reset = ():this => {
        this.resetGridRowItems();
        this.resetActiveRow();
        return this;
    };

    resetGridRowItems = ():this => {
        this.setGridRowItems([]);
        return this;
    };

    private resetActiveRow = ():this => {
        this.setActiveRowWidth(0);
        this.setActiveRowItems([]);
        return this;
    };

    setGridItems = (items:Array<any>):void => {
        let newGridItems = this.filterNewGridItems(items);
        let gridItems = this.getGridRowItems().length ? this.getGridRowItems().pop().concat(newGridItems) : newGridItems;
        if (!gridItems.length) return;
        gridItems.forEach((item:any, index:number) => {
            this.pushItemToRow(item, this.getGridRowMaxHeight());
            let rowItems = this.releaseRowItems(index == gridItems.length - 1);
            if (rowItems.length) this.getGridRowItems().push(rowItems);
        });
    };

    private filterNewGridItems = (items:Array<any>):Array<any> => {
        return items.filter((item:any) => !this.existsInGrid(item.id));
    };

    private existsInGrid = (id:number):boolean => {
        if (!this.getGridRowItems().length) return false;
        // Convert multi-dimensional array (of rows of items) into single-dimensional array (of items).
        let gridItems = [].concat.apply([], this.getGridRowItems());
        return gridItems.some((item:any) => item.id == id);
    };

    private pushItemToRow = (item:any, maxHeight:number):number => {
        let scaledToMaxHeightItem = this.scaleItemToMaxHeight(item, maxHeight);
        let predictedRowWidth = this.predictRowWidth(scaledToMaxHeightItem.thumbnails.medium.width);
        this.setActiveRowWidth(predictedRowWidth);
        return this.getActiveRowItems().push(scaledToMaxHeightItem);
    };

    private releaseRowItems = (force:boolean = false):Array<any> => {
        let rowItems = [];
        if (this.getActiveRowWidth() == this.getGridRowMaxWidth()) {
            rowItems = this.scaleRowItemsToMaxWidth();
        } else if (this.getActiveRowWidth() > this.getGridRowMaxWidth()) {
            rowItems = this.scaleRowItemsToMaxWidth();
            let rowWidth = this.calculateRowWidth(rowItems);
            let diffWidth = this.getGridRowMaxWidth() - rowWidth;
            rowItems[rowItems.length - 1].thumbnails.medium.width = rowItems[rowItems.length - 1].thumbnails.medium.width + diffWidth;
        }
        if (force && !rowItems.length) {
            rowItems = this.getActiveRowItems();
        }
        if (rowItems.length) {
            this.resetActiveRow();
        }
        return rowItems;
    };

    private calculateRowWidth = (rowItems:Array<any>):number => {
        let width = 0;
        rowItems.forEach((item:any) => width += item.thumbnails.medium.width);
        return width;
    };

    private predictRowWidth = (newItemWidth:any):number => {
        let width = newItemWidth;
        this.getActiveRowItems().forEach((item:any) => width += item.thumbnails.medium.width);
        return width;
    };

    private scaleItemToMaxHeight = (item:any, maxHeight:number):any => {
        let scaleRate = item.thumbnails.medium.height * 100 / maxHeight;
        item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
        item.thumbnails.medium.height = Math.floor(maxHeight);
        return item;
    };

    private scaleRowItemsToMaxWidth = ():Array<any> => {
        let scaleRate = this.getActiveRowWidth() * 100 / this.getGridRowMaxWidth();
        return this.getActiveRowItems().map((item:any) => {
            item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
            item.thumbnails.medium.height = Math.floor(item.thumbnails.medium.height * 100 / scaleRate);
            return item;
        });
    };
}
