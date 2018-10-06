import {kebabCase} from "lodash";
import * as directives from "./directives";
import * as services from "./services/factory";

const AppPlugin = {
    install(Vue) {
        // Register services factory.
        Vue.prototype.$services = services;
        // Register directives.
        Object.keys(directives).forEach((name) => Vue.directive(kebabCase(name), directives[name]));
    },
};

export default AppPlugin;
