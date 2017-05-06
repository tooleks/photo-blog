import {Injectable} from '@angular/core';
import {EnvironmentDetectorService} from '../../../core';

@Injectable()
export class LocalStorageService {
    constructor(protected environmentDetector:EnvironmentDetectorService) {
    }

    set(name:string, value:any):void {
        if (this.environmentDetector.isBrowser()) {
            localStorage.setItem(name, JSON.stringify(value));
        }
    }

    get(name:string):any {
        if (this.environmentDetector.isBrowser()) {
            const value = localStorage.getItem(name);
            return value ? JSON.parse(value) : null;
        }
        return null;
    }
}
