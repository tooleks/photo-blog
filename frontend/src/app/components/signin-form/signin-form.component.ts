import {Component, OnInit} from '@angular/core';
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
import {SignIn as Model} from './models';

@Component({
    selector: 'signin-form',
    templateUrl: 'signin-form.component.html',
})
export class SignInFormComponent implements OnInit {
    protected model:Model;
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
        this.title.setTitle('Sing In');
        this.metaTags.setTitle(this.title.getPageName());
        this.model = new Model;
    }

    submit = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.auth.signIn(this.model.email, this.model.password))
            .then(this.onSubmitSuccess);
    };

    onSubmitSuccess = (user:any):any => {
        this.notices.success('Hello, ' + user.name + '!');
        this.navigator.navigate(['/']);
        return user;
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
