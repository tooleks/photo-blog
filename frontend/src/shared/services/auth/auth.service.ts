import {Injectable, Inject} from '@angular/core';
import {AuthProviderService} from './auth-provider.service';
import {UserService} from '../user/user.service';
import {AuthModel, UserModel} from '../../models';

@Injectable()
export class AuthService {
    constructor(@Inject(UserService) protected userService:UserService,
                @Inject(AuthProviderService) protected authProviderService:AuthProviderService) {
    }

    signIn = (email:string, password:string):Promise<UserModel> => {
        return this.userService.getAuthByCredentials(email, password)
            .then(this.authProviderService.setAuth)
            .then((auth:AuthModel):Promise<UserModel> => this.userService.getById(auth.user_id))
            .then(this.authProviderService.setUser);
    };

    signOut = ():Promise<UserModel> => {
        return new Promise((resolve, reject) => {
            if (this.authProviderService.isAuthenticated()) {
                let user:UserModel = this.authProviderService.getUser();
                this.authProviderService.setAuth(null);
                this.authProviderService.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    };
}
