import {Component, OnInit} from '@angular/core';
import {MetaTagsService} from '../../../core';
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    ProcessLockerServiceProvider,
    ProcessLockerService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';
import {ContactMe as Model} from './models';

@Component({
    selector: 'contact-me-form',
    templateUrl: 'contact-me-form.component.html',
})
export class ContactMeFormComponent implements OnInit {
    model: Model;
    protected navigator: NavigatorService;
    protected processLocker: ProcessLockerService;

    constructor(protected api: ApiService,
                protected title: TitleService,
                protected metaTags: MetaTagsService,
                protected notices: NoticesService,
                navigatorProvider: NavigatorServiceProvider,
                processLockerServiceProvider: ProcessLockerServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.processLocker = processLockerServiceProvider.getInstance();
    }

    ngOnInit(): void {
        this.model = new Model;
        this.title.setPageNameSegment('Contact Me');
        this.metaTags.setTitle(this.title.getPageNameSegment());
    }

    contactMe(): Promise<any> {
        return this.processLocker
            .lock(() => this.api.post('/contact_messages', this.model))
            .then((data) => this.onContactMeSuccess(data));
    }

    onContactMeSuccess(data) {
        this.notices.success('Your message has been successfully sent.');
        this.navigator.navigate(['/photos']);
        return data;
    }

    isProcessing(): boolean {
        return this.processLocker.isLocked();
    }
}
