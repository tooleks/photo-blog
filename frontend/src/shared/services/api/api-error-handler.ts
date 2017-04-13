import {Response} from '@angular/http';
import {Observable} from 'rxjs/Observable';

export class ApiErrorHandler {
    handleResponse = (response:Response) => {
        return Observable.throw(new Error(response.toString()));
    };
}
