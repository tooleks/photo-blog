import {Injectable} from '@angular/core';

@Injectable()
export class EnvironmentDetectorService {
    isBrowser():boolean {
        // #browser-specific
        return typeof (window) !== 'undefined';
    }

    isServer():boolean {
        return !this.isBrowser();
    }
}
