import {Component, Inject} from '@angular/core';
import {SignInForm} from './signin-form';
import {AuthService} from '../../../shared/services/auth';
import {TitleService} from '../../../shared/services/title';
import {ScrollerService} from '../../../shared/services/scroller';
import {LockProcessService, LockProcessServiceProvider} from '../../../shared/services/lock-process';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {NotificatorService} from '../../../shared/services/notificator';
import {User} from '../../../shared/models';

@Component({
    selector: 'signin-form',
    template: require('./signin-form.component.html'),
})
export class SignInFormComponent {
    private form:SignInForm;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(AuthService) private auth:AuthService,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(NotificatorService) private notificator:NotificatorService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();
        this.title.setTitle('Sing In');
        this.form = new SignInForm;
    }

    signIn = () => {
        return this.lockProcess
            .process(this.auth.signIn, [this.form.email, this.form.password])
            .then((user:User) => {
                this.notificator.success('Hello, ' + user.name + '!');
                this.navigator.navigate(['/']);
                return user;
            });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
