import {Component, Output, EventEmitter} from '@angular/core';
import {TitleService} from '../../../../lib';

@Component({
    selector: 'topbar',
    templateUrl: 'topbar.component.html',
    styleUrls: ['topbar.component.css'],
})
export class TopBarComponent {
    @Output() onNavClick:EventEmitter<any> = new EventEmitter<any>();

    constructor(protected title:TitleService) {
    }

    protected navClick = () => {
        this.onNavClick.emit(null);
    };

    protected getPageName = ():string => {
        return this.title.getPageName();
    };
}
