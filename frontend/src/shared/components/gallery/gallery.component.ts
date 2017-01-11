import {Component, Input, HostListener, SimpleChanges, Inject} from '@angular/core';
import {CallbackHandlerService} from '../../services/callback-handler';

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

    private activeItem:{index:number, value:any, url:string, description:string, loaded:boolean};

    constructor(@Inject(CallbackHandlerService) private callbackHandler:CallbackHandlerService) {
    }

    ngOnInit() {
        this.resetActiveItem();
    }

    ngOnChanges(changes:SimpleChanges) {
        if (this.defaultActiveItemId && this.items.length) {
            this.viewItemById(this.defaultActiveItemId);
        }
    };

    @HostListener('document:keydown', ['$event'])
    onDocumentKeyDown = (event:KeyboardEvent) => {
        if (this.activeItem.value) {
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

    setActiveItem = (item:any, index:number):Promise<any> => {
        return new Promise((resolve, reject) => {
            let image = new Image;
            image.onload = () => {
                this.activeItem.index = index;
                this.activeItem.value = item;
                this.activeItem.url = item.thumbnails[0].absolute_url;
                this.activeItem.description = item.description;
                this.activeItem.loaded = true;
                resolve();
            };
            this.activeItem.loaded = false;
            image.src = item.thumbnails[0].absolute_url;
        });
    };

    resetActiveItem = ():void => {
        this.activeItem = {index: null, value: null, url: null, description: null, loaded: false};
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
        for (let index = 0; index < this.items.length; index++) {
            if (this.items[index].id == id) {
                this.setActiveItem(this.items[index], index).then(() => this.callbackHandler.resolveCallback(this.onOpenItemCallback, [this.activeItem.value]));
                break;
            } else if (index === this.items.length - 1) {
                this.callbackHandler.resolveCallback(this.onLoadMoreCallback)
                    .then((items:Array<any>) => {
                        if (items.length > this.items.length) {
                            this.setItems(items);
                            this.viewItemById(id);
                        }
                    });
            }
        }
    };

    viewItem = (item:any):void => {
        let id = item.id;
        for (let index = 0; index < this.items.length; index++) {
            if (this.items[index].id == id) {
                this.setActiveItem(this.items[index], index).then(() => this.callbackHandler.resolveCallback(this.onOpenItemCallback, [this.activeItem.value]));
                break;
            }
        }
    };

    viewPrevItem = ():void => {
        let prevItemIndex = this.activeItem.index - 1;
        if (this.items[prevItemIndex]) {
            this.viewItem(this.items[prevItemIndex]);
        }
    };

    viewNextItem = (loadMoreIfNotExist:boolean):void => {
        let nextItemIndex = this.activeItem.index + 1;
        if (this.items[nextItemIndex]) {
            this.viewItem(this.items[nextItemIndex]);
        } else if (loadMoreIfNotExist) {
            this.callbackHandler.resolveCallback(this.onLoadMoreCallback)
                .then((items:Array<any>) => {
                    if (items.length > this.items.length) {
                        this.setItems(items);
                        this.viewNextItem(false);
                    }
                });
        }
    };

    closeItem = ():void => {
        this.callbackHandler.resolveCallback(this.onCloseItemCallback, [this.activeItem.value]);
        this.resetActiveItem();
    };

    editItem = ():void => {
        this.callbackHandler.resolveCallback(this.onEditItemCallback, [this.activeItem.value]);
    };

    deleteItem = ():void => {
        this.callbackHandler.resolveCallback(this.onDeleteItemCallback, [this.activeItem.value]);
    };
}
