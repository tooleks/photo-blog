import {Injectable} from '@angular/core';
import {env as values} from '../../../../env';

@Injectable()
export class EnvService {
    private values:any;

    constructor() {
        this.values = values;
    }

    get = (property:string):any => {
        return this.has(property) ? this.values[property] : null;
    };

    has = (property:string):boolean => {
        return this.values.hasOwnProperty(property);
    };
}
