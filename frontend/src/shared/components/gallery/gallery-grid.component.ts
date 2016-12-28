import {Component, Input, Inject, HostListener, ElementRef, SimpleChanges, ViewChild} from '@angular/core';
import {GridRow} from './grid-row';

@Component({
    selector: 'gallery-grid',
    template: require('./gallery-grid.component.html'),
    styles: [require('./gallery-grid.component.css').toString()],
})
export class GalleryGridComponent {
    @Input() rowHeight:number = 0;
    @Input() rowWidth:number = 0;
    @Input() clickGridItemCallback:any;
    @Input() galleryItems:Array<any> = [];
    @Input() updateInterval:number;

    gridLastRow:GridRow;
    gridItems:Array<any> = [];
    elementSizeCheckInterval:any = null;
    elementProperties:any = {width: 0, height: 0};

    constructor(@Inject(ElementRef) protected elementRef:ElementRef) {
        this.gridLastRow = new GridRow;
    }

    ngOnChanges(changes:SimpleChanges) {
        if (changes['rowHeight']) {
            this.gridLastRow.setMaxHeight(changes['rowHeight'].currentValue);
            this.setGridItems(this.galleryItems);
        }

        if (changes['galleryItems'] && changes['galleryItems'].currentValue.length) {
            this.gridLastRow.setMaxWidth(this.elementRef.nativeElement.offsetWidth);
            this.setGridItems(changes['galleryItems'].currentValue);
        }
    }

    ngAfterContentInit() {
        this.elementSizeCheckInterval = setInterval(() => {
            let height = this.elementRef.nativeElement.offsetHeight;
            let width = this.elementRef.nativeElement.offsetWidth;
            if ((height !== this.elementProperties.height) || (width !== this.elementProperties.width)) {
                this.elementProperties = {width: width, height: height};
                this.gridLastRow.setMaxWidth(this.elementRef.nativeElement.offsetWidth);
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
        this.gridLastRow.resetItems();
        let gridItems:Array<any> = [];
        for (let index = 0; index < items.length; index++) {
            let rowItems = this.gridLastRow.appendItem(items[index]);
            if (!this.gridLastRow.getItems().length || index == items.length - 1) {
                gridItems = gridItems.concat(rowItems);
            }
        }
        this.gridItems = gridItems;
    };

    getGridItems = ():Array<any> => this.gridItems;
}
