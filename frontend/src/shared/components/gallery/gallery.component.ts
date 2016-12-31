import {Component, Input, HostListener, SimpleChanges} from '@angular/core';

@Component({
    selector: 'gallery',
    template: require('./gallery.component.html'),
    styles: [require('./gallery.component.css').toString()],
})
export class GalleryComponent {
    @Input() items:Array<any> = [];
    @Input() defaultActiveItemId:string;
    @Input() onLoadMoreCallback:any;
    @Input() onOpenItemCallback:any;
    @Input() onCloseItemCallback:any;
    @Input() onEditItemCallback:any;
    @Input() onDeleteItemCallback:any;

    activeItem:any;
    activeItemIndex:number;

    ngOnChanges(changes:SimpleChanges) {
        if (this.defaultActiveItemId && this.items.length) {
            this.viewItemById(this.defaultActiveItemId);
            this.defaultActiveItemId = null;
        }
    };

    @HostListener('document:keydown', ['$event'])
    handleKeyboardEvent = (event:KeyboardEvent) => {
        if (this.activeItem) {
            switch (event.key) {
                case 'Escape':
                    return this.closeItem();
                case 'ArrowLeft':
                    return this.viewPrevItem();
                case 'ArrowRight':
                    return this.viewNextItem(true);
            }
        }
    };

    processCallback = (callback:any, args?:any[]) => {
        return typeof callback === 'function' ? Promise.resolve(callback(...args)) : Promise.reject(new Error);
    };

    setActiveItem = (item:any, index:number) => {
        this.activeItem = item;
        this.activeItemIndex = index;
    };

    getActiveItem = ():any => {
        return this.activeItem;
    };

    setItems = (items:Array<any>):void => {
        this.items = items;
    };

    getItems = ():Array<any> => {
        return this.items;
    };

    viewItemById = (id:string):void => {
        this.items.some((item:any, index:number) => {
            if (item.id == id) {
                this.viewItem(item);
                return true;
            } else if (index === this.items.length - 1) {
                this.processCallback(this.onLoadMoreCallback)
                    .then((items:Array<any>) => {
                        this.setItems(items);
                        this.viewItemById(id);
                    })
                    .catch((error:any) => {
                        //
                    });
            }
        });
    };

    viewItem = (item:any):void => {
        let id = item.id;
        this.items.some((item:any, index:number) => {
            if (item.id == id) {
                this.setActiveItem(item, index);
                this.processCallback(this.onOpenItemCallback, [this.activeItem]);
                return true;
            }
        });
    };

    viewPrevItem = ():void => {
        let prevItemIndex = this.activeItemIndex - 1;
        if (this.items[prevItemIndex]) {
            this.viewItem(this.items[prevItemIndex]);
        }
    };

    viewNextItem = (loadMoreIfNotExist:boolean):void => {
        let nextItemIndex = this.activeItemIndex + 1;
        if (this.items[nextItemIndex]) {
            this.viewItem(this.items[nextItemIndex]);
        } else if (loadMoreIfNotExist) {
            this.processCallback(this.onLoadMoreCallback)
                .then((items:Array<any>) => {
                    this.setItems(items);
                    this.viewNextItem(false);
                })
                .catch((error:any) => {
                    //
                });
        }
    };

    closeItem = ():void => {
        this.processCallback(this.onCloseItemCallback, [this.activeItem]);
        this.setActiveItem(null, null);
    };

    editItem = ():void => {
        this.processCallback(this.onEditItemCallback, [this.activeItem]);
    };

    deleteItem = ():void => {
        this.processCallback(this.onDeleteItemCallback, [this.activeItem]);
    };
}
