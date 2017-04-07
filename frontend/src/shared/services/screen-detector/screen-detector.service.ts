import {Injectable} from '@angular/core';
import {EnvironmentDetectorService} from '../environment-detector';

@Injectable()
export class ScreenDetectorService {
    constructor(private environmentDetector:EnvironmentDetectorService) {
    }

    isLargeScreen = ():boolean => {
        return this.environmentDetector.isBrowser() ? window.innerWidth > 767 : true;
    };

    isSmallScreen = ():boolean => {
        return !this.isLargeScreen();
    };
}
