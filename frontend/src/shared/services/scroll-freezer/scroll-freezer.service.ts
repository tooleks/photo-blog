import {Injectable, EventEmitter} from '@angular/core';
import {EnvironmentDetectorService} from '../environment-detector';

@Injectable()
export class ScrollFreezerService {
    freezed:EventEmitter<boolean> = new EventEmitter<boolean>();
    unfreezed:EventEmitter<boolean> = new EventEmitter<boolean>();

    constructor(protected environmentDetector:EnvironmentDetectorService) {
    }

    freezeBackgroundScroll = ():void => {
        if (this.environmentDetector.isBrowser()) {
            const top = window.scrollY;
            window.onscroll = () => window.scroll(0, top);
            this.freezed.emit(true);
        }
    };

    unfreezeBackgroundScroll = ():void => {
        if (this.environmentDetector.isBrowser()) {
            window.onscroll = null;
            this.unfreezed.emit(true);
        }
    };
}
