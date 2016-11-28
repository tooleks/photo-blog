import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    protected constant = {
        MERGE_TYPE_APPEND: 'append',
        MERGE_TYPE_PREPEND: 'prepend',
    };

    protected limit:number;
    protected offset:number;
    protected page:number;
    protected items:Object[];

    constructor() {
        this.reset();
    }

    reset() {
        this.limit = 100;
        this.offset = 0;
        this.page = 1;
        this.items = [];
    }

    appendItems(items:any) {
        return new Promise((resolve, reject) => {
            this.mergeItems(items, this.constant.MERGE_TYPE_APPEND);
            this.offset = this.getItemsCount();
            this.setPage(this.calculatePage());
            resolve(this.items);
        });
    }

    prependItems(items:any) {
        this.mergeItems(items, this.constant.MERGE_TYPE_PREPEND);
        this.offset = this.getItemsCount();
        this.setPage(this.calculatePage());
    }

    getLimitForPage(page:number) {
        return page !== undefined ? this.limit * Math.abs(Math.ceil(page)) : this.limit;
    }

    getLimit() {
        return this.limit;
    }

    getOffset() {
        return this.offset;
    }

    getPage() {
        return this.page;
    }

    setPage(page:number) {
        if (page > 1) {
            this.page = page;
        }
    }

    getItems() {
        return this.items;
    }

    protected calculatePage() {
        return Math.ceil(this.items.length / this.limit);
    }

    protected mergeItems(items:any, mergeType:string) {
        if (mergeType === this.constant.MERGE_TYPE_APPEND) this.items = this.items.concat(items);
        if (mergeType === this.constant.MERGE_TYPE_PREPEND) this.items = items.concat(this.items);
    }

    protected getItemsCount() {
        return this.items.length;
    }
}
