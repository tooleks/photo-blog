import {Component, Inject} from '@angular/core';
import {SignInForm} from './signin-form';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {NotificatorService} from '../../../shared/services/notificator';
import {UserModel} from '../../../shared/models/user-model';

@Component({
    selector: 'signin-form',
    template: require('./signin-form.component.html'),
})
export class SignInFormComponent {
    private navigatorService:NavigatorService;
    private form:SignInForm;

    constructor(@Inject(AuthService) private authService:AuthService,
                @Inject(NotificatorService) private notificatorService:NotificatorService,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
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
