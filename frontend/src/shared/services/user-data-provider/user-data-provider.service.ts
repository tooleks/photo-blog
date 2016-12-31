import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {Auth, User} from '../../models';

@Injectable()
export class UserDataProviderService {
    constructor(@Inject(ApiService) private apiService:ApiService) {
    }

    getById = (id:number):Promise<User> => {
        return this.apiService
            .get('/user/' + id)
            .toPromise();
    };

    getAuthByCredentials = (email:string, password:string):Promise<Auth> => {
        return this.apiService
            .post('/token', {email: email, password: password})
            .toPromise();
    };
}
