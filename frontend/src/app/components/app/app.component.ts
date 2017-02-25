import {Component, Inject} from '@angular/core';
import {EnvService, TitleService, AuthProviderService} from '../../../shared/services';

import '../../../../public/app/css/styles.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }

    private getCurrentYear = () => {
        return (new Date).getFullYear();
    };
}
