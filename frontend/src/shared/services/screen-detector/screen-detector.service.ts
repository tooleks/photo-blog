import {Injectable} from '@angular/core';

@Injectable()
export class ScreenDetectorService {
    isLargeScreen = ():boolean => {
        return window.innerWidth > 767;
    };

    isSmallScreen = ():boolean => {
        return !this.isLargeScreen();
    };
}
