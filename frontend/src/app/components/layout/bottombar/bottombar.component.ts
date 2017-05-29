import {Component} from '@angular/core';
import {AppService} from '../../../../shared';

@Component({
    selector: 'bottombar',
    templateUrl: 'bottombar.component.html',
    styles: [require('./bottombar.component.css').toString()],
})
export class BottomBarComponent {
    constructor(protected app: AppService) {
    }

    getCurrentYear(): number {
        return (new Date).getFullYear();
    }

    getAppName(): string {
        return this.app.getName();
    }
}
