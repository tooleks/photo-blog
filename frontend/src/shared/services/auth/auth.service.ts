import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';
import {AuthUserProviderService} from './auth-user-provider.service';

@Injectable()
export class AuthService {
    constructor(@Inject(ApiService) private apiService:ApiService,
                @Inject(AuthUserProviderService) private authUserProviderService:AuthUserProviderService) {
    }

    signIn(email:string, password:string) {
        return new Promise((resolve) => {
            this.apiService
                .post('user/authenticate', {email: email, password: password})
                .subscribe((user:UserModel) => {
                    this.authUserProviderService.setUser(user);
                    resolve(user);
                });
        });
    }

    signOut() {
        return new Promise((resolve, reject) => {
            if (this.authUserProviderService.isAuthenticatedUser()) {
                let user:UserModel = this.authUserProviderService.getUser();
                this.authUserProviderService.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    }
}
