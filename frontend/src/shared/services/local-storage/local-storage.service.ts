import {Injectable} from '@angular/core';

@Injectable()
export class LocalStorageService {
    set(name:string, value:Object) {
        localStorage.setItem(name, JSON.stringify(value));
    }

    get(name:string) {
        let value = localStorage.getItem(name);
        return value ? JSON.parse(value) : null;
    }
}
