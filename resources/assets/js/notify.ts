import { reactive } from 'vue';
import { v4 as uuidv4 } from 'uuid';

interface NotificationItem {
    _message: string;
    _title: string;
    _icon: string;
    _color: string;
    _buttons: Array<any>;
    _uuid: string;

    resolve: CallableFunction;
    reject: CallableFunction;

    _prompt: boolean;
    _prompt_value: string;
    _select: Array<Object>;
    _select_values: Array<any>;
    _timeout_running: boolean;
    _indeterminate: boolean;
    _timeout?: number;
}

const Notify = {
    notifications: reactive<Array<NotificationItem>>([]),
    addNotification(obj: NotificationItem) {
        var vm = this;

        return new Promise((resolve, reject) => {
            obj.resolve = resolve;
            obj.reject = reject;
            obj._timeout_running = true;

           vm.notifications.push(obj);
        });
    },
    removeNotification(obj: NotificationItem, result: any, message = null) {
        if (obj._prompt == true || obj._select.length > 0) {
            obj.resolve((result == true ? message : false));
        } else if (result !== null) {
            obj.resolve(result);
        }
        this.notifications.splice(this.notifications.indexOf(obj), 1);
    }
};

const Notification = class Notification implements NotificationItem {
    _message: string;
    _title: string = '';
    _icon: string;
    _color: string;
    _buttons: Array<any>;
    _uuid: string;

    _indeterminate: boolean = false;
    _timeout?: number = undefined;
    _timeout_running: boolean = false;

    _prompt: boolean = false;
    _prompt_value: string = '';

    _confirm: boolean = false;

    _select: Array<Object> = [];
    _select_values: Array<any> = [];

    resolve: CallableFunction = () => {};
    reject: CallableFunction = () => {};

    constructor(message: string) {
        this._message = message;
        this._icon = 'information-circle';
        this._color = 'accent';
        this._buttons = [];
        this._uuid = uuidv4();

        return this;
    }

    title(title: string) {
        this._title = title;

        return this;
    }

    message(message: string) {
        this._message = message;

        return this;
    }

    icon(icon: string) {
        this._icon = icon;

        return this;
    }

    color(color: string) {
        this._color = color;

        return this;
    }

    timeout(timeout = 7500) {
        this._timeout = timeout;

        return this;
    }

    indeterminate() {
        this._indeterminate = true;

        return this;
    }

    prompt(value = '') {
        this._prompt = true;
        this._prompt_value = value;

        return this;
    }

    confirm() {
        this._confirm = true;

        return this;
    }

    select(options: Object) {
        this._select.push(options);

        return this;
    }

    addButton(button: any) {
        this._buttons.push(button);

        return this;
    }

    show() {
        if (this._confirm && this._buttons.length == 0) {
            this.addButton({
                key: true,
                value: 'voyager::generic.yes',
                color: 'green',
            }).addButton({
                key: false,
                value: 'voyager::generic.no',
                color: 'red',
            });
        } else if (this._prompt && this._buttons.length == 0) {
            this.addButton({
                key: true,
                value: 'voyager::generic.ok',
                color: 'green',
            }).addButton({
                key: false,
                value: 'voyager::generic.cancel',
                color: 'red',
            });
        }
        if (!this._prompt && !this._confirm) {
            Notify.addNotification(this);

            return this;
        }
        var vm = this;

        return new Promise((resolve, reject) => {
            Notify.addNotification(vm)
            .then((result, message = null) => {
                // @ts-ignore
                resolve(result, message);
            });
        });
    }

    remove() {

    }
};

export {
    Notify,
    Notification
};