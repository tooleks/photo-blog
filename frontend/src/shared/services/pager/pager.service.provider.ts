import {Injectable} from '@angular/core';
import {PagerService} from './pager.service';

@Injectable()
export class PagerServiceProvider {
    getInstance = (page:any, perPage:any):PagerService => {
        return new PagerService(page, perPage);
    };
}
