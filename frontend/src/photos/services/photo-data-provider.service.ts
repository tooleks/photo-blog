import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../../shared/services/api/api.service';

@Injectable()
export class PhotoDataProviderService {
    constructor(@Inject(ApiService) protected api:ApiService) {
    }

    create(attributes:any):Promise<any> {
        return this.api.post('/photo', attributes).toPromise();
    }

    upload(file:FileList):Promise<any> {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/uploaded_photo', form).toPromise();
    }

    updateById(id:number, attributes:any):Promise<any> {
        return this.api.put('/photo/' + id, attributes).toPromise();
    }

    uploadById(id:number, file:FileList):Promise<any> {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/uploaded_photo/' + id, form).toPromise();
    }

    deleteById(id:number):Promise<any> {
        return this.api.delete('/photo/' + id).toPromise();
    }

    getById(id:number):Promise<any> {
        return this.api.get('/photo/' + id).toPromise();
    }

    getAll(take:number, skip:number):Promise<any> {
        return this.api.get('/photo', {
            params: {
                take: take,
                skip: skip,
            }
        }).toPromise();
    }

    getByTag(take:number, skip:number, tag:string):Promise<any> {
        return this.api.get('/photo', {
            params: {
                take: take,
                skip: skip,
                tag: tag,
            }
        }).toPromise();
    }

    getBySearchQuery(take:number, skip:number, query:string):Promise<any> {
        return this.api.get('/photo', {
            params: {
                take: take,
                skip: skip,
                query: query,
            }
        }).toPromise();
    }
}
