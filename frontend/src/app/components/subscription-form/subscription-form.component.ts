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
import {Subscription as Model} from './models';

@Component({
    selector: 'subscription-form',
    templateUrl: 'subscription-form.component.html',
})
export class SubscriptionFormComponent implements OnInit {
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
        this.title.setTitle('Subscription');
        this.metaTags.setTitle(this.title.getPageName());
        this.model = new Model;
    }

    submit = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/subscriptions', this.model))
            .then(this.onSubmitSuccess);
    };

    onSubmitSuccess = (data:any):any => {
        this.notices.success('You have successfully subscribed to the website updates.');
        this.navigator.navigate(['/']);
        return data;
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
