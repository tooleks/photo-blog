import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';

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

    private elementRefProperties:{width:number, height:number} = {width: 0, height: 0};
    private elementSizeCheckInterval:any = null;
    private rowWidth:number = 0;
    private rowMaxWidth:number;
    private rowMaxHeight:number;
    private activeRowItems:Array<any> = [];
    private gridRowItems:Array<any> = [];

    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
        this.resetRow();
        this.rowMaxHeight = 0;
        this.rowMaxWidth = 0;
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.rowMaxHeight = changes['rowHeight'].currentValue;
            this.setGridItems(this.galleryItems);
        }
        if (changes['galleryItems'] && changes['galleryItems'].currentValue.length) {
            this.rowMaxWidth = this.elementRef.nativeElement.offsetWidth;
            this.setGridItems(changes['galleryItems'].currentValue);
        }
    }

    ngAfterContentInit() {
        this.elementSizeCheckInterval = setInterval(() => {
            let height = this.elementRef.nativeElement.offsetHeight;
            let width = this.elementRef.nativeElement.offsetWidth;
            if (width !== this.elementRefProperties.width) {
                this.elementRefProperties = {width: width, height: height};
                this.rowMaxWidth = this.elementRef.nativeElement.offsetWidth;
                this.resetGridRowItems();
                this.setGridItems(this.galleryItems);
            }
        }, this.updateInterval);
    }

    ngOnDestroy() {
        if (this.elementSizeCheckInterval !== null) {
            clearInterval(this.elementSizeCheckInterval);
        }
    }

    resetGridRowItems = () => {
        this.gridRowItems = [];
    };

    setGridItems = (items:Array<any>) => {
        let newGridItems = this.getNewGridItems(items);
        let itemsToProcess = this.gridRowItems.length ? this.gridRowItems.pop().concat(newGridItems) : newGridItems;
        if (!itemsToProcess.length) {
            return;
        }
        itemsToProcess.forEach((item:any, index:number) => {
            this.pushItemToRow(item, this.rowMaxHeight);
            let activeRowItems = this.releaseRowItems(index == itemsToProcess.length - 1);
            if (activeRowItems.length) {
                this.gridRowItems.push(activeRowItems);
            }
        });
    };

    private getNewGridItems = (items:Array<any>) => {
        return items.filter((item:any) => {
            return !this.existsInGrid(item.id);
        });
    };

    private existsInGrid = (id:number) => {
        if (!this.gridRowItems.length) {
            return false;
        }
        let gridItems = [].concat.apply([], this.gridRowItems);
        return gridItems.some((item:any) => item.id == id);
    };

    private pushItemToRow = (item:any, maxHeight:number):number => {
        let scaledToMaxHeightItem = this.scaleItemToMaxHeight(item, maxHeight);
        this.rowWidth = this.predictRowWidth(scaledToMaxHeightItem);
        return this.activeRowItems.push(scaledToMaxHeightItem);
    };

    private releaseRowItems = (force:boolean = false):Array<any> => {
        let items = [];
        if (this.rowWidth > this.rowMaxWidth) {
            items = this.scaleRowItemsToMaxWidth();
        }
        if (force && !items.length) {
            items = this.activeRowItems;
        }
        if (items.length) {
            this.resetRow();
        }
        return items;
    };

    private resetRow = ():void => {
        this.rowWidth = 0;
        this.activeRowItems = [];
    };

    private predictRowWidth = (item:any):number => {
        let width = item.thumbnails.medium.width;
        this.activeRowItems.forEach((item:any) => width += item.thumbnails.medium.width);
        return width;
    };

    private scaleItemToMaxHeight = (item:any, maxHeight:number):any => {
        let scaleRate = item.thumbnails.medium.height * 100 / maxHeight;
        item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
        item.thumbnails.medium.height = Math.floor(maxHeight);
        return item;
    };

    private scaleRowItemsToMaxWidth = ():Array<any> => {
        let scaleRate = this.rowWidth * 100 / this.rowMaxWidth;
        return this.activeRowItems.map((item:any) => {
            item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
            item.thumbnails.medium.height = Math.floor(item.thumbnails.medium.height * 100 / scaleRate);
            return item;
        });
    };
}
