String.prototype.startWith = function(start) {
    if (!this.startsWith(start)) {
        return start + this;
    }

    return this;
}

String.prototype.endWith = function(end) {
    if (!this.endsWith(end)) {
        return this + end;
    }

    return this;
}