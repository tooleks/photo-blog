import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';

@Component({
    selector: 'unsubscription',
    templateUrl: 'unsubscription.component.html',
})
export class UnsubscriptionComponent {
    private token:string = null;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(ApiService) private api:ApiService,
                @Inject(TitleService) private title:TitleService,
                @Inject(NoticesService) private notices:NoticesService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngAfterViewInit() {
        this.route.params
            .map((params:any) => params['token'])
            .subscribe((token:string) => this.token = String(token));
    }

    ngOnInit() {
        this.title.setTitle('Unsubscription');
    }

    unsubscribe = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.delete('/subscriptions/' + this.token).toPromise())
            .then((data:any) => {
                this.notices.success('You have successfully unsubscribed from the website updates.');
                this.navigator.navigate(['/']);
                return data;
            })
            .catch((error:any) => {
                this.navigator.navigate(['/']);
            });
    };

    navigateToHomePage = ():void => {
        this.navigator.navigate(['/']);
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
