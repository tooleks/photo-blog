import {Component, Inject} from '@angular/core';
import {TitleService, ScrollerService, EnvService} from '../../../shared/services';

@Component({
    selector: 'about-me',
    template: `<div [innerHtml]="getContent() | safeHtml"></div>`,

})
export class AboutMeComponent {
    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService) {
    }

    ngOnInit() {
        this.scroller.scrollToTop();
        this.title.setTitle('About Me');
    }

    getContent = () => {
        return this.env.get('aboutMePageContent');
    };
}
