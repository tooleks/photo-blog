import {Component} from '@angular/core';
import {AppService} from '../../../../shared';

@Component({
    selector: 'bottombar',
    templateUrl: 'bottombar.component.html',
    styleUrls: ['bottombar.component.css'],
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
