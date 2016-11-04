import {Component, Inject} from '@angular/core';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {NotificatorService} from '../../../shared/services/notificator';
import {UserModel} from '../../../shared/models/user-model';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent {
    private navigatorService:NavigatorService;

    constructor(@Inject(AuthService) private authService:AuthService,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(NotificatorService) private notificatorService:NotificatorService) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.authService.signOut().then((user:UserModel) => {
            this.notificatorService.success('Bye, ' + user.name + '!');
            this.navigatorService.navigate(['/'])
        });
    }
}
