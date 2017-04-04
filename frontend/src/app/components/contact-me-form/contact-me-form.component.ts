import {Component, Inject} from '@angular/core';
import {ContactMeForm} from './models';
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
    selector: 'contact-me-form',
    templateUrl: 'contact-me-form.component.html',
})
export class ContactMeFormComponent {
    private form:ContactMeForm;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ApiService) private api:ApiService,
                @Inject(TitleService) private title:TitleService,
                @Inject(NoticesService) private notices:NoticesService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.title.setTitle('Contact Me');
        this.form = new ContactMeForm;
    }

    send = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/contact_messages', this.form).toPromise())
            .then((data:any) => {
                this.notices.success('Your message successfully sent.');
                this.navigator.navigate(['/']);
                return data;
            });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
