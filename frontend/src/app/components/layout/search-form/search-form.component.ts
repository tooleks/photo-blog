import {Component} from '@angular/core';

@Component({
    selector: 'search-form',
    template: require('./search-form.component.html'),
})
export class SearchFormComponent {
    query:string;

    search() {
        // todo: implement search action
    }
}
