import {Component, Inject} from '@angular/core';
import {SignInForm} from './signin-form';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {TitleService} from '../../../shared/services/title/title.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {NotificatorService} from '../../../shared/services/notificator';
import {UserModel} from '../../../shared/models/user-model';

@Component({
    selector: 'signin-form',
    template: require('./signin-form.component.html'),
})
export class SignInFormComponent {
    protected navigatorService:NavigatorService;
    protected form:SignInForm;

    constructor(@Inject(AuthService) protected authService:AuthService,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(NotificatorService) protected notificatorService:NotificatorService,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.titleService.setTitle('Sing In');
        this.form = new SignInForm;
    }

    signIn() {
        this.authService
            .signIn(this.form.email, this.form.password)
            .then((user:UserModel) => {
                this.notificatorService.success('Hello, ' + user.name + '!');
                this.navigatorService.navigate(['/']);
            });
    }
}
