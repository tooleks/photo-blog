import {Injectable} from '@angular/core';
import {Notice} from '../models';
import {EnvironmentDetectorService} from '../../../shared/services';

@Injectable()
export class NoticesService {
    public deleteTimeout:number = 4500;
    protected notices:Array<Notice> = [];

    constructor(protected environmentDetector:EnvironmentDetectorService) {
        if (environmentDetector.isBrowser()) {
            setInterval(this.shiftAfterDeleteTimeout, 100);
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

    shiftAfterDeleteTimeout = ():void => {
        if (this.notices.length && this.notices[0].timestampCreated + this.deleteTimeout < Math.floor(Date.now())) {
            this.notices.shift();
        }
    };
}
