import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    private constant = {
        MERGE_TYPE_APPEND: 'append',
        MERGE_TYPE_PREPEND: 'prepend',
    };

    private limit:number = 100;
    private offset:number = 0;
    private page:number = 1;
    private items:Object[] = [];

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

    private calculatePage() {
        return Math.ceil(this.items.length / this.limit);
    }

    private mergeItems(items:any, mergeType:string) {
        if (mergeType === this.constant.MERGE_TYPE_APPEND) this.items = this.items.concat(items);
        if (mergeType === this.constant.MERGE_TYPE_PREPEND) this.items = items.concat(this.items);
    }

    private getItemsCount() {
        return this.items.length;
    }
}
