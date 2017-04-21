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

    handleResponse = (response:Response) => {
        switch (response.status) {
            case 0:
                this.handleUnknownError(response);
                break;
            case 401:
                this.handleUnauthorizedError(response);
                break;
            case 422:
                this.handleValidationErrors(response);
                break;
            default:
                this.handleHttpError(response);
                break;
        }
        throw new Error(this.extractResponseBody(response).message);
    };

    protected handleUnknownError = (response:Response):void => {
        const body = this.extractResponseBody(response);
        this.notices.error(body.message, 'Remote server connection error.');
    };

    protected handleUnauthorizedError = (response:Response):void => {
        this.navigator.navigate(['/signout']);
    };

    protected handleValidationErrors = (response:Response):void => {
        const body = this.extractResponseBody(response);
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:string) => this.notices.warning(message));
            }
        }
    };

    protected handleHttpError = (response:Response):void => {
        const body = this.extractResponseBody(response);
        this.notices.error(body.message, `${response.status} Error`);
    };

    protected extractResponseBody = (response:Response):any => {
        return response.json() || {};
    };
}
