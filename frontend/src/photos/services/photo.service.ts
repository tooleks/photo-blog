import {Injectable, Inject} from '@angular/core';
import {ApiService} from '../../shared/services/api/api.service';

@Injectable()
export class PhotoService {
    constructor(@Inject(ApiService) protected apiService:ApiService) {
    }

    create(attributes:any) {
        return this.apiService.post('/photo', attributes).toPromise();
    }

    upload(file:FileList) {
        let form = new FormData;
        form.append('file', file);
        return this.apiService.post('/uploaded_photo', form).toPromise();
    }

    updateById(id:number, attributes:any) {
        return this.apiService.put('/photo/' + id, attributes).toPromise();
    }

    uploadById(id:number, file:FileList) {
        let form = new FormData;
        form.append('file', file);
        return this.apiService.post('/uploaded_photo/' + id, form).toPromise();
    }

    deleteById(id:number) {
        return this.apiService.delete('/photo/' + id).toPromise();
    }

    getById(id:number) {
        return this.apiService.get('/photo/' + id).toPromise();
    }

    getAll(take:number, skip:number) {
        return this.apiService.get('/photo', {
            params: {
                take: take,
                skip: skip,
            }
        }).toPromise();
    }

    getByTag(take:number, skip:number, tag:string) {
        return this.apiService.get('/photo', {
            params: {
                take: take,
                skip: skip,
                tag: tag,
            }
        }).toPromise();
    }

    getBySearchQuery(take:number, skip:number, query:string) {
        return this.apiService.get('/photo', {
            params: {
                take: take,
                skip: skip,
                query: query,
            }
        }).toPromise();
    }
}
