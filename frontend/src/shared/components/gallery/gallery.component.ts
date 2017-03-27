import {Component, Input, Output, Inject, EventEmitter, HostListener, SimpleChanges, ViewChild} from '@angular/core';
import {CallbackHandlerService, ScrollFreezerService} from '../../services';
import {GalleryGridComponent} from './gallery-grid.component';
import {GalleryItem} from './gallery-item';

@Component({
    selector: 'gallery',
    templateUrl: './gallery.component.html',
    styles: [String(require('./gallery.component.css'))],
})
export class GalleryComponent {
    @ViewChild('galleryGridComponent') galleryGridComponent:GalleryGridComponent;
    @Input() items:Array<GalleryItem> = [];
    @Input() defaultItemId:string;
    @Input() onLoadMoreCallback:any;
    @Input() showCloseButton:boolean = true;
    @Input() showInfoButton:boolean = true;
    @Input() showEditButton:boolean = true;
    @Input() showDeleteButton:boolean = false;
    @Output() onOpenItem:EventEmitter<GalleryItem> = new EventEmitter<GalleryItem>();
    @Output() onCloseItem:EventEmitter<GalleryItem> = new EventEmitter<GalleryItem>();
    @Output() onEditItem:EventEmitter<GalleryItem> = new EventEmitter<GalleryItem>();
    @Output() onDeleteItem:EventEmitter<GalleryItem> = new EventEmitter<GalleryItem>();

    private openedInfo:boolean = false;
    private openedItem:GalleryItem;
    private openedItemIndex:number;
    private openedItemIsLoaded:boolean;

    constructor(@Inject(CallbackHandlerService) private callbackHandler:CallbackHandlerService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.resetOpenedItem();
    }

    ngOnChanges(changes:SimpleChanges) {
        // We will view default item only on the fresh load of items.
        // This is a buggy piece of code. Be aware when making a changes.
        if (this.defaultItemId && changes['items'] && !changes['items'].previousValue.length) {
            this.viewItemById(this.defaultItemId);
        }
    }

    @HostListener('document:keydown', ['$event'])
    onDocumentKeyDown = (event:KeyboardEvent) => {
        if (this.openedItem) {
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

    reset = () => {
        this.resetOpenedItem();
        this.galleryGridComponent.resetGridRowItems();
    };

    setOpenedItem = (item:GalleryItem, index:number):void => {
        this.scrollFreezer.freezeBackgroundScroll();
        this.openedItem = item;
        this.openedItemIndex = index;
        new Promise((resolve) => {
            let image = new Image;
            let loaded = false;
            image.onload = () => {
                loaded = true;
                this.openedItemIsLoaded = true;
                resolve();
            };
            setTimeout(() => (this.openedItemIsLoaded = loaded), 400);
            image.src = item.getLargeSizeUrl();
        }).then(() => this.onOpenItem.emit(this.openedItem));
    };

    resetOpenedItem = ():void => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.openedItem = null;
        this.openedItemIndex = null;
        this.openedItemIsLoaded = false;
    };

    getOpenedItem = ():GalleryItem => {
        return this.openedItem;
    };

    getOpenedInfo = ():boolean => {
        return this.openedInfo;
    };

    setItems = (items:Array<GalleryItem>):void => {
        this.items = items;
    };

    getItems = ():Array<GalleryItem> => {
        return this.items;
    };

    viewItemById = (id:any):void => {
        this.items.some((item:GalleryItem, index:number) => {
            if (item.getId() == id && index != this.openedItemIndex) {
                this.setOpenedItem(item, index);
                return true;
            } else if (index === this.items.length - 1) {
                this.loadMoreItems().then((items:Array<GalleryItem>) => {
                    this.viewItemById(id);
                });
            }
            return false;
        });
    };

    viewItem = (item:GalleryItem):void => {
        let id = item.getId();
        this.items.some((item:GalleryItem, index:number) => {
            if (item.getId() == id && index != this.openedItemIndex) {
                this.setOpenedItem(this.items[index], index);
                return true;
            }
            return false;
        });
    };

    viewPrevItem = ():void => {
        let prevItemIndex = this.openedItemIndex - 1;
        if (this.items[prevItemIndex]) {
            this.viewItem(this.items[prevItemIndex]);
        }
    };

    viewNextItem = (loadMoreIfNotExist:boolean):void => {
        let nextItemIndex = this.openedItemIndex + 1;
        if (this.items[nextItemIndex]) {
            this.viewItem(this.items[nextItemIndex]);
        } else if (loadMoreIfNotExist) {
            this.loadMoreItems().then((items:Array<GalleryItem>) => {
                this.viewNextItem(false)
            });
        }
    };

    loadMoreItems = ():Promise<Array<GalleryItem>> => {
        return this.callbackHandler
            .resolveCallback(this.onLoadMoreCallback)
            .then((items:Array<GalleryItem>) => {
                this.setItems(items);
                return items;
            });
    };

    closeItem = ():void => {
        this.onCloseItem.emit(this.openedItem);
        this.resetOpenedItem();
    };

    infoItem = ():void => {
        this.openedInfo = !this.openedInfo;
    };

    editItem = ():void => {
        this.onEditItem.emit(this.openedItem);
    };

    deleteItem = ():void => {
        this.onDeleteItem.emit(this.openedItem);
    };
}
