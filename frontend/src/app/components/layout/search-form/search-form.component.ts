import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NavigatorService, NavigatorServiceProvider} from '../../../../shared/services/navigator';
import 'rxjs/Rx';

@Component({
    selector: 'search-form',
    template: require('./search-form.component.html'),
})
export class SearchFormComponent {
    private query:string;
    private navigator:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((params) => params['query'])
            .subscribe((query:string) => this.query = query);
    }

    search = () => {
        if (this.query.length) {
            this.navigator.navigate(['photos/search'], {queryParams: {query: this.query}});
        } else {
            this.navigator.navigate(['']);
        }
    };
}
