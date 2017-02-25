import {Injectable} from '@angular/core';

@Injectable()
export class CallbackHandlerService {
    resolveCallback = (callback:any, args?:any[]) => {
        return typeof callback === 'function'
            ? Promise.resolve(callback(...args))
            : Promise.reject(new Error('Callback must be a function.'));
    };
}
