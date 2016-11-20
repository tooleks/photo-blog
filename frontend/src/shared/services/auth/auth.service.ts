import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';
import {AuthUserProviderService} from './auth-user-provider.service';
import {AuthModel} from '../../models/auth-model';
import {UserService} from '../user/user.service';

@Injectable()
export class AuthService {
    constructor(@Inject(ApiService) private apiService:ApiService,
                @Inject(UserService) private userService:UserService,
                @Inject(AuthUserProviderService) private authUserProviderService:AuthUserProviderService) {
    }

    signIn(email:string, password:string) {
        return new Promise((resolve, reject) => {
            this.apiService
                .post('/token', {email: email, password: password})
                .subscribe((auth:AuthModel) => {
                    this.authUserProviderService
                        .setAuth(auth)
                        .then((auth:AuthModel) => {
                            this.userService
                                .getById(auth.user_id)
                                .then((user:UserModel) => {
                                    this.authUserProviderService
                                        .setUser(user)
                                        .then((user:UserModel) => {
                                            resolve(user);
                                        });
                                });
                        });
                });
        });
    }

    signOut() {
        return new Promise((resolve, reject) => {
            if (this.authUserProviderService.hasAuth()) {
                let user:UserModel = this.authUserProviderService.getUser();
                this.authUserProviderService.setAuth(null);
                this.authUserProviderService.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    }
}
