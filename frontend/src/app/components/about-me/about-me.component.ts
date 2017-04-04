import {Component, Inject} from '@angular/core';
import {TitleService, EnvService} from '../../../shared/services';

@Component({
    selector: 'about-me',
    template: `<div [innerHtml]="getContent() | safeHtml"></div>`,

})
export class AboutMeComponent {
    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService) {
    }

    ngOnInit() {
        this.title.setTitle('About Me');
    }

    getContent = () => {
        return this.env.get('aboutMePageContent');
    };
}
