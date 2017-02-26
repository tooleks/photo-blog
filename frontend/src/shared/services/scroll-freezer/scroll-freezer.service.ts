import {Injectable} from '@angular/core';

@Injectable()
export class ScrollFreezerService {
    private defaultBackgroundOverflow:string;
    private backgroundOverflow:string;

    setDefaultBackgroundOverflow = (defaultBackgroundOverflow:string = '') => {
        this.backgroundOverflow = this.defaultBackgroundOverflow = defaultBackgroundOverflow;
    };

    freezeBackgroundScroll = () => {
        let top = window.scrollY;
        this.backgroundOverflow = 'hidden';
        window.onscroll = () => window.scroll(0, top);
    };

    unfreezeBackgroundScroll = () => {
        this.backgroundOverflow = this.defaultBackgroundOverflow;
        window.onscroll = null;
    };

    getBackgroundOverflow = () => {
        return this.backgroundOverflow;
    };
}
