import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';

@Injectable()
export class UserService {
    constructor(@Inject(ApiService) protected apiService:ApiService) {
    }

    getById(id:number) {
        return this.apiService.get('/user/' + id);
    }

    getAuthByCredentials(email:string, password:string) {
        return this.apiService.post('/token', {email: email, password: password});
    }
}
