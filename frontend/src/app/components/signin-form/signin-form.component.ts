import {Component, OnInit} from '@angular/core';
import {SignInForm as Form} from './models';
import {
    AuthService,
    TitleService,
    MetaTagsService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../lib/notices';

@Component({
    selector: 'signin-form',
    templateUrl: 'signin-form.component.html',
})
export class SignInFormComponent implements OnInit {
    private form:Form;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(private auth:AuthService,
                private title:TitleService,
                private metaTags:MetaTagsService,
                private notices:NoticesService,
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

    private initTitle = ():void => {
        this.title.setTitle('Sing In');
    };

    private initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    private initForm = ():void => {
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
