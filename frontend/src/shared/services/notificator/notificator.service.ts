import {Injectable, Inject} from '@angular/core';
import {ToastsManager} from 'ng2-toastr/ng2-toastr';

@Injectable()
export class NotificatorService {
    constructor(@Inject(ToastsManager) private toastsManager:ToastsManager) {
    }

    error(message:string, title?:string) {
        this.toastsManager.error(message, title);
    }

    warning(message:string, title?:string) {
        this.toastsManager.warning(message, title);
    }

    success(message:string, title?:string) {
        this.toastsManager.success(message, title);
    }

    info(message:string, title?:string) {
        this.toastsManager.info(message, title);
    }
}
