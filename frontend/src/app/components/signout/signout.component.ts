import {Component, Inject} from '@angular/core';
import {AuthService} from '../../../shared/services/auth/auth.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {User} from '../../../shared/models';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent {
    private navigator:NavigatorService;

    constructor(@Inject(AuthService) private auth:AuthService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit() {
        this.auth
            .signOut()
            .then((user:User) => {
                this.navigator.navigate(['/signin']);
            })
            .catch((error:any) => {
                this.navigator.navigate(['/signin']);
            });
    }
}
