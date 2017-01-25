import {Component, Input, Output, Inject, EventEmitter, HostListener, SimpleChanges} from '@angular/core';
import {CallbackHandlerService} from '../../services';

@Component({
    selector: 'gallery',
    template: require('./gallery.component.html'),
    styles: [require('./gallery.component.css').toString()],
})
export class GalleryComponent {
    @Input() items:Array<any> = [];
    @Input() defaultOpenedItemId:string;
    @Input() onLoadMoreCallback:any;
    @Input() showCloseButton:boolean = true;
    @Input() showEditButton:boolean = true;
    @Input() showDeleteButton:boolean = false;
    @Output() onOpenItem:EventEmitter<any> = new EventEmitter<any>();
    @Output() onCloseItem:EventEmitter<any> = new EventEmitter<any>();
    @Output() onEditItem:EventEmitter<any> = new EventEmitter<any>();
    @Output() onDeleteItem:EventEmitter<any> = new EventEmitter<any>();

    private openedItem:any;
    private openedItemIndex:any;
    private openedItemIsLoaded:boolean;

    constructor(@Inject(CallbackHandlerService) private callbackHandler:CallbackHandlerService) {
    }

    ngOnInit() {
        this.resetOpenedItem();
    }

    ngOnChanges(changes:SimpleChanges) {
        if (this.defaultOpenedItemId && this.items.length) {
            this.viewItemById(this.defaultOpenedItemId);
            this.defaultOpenedItemId = null;
        }
    };

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

    setOpenedItem = (item:any, index:number):Promise<any> => {
        return new Promise((resolve, reject) => {
            let image = new Image;
            image.onload = () => {
                this.openedItem = item;
                this.openedItemIndex = index;
                this.openedItemIsLoaded = true;
                resolve();
            };
            this.openedItemIsLoaded = false;
            image.src = item.thumbnails.medium.url;
        }).then(() => this.onOpenItem.emit(this.openedItem));
    };

    resetOpenedItem = ():void => {
        this.openedItem = null;
        this.openedItemIndex = null;
        this.openedItemIsLoaded = false;
    };

    getOpenedItem = ():any => {
        return this.openedItem;
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
                this.setOpenedItem(item, index);
                return true;
            } else if (index === this.items.length - 1) {
                this.loadMoreItems().then((items:Array<any>) => {
                    if (items.length > this.items.length) {
                        this.viewItemById(id);
                    }
                });
            }
            return false;
        });
    };

    viewItem = (item:any):void => {
        let id = item.id;
        this.items.some((item:any, index:number) => {
            if (item.id == id) {
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
            this.loadMoreItems().then((items:Array<any>) => {
                if (items.length > this.items.length) {
                    this.viewNextItem(false)
                }
            });
        }
    };

    loadMoreItems = ():Promise<Array<any>> => {
        return this.callbackHandler
            .resolveCallback(this.onLoadMoreCallback)
            .then((items:Array<any>) => {
                if (items.length > this.items.length) {
                    this.setItems(items);
                }
                return items;
            });
    };

    closeItem = ():void => {
        this.onCloseItem.emit(this.openedItem);
        this.resetOpenedItem();
    };

    editItem = ():void => {
        this.onEditItem.emit(this.openedItem);
    };

    deleteItem = ():void => {
        this.onDeleteItem.emit(this.openedItem);
    };
}
