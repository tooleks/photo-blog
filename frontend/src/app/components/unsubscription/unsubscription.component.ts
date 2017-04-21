import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    ProcessLockerServiceProvider,
    ProcessLockerService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';

@Component({
    selector: 'unsubscription',
    templateUrl: 'unsubscription.component.html',
})
export class UnsubscriptionComponent implements OnInit {
    protected token:string = null;
    protected navigator:NavigatorService;
    protected processLocker:ProcessLockerService;

    constructor(protected route:ActivatedRoute,
                protected api:ApiService,
                protected title:TitleService,
                protected notices:NoticesService,
                navigatorProvider:NavigatorServiceProvider,
                processLockerServiceProvider:ProcessLockerServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.processLocker = processLockerServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.title.setTitle('Unsubscription');
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers = ():void => {
        this.route.params
            .map((params:any) => params['token'])
            .subscribe((token:string) => this.token = String(token));
    };

    unsubscribe = ():Promise<any> => {
        return this.processLocker
            .lock(() => this.api.delete(`/subscriptions/${this.token}`))
            .then(this.onUnsubscribeSuccess)
            .catch(this.onUnsubscribeError);
    };

    onUnsubscribeSuccess = (data:any):any => {
        this.notices.success('You have successfully unsubscribed from the website updates.');
        this.navigator.navigate(['/']);
        return data;
    };

    onUnsubscribeError = (error:any):any => {
        this.navigator.navigate(['/']);
        return Promise.reject(error);
    };

    navigateToHomePage = ():void => {
        this.navigator.navigate(['/']);
    };

    isProcessing = ():boolean => {
        return this.processLocker.isLocked();
    };
}
