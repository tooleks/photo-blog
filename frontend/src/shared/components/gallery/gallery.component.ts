import {Component, Input, HostListener} from '@angular/core';
import {GalleryItem} from './gallery-item';

@Component({
    selector: 'gallery',
    template: require('./gallery.component.html'),
    styles: [require('./gallery.component.css').toString()],
})
export class GalleryComponent {
    @Input() items:GalleryItem[] = [];
    @Input() initItemId:string;
    @Input() loadMoreCallback:any;
    @Input() openViewCallback:any;
    @Input() closeViewCallback:any;
    @Input() editItemCallback:any;
    @Input() deleteItemCallback:any;

    activeItem:GalleryItem;
    activeItemIndex:number;

    ngOnChanges(changes:any) {
        if (this.initItemId && changes['items']) {
            this.items.forEach((item:GalleryItem) => {
                if (item.id == this.initItemId) {
                    this.initItemId = null;
                    this.viewItem(item);
                }
            });
        }
    }

    @HostListener('document:keydown', ['$event'])
    handleKeyboardEvent(event:KeyboardEvent) {
        switch (event.key) {
            case 'Escape':
            {
                this.closeView();
                break;
            }
            case 'ArrowLeft':
            {
                this.viewPrevItem();
                break;
            }
            case 'ArrowRight':
            {
                this.viewNextItem(true);
                break;
            }
        }
    }

    viewItem(item:GalleryItem) {
        this.activeItem = item;
        this.items.forEach((item, index) => {
            if (item.id == this.activeItem.id) {
                this.activeItemIndex = index;
            }
        });
        if (typeof(this.openViewCallback) === 'function') {
            this.openViewCallback(this.activeItem);
        }
    }

    viewPrevItem() {
        var prevItemIndex = this.activeItemIndex - 1;
        if (this.items[prevItemIndex]) {
            this.viewItem(this.items[prevItemIndex]);
        }
    }

    viewNextItem(load:any) {
        if (load === undefined) load = true;
        var nextItemIndex = this.activeItemIndex + 1;
        if (this.items[nextItemIndex]) {
            this.viewItem(this.items[nextItemIndex]);
        } else if (load) {
            if (typeof(this.loadMoreCallback) === 'function') {
                this.loadMoreCallback().then((items:any) => {
                    this.items = items;
                    this.viewNextItem(false);
                });
            }
        }
    }

    closeView() {
        this.activeItem = null;
        this.activeItemIndex = null;
        if (typeof(this.closeViewCallback) === 'function') {
            this.closeViewCallback();
        }
    }

    editItem() {
        this.editItemCallback(this.activeItem);
    }

    deleteItem() {
        this.deleteItemCallback(this.activeItem);
    }
}
