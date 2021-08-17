interface String {
    startWith(start: string) :String;
    endWith(end: string) :String;
}

String.prototype.startWith = function(start: string) {
    if (!this.startsWith(start)) {
        return start + this;
    }

    return this;
}

String.prototype.endWith = function(end: string) {
    if (!this.endsWith(end)) {
        return this + end;
    }

    return this;
}