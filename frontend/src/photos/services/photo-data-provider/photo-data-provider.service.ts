import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../../../shared/services';

@Injectable()
export class PhotoDataProviderService {
    constructor(@Inject(ApiService) private api:ApiService) {
    }

    create = (attributes:any):Promise<any> => {
        return this.api.post('/published_photos', attributes).toPromise();
    };

    upload = (file:FileList):Promise<any> => {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/photos', form).toPromise();
    };

    updateById = (id:number, attributes:any):Promise<any> => {
        return this.api.put('/published_photos/' + id, attributes).toPromise();
    };

    uploadById = (id:number, file:FileList):Promise<any> => {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/photos/' + id, form).toPromise();
    };

    deleteById = (id:number):Promise<any> => {
        return this.api.delete('/published_photos/' + id).toPromise();
    };

    getById = (id:number):Promise<any> => {
        return this.api.get('/published_photos/' + id).toPromise();
    };

    getAll = (take:number, skip:number):Promise<any> => {
        return this.api.get('/published_photos', {
            params: {
                take: take,
                skip: skip,
            }
        }).toPromise();
    };

    getByTag = (take:number, skip:number, tag:string):Promise<any> => {
        return this.api.get('/published_photos', {
            params: {
                take: take,
                skip: skip,
                tag: tag,
            }
        }).toPromise();
    };

    getBySearchQuery = (take:number, skip:number, query:string):Promise<any> => {
        return this.api.get('/published_photos', {
            params: {
                take: take,
                skip: skip,
                query: query,
            }
        }).toPromise();
    };
}
