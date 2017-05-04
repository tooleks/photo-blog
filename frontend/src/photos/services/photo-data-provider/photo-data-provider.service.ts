import {Injectable} from '@angular/core';
import {ApiService} from '../../../shared';

@Injectable()
export class PhotoDataProviderService {
    constructor(protected api:ApiService) {
    }

    create(attributes:any):Promise<any> {
        return this.api.post('/published_photos', attributes);
    }

    upload(file:any):Promise<any> {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/photos', form);
    }

    updateById(id:number, attributes:any):Promise<any> {
        return this.api.put('/published_photos/' + id, attributes);
    }

    uploadById(id:number, file:any):Promise<any> {
        let form = new FormData;
        form.append('file', file);
        return this.api.post('/photos/' + id, form);
    }

    deleteById(id:number):Promise<any> {
        return this.api.delete('/published_photos/' + id);
    }

    getById(id:number):Promise<any> {
        return this.api.get('/published_photos/' + id);
    }

    getAll(page:number, perPage:number):Promise<any> {
        return this.api.get('/published_photos', {
            params: {
                page: page,
                per_page: perPage,
            }
        });
    }

    getByTag(page:number, perPage:number, tag:string):Promise<any> {
        return this.api.get('/published_photos', {
            params: {
                page: page,
                per_page: perPage,
                tag: tag,
            }
        });
    }

    getBySearchPhrase(page:number, perPage:number, searchPhrase:string):Promise<any> {
        return this.api.get('/published_photos', {
            params: {
                page: page,
                per_page: perPage,
                search_phrase: searchPhrase,
            }
        });
    }
}
