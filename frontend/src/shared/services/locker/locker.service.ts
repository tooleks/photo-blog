import {Injectable} from '@angular/core';

@Injectable()
export class LockerService {
    protected locked:boolean = false;

    lock = ():void => {
        this.locked = true;
    };

    unlock = ():void => {
        this.locked = false;
    };

    isLocked = ():boolean => {
        return this.locked === true;
    };
}
