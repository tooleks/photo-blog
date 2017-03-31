import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api';

@Injectable()
export class UserDataProviderService {
    constructor(@Inject(ApiService) private api:ApiService) {
    }

    getById = (id:number):Promise<any> => {
        return this.api
            .get('/users/' + id)
            .toPromise();
    };

    getAuthByCredentials = (email:string, password:string):Promise<any> => {
        return this.api
            .post('/token', {email: email, password: password})
            .toPromise();
    };
}
