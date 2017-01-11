import {Injectable} from '@angular/core';

@Injectable()
export class ScrollerService {
    scrollToTop = ():void => {
        window.scrollTo(0, 0);
    };
}
