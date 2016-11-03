import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';
import {LocalStorageService} from '../local-storage/local-storage.service';

@Injectable()
export class AuthService {
    constructor(@Inject(ApiService) private apiService:ApiService,
                @Inject(LocalStorageService) private localStorageService:LocalStorageService) {
    }

    signIn(email:string, password:string) {
        this.apiService
            .post('user/authenticate', {email: email, password: password})
            .subscribe(this.onSignIn.bind(this));
    }

    private onSignIn(user:UserModel) {
        this.localStorageService.set('user', user);
    }
}
