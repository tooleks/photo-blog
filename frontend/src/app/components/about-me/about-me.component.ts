import {Component, Inject} from '@angular/core';
import {EnvService} from '../../../shared/services';

@Component({
    selector: 'about-me',
    template: `<div [innerHtml]="getContent() | safeHtml"></div>`,

})
export class AboutMeComponent {
    constructor(@Inject(EnvService) private env:EnvService) {
    }

    getContent = () => {
        return this.env.get('aboutMePageContent');
    };
}
