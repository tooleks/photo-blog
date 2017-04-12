import {Injectable} from '@angular/core';
import {Storage} from './interfaces';

@Injectable()
export class AppService {
    constructor(protected storage:Storage) {
    }

    inDebugMode = ():boolean => {
        return Boolean(this.storage.get('debugMode'));
    };

    getApiUrl = ():string => {
        return String(this.storage.get('apiUrl'));
    };

    getUrl = ():string => {
        return String(this.storage.get('appUrl'));
    };

    getName = ():string => {
        return String(this.storage.get('appName'));
    };

    getDescription = ():string => {
        return String(this.storage.get('appDescription'));
    };
}
