interface EventHandler {
    event: string;
    callback: Function
};

let handler: Array<EventHandler> = [];

export default {
    on(event: string, callback: Function) {
        handler.push({
            event: event,
            callback: callback,
        });
    },

    emit(event: string, payload: any) {
        handler.filter((h) => {
            return h.event == event;
        }).forEach((h) => {
            h.callback(payload);
        });
    },

    hasListener(event: string) {
        return handler.filter((h) => {
            return h.event == event;
        }).length > 0;
    },
};