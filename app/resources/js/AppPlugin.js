import {kebabCase} from "lodash";
import * as filters from "./filters";
import * as directives from "./directives";
import * as services from "./services/factory";

const AppPlugin = {
    install(Vue) {
        // Register services factory.
        if (Vue.prototype.$services) throw new Error("Vue.prototype.$services property already reserved.");
        Vue.prototype.$services = services;
        // Register language support helper.
        if (Vue.prototype.$lang) throw new Error("Vue.prototype.$lang property already reserved.");
        Vue.prototype.$lang = services.getLang;
        // Register filters.
        Object.keys(filters).forEach((name) => Vue.filter(kebabCase(name), filters[name]));
        // Register directives.
        Object.keys(directives).forEach((name) => Vue.directive(kebabCase(name), directives[name]));
        // Register error handler.
        Vue.config.errorHandler = function errorHandler(error, vm, info) {
            services.getAlert().error(services.getLang("Whoops, something went wrong... Unexpected Error."));
            console.log({error, vm, info});
        };
    },
};

export default AppPlugin;
