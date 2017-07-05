import 'zone.js/dist/zone-node';
import 'reflect-metadata';
import 'rxjs/Rx';

import * as express from 'express';
import {ServerAppModule} from './app/server-app.module';
import {ngExpressEngine} from './sys/ng-express-engine/express-engine';
import {ROUTES} from './routes';
import {enableProdMode} from '@angular/core';

if (process.env.NODE_ENV === 'production') {
    enableProdMode();
}

const app = express();

const frontendPort = process.env.DEFAULT_PORT || 8000;
const frontendHost = process.env.DEFAULT_HOST || `http://localhost:${frontendPort}`;
const backendHost = process.env.BACKEND_URL;

app.disable('x-powered-by');

app.engine('html', ngExpressEngine({
    bootstrap: ServerAppModule
}));

app.set('view engine', 'html');
app.set('views', 'src');

app.use('/', express.static('dist', {index: false}));

app.get('/sitemap.xml', (req, res) => res.redirect(301, `${backendHost}/sitemap.xml`));
app.get('/rss.xml', (req, res) => res.redirect(301, `${backendHost}/rss.xml`));

ROUTES.forEach(route => {
    app.get(route, (req, res) => {
        console.time(`GET: ${req.originalUrl}`);
        res.render('../dist/index', {
            req: req,
            res: res
        });
        console.timeEnd(`GET: ${req.originalUrl}`);
    });
});

app.get('*', (req, res) => res.status(404).redirect('/404'));

app.listen(frontendPort, () => {
    console.log(`Listening at ${frontendHost}`);
});
