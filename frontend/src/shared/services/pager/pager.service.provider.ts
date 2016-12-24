import {Injectable} from '@angular/core';
import {PagerService} from './pager.service';

@Injectable()
export class PagerServiceProvider {
    getInstance = (limit?:number, offset?:number):PagerService => {
        return new PagerService(limit, offset);
    };
}
