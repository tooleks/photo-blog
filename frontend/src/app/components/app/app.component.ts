import {Component, Inject} from '@angular/core';
import {AuthUserProviderService} from '../../../shared/services/auth';

import '../../../../public/app/css/style.css';
import '../../../../public/app/css/overrides.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
})
export class AppComponent {
    constructor(@Inject(AuthUserProviderService) private authUserProviderService:AuthUserProviderService) {
    }
}
