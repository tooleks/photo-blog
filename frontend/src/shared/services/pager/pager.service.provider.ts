import {Injectable} from '@angular/core';
import {PagerService} from './pager.service';

@Injectable()
export class PagerServiceProvider {
    getInstance(limit?:number, offset?:number) {
        return new PagerService(limit, offset);
    }
}
