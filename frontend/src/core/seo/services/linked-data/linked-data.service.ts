import {Injectable} from '@angular/core';

@Injectable()
export class LinkedDataService {
    protected items:Array<any> = [];

    setItems(items:Array<any>) {
        this.items = items;
    }

    getItems():Array<any> {
        return this.items;
    }

    pushItem(item:any):void {
        this.items.push(item);
    }

    popItem():any {
        return this.items.pop();
    }
}
