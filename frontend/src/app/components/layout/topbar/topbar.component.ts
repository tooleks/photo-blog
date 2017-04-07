import {Component, Output, EventEmitter} from '@angular/core';
import {TitleService} from '../../../../shared/services';

@Component({
    selector: 'topbar',
    templateUrl: 'topbar.component.html',
    styleUrls: ['topbar.component.css'],
})
export class TopBarComponent {
    @Output() onNavClick:EventEmitter<any> = new EventEmitter<any>();

    constructor(private title:TitleService) {
    }

    private navClick = () => {
        this.onNavClick.emit(null);
    };

    private getPageName = ():string => {
        return this.title.getPageName();
    };
}
