
(function ($) {

    var defaults = {
        test: 'aa'
    };

    var diform = function (param) {

        switch (typeof (param)) {
            case 'object':
            case 'undefined':

                var config = $.extend(defaults, param);

                var $_this = $(this);

                $_this.data('diform', config);

                $_this.change(function (i_evt) {

                    var type, typeChecker;
                    type = $(i_evt.target).attr('type');
                    if ((typeChecker = diform.type[type])) {
                        typeChecker(i_evt.target);
                    }
                });
                break;

            case 'string':
                if (diform.methods[param]) {
                    diform.methods[param](arguments[1]);
                }
                break;
        }

        return $_this;
    }

    diform.type = {};
    diform.methods = {
        addType: function (config_type) {

            diform.type[config_type.type] = config_type;
        },
        test: function () {
            console.log(this);
        }
    }

    $.fn.extend({
        diform: diform
    });
})(jQuery);




