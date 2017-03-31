import {Component, Inject} from '@angular/core';
import {SignInForm} from './models';
import {
    AuthService,
    TitleService,
    ScrollerService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';

@Component({
    selector: 'signin-form',
    templateUrl: 'signin-form.component.html',
})
export class SignInFormComponent {
    private form:SignInForm;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(AuthService) private auth:AuthService,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(NoticesService) private notices:NoticesService,
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
            .then((user:any) => {
                this.notices.success('Hello, ' + user.name + '!');
                this.navigator.navigate(['/']);
                return user;
            });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
