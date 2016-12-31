export class GridRow {
    private items:Array<any>;
    private maxHeight:number;
    private maxWidth:number;

    constructor() {
        this.resetItems();
        this.setMaxHeight(0);
        this.setMaxWidth(0);
    }

    setMaxHeight = (maxHeight:number):void => {
        this.maxHeight = maxHeight;
    };

    getMaxHeight = ():number => {
        return this.maxHeight;
    };

    setMaxWidth = (maxWidth:number):void => {
        this.maxWidth = maxWidth;
    };

    getMaxWidth = ():number => {
        return this.maxWidth;
    };

    appendItem = (item:any):Array<any> => {
        let scaledToMaxHeightItem = this.scaleItemToMaxHeight(item);
        let predictedRowWidth = this.predictRowWidth(scaledToMaxHeightItem);
        this.items.push(scaledToMaxHeightItem);
        if (predictedRowWidth > this.maxWidth) {
            let scaledToMaxWidthItems = this.scaleItemsToMaxWidth(predictedRowWidth);
            this.resetItems();
            return scaledToMaxWidthItems;
        }
        return this.items;
    };

    resetItems = ():void => {
        this.items = [];
    };

    getItems = ():Array<any> => {
        return this.items;
    };

    private predictRowWidth = (newItem:any):number => {
        let width = newItem.thumbnails[1].width;
        for (let index = 0; index < this.items.length; index++) {
            width += this.items[index].thumbnails[1].width;
        }
        return width;
    };

    private scaleItemToMaxHeight = (item:any):any => {
        let scaleRate = item.thumbnails[1].height * 100 / this.maxHeight;
        item.thumbnails[1].height = this.maxHeight;
        item.thumbnails[1].width = item.thumbnails[1].width * 100 / scaleRate;
        return item;
    };

    private scaleItemsToMaxWidth = (width:number):Array<any> => {
        let scaleRate = width * 100 / this.maxWidth;
        for (let index = 0; index < this.items.length; index++) {
            this.items[index].thumbnails[1].height = this.items[index].thumbnails[1].height * 100 / scaleRate;
            this.items[index].thumbnails[1].width = this.items[index].thumbnails[1].width * 100 / scaleRate;
        }
        return this.items;
    };
}
