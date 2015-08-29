/* Frontend Debugger javascript version 1.1
@preserve */

function supports_html5_storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

window.onload = function () {
    var i, linenums, wrap, lineends,
        buttonLinenums, buttonWrap, buttonLineends,
        buttonHighlightListener;

    if (!supports_html5_storage()) {
        alert('Please upgrade your browser.');
        return;
    }

    function getValue(name) {
        var value;

        value = localStorage.getItem(name);
        if (value === null) {
            return true;
        } else {
            return JSON.parse(value);
        }
    }

    function setValue(name, value) {
        if (typeof value === 'undefined') {
            value = true;
        }
        localStorage.setItem(name, JSON.stringify(value));
    }

    function toggleLinenums(target) {
        var parts = document.getElementsByClassName('linenums');

        target.classList.toggle('on');
        for (i = 0; i < parts.length; i++) {
            parts[i].classList.toggle('hide-linenums');
        }
    }

    function toggleWrap(target) {
        var codes = document.getElementsByTagName('pre');

        target.classList.toggle('on');
        for (i = 0; i < codes.length; i++) {
            codes[i].classList.toggle('no-wrap');
        }
    }

    function toggleLineends(target) {
        var listElements = document.getElementsByTagName('li');

        target.classList.toggle('on');
        for (i = 0; i < listElements.length; i++) {
            listElements[i].classList.toggle('no-end');
        }
    }

    function highlight(elementName, mimeType, color) {
        var elements;

        if ( mimeType === '' ) {
            elements = jQuery('span.tag:contains("<' + String(elementName) + '")');
        } else {
            elements = jQuery('span.tag:contains("<'
                + String(elementName) + '") ~ span.atv:contains("' + String(mimeType) + '")')
                .prevUntil('li', 'span.tag:contains("<' + String(elementName) + '")');
        }

        elements.css('outline', '2px dashed ' + String(color)).css('background-color', 'black').attr('data-next', '1');
    };

    function highlightToBeFixed() {
        var script = document.createElement('script');

        script.src = jQueryUrl;
        script.onload = function () {
            highlight('style', '', 'magenta');
            highlight('link', 'stylesheet', 'pink');
            highlight('script', '', 'red');
        };
        document.head.appendChild(script);
    };


    // line numbers
    buttonLinenums = document.getElementById('toggle-linenums');
    linenums = getValue('linenums');
    if (! linenums) {
        toggleLinenums(buttonLinenums);
    }
    buttonLinenums.addEventListener('click', function (event) {
        linenums = !linenums;
        setValue('linenums', linenums);
        toggleLinenums(event.target);
    });

    // wrap long lines
    buttonWrap = document.getElementById('toggle-wrap');
    wrap = getValue('wrap');
    if (! wrap) {
        toggleWrap(buttonWrap);
    }
    buttonWrap.addEventListener('click', function (event) {
        wrap = !wrap;
        setValue('wrap', wrap);
        toggleWrap(event.target);
    });

    // line ends
    buttonLineends = document.getElementById('toggle-lineends');
    lineends = getValue('lineends');
    if (! lineends) {
        toggleLineends(buttonLineends);
    }
    buttonLineends.addEventListener('click', function (event) {
        lineends = !lineends;
        setValue('lineends', lineends);
        toggleLineends(event.target);
    });

    // highlight elements to be fixed
    buttonHighlight = document.getElementById('button-highlight');
    buttonHighlightNext = function (event) {
        var next = buttonHighlight.dataset.next++,
            bodyRect = document.body.getBoundingClientRect(),
            elements = jQuery('span[data-next]'),
            elemRect,
            offset;

        elemRect = elements.eq(next)[0].getBoundingClientRect(),
        offset = elemRect.top - bodyRect.top;
        window.scrollTo(0, offset);

        if (next + 1 >= elements.length) {
            buttonHighlight.dataset.next = 0;
        }
    }
    buttonHighlightListener = function (event) {
        buttonHighlight.removeEventListener('click', buttonHighlightListener);
        buttonHighlight.dataset.next = 0;
        buttonHighlight.addEventListener('click', buttonHighlightNext);
        event.target.classList.toggle('on');
        if ( typeof jQueryUrl === 'undefined' ) {
            return;
        }
        highlightToBeFixed();
    };
    buttonHighlight.classList.toggle('on');
    buttonHighlight.addEventListener('click', buttonHighlightListener);

    // enable transitions
    setTimeout(function () {
        document.documentElement.classList.add('transition');
    }, 10);

}
