import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
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
    protected lockProcess:LockProcessService;

    constructor(protected route:ActivatedRoute,
                protected api:ApiService,
                protected title:TitleService,
                protected notices:NoticesService,
                navigatorProvider:NavigatorServiceProvider,
                lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
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
        return this.lockProcess
            .process(() => this.api.delete(`/subscriptions/${this.token}`))
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
        return this.lockProcess.isProcessing();
    };
}
