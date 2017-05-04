import {Component} from '@angular/core';
import {NoticesService} from '../services';
import {Notice} from '../models';

@Component({
    selector: 'notices',
    templateUrl: 'notices.component.html',
    styleUrls: ['notices.component.css'],
})
export class NoticesComponent {
    constructor(protected notices:NoticesService) {
    }

    getNotices():Array<Notice> {
        return this.notices.get();
    }

    deleteNotice(index:number):void {
        this.notices.deleteByIndex(index);
    }
}
