import {Component, OnInit} from '@angular/core';
import {EnvService, MetaTagsService} from '../../../core';
import {TitleService} from '../../../shared';

@Component({
    selector: 'about-me',
    templateUrl: 'about-me.component.html',
})
export class AboutMeComponent implements OnInit {
    constructor(protected title:TitleService, protected metaTags:MetaTagsService, protected env:EnvService) {
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
    }

    protected initTitle = ():void => {
        this.title.setPageNameSegment('About Me');
    };

    protected initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageNameSegment());
    };

    getContent = ():string => {
        return this.env.get('pageAboutMe');
    };
}
