import {Component, OnInit} from '@angular/core';
import {TitleService, MetaTagsService, EnvService} from '../../../shared/services';

@Component({
    selector: 'about-me',
    templateUrl: 'about-me.component.html',
})
export class AboutMeComponent implements OnInit {
    constructor(private title:TitleService, private metaTags:MetaTagsService, private env:EnvService) {
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
    }

    private initTitle = ():void => {
        this.title.setTitle('About Me');
    };

    private initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    getContent = ():string => {
        return this.env.get('pageAboutMe');
    };
}
