import {Injectable} from '@angular/core';
import {ApiService} from '../api';

@Injectable()
export class UserDataProviderService {
    constructor(protected api:ApiService) {
    }

    getById(id:number):Promise<any> {
        return this.api.get('/users/' + id);
    }

    getAuthByCredentials(email:string, password:string):Promise<any> {
        return this.api.post('/token', {email: email, password: password});
    }
}
