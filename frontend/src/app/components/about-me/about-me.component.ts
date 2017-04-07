import {Component, OnInit} from '@angular/core';
import {TitleService, EnvService} from '../../../shared/services';

@Component({
    selector: 'about-me',
    templateUrl: 'about-me.component.html',
})
export class AboutMeComponent implements OnInit {
    constructor(private title:TitleService, private env:EnvService) {
    }

    ngOnInit():void {
        this.title.setTitle('About Me');
    }

    getContent = ():string => {
        return this.env.get('pageAboutMe');
    };
}
