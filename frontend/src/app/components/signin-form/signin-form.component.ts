import {Component, Inject} from '@angular/core';
import {SignInForm} from './signin-form';
import {AuthService} from '../../../shared/services/auth';
import {TitleService} from '../../../shared/services/title';
import {ScrollerService} from '../../../shared/services/scroller';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {NotificatorService} from '../../../shared/services/notificator';
import {User} from '../../../shared/models';

@Component({
    selector: 'signin-form',
    template: require('./signin-form.component.html'),
})
export class SignInFormComponent {
    private navigator:NavigatorService;
    private form:SignInForm;

    constructor(@Inject(AuthService) private auth:AuthService,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(NotificatorService) private notificator:NotificatorService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();
        this.title.setTitle('Sing In');
        this.form = new SignInForm;
    }

    signIn = () => {
        this.auth
            .signIn(this.form.email, this.form.password)
            .then((user:User) => {
                this.notificator.success('Hello, ' + user.name + '!');
                this.navigator.navigate(['/']);
            });
    };
}
