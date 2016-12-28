import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {AuthModel, UserModel} from '../../models';

@Injectable()
export class UserService {
    constructor(@Inject(ApiService) protected apiService:ApiService) {
    }

    getById = (id:number):Promise<UserModel> => {
        return this.apiService
            .get('/user/' + id)
            .toPromise();
    };

    getAuthByCredentials = (email:string, password:string):Promise<AuthModel> => {
        return this.apiService
            .post('/token', {email: email, password: password})
            .toPromise();
    };
}
