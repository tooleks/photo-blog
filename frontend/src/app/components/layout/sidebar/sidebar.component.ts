import {Component, Inject, HostListener, trigger, state, style, transition, animate} from '@angular/core';
import {AuthProviderService} from '../../../../shared/services';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
    styles: [require('./sidebar.component.css').toString()],
    animations: [
        trigger('slideInOut', [
            state('in', style({
                'transform': 'translate3d(0, 0, 0)',
                'box-shadow': '1px 0 3px rgba(0, 0, 0, 0.25)'
            })),
            state('out', style({
                'transform': 'translate3d(-220px, 0, 0)',
                'box-shadow': 'none'
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
        trigger('appearInOut', [
            state('in', style({
                'display': 'block',
                'opacity': '0.85'
            })),
            state('out', style({
                'display': 'none',
                'opacity': '0'
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
    ],
})
export class SideBarComponent {
    private animationState:string;

    constructor(@Inject(AuthProviderService) private authProvider:AuthProviderService) {
        window.innerWidth > 767 ? this.show() : this.hide();
    }

    @HostListener('window:resize', ['$event'])
    onWindowResize(event:any) {
        if (window.innerWidth > 767) {
            this.show();
        }
    }

    toggle = () => {
        if (!(window.innerWidth > 767)) {
            this.isVisible() ? this.hide() : this.show();
        }
    };

    show = () => {
        this.animationState = 'in';
    };

    hide = () => {
        this.animationState = 'out';
    };

    isVisible = () => {
        return this.animationState === 'in';
    };
}
