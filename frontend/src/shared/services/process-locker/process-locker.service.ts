import {Injectable} from '@angular/core';
import {LockerService} from '../locker';

@Injectable()
export class ProcessLockerService {
    constructor(protected locker: LockerService) {
    }

    lock(callback, args?): Promise<any> {
        return this.process(callback, args)
            .then((result) => this.onProcessSuccess(result))
            .catch((error) => this.onProcessError(error));
    }

    isLocked(): boolean {
        return this.locker.isLocked();
    }

    protected process(callback, args?): Promise<any> {
        return new Promise((resolve, reject) => {
            if (!this.locker.isLocked()) {
                this.locker.lock();
                resolve(callback(...args));
            }
        });
    }

    protected onProcessSuccess(result) {
        this.locker.unlock();
        return result;
    }

    protected onProcessError(error) {
        this.locker.unlock();
        throw error;
    }
}
