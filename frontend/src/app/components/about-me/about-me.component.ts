import {Component, OnInit} from '@angular/core';
import {TitleService, MetaTagsService, EnvService} from '../../../lib';

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
        this.title.setTitle('About Me');
    };

    protected initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    getContent = ():string => {
        return this.env.get('pageAboutMe');
    };
}
