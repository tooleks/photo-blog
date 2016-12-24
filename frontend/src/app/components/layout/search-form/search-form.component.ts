import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NavigatorService, NavigatorServiceProvider} from '../../../../shared/services/navigator';
import 'rxjs/Rx';

@Component({
    selector: 'search-form',
    template: require('./search-form.component.html'),
})
export class SearchFormComponent {
    protected navigatorService:NavigatorService;
    protected query:string;

    constructor(@Inject(ActivatedRoute) protected route:ActivatedRoute,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((params) => params['query'])
            .subscribe((query:string) => {
                this.query = query;
            });
    }

    search = () => {
        if (this.query.length) {
            this.navigatorService.navigate(['photos/search'], {queryParams: {query: this.query}});
        } else {
            this.navigatorService.navigate(['']);
        }
    };
}
