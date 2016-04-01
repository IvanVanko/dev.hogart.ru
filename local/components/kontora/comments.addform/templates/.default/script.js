jQuery(function() {
    var $ = jQuery;
    var $textarea = $('#textarea-1');
    var $counterTarget = $('#textareaFeedback');
    var maxLength = $textarea.attr('maxlength');

    var updateDisplayedCharCount = function(curLength, maxlength, target){
        var remaning = Math.max(maxLength - curLength, 0);
        $(target).html('Осталось : ' + remaning);
    };

    var updateCharCount = function(content) {
        var charcount = $('<div/>').append(content).text().length;
        updateDisplayedCharCount(charcount, maxLength, $counterTarget);
    };

    $textarea.cleditor({
        controls: 'bold italic underline | bullets numbering | outdent indent | undo redo',
        useCSS: false,
        doctype: '<!DOCTYPE html>',
        updateTextArea: function(content) {
            var whiteListHTML = function(content, whiteList) {
                var sanitizeInPlace = function(DOMElement, keepTop) {
                    var allowed, item, i;

                    for (i = DOMElement.children.length - 1; i >= 0; i--) {
                        sanitizeInPlace(DOMElement.children[i], false);
                    }

                    allowed = keepTop || whiteList[DOMElement.localName];
                    if (!allowed) {
                        for (i = 0; i < DOMElement.childNodes.length; i++) {
                            item = DOMElement.childNodes[i].cloneNode(true);
                            DOMElement.parentNode.insertBefore(item, DOMElement);
                        }
                        DOMElement.parentNode.removeChild(DOMElement);
                    } else if (DOMElement.hasAttributes()) {
                        for (i = DOMElement.attributes.length - 1; i >= 0; i--) {
                            item = DOMElement.attributes[i];
                            if (!(allowed[item.localName] && (item.value.search(allowed[item.localName]) > -1))) {
                                DOMElement.removeAttribute(item.localName);
                            }
                        }
                    }
                };

                var sandbox = document.implementation.createHTMLDocument();
                sandbox.body.innerHTML = content;

                sanitizeInPlace(sandbox.body, true);

                content = sandbox.body.innerHTML;
                return content;
            };

            content = whiteListHTML(content, {
                "b": {},
                "i": {},
                "p": {},
                "br": {},
                "ul": {},
                "ol": {},
                "li": {},
                "hr": {},
                "blockquote": {},
                "a": {
                    "href": /^(https?\:)?\/\//
                }
            });

            updateCharCount(content);

            return content;
        }
    });
    (function() {
        $textarea.keyup(function() {
            var curLength = $textarea.val().length;
            updateDisplayedCharCount(curLength, maxLength, $counterTarget);
        });
    })();
});