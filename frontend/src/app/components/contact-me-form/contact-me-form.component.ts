import {Component, OnInit} from '@angular/core';
import {MetaTagsService} from '../../../core';
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';
import {ContactMe as Model} from './models';

@Component({
    selector: 'contact-me-form',
    templateUrl: 'contact-me-form.component.html',
})
export class ContactMeFormComponent implements OnInit {
    protected model:Model;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;

    constructor(protected api:ApiService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected notices:NoticesService,
                navigatorProvider:NavigatorServiceProvider,
                lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.model = new Model;
        this.title.setTitle('Contact Me');
        this.metaTags.setTitle(this.title.getPageName());
    }

    contactMe = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/contact_messages', this.model))
            .then(this.onContactMeSuccess);
    };

    onContactMeSuccess = (data:any):any => {
        this.notices.success('Your message successfully sent.');
        this.navigator.navigate(['/']);
        return data;
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
