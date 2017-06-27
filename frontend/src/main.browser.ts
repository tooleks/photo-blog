import 'core-js/es6';
import 'core-js/es7/reflect';
import 'zone.js/dist/zone';
import 'reflect-metadata';

import 'rxjs/Observable';
import 'rxjs/add/operator/map';

import 'hammerjs';

import 'font-awesome/css/font-awesome.css';
import 'bootstrap/dist/css/bootstrap.css';

import '../assets/app/css/bootstrap.css';
import '../assets/app/css/overrides.css';

import {platformBrowserDynamic} from '@angular/platform-browser-dynamic';
import {BrowserAppModule} from './app/browser-app.module';
import {enableProdMode} from '@angular/core';

if (process.env.NODE_ENV === 'production') {
    enableProdMode();
}

platformBrowserDynamic().bootstrapModule(BrowserAppModule);
