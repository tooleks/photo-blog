import {Component, OnInit} from '@angular/core';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';
import {SubscriptionForm as Form} from './models';

@Component({
    selector: 'subscription-form',
    templateUrl: 'subscription-form.component.html',
})
export class SubscriptionFormComponent implements OnInit {
    private form:Form;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(private api:ApiService,
                private title:TitleService,
                private notices:NoticesService,
                navigatorProvider:NavigatorServiceProvider,
                lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.title.setTitle('Subscription');
        this.initForm();
    }

    setForm = (form:Form):void => {
        this.form = form;
    };

    getForm = ():Form => {
        return this.form;
    };

    initForm = ():void => {
        this.setForm(new Form);
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
