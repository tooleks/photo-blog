export default class MapperService {
    constructor() {
        this.resolvers = {};
    }

    getResolvers() {
        return this.resolvers;
    }

    registerResolver(from, to, resolver) {
        if (typeof this.resolvers[from] === "undefined") {
            this.resolvers[from] = {};
        }
        this.resolvers[from][to] = resolver;
    }

    removeResolver(from, to) {
        delete this.resolvers[from][to];
    }

    assertResolver(from, to) {
        if (typeof this.getResolvers()[from] === "undefined") {
            throw new Error(`Resolver for "${from}" not found.`);
        }
        if (typeof this.getResolvers()[from] !== "object") {
            throw new Error(`Invalid resolver type for "${from}".`);
        }
        if (typeof this.getResolvers()[from][to] === "undefined") {
            throw new Error(`Resolver for "${to}" not found.`);
        }
        if (typeof this.getResolvers()[from][to] !== "function") {
            throw new Error(`Invalid resolver type for "${to}".`);
        }
    }

    existsResolver(from, to) {
        try {
            this.assertResolver(from, to);
            return true;
        } catch (error) {
            return false;
        }
    }

    map(value, from, to) {
        this.assertResolver(from, to);
        const resolver = this.getResolvers()[from][to];
        return resolver(value);
    }
}
