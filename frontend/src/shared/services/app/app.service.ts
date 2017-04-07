import {Injectable} from '@angular/core';
import {EnvService} from "../env/env.service";

@Injectable()
export class AppService {
    constructor(private env:EnvService) {
    }

    getName = ():string => {
        return String(this.env.get('appName'));
    };

    getUrl = ():string => {
        return String(this.env.get('appUrl'));
    };
}
