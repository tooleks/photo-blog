import {Component, Inject} from '@angular/core';
import {AuthUserProviderService} from '../../../../shared/services/auth/auth-user-provider.service';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
})
export class SideBarComponent {
    constructor(@Inject(AuthUserProviderService) protected authUserProviderService:AuthUserProviderService) {
    }
}
