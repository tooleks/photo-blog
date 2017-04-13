import {Component, OnInit} from '@angular/core';
import {AuthService, NavigatorServiceProvider, NavigatorService} from '../../../shared';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent implements OnInit {
    protected navigator:NavigatorService;

    constructor(protected auth:AuthService, navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit():void {
        this.auth
            .signOut()
            .then((user:any) => {
                this.navigator.navigate(['/signin']);
            })
            .catch((error:any) => {
                this.navigator.navigate(['/signin']);
                throw error;
            });
    }
}
