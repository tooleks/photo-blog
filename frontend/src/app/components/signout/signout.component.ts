import {Component, Inject} from '@angular/core';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {UserModel} from '../../../shared/models/user-model';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent {
    private navigatorService:NavigatorService;

    constructor(@Inject(AuthService) private authService:AuthService,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
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
