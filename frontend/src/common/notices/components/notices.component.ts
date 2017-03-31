import {Component, Inject} from '@angular/core';
import {NoticesService} from '../services';
import {Notice} from '../models';

@Component({
    selector: 'notices',
    templateUrl: 'notices.component.html',
    styleUrls: ['notices.component.css'],
})
export class NoticesComponent {
    constructor(@Inject(NoticesService) private notices:NoticesService) {
    }

    getNotices = ():Array<Notice> => {
        return this.notices.get();
    };

    deleteNoticeByIndex = (index:number):void => {
        this.notices.deleteByIndex(index);
    };
}
