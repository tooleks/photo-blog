import {Injectable} from '@angular/core';

@Injectable()
export class LockerService {
    protected locked:boolean = false;

    lock() {
        this.locked = true;
        return this.locked;
    }

    unlock() {
        this.locked = false;
        return this.locked;
    }

    isLocked() {
        return this.locked === true;
    }
}
