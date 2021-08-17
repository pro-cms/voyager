interface Array<T> {
    where(prop: string, value?: string): Array<T>;
    whereNot(prop: string, value?: string): Array<T>;
    whereLike(query: string, prop?: string): Array<T>;
    whereNull(query: string): Array<T>;
    shuffle(): Array<T>;
    first(): T;
    pluck(prop: string): Array<T>;
    diff(arr: Array<T>): Array<T>;
    insert(el: T): Array<T>;
    moveElementUp(el: T): Array<T>;
    moveElementDown(el: T): Array<T>;
    removeAtIndex(index: number): Array<T>;
}

Array.prototype.where = function (prop, value = undefined) {
    return this.filter(function (el) {
        if (value !== undefined) {
            return el[prop] == value;
        }

        return el == prop;
    });
}

Array.prototype.whereNot = function (prop, value = undefined) {
    return this.filter(function (el) {
        if (value !== undefined) {
            return el[prop] !== value;
        }

        return el !== prop;
    });
}

Array.prototype.whereLike = function (query, prop = undefined) {
    return this.filter(function (el) {
        if (prop !== undefined) {
            return el[prop].toLowerCase().includes(query.toLowerCase());
        } else {
            return el.toLowerCase().includes(query.toLowerCase());
        }
    });
}

Array.prototype.whereNull = function (prop) {
    return this.filter(function (el) {
        return el[prop] === null || el[prop] === undefined;
    });
}

Array.prototype.shuffle = function () {
    for (let i = this.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [this[i], this[j]] = [this[j], this[i]];
    }

    return this;
}

Array.prototype.first = function () {
    return this[0];
}

Array.prototype.pluck = function (prop) {
    return this.map(function (el) {
        return el[prop];
    });
}

Array.prototype.diff = function (arr) {
    return this.filter(x => !arr.includes(x));
}

Array.prototype.moveElementUp = function (el) {
    var i = this.indexOf(el);
    if (i > 0) {
        [this[i], this[i-1]] = [this[i-1], this[i]];
    }

    return this;
}

Array.prototype.moveElementDown = function (el) {
    var i = this.indexOf(el);
    if (i < this.length - 1) {
        [this[i], this[i+1]] = [this[i+1], this[i]];
    }
    
    return this;
}

Array.prototype.insert = function (el) {
    this.push(el);
    return this;
}

Array.prototype.removeAtIndex = function (index) {
    this.splice(index, 1);
    return this;
}