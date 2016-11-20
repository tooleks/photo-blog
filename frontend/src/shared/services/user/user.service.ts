import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../api/api.service';
import {UserModel} from '../../models/user-model';

@Injectable()
export class UserService {
    constructor(@Inject(ApiService) private apiService:ApiService) {
    }

    getById(id:number) {
        return new Promise((resolve, reject) => {
            return this.apiService
                .get('/user/' + id)
                .subscribe((user:UserModel) => {
                    resolve(user);
                })
        });
    }
}
