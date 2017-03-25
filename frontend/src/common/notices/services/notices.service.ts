import {Injectable} from '@angular/core';
import {Notice} from '../models';

@Injectable()
export class NoticesService {
    public deleteTimeout:number = 4500;
    private notices:Array<Notice> = [];

    constructor() {
        setInterval(this.shiftAfterDeleteTimeout, 100);
    }

    success = (title:string, text?:string):void => {
        this.notices.push(new Notice(this.getCurrentTimestamp(), 'success', title, text))
    };

    warning = (title:string, text?:string):void => {
        this.notices.push(new Notice(this.getCurrentTimestamp(), 'warning', title, text))
    };

    error = (title:string, text?:string):void => {
        this.notices.push(new Notice(this.getCurrentTimestamp(), 'error', title, text))
    };

    info = (title:string, text?:string):void => {
        this.notices.push(new Notice(this.getCurrentTimestamp(), 'info', title, text))
    };

    get = ():Array<Notice> => {
        return this.notices;
    };

    deleteByIndex = (index:number):void => {
        this.notices = this.notices.filter((element, elementIndex) => elementIndex !== index);
    };

    shiftAfterDeleteTimeout = ():void => {
        if (this.notices.length && this.notices[0].timestampCreated + this.deleteTimeout < this.getCurrentTimestamp()) {
            this.notices.shift();
        }
    };

    private getCurrentTimestamp = ():number => {
        return Math.floor(Date.now());
    };
}
