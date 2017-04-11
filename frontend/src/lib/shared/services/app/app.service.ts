import {Injectable} from '@angular/core';
import {EnvService} from '../env/env.service';

@Injectable()
export class AppService {
    constructor(protected env:EnvService) {
    }

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
