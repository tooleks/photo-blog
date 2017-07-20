import {Injectable} from '@angular/core';
import {Router, NavigationEnd} from '@angular/router';
import {EnvironmentDetectorService} from '../../../detector';
declare let ga: Function;

@Injectable()
export class GoogleAnalyticsService {
    constructor(protected router: Router, protected environmentDetector: EnvironmentDetectorService, protected config) {
    }

    init(): void {
        if (this.environmentDetector.isBrowser()) {
            this.initScript();
            this.initRouterSubscribers();
        }
    }

    protected initScript(): void {
        let script, ref = document.getElementsByTagName('script')[0];
        script = document.createElement('script');
        script.async = true;
        script.text = `
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            
            ga('create', '${this.config['trackingId']}', 'auto');
        `;
        ref.parentNode.insertBefore(script, ref);
    }

    protected initRouterSubscribers(): void {
        this.router.events
            .filter((event) => event instanceof NavigationEnd)
            .subscribe((event: NavigationEnd) => {
                ga('set', 'page', event.urlAfterRedirects);
                ga('send', 'pageview');
            });
    }
}
