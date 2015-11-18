/*********************************************************************************************************************
 * Copyright (c) 2013-2014 Cristian Tapia O.
 * Dual licensed under the MIT ( http://www.opensource.org/licenses/mit-license.php )
 * and GPL ( http://www.opensource.org/licenses/gpl-license.php ) licenses.
 **********************************************************************************************************************/

(function ($) {
    
    $.fn.validate = function (args) {
        
        this.each(function () {
            
            var _this 	= 	$(this),
                _msg 	= 	"";
            
            var option = {
                required    : args.required,
                minlen      : args.minlen || null,
                maxlen      : args.maxlen || null,
                regexp      : args.regexp || null,
                message     : {},
                background  : args.background || "#F9C",
                border      : args.border || "#F0C dotted 1px",
                onerror     : args.onerror || false
            };
            
            $.isValid = (typeof $.isValid === "undefined") ? true : $.isValid;
            
            var ext = $.extend(option, args);
            
            var elemval = $.trim(_this.val());
            
            _this.focus(function () {
                
                _this.removeAttr('style');
                
                if(_this.next().attr('id') === "sp_err_" + _this.attr('id')){
                    _this.next().remove();
                }
                
                ext.onerror = false;
                
                $.isValid = true;
                
            });
            
            if (ext.required) {
                
                ext.onerror = (elemval == 0 || elemval == "") ? true : false;
                
                _msg = ext.message.required;
                
                if (ext.minlen !== null && !ext.onerror) {
                    
                    ext.onerror = (ext.minlen > elemval.length) ? true : false;
                    
                    _msg = ext.message.minlen;
                }
                
                if (ext.maxlen !== null && !ext.onerror) {
                    
                    ext.onerror = (ext.maxlen < elemval.length) ? true : false;
                    
                    _msg = ext.message.maxlen;
                }
                
                if (ext.regexp !== null && !ext.onerror) {
                    
                    var eq = ext.regexp.test(elemval);
                    
                    ext.onerror = (!eq) ? true : false;
                    
                    _msg = ext.message.regexp;
                }
                
                if (ext.onerror) {
                    
                    _this.css({
                        'background-color'  : ext.background,
                        'border'            : ext.border
                    });
                    
                    if (typeof _msg === 'undefined') {
                        
                        _msg = 'Error: Revisa este campo';
                    }
                    
                    if (_this.next().attr('id') !== "sp_err_" + _this.attr('id')) {
                        
                        _this.after(" <span id='sp_err_" + _this.attr('id') + "'>" + _msg + "</span>");
                        
                        $('#sp_err_' + _this.attr('id')).css({
                            'color'         : '#F00',
                            'font-family'   : 'calibri',
                            'font-size'     : '12px'
                        });
                    }
                    
                    $.isValid = false;
                }
            }
        });
    };
    
    $.fn.onfocus = function () {
        
        $(this).focus(function () {
            
            $(this).css('border', 'solid 2px #FC0');
            
        });
        
        $(this).blur(function () {
            
            $(this).removeAttr('style');
            
        });
        
    };
    
    $.fn.key = function (key, fn) {
        var _arrkey = ["esc", "tab", "enter", "espace", "f5", "backsp"];
        var _aascci = [27, 9, 13, 32, 116, 8];
        
        $(this).keyup(function (event) {
            
            var _key = event.keyCode;
            
            for (var i = 0; i < _arrkey.length; i++) {
                if (key === _arrkey[i] && _key === _aascci[i]) {
                    return fn();
                }
            }
            
        });
    };
    
    $.fn.number = function () {
        
        this.keypress(function (event) {
            
            if (event.which !== 8 && event.which !== 13) {
                
                if (event.which && (event.which < 48 || event.which > 57)) {
                    
                    event.preventDefault();
                }
            }
            
        });
        
        return this;
    };
    
    
    $.crypthtml = function (from, to) {
        
        var html = $('#' + from).val();
        
        var code = escape(html);
        
        $('#' + to).val('<script>\ndocument.write(unescape(\'' + code + '\'));\n<\/script>');
        
    };
    
    $.ajaxreq = function ( args ) {
        
        var url         =   args.url,
            type        =   args.type || 'GET',
            contentType =   args.contentType || 'application/x-www-form-urlencoded',
            params      =   args.params || null,
            callback    =   args.callback || false,
            xmlhttp     =   false;
            
        try {
            
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            
        } catch (e) {
            
            try {
                
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                
            } catch (E) {
                
                xmlhttp = false;
            }
        }
        
        if (!xmlhttp && typeof XMLHttpRequest !== 'undefined') {
            
            xmlhttp = new XMLHttpRequest();
        }
        
        if (type === 'GET' && params !== null) {
            
            url += '?' + params;
            
        }
        
        xmlhttp.open(type, url, true);
        
        xmlhttp.setRequestHeader('Content-Type', contentType);
        
        xmlhttp.send(params);
        
        xmlhttp.onreadystatechange = function () {
            
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                
                var x = (callback) ? callback(xmlhttp.responseText) : xmlhttp.responseText;
                
                return x;
                
            }
        };
    };
    
    $.math = function () {

        this.avg = function (array) {
            var sum = 0;
            var j = 0;

            for (var i = 0; i < array.length; i++) {

                j++;
                sum += array[i];

            }

            var avg = sum / j;

            return avg;
        };

        this.pow2 = function (val) {

            var num = Math.abs(val);
            var i2 = Math.pow(num, 2);
            
            return i2;
        };
    };
    
    $.extend($.expr[':'], {
        over100: function (a) {            
            return $(a).height() > 100;
        }
    });
    
})(jQuery);