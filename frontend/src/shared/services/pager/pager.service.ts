import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    private limit:number;
    private offset:number;
    private page:number;
    private items:Array<any>;

    constructor(limit?:number, offset?:number) {
        this.reset(limit, offset);
    }

    reset = (limit?:number, offset?:number) => {
        this.limit = limit ? limit : 20;
        this.offset = offset ? offset : 0;
        this.page = 1;
        this.items = [];
    };

    appendItems = (items:Array<any>):Array<any> => {
        this.items = this.items.concat(items);
        this.offset = this.items.length;
        this.setPage(this.calculatePage());
        return this.items;
    };

    prependItems = (items:Array<any>):Array<any> => {
        this.items = items.concat(this.items);
        this.offset = this.items.length;
        this.setPage(this.calculatePage());
        return this.items;
    };

    calculateLimitForPage = (page:number):number => {
        return page !== undefined ? this.limit * Math.abs(Math.ceil(page)) : this.limit;
    };

    getLimit = ():number => {
        return this.limit;
    };

    getOffset = ():number => {
        return this.offset;
    };

    getPage = ():number => {
        return this.page;
    };

    setPage = (page:number):void => {
        if (page > 1) {
            this.page = page;
        }
    };

    getItems = ():Array<any> => {
        return this.items
    };

    private calculatePage = ():number => {
        return Math.ceil(this.items.length / this.limit);
    };
}
