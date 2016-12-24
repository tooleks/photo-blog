import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    protected limit:number;
    protected offset:number;
    protected page:number;
    protected items:Array<any>;

    constructor(limit?:number, offset?:number) {
        this.reset(limit, offset);
    }

    reset = (limit?:number, offset?:number) => {
        this.limit = limit ? limit : 20;
        this.offset = offset ? offset : 0;
        this.page = 1;
        this.items = [];
    };

    appendItems = (items:Array<any>) => {
        this.items = this.items.concat(items);
        this.offset = this.getItemsCount();
        this.setPage(this.calculatePage());
        return Promise.resolve(this.items);
    };

    prependItems = (items:Array<any>) => {
        this.items = items.concat(this.items);
        this.offset = this.getItemsCount();
        this.setPage(this.calculatePage());
        return Promise.resolve(this.items);
    };

    getLimitForPage = (page:number):number => {
        return page !== undefined ? this.limit * Math.abs(Math.ceil(page)) : this.limit;
    };

    getLimit = ():number => this.limit;

    getOffset = ():number => this.offset;

    getPage = ():number => this.page;

    setPage = (page:number) => {
        if (page > 1) {
            this.page = page;
        }
    };

    getItems = ():Array<any> => this.items;

    protected calculatePage = ():number => Math.ceil(this.items.length / this.limit);

    protected getItemsCount = ():number => this.items.length;
}
