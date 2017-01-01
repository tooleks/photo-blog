import {Component, Inject} from '@angular/core';
import {AuthProviderService} from '../../../shared/services/auth';
import {EnvService} from '../../../shared/services/env';
import {TitleService} from '../../../shared/services/title';

import '../../../../public/app/css/style.css';
import '../../../../public/app/css/overrides.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
})
export class AppComponent {
    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    ngOnInit() {
        this.title.setTitle(this.env.get('appName'));
    }
}
