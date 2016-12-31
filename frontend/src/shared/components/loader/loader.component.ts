import {Component, Input} from '@angular/core';

@Component({
    selector: 'loader',
    template: require('./loader.component.html'),
})
export class LoaderComponent {
    @Input() loading:boolean;
}
