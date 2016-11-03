import {Injectable} from '@angular/core';

@Injectable()
export class LockerService {
    private locked:boolean = false;

    lock() {
        this.locked = true;
    }

    unlock() {
        this.locked = false;
    }

    isLocked() {
        return this.locked === true;
    }
}
