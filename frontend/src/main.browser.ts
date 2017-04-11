import 'zone.js/dist/zone';
import 'reflect-metadata';
import 'rxjs/Observable';
import 'rxjs/add/operator/map';

import {platformBrowserDynamic} from '@angular/platform-browser-dynamic';
import {BrowserAppModule} from './app/browser-app.module';
import {enableProdMode} from '@angular/core';

if (process.env.NODE_ENV === 'production') {
    enableProdMode();
}

platformBrowserDynamic().bootstrapModule(BrowserAppModule);
