interface EventHandler {
    event: string;
    callback: CallableFunction
};

let handler: Array<EventHandler> = [];

export default {
    on(event: string, callback: CallableFunction) {
        handler.push({
            event: event,
            callback: callback,
        });
    },

    emit() {
        var args = Array.from(arguments);
        var event = args[0];
        var payload = args.splice(1);

        handler.filter((h) => {
            return h.event == event;
        }).forEach((h) => {
            h.callback(...payload);
        });
    },

    hasListener(event: string) {
        return handler.filter((h) => {
            return h.event == event;
        }).length > 0;
    },
};