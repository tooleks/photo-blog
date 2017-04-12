import {Injectable} from '@angular/core';
import {UserDataProviderService} from '../user-data-provider';
import {AuthProviderService} from './auth-provider.service';

@Injectable()
export class AuthService {
    constructor(protected userDataProvider:UserDataProviderService, protected authProvider:AuthProviderService) {
    }

    signIn = (email:string, password:string):Promise<any> => {
        return this.userDataProvider
            .getAuthByCredentials(email, password)
            .then(this.authProvider.setAuth)
            .then((auth:any):Promise<any> => this.userDataProvider.getById(auth.user_id))
            .then(this.authProvider.setUser);
    };

    signOut = ():Promise<any> => {
        return new Promise((resolve, reject) => {
            if (this.authProvider.isAuthenticated()) {
                let user:any = this.authProvider.getUser();
                this.authProvider.setAuth(null);
                this.authProvider.setUser(null);
                resolve(user);
            } else {
                reject();
            }
        });
    };
}
