import {Component, Inject} from '@angular/core';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {User} from '../../../shared/models';

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
            .then((user:User) => {
                this.navigatorService.navigate(['/signin']);
            })
            .catch((error:any) => {
                this.navigatorService.navigate(['/signin']);
            });
    }
}
