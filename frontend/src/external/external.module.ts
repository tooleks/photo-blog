import {NgModule, ModuleWithProviders} from '@angular/core';
import {env} from '../../env';

const config = {
    googleAnalytics: {
        trackingId: env.googleAnalyticsTrackingId,
    }
};

@NgModule()
export class ExternalModule {
    constructor() {
        // Workaround as ExternalModule.initWithProviders() call not working in @NgModule imports section.
        ExternalModule.initWithProviders(config);
    }

    static initWithProviders(config:any):ModuleWithProviders {
        if (typeof (window) !== 'undefined') {
            let loadProvidersScripts:any = {
                googleAnalytics: (config:any) => {
                    let d = document, googleAnalyticsScript:any, ref = d.getElementsByTagName('script')[0];
                    googleAnalyticsScript = d.createElement('script');
                    googleAnalyticsScript.async = true;
                    googleAnalyticsScript.text = `
                        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
                        
                        ga('create', '${config['trackingId']}', 'auto');
                    `;
                    ref.parentNode.insertBefore(googleAnalyticsScript, ref);
                },
            };

            Object.keys(config).forEach((provider:any) => loadProvidersScripts[provider](config[provider]));
        }

        return {
            ngModule: ExternalModule,
        };
    }
}
