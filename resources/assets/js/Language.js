function Language(code) {
    this.code = code;
}

Language.prototype.description = function () {
    switch(this.code) {
        case 'en':
            return 'English';
        case 'ja':
            return 'Japanese';
        case 'zh':
            return 'Chinese';
        default:
            return '-'
    }
};

module.exports = Language;

// new Language('en').description()               English