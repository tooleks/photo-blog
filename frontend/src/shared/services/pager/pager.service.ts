import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    private page:number;
    private perPage:number;

    constructor(page:any, perPage:any) {
        this.setPage(page);
        this.setPerPage(perPage);
    }

    setPage = (page:any):this => {
        page = parseInt(page);
        this.page = page > 0 ? page : 1;
        return this;
    };

    getPage = ():number => {
        return this.page;
    };

    getNextPage = ():number => {
        return this.page + 1;
    };

    getPrevPage = ():number => {
        return this.page > 1 ? this.page - 1 : null;
    };

    setPerPage = (perPage:any):this => {
        perPage = parseInt(perPage);
        this.perPage = perPage;
        return this;
    };

    getPerPage = ():number => {
        return this.perPage;
    };
}
