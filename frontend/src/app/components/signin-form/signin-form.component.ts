import {Component, Inject} from '@angular/core';
import {SignInForm} from './signin-form';
import {AuthService} from '../../../shared/services/auth/auth.service';

@Component({
    selector: 'signin-form',
    template: require('./signin-form.component.html'),
})
export class SignInFormComponent {
    private form:SignInForm;

    constructor(@Inject(AuthService) private authService:AuthService) {
    }

    ngOnInit() {
        this.form = new SignInForm;
    }

    signIn() {
        this.authService.signIn(this.form.email, this.form.password);
    }
}
