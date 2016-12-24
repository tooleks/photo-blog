import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';

@Injectable()
export class UserService {
    constructor(@Inject(ApiService) protected apiService:ApiService) {
    }

    getById = (id:number) => this.apiService.get('/user/' + id);

    getAuthByCredentials = (email:string, password:string) => this.apiService.post('/token', {
        email: email,
        password: password
    });
}
