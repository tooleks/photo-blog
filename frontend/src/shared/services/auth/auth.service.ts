import {Injectable, Inject} from '@angular/core';
import {UserModel} from '../../models/user-model';
import {AuthUserProviderService} from './auth-user-provider.service';
import {AuthModel} from '../../models/auth-model';
import {UserService} from '../user/user.service';

@Injectable()
export class AuthService {
    constructor(@Inject(UserService) private userService:UserService,
                @Inject(AuthUserProviderService) private authUserProviderService:AuthUserProviderService) {
    }

    signIn(email:string, password:string) {
        return new Promise((resolve, reject) => {
            this.userService
                .getAuthByCredentials(email, password)
                .subscribe((auth:AuthModel) => {
                    this.authUserProviderService
                        .setAuth(auth)
                        .then((auth:AuthModel) => {
                            this.userService
                                .getById(auth.user_id)
                                .subscribe((user:UserModel) => {
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
