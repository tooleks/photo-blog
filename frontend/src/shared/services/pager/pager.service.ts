import {Injectable} from '@angular/core';

@Injectable()
export class PagerService {
    protected page: number;
    protected perPage: number;

    constructor(page, perPage) {
        this.setPage(page);
        this.setPerPage(perPage);
    }

    setPage(page): this {
        page = parseInt(page);
        this.page = page > 0 ? page : 1;
        return this;
    }

    getPage(): number {
        return this.page;
    }

    getNextPage(): number {
        return this.page + 1;
    }

    getPrevPage(): number {
        return this.page > 1 ? this.page - 1 : null;
    }

    setPerPage(perPage): this {
        perPage = parseInt(perPage);
        this.perPage = perPage;
        return this;
    }

    getPerPage(): number {
        return this.perPage;
    }
}
