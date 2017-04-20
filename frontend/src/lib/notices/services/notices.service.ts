import {Injectable} from '@angular/core';
import {Notice} from '../models';

@Injectable()
export class NoticesService {
    protected notices:Array<Notice> = [];
    public deleteTimeout:number = 4500;
    public deleteInterval:number = 100;

    constructor() {
        // #browser-specific
        if (typeof (window) !== 'undefined') {
            setInterval(this.shiftAfterDeleteTimeout, this.deleteInterval);
        }
    }

    success = (title:string, text?:string):void => {
        this.notices.push(new Notice(Math.floor(Date.now()), 'success', title, text))
    };

    warning = (title:string, text?:string):void => {
        this.notices.push(new Notice(Math.floor(Date.now()), 'warning', title, text))
    };

    error = (title:string, text?:string):void => {
        this.notices.push(new Notice(Math.floor(Date.now()), 'error', title, text))
    };

    info = (title:string, text?:string):void => {
        this.notices.push(new Notice(Math.floor(Date.now()), 'info', title, text))
    };

    get = ():Array<Notice> => {
        return this.notices;
    };

    deleteByIndex = (index:number):void => {
        this.notices = this.notices.filter((element, elementIndex) => elementIndex !== index);
    };
    
    deleteAll = ():void => {
        this.notices = [];
    };

    shiftAfterDeleteTimeout = ():void => {
        if (this.notices.length && this.notices[0].timestampCreated + this.deleteTimeout < Math.floor(Date.now())) {
            this.notices.shift();
        }
    };
}
