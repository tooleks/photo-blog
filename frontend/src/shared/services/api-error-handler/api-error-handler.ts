import {Injectable, Inject} from '@angular/core';
import {Response} from '@angular/http';
import {Observable} from 'rxjs/Observable';
import {ApiErrorHandler as BaseApiErrorHandler} from '../api/api-error-handler';
import {NotificatorService} from '../notificator';
import {NavigatorService, NavigatorServiceProvider} from '../navigator';

@Injectable()
export class ApiErrorHandler extends BaseApiErrorHandler {
    protected navigatorService:NavigatorService;

    constructor(@Inject(NotificatorService) protected notificator:NotificatorService,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        super();
        this.navigatorService = navigatorServiceProvider.getInstance();
    }

    handleResponse = (response:Response) => {
        let body = response.json();

        switch (response.status) {
            case 0:
                this.handleHttpError(response, body);
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

        return Observable.throw(new Error(body.message));
    };

    protected handleUnknownError = (response:any, body:any) => {
        this.notificator.error(body.message, 'Unknown Error');
    };

    protected handleUnauthorizedError = (response:any, body:any) => {
        this.navigatorService.navigate(['/signout']);
    };

    protected handleHttpError = (response:any, body:any) => {
        this.notificator.error(body.message, response.status + ' Error');
    };

    protected handleValidationErrors = (response:any, body:any) => {
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:string) => this.notificator.warning(message));
            }
        }
    };
}
