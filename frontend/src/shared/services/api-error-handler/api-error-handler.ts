import {Injectable} from '@angular/core';
import {Response} from '@angular/http';
import {NavigatorService, NavigatorServiceProvider} from '../navigator';
import {NoticesService} from '../../../lib';

@Injectable()
export class ApiErrorHandler {
    protected navigator: NavigatorService;

    constructor(protected notices: NoticesService, protected navigatorProvider: NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    onResponseError(response: Response): Promise<any> {
        const body = response.json();
        switch (response.status) {
            case 0: {
                this.onResponseUnknownError(response);
                break;
            }
            case 401: {
                this.onResponseUnauthorizedError(response);
                break;
            }
            case 422: {
                this.onResponseValidationError(response);
                break;
            }
            default: {
                this.onResponseHttpError(response);
                break;
            }
        }
        return Promise.reject(new Error(body.message));
    }

    protected onResponseUnknownError(response: Response) {
        const body = response.json();
        this.notices.error(body.message, 'Remote server connection error. Try again later.');
    }

    protected onResponseUnauthorizedError(response: Response) {
        this.onResponseHttpError(response);
        this.navigator.navigate(['/signout']);
    }

    protected onResponseValidationError(response: Response) {
        const body = response.json();
        body.errors = body.errors || {};
        for (let attribute in body.errors) {
            if (body.errors.hasOwnProperty(attribute)) {
                body.errors[attribute].forEach((message: string) => this.notices.warning(message));
            }
        }
    }

    protected onResponseHttpError(response: Response) {
        const body = response.json();
        this.notices.error(body.message, `${response.status} HTTP Error`);
    }
}
