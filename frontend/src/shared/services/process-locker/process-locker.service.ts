import {Injectable} from '@angular/core';
import {LockerService} from '../locker';

@Injectable()
export class ProcessLockerService {
    constructor(protected locker:LockerService) {
    }

    lock(callback:any, args?:any):Promise<any> {
        return this.process(callback, args)
            .then((result:any) => this.onProcessSuccess(result))
            .catch((error:any) => this.onProcessError(error));
    }

    isLocked():boolean {
        return this.locker.isLocked();
    }

    protected process(callback:any, args?:any):Promise<any> {
        return new Promise((resolve, reject) => {
            if (!this.locker.isLocked()) {
                this.locker.lock();
                resolve(callback(...args));
            }
        });
    }

    protected onProcessSuccess(result:any):any {
        this.locker.unlock();
        return result;
    }

    protected onProcessError(error:any):any {
        this.locker.unlock();
        throw error;
    }
}
