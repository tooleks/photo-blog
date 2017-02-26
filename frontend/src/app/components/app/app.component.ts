import {Component, Inject, ViewChild} from '@angular/core';
import {EnvService, TitleService, AuthProviderService} from '../../../shared/services';

import '../../../../public/app/css/styles.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    private pageOverflow = '';

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }

    private showSideBar = () => {
        this.sideBarComponent.show();
        this.freezePageScroll();
    };

    private hideSideBar = () => {
        this.sideBarComponent.hide();
        this.unfreezePageScroll();
    };
    
    private toggleSideBar = () => {
        this.sideBarComponent.isVisible() ? this.hideSideBar() : this.showSideBar();
    };

    private freezePageScroll = () => {
        let top = window.scrollY;
        this.pageOverflow = 'hidden';
        window.onscroll = () => window.scroll(0, top);
    };

    private unfreezePageScroll = () => {
        this.pageOverflow = '';
        window.onscroll = null;
    };

    private getCurrentYear = () => {
        return (new Date).getFullYear();
    };
}
