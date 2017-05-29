import {Component, OnInit} from '@angular/core';
import {MetaTagsService} from '../../../core';
import {TitleService} from '../../../shared';

@Component({
    selector: 'about-me',
    templateUrl: 'about-me.component.html',
})
export class AboutMeComponent implements OnInit {
    constructor(protected title: TitleService, protected metaTags: MetaTagsService) {
    }

    ngOnInit(): void {
        this.title.setPageNameSegment('About Me');
        this.metaTags.setTitle(this.title.getPageNameSegment());
    }

    getContent(): string {
        return process.env.PAGE_ABOUT_ME;
    }
}
