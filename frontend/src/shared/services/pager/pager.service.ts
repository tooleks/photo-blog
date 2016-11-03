import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    private limit:number = 12;
    private offset:number = 0;
    private page:number = 1;
    private items:Object[] = [];

    addItems(items:any) {
        this.items = this.mergeItems(items);
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

    private mergeItems(items:any) {
        return this.items.concat(items);
    }

    private getItemsCount() {
        return this.items.length;
    }
}
