import {Component, Inject, Output, EventEmitter} from '@angular/core';
import {TitleService} from '../../../../shared/services';

@Component({
    selector: 'topbar',
    template: require('./topbar.component.html'),
    styles: [require('./topbar.component.css').toString()],
})
export class TopBarComponent {
    @Output() onNavClick:EventEmitter<any> = new EventEmitter<any>();

    constructor(@Inject(TitleService) private title:TitleService) {
    }

    private navClick = () => {
        this.onNavClick.emit(null);
    };

    private getPageName = ():string => {
        return this.title.getPageName();
    };
}
