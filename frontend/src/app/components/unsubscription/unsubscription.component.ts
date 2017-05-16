import {Component, OnInit, AfterViewInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import 'rxjs/add/operator/map';
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
export class UnsubscriptionComponent implements OnInit, AfterViewInit {
    protected token: string = null;
    protected navigator: NavigatorService;
    protected processLocker: ProcessLockerService;

    constructor(protected route: ActivatedRoute,
                protected api: ApiService,
                protected title: TitleService,
                protected notices: NoticesService,
                navigatorProvider: NavigatorServiceProvider,
                processLockerServiceProvider: ProcessLockerServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.processLocker = processLockerServiceProvider.getInstance();
    }

    ngOnInit(): void {
        this.title.setPageNameSegment('Unsubscription');
    }

    ngAfterViewInit(): void {
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers(): void {
        this.route.params
            .map((params) => params['token'])
            .map((token) => String(token))
            .subscribe((token: string) => this.token = token);
    }

    unsubscribe(): Promise<any> {
        return this.processLocker
            .lock(() => this.api.delete(`/subscriptions/${this.token}`))
            .then((data) => this.onUnsubscribeSuccess(data))
            .catch((error) => this.onUnsubscribeError(error));
    }

    onUnsubscribeSuccess(data) {
        this.notices.success('You have been successfully unsubscribed from the website updates.');
        this.navigator.navigate(['/']);
        return data;
    }

    onUnsubscribeError(error) {
        this.navigator.navigate(['/']);
        throw error;
    }

    navigateToHomePage(): void {
        this.navigator.navigate(['/']);
    }

    isProcessing(): boolean {
        return this.processLocker.isLocked();
    }
}
