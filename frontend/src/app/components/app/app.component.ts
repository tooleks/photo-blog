import {Component, Inject, ViewChild} from '@angular/core';
import {AuthProviderService} from '../../../shared/services/auth';
import {EnvService} from '../../../shared/services/env';
import {TitleService} from '../../../shared/services/title';
import {SideBarComponent} from '../layout/sidebar/sidebar.component';

import '../../../../public/app/css/styles.css';
import '../../../../public/app/css/overrides.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    @ViewChild('sideBar') private sideBarComponent:SideBarComponent;

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }
}
