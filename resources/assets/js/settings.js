export default {
    install (Vue) {
        Vue.prototype.$settings = new Vue({
            methods: {
                setting: function (key, default_value) {
                    var settings = this.$store.settings.filter(function (setting) {
                        var ident = setting.group + '.' + setting.key;
                        return ident == key;
                    });
                    if (settings.length == 1) {
                        return this.translate(settings[0].value, true);
                    }

                    return default_value;
                }
            }
        });
    }
};