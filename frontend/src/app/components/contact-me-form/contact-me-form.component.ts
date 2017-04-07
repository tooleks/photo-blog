import {Component, OnInit} from '@angular/core';
import {ContactMeForm as Form} from './models';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';

@Component({
    selector: 'contact-me-form',
    templateUrl: 'contact-me-form.component.html',
})
export class ContactMeFormComponent implements OnInit {
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
        this.title.setTitle('Contact Me');
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

    send = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/contact_messages', this.getForm()))
            .then((data:any) => {
                this.notices.success('Your message successfully sent.');
                this.navigator.navigate(['/']);
                return data;
            });
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
