import {Component, OnInit, AfterViewInit} from '@angular/core';
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
export class UnsubscriptionComponent implements OnInit, AfterViewInit {
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
    }

    ngAfterViewInit():void {
        this.route.params
            .map((params:any) => params['token'])
            .subscribe((token:string) => this.token = String(token));
    }

    unsubscribe = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.delete('/subscriptions/' + this.token))
            .then((data:any) => {
                this.notices.success('You have successfully unsubscribed from the website updates.');
                this.navigator.navigate(['/']);
                return data;
            })
            .catch((error:any) => {
                this.navigator.navigate(['/']);
                throw error;
            });
    };

    navigateToHomePage = ():void => {
        this.navigator.navigate(['/']);
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
