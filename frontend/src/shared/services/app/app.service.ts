import {Injectable} from '@angular/core';
import {Info} from './interfaces';

@Injectable()
export class AppService {
    constructor(protected info:Info) {
    }

    inDebugMode():boolean {
        return Boolean(this.info.get('debugMode'));
    }

    getApiUrl():string {
        return String(this.info.get('apiUrl'));
    }

    getUrl():string {
        return String(this.info.get('appUrl'));
    }

    getName():string {
        return String(this.info.get('appName'));
    }

    getDescription():string {
        return String(this.info.get('appDescription'));
    }

    getAuthor():string {
        return String(this.info.get('appAuthor'));
    }

    getImage():string {
        return String(this.info.get('appImage'));
    }
}
