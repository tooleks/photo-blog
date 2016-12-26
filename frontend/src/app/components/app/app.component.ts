import {Component, Inject} from '@angular/core';
import {AuthProviderService} from '../../../shared/services/auth';
import {EnvService} from '../../../shared/services/env';
import {TitleService} from '../../../shared/services/title/title.service';

import '../../../../public/app/css/style.css';
import '../../../../public/app/css/overrides.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
})
export class AppComponent {
    constructor(@Inject(EnvService) protected envService:EnvService,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(AuthProviderService) protected authProviderService:AuthProviderService) {
    }

    ngOnInit() {
        this.titleService.setTitle(this.envService.get('appName'));
    }
}
