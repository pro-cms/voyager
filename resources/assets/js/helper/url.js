Vue.mixin({
    methods: {
        route: function () {
            var args = Array.prototype.slice.call(arguments);
            var name = args.shift();
            if (this.$store.routes[name] === undefined) {
                console.warn('Route "'+name+'" does not exist!');
            } else {
                return this.$store.routes[name]
                    .split('/')
                    .map(s => s[0] == '{' ? args.shift() : s)
                    .join('/');
            }
        },
    }
});