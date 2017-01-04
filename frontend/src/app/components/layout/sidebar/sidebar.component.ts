import {Component, Inject, HostListener, trigger, state, style, transition, animate} from '@angular/core';
import {AuthProviderService} from '../../../../shared/services/auth';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
    styles: [require('./sidebar.component.css').toString()],
    animations: [
        trigger('slideInOut', [
            state('in', style({
                transform: 'translate3d(0, 0, 0)'
            })),
            state('out', style({
                transform: 'translate3d(-230px, 0, 0)'
            })),
            transition('in => out', animate('300ms ease-in-out')),
            transition('out => in', animate('300ms ease-in-out'))
        ]),
    ],
})
export class SideBarComponent {
    private windowWidth:number = 768;
    private state:string = 'in';

    constructor(@Inject(AuthProviderService) private authProvider:AuthProviderService) {
    }

    @HostListener('window:resize', ['$event'])
    onWindowResize(event:any) {
        this.windowWidth = event.target.innerWidth;
        this.state = this.windowWidth <= 767 ? 'out' : 'in';
    }

    toggle = () => {
        if (this.windowWidth <= 767) {
            this.state = this.state === 'in' ? 'out' : 'in';
        }
    };
}
