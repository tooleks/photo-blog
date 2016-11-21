import {Injectable, Inject} from '@angular/core';
import {NotificatorService} from '../notificator';
import {NavigatorService, NavigatorServiceProvider} from '../navigator';

@Injectable()
export class ApiErrorHandler {
    private navigatorService:NavigatorService;

    constructor(@Inject(NotificatorService) private notificator:NotificatorService,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = navigatorServiceProvider.getInstance();
    }

    handle(error:any) {
        let body = error.json();

        if (error.status === 0) {
            this.notificator.error(body.message, 'Unknown Error');
            return;
        }

        if (error.status === 401) {
            this.navigatorService.navigate(['/signout']);
            return;
        }

        if (error.status === 422) {
            this.handleValidationErrors(error, body);
            return;
        }

        this.handleHttpError(error, body);
    }

    private handleHttpError(error:any, body:any) {
        this.notificator.error(body.message, error.status + ' Error');
    }

    private handleValidationErrors(error:any, body:any) {
        body.errors = body.errors || {};
        for (var property in body.errors) {
            if (body.errors.hasOwnProperty(property)) {
                body.errors[property].forEach((message:string) => this.notificator.warning(message));
            }
        }
    }
}
