import {Component, Input, HostListener, SimpleChanges} from '@angular/core';

@Component({
    selector: 'gallery',
    template: require('./gallery.component.html'),
    styles: [require('./gallery.component.css').toString()],
})
export class GalleryComponent {
    @Input() items:Array<any> = [];
    @Input() defaultActiveItemId:string;
    @Input() loadMoreCallback:any;
    @Input() openItemCallback:any;
    @Input() closeItemCallback:any;
    @Input() editItemCallback:any;
    @Input() deleteItemCallback:any;

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

    getActiveItem = () => this.activeItem;

    setItems = (items:Array<any>) => this.items = items;

    getItems = () => this.items;

    viewItemById = (id:string) => {
        this.items.some((item:any, index:number) => {
            if (item.id == id) {
                this.viewItem(item);
                return true;
            } else if (index === this.items.length - 1) {
                this.processCallback(this.loadMoreCallback)
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

    viewItem = (item:any) => {
        let id = item.id;
        this.items.some((item:any, index:number) => {
            if (item.id == id) {
                this.setActiveItem(item, index);
                this.processCallback(this.openItemCallback, [this.activeItem]);
                return true;
            }
        });
    };

    viewPrevItem = () => {
        let prevItemIndex = this.activeItemIndex - 1;
        if (this.items[prevItemIndex]) {
            this.viewItem(this.items[prevItemIndex]);
        }
    };

    viewNextItem = (loadMoreIfNotExist:boolean) => {
        let nextItemIndex = this.activeItemIndex + 1;
        if (this.items[nextItemIndex]) {
            this.viewItem(this.items[nextItemIndex]);
        } else if (loadMoreIfNotExist) {
            this.processCallback(this.loadMoreCallback)
                .then((items:Array<any>) => {
                    this.setItems(items);
                    this.viewNextItem(false);
                })
                .catch((error:any) => {
                    //
                });
        }
    };

    closeItem = () => {
        this.processCallback(this.closeItemCallback, [this.activeItem]);
        this.setActiveItem(null, null);
    };

    editItem = () => this.processCallback(this.editItemCallback, [this.activeItem]);

    deleteItem = () => this.processCallback(this.deleteItemCallback, [this.activeItem]);
}
