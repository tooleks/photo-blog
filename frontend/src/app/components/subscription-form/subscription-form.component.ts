import {Component, OnInit} from '@angular/core';
import {MetaTagsService} from '../../../core'
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';
import {SubscriptionForm as Form} from './models';

@Component({
    selector: 'subscription-form',
    templateUrl: 'subscription-form.component.html',
})
export class SubscriptionFormComponent implements OnInit {
    protected form:Form;
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
        this.initTitle();
        this.initMeta();
        this.initForm();
    }

    protected initTitle = ():void => {
        this.title.setTitle('Subscription');
    };

    protected initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    protected initForm = ():void => {
        this.setForm(new Form);
    };

    setForm = (form:Form):void => {
        this.form = form;
    };

    getForm = ():Form => {
        return this.form;
    };

    subscribe = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/subscriptions', this.getForm()))
            .then((data:any) => {
                this.notices.success('You have successfully subscribed to the website updates.');
                this.navigator.navigate(['/']);
                return data;
            });
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
