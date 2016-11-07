import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NavigatorService, NavigatorServiceProvider} from '../../../../shared/services/navigator';
import 'rxjs/Rx';

@Component({
    selector: 'search-form',
    template: require('./search-form.component.html'),
})
export class SearchFormComponent {
    private navigatorService:NavigatorService;
    private query:string;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((params) => params['query'])
            .subscribe((query) => {
                this.query = query;
            });
    }

    search() {
        if (this.query.length) {
            this.navigatorService.navigate(['photos/search'], {queryParams: {query: this.query}});
        } else {
            this.navigatorService.navigate(['']);
        }
    }
}
