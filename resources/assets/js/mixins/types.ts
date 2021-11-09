export default {
    methods: {
        isArray: function (input: any) {
            return (input && typeof input === 'object' && input instanceof Array);
        },
        isObject: function (input: any) {
            return (input && typeof input === 'object' && input.constructor === Object);
        },
        isString: function (input: any) {
            return (typeof input === 'string');
        },
        isNumber: function (input: any) {
            return (typeof input === 'number');
        },
        isNumeric: function (input: any) {
            return !isNaN(Number(input));
        },
        isBoolean: function (input: any) {
            return (typeof input === 'boolean');
        },
    }
};