import {Component, Output, EventEmitter} from '@angular/core';
import {TitleService} from '../../../../shared';

@Component({
    selector: 'topbar',
    templateUrl: 'topbar.component.html',
    styles: [require('./topbar.component.css').toString()],
})
export class TopBarComponent {
    @Output() onNavClick: EventEmitter<any> = new EventEmitter<any>();

    constructor(protected title: TitleService) {
    }

    navClick(): void {
        this.onNavClick.emit(null);
    }

    getPageName(): string {
        return this.title.getPageNameSegment();
    }
}
