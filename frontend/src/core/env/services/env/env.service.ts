import {Injectable} from '@angular/core';

@Injectable()
export class EnvService {
    constructor(protected variables) {
    }

    get(variable: string) {
        return this.has(variable) ? this.variables[variable] : null;
    }

    has(variable: string): boolean {
        return this.variables && this.variables.hasOwnProperty(variable);
    }
}
