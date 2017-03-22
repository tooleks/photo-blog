import {Injectable} from '@angular/core';

@Injectable()
export class ScrollFreezerService {
    freezeBackgroundScroll = () => {
        let top = window.scrollY;
        window.onscroll = () => window.scroll(0, top);
    };

    unfreezeBackgroundScroll = () => {
        window.onscroll = null;
    };
}
