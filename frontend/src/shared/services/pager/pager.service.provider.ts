import {Injectable} from '@angular/core';
import {PagerService} from './pager.service';

@Injectable()
export class PagerServiceProvider {
    getInstance(page, perPage):PagerService {
        return new PagerService(page, perPage);
    }
}
