import {Injectable} from '@angular/core';
import {Response} from '@angular/http';
import {ApiErrorHandler as BaseApiErrorHandler} from '../api';
import {NavigatorService, NavigatorServiceProvider} from '../navigator';
import {NoticesService} from '../../../notices';

@Injectable()
export class ApiErrorHandler extends BaseApiErrorHandler {
    protected navigator:NavigatorService;

    constructor(protected notices:NoticesService,
                protected navigatorProvider:NavigatorServiceProvider) {
        super();
        this.navigator = navigatorProvider.getInstance();
    }

    handleResponse = (response:Response) => {
        let body = response.json();
        switch (response.status) {
            case 0:
                this.handleUnknownError(response, body);
                break;
            case 401:
                this.handleUnauthorizedError(response, body);
                break;
            case 422:
                this.handleValidationErrors(response, body);
                break;
            default:
                this.handleHttpError(response, body);
                break;
        }
        throw new Error(body.message);
    };

    protected handleUnknownError = (response:any, body:any):void => {
        this.notices.error(body.message, 'Remote server connection error.');
    };

    protected handleUnauthorizedError = (response:any, body:any):void => {
        this.navigator.navigate(['/signout']);
    };

    protected handleHttpError = (response:any, body:any):void => {
        this.notices.error(body.message, response.status + ' Error');
    };

    protected handleValidationErrors = (response:any, body:any):void => {
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:string) => this.notices.warning(message));
            }
        }
    };
}
