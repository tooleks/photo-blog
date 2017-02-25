import {Component, Output, EventEmitter} from '@angular/core';

@Component({
    selector: 'topbar',
    template: require('./topbar.component.html'),
    styles: [require('./topbar.component.css').toString()],
})
export class TopBarComponent {
    @Output() onNavClick:EventEmitter<any> = new EventEmitter<any>();

    private navClick = () => {
        this.onNavClick.emit(null);
    };
}
