import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';
import {AuthUserProviderService} from "./auth-user-provider.service";
import {NotificatorService} from "../notificator/notificator.service";

@Injectable()
export class AuthService {
    constructor(@Inject(ApiService) private apiService:ApiService,
                @Inject(AuthUserProviderService) private authUserProviderService:AuthUserProviderService,
                @Inject(NotificatorService) private notificatorService:NotificatorService) {
    }

    signIn(email:string, password:string) {
        return new Promise((resolve) => {
            this.apiService
                .post('user/authenticate', {email: email, password: password})
                .subscribe((user:UserModel) => {
                    this.authUserProviderService.setUser(user);
                    this.notificatorService.success('Hello, ' + user.name + '!');
                    resolve(user);
                });
        });
    }

    signOut() {
        return new Promise((resolve) => {
            this.authUserProviderService.setUser(null);
            resolve();
        });
    }
}
