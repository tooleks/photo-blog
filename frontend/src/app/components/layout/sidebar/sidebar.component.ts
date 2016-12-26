import {Component, Inject} from '@angular/core';
import {AuthProviderService} from '../../../../shared/services/auth';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
})
export class SideBarComponent {
    constructor(@Inject(AuthProviderService) protected authProviderService:AuthProviderService) {
    }
}
