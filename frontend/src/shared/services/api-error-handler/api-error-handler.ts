import {Injectable} from '@angular/core';
import {Response} from '@angular/http';
import {NavigatorService, NavigatorServiceProvider} from '../navigator';
import {NoticesService} from '../../../lib';

@Injectable()
export class ApiErrorHandler {
    protected navigator:NavigatorService;

    constructor(protected notices:NoticesService, protected navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    onResponseError = (response:Response):Promise<any> => {
        switch (response.status) {
            case 0:
                return this.onResponseUnknownError(response);
            case 401:
                return this.onResponseUnauthorizedError(response);
            case 422:
                return this.onResponseValidationError(response);
            default:
                return this.onResponseHttpError(response);
        }
    };

    protected onResponseUnknownError = (response:Response):Promise<any> => {
        const body = response.json();
        this.notices.error(body.message, 'Remote server connection error. Try again later.');
        return Promise.reject(new Error(body.message));
    };

    protected onResponseUnauthorizedError = (response:Response):Promise<any> => {
        const body = response.json();
        this.navigator.navigate(['/signout']);
        return Promise.reject(new Error(body.message));
    };

    protected onResponseValidationError = (response:Response):Promise<any> => {
        const body = response.json();
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:string) => this.notices.warning(message));
            }
        }
        return Promise.reject(new Error(body.message));
    };

    protected onResponseHttpError = (response:Response):Promise<any> => {
        const body = response.json();
        this.notices.error(body.message, `${response.status} Error`);
        return Promise.reject(new Error(body.message));
    };
}
