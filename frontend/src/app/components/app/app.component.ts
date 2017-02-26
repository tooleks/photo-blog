import {Component, Inject, ViewChild} from '@angular/core';
import {EnvService, TitleService, AuthProviderService, ScrollFreezerService} from '../../../shared/services';

import '../../../../public/app/css/styles.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    @ViewChild('sideBarComponent') sideBarComponent:any;

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }

    private onShowSideBar = (event) => {
        if (event.isSmallDevice) {
            this.scrollFreezer.freezeBackgroundScroll();
        }
    };

    private onHideSideBar = (event) => {
        this.scrollFreezer.unfreezeBackgroundScroll();
    };

    private onToggleSideBar = (event) => {
        if (event.isVisible) {
            this.scrollFreezer.freezeBackgroundScroll();
        } else {
            this.scrollFreezer.unfreezeBackgroundScroll();
        }
    };

    private getCurrentYear = () => {
        return (new Date).getFullYear();
    };
}
