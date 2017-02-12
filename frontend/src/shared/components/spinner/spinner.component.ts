import {Component, Input} from '@angular/core';

@Component({
    selector: 'spinner',
    template: require('./spinner.component.html'),
})
export class SpinnerComponent {
    @Input() visible:boolean = false;
}
