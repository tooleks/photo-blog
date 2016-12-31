import {Injectable, Inject} from '@angular/core';
import {AuthProviderService} from './auth-provider.service';
import {UserDataProviderService} from '../user-data-provider';
import {Auth, User} from '../../models';

@Injectable()
export class AuthService {
    constructor(@Inject(UserDataProviderService) private userDataProvider:UserDataProviderService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    signIn = (email:string, password:string):Promise<User> => {
        return this.userDataProvider
            .getAuthByCredentials(email, password)
            .then(this.authProvider.setAuth)
            .then((auth:Auth):Promise<User> => this.userDataProvider.getById(auth.user_id))
            .then(this.authProvider.setUser);
    };

    signOut = ():Promise<User> => {
        return new Promise((resolve, reject) => {
            if (this.authProvider.isAuthenticated()) {
                let user:User = this.authProvider.getUser();
                this.authProvider.setAuth(null);
                this.authProvider.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    };
}
