import {Injectable} from '@angular/core';

@Injectable()
export class AppService {
    constructor(protected env:any) {
    }

    isDebugMode = ():boolean => {
        return Boolean(this.env.get('debugMode'));
    };

    getApiUrl = ():string => {
        return String(this.env.get('apiUrl'));
    };

    getUrl = ():string => {
        return String(this.env.get('appUrl'));
    };

    getName = ():string => {
        return String(this.env.get('appName'));
    };

    getDescription = ():string => {
        return String(this.env.get('appDescription'));
    };
}
