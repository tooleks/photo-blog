import {Component, Inject} from '@angular/core';
import {NoticesService} from '../services';
import {Notice} from '../models';

@Component({
    selector: 'notices',
    template: require('./notices.component.html'),
    styles: [require('./notices.component.css').toString()],
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
