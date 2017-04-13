import {Component, OnInit} from '@angular/core';
import {SignInForm as Form} from './models';
import {MetaTagsService} from '../../../core'
import {NoticesService} from '../../../lib';
import {
    AuthService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';

@Component({
    selector: 'signin-form',
    templateUrl: 'signin-form.component.html',
})
export class SignInFormComponent implements OnInit {
    protected form:Form;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;

    constructor(protected auth:AuthService,
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
        this.title.setTitle('Sing In');
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

    signIn = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.auth.signIn(this.getForm().email, this.getForm().password))
            .then((user:any) => {
                this.notices.success('Hello, ' + user.name + '!');
                this.navigator.navigate(['/']);
                return user;
            });
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
