import * as fs from 'fs';
import {Request, Response, Send} from 'express';
import {Provider, NgModuleFactory, NgModuleRef, PlatformRef, ApplicationRef, Type} from '@angular/core';
import {platformServer, platformDynamicServer, PlatformState, INITIAL_CONFIG} from '@angular/platform-server';

/**
 * These are the allowed options for the engine
 */
export interface NgSetupOptions {
    aot?: boolean;
    bootstrap: Type<{}> | NgModuleFactory<{}>;
    providers?: Provider[];
}

/**
 * This holds a cached version of each index used.
 */
const templateCache: { [key: string]: string } = {};

/**
 * This is an express engine for handling Angular Applications
 */
export function ngExpressEngine(setupOptions: NgSetupOptions) {

    setupOptions.providers = setupOptions.providers || [];

    return function (filePath, options: { req: Request, res?: Response }, callback: Send) {
        try {
            const moduleFactory = setupOptions.bootstrap;

            if (!moduleFactory) {
                throw new Error('You must pass in a NgModule or NgModuleFactory to be bootstrapped');
            }

            const extraProviders = setupOptions.providers.concat(
                getReqResProviders(options.req, options.res),
                [
                    {
                        provide: INITIAL_CONFIG,
                        useValue: {
                            document: getDocument(filePath),
                            url: options.req.originalUrl
                        }
                    }
                ]);

            const moduleRefPromise = setupOptions.aot ?
                platformServer(extraProviders).bootstrapModuleFactory(<NgModuleFactory<{}>>moduleFactory) :
                platformDynamicServer(extraProviders).bootstrapModule(<Type<{}>>moduleFactory);

            moduleRefPromise.then((moduleRef: NgModuleRef<{}>) => {
                handleModuleRef(moduleRef, callback);
            });

        } catch (e) {
            callback(e);
        }
    }
}

function getReqResProviders(req: Request, res: Response): Provider[] {
    const providers: Provider[] = [
        {
            provide: 'REQUEST',
            useValue: req
        }
    ];
    if (res) {
        providers.push({
            provide: 'RESPONSE',
            useValue: res
        });
    }
    return providers;
}

/**
 * Get the document at the file path
 */
function getDocument(filePath: string): string {
    return templateCache[filePath] = templateCache[filePath] || fs.readFileSync(filePath).toString();
}

/**
 * Handle the request with a given NgModuleRef
 */
function handleModuleRef(moduleRef: NgModuleRef<{}>, callback: Send) {
    const state = moduleRef.injector.get(PlatformState);
    const appRef = moduleRef.injector.get(ApplicationRef);

    appRef.isStable
        .filter((isStable: boolean) => isStable)
        .first()
        .subscribe((stable) => {
            const bootstrap = moduleRef.instance['ngOnBootstrap'];
            bootstrap && bootstrap();
            callback(null, state.renderToString());
            moduleRef.destroy();
        });
}
