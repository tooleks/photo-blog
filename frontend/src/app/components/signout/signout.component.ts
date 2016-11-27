import {Component, Inject} from '@angular/core';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {UserModel} from '../../../shared/models/user-model';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent {
    protected navigatorService:NavigatorService;

    constructor(@Inject(AuthService) protected authService:AuthService,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.authService
            .signOut()
            .then((user:UserModel) => {
                this.navigatorService.navigate(['/signin'])
            });
    }
}
