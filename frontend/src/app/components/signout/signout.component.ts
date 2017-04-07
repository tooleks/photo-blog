import {Component, OnInit} from '@angular/core';
import {AuthService, NavigatorServiceProvider, NavigatorService} from '../../../shared/services';

@Component({
    selector: 'signout',
    template: '',
})
export class SignOutComponent implements OnInit {
    private navigator:NavigatorService;

    constructor(private auth:AuthService, navigatorProvider:NavigatorServiceProvider) {
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
