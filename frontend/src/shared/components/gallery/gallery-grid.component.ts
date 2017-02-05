import {Component, Input, Output, Inject, ElementRef, SimpleChanges, EventEmitter} from '@angular/core';

@Component({
    selector: 'gallery-grid',
    template: require('./gallery-grid.component.html'),
    styles: [require('./gallery-grid.component.css').toString()],
})
export class GalleryGridComponent {
    @Input() rowHeight:number = 0;
    @Input() rowWidth:number = 0;
    @Input() galleryItems:Array<any> = [];
    @Input() updateInterval:number;
    @Output() onClickGridItem:EventEmitter<any> = new EventEmitter<any>();

    private elementRefProperties:{width:number, height:number} = {width: 0, height: 0};
    private elementSizeCheckInterval:any = null;
    private rowMaxWidth:number;
    private rowMaxHeight:number;
    private rowItems:Array<any> = [];
    private gridItems:Array<any> = [];

    constructor(@Inject(ElementRef) private elementRef:ElementRef) {
        this.resetRow();
        this.setRowMaxHeight(0);
        this.setRowMaxWidth(0);
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.setRowMaxHeight(changes['rowHeight'].currentValue);
            this.setGridItems(this.galleryItems);
        }

        if (changes['galleryItems'] && changes['galleryItems'].currentValue.length) {
            this.setRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridItems(changes['galleryItems'].currentValue);
        }
    }

    ngAfterContentInit() {
        this.elementSizeCheckInterval = setInterval(() => {
            let height = this.elementRef.nativeElement.offsetHeight;
            let width = this.elementRef.nativeElement.offsetWidth;
            if ((height !== this.elementRefProperties.height) || (width !== this.elementRefProperties.width)) {
                this.elementRefProperties = {width: width, height: height};
                this.setRowMaxWidth(this.elementRef.nativeElement.offsetWidth);
                this.setGridItems(this.galleryItems);
            }
        }, this.updateInterval);
    }

    ngOnDestroy() {
        if (this.elementSizeCheckInterval !== null) {
            clearInterval(this.elementSizeCheckInterval);
        }
    }

    setGridItems = (items:Array<any>) => {
        this.resetRow();
        let gridItems:Array<any> = [];
        items.forEach((item:any, index:number) => {
            this.pushItemToRow(item);
            gridItems = gridItems.concat(this.releaseRowItems(index == items.length - 1));
        });
        this.gridItems = gridItems;
    };

    getGridItems = ():Array<any> => {
        return this.gridItems;
    };

    private setRowMaxWidth = (rowMaxWidth:number):void => {
        this.rowMaxWidth = rowMaxWidth;
    };

    private getRowMaxWidth = ():number => {
        return this.rowMaxWidth;
    };

    private setRowMaxHeight = (rowMaxHeight:number):void => {
        this.rowMaxHeight = rowMaxHeight;
    };

    private getRowMaxHeight = ():number => {
        return this.rowMaxHeight;
    };

    private pushItemToRow = (newItem:any):number => {
        let scaledToMaxHeightItem = this.scaleItemToMaxHeight(newItem);
        this.rowWidth = this.predictRowWidth(scaledToMaxHeightItem);
        return this.rowItems.push(scaledToMaxHeightItem);
    };

    private releaseRowItems = (force:boolean = false):Array<any> => {
        let items = [];
        if (this.rowWidth > this.rowMaxWidth) {
            items = this.scaleRowItemsToMaxWidth();
        }
        if (force && !items.length) {
            items = this.getRowItems();
        }
        if (items.length) {
            this.resetRow();
        }
        return items;
    };

    private resetRow = ():void => {
        this.rowWidth = 0;
        this.rowItems = [];
    };

    private getRowItems = ():Array<any> => {
        return this.rowItems;
    };

    private predictRowWidth = (newItem:any):number => {
        let width = newItem.thumbnails.medium.width;
        this.rowItems.forEach((item:any) => width += item.thumbnails.medium.width);
        return width;
    };

    private scaleItemToMaxHeight = (item:any):any => {
        let scaleRate = item.thumbnails.medium.height * 100 / this.rowMaxHeight;
        item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
        item.thumbnails.medium.height = Math.floor(this.rowMaxHeight);
        return item;
    };

    private scaleRowItemsToMaxWidth = ():Array<any> => {
        let scaleRate = this.rowWidth * 100 / this.rowMaxWidth;
        return this.rowItems.map((item:any) => {
            item.thumbnails.medium.width = Math.floor(item.thumbnails.medium.width * 100 / scaleRate);
            item.thumbnails.medium.height = Math.floor(item.thumbnails.medium.height * 100 / scaleRate);
            return item;
        });
    };
}
