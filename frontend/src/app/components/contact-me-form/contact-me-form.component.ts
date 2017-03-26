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
import {ContactMeForm} from './contact-me-form';

@Component({
    selector: 'contact-me-form',
    templateUrl: './contact-me-form.component.html',
})
export class ContactMeFormComponent {
    private form:ContactMeForm;
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
        this.title.setTitle('Contact Me');
        this.form = new ContactMeForm;
    }

    send = () => {
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
