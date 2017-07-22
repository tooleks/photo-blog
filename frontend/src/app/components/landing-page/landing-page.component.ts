import {Component, OnInit} from '@angular/core'
import {AppService, TitleService} from '../../../shared';
import {MetaTagsService} from '../../../core';

@Component({
    selector: 'landing-page',
    templateUrl: 'landing-page.component.html',
    styles: [require('./landing-page.component.css').toString()],
})
export class LandingPageComponent implements OnInit {
    constructor(protected app: AppService,
                protected title: TitleService,
                protected metaTags: MetaTagsService) {
    }

    ngOnInit(): void {
        this.title.setPageNameSegment('Home');
        this.metaTags.setTitle(this.title.getPageNameSegment());
    }

    getHeading(): string {
        return this.app.getName();
    }

    getBackgroundStyles() {
        return {
            'background-image': `url(${this.app.getLandingPageImage()})`,
            'background-color': this.app.getLandingPageColor(),
        };
    }
}
