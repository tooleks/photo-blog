import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';

@Injectable()
export class UserService {
    constructor(@Inject(ApiService) protected apiService:ApiService) {
    }

    getById = (id:number) => {
        return this.apiService
            .get('/user/' + id)
            .toPromise();
    };

    getAuthByCredentials = (email:string, password:string) => {
        return this.apiService
            .post('/token', {
                email: email,
                password: password
            })
            .toPromise();
    };
}
