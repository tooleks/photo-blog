import {Injectable} from '@angular/core';

@Injectable()
export class TransferState {
    protected _map = new Map<string, any>();

    constructor() {
    }

    keys() {
        return this._map.keys();
    }

    get(key: string) {
        return this._map.get(key);
    }

    set(key: string, value): Map<string, any> {
        return this._map.set(key, value);
    }

    toJson() {
        const obj = {};
        Array.from(this.keys())
            .forEach(key => {
                obj[key] = this.get(key);
            });
        return obj;
    }

    initialize(obj): void {
        Object.keys(obj)
            .forEach(key => {
                this.set(key, obj[key]);
            });
    }

    inject(): void {
    }
}
