import {Injectable} from '@angular/core';

@Injectable()
export class LockerService {
    private locked:boolean = false;

    lock = ():void => {
        this.locked = true;
    };

    unlock = ():void => {
        this.locked = false;
    };

    isLocked = ():boolean => this.locked === true;
}
