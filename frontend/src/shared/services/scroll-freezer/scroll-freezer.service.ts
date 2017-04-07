import {Injectable} from '@angular/core';
import {EnvironmentDetectorService} from '../environment-detector';

@Injectable()
export class ScrollFreezerService {
    constructor(private environmentDetector:EnvironmentDetectorService) {
    }

    freezeBackgroundScroll = ():void => {
        if (this.environmentDetector.isBrowser()) {
            const top = window.scrollY;
            window.onscroll = () => window.scroll(0, top);
        }
    };

    unfreezeBackgroundScroll = ():void => {
        if (this.environmentDetector.isBrowser()) {
            window.onscroll = null;
        }
    };
}
