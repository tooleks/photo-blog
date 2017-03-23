import {Component, Inject} from '@angular/core';
import {
    ApiService,
    TitleService,
    ScrollerService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';
import {SubscriptionForm} from './subscription-form';

@Component({
    selector: 'subscription',
    template: require('./subscription.component.html'),
})
export class SubscriptionComponent {
    private form:SubscriptionForm;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ApiService) private api:ApiService,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(NoticesService) private notices:NoticesService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();
        this.title.setTitle('Subscription');
        this.form = new SubscriptionForm;
    }

    subscribe = () => {
        return this.lockProcess
            .process(() => this.api.post('/subscriptions', this.form).toPromise())
            .then((data:any) => {
                this.notices.success('You have successfully subscribed to the website updates.');
                this.navigator.navigate(['/']);
                return data;
            });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}