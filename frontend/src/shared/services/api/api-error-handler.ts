import {Injectable, Inject} from '@angular/core';
import {NotificatorService} from '../notificator/notificator.service';

@Injectable()
export class ApiErrorHandler {
    constructor(@Inject(NotificatorService) private notificator:NotificatorService) {
    }

    handle(error:any) {
        let body = error.json();
        error.status === 422 ? this.handleValidationErrors(error, body) : this.handleHttpError(error, body);
    }

    private handleHttpError(error:any, body:any) {
        this.notificator.error(body.message, error.status + ' Error');
    }

    private handleValidationErrors(error:any, body:any) {
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:any) => this.notificator.warning(message));
            }
        }
    }
}
