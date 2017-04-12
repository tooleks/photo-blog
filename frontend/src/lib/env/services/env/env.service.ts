import {Injectable} from '@angular/core';

@Injectable()
export class EnvService {
    constructor(protected variables:any) {
    }

    get = (variable:string):any => {
        return this.has(variable) ? this.variables[variable] : null;
    };

    has = (variable:string):boolean => {
        return this.variables && this.variables.hasOwnProperty(variable);
    };
}
