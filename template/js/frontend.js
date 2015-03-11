function supports_html5_storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

window.onload = function () {
    var i, linenums, wrap, lineends,
        buttonLinenums, buttonWrap, ButtonLineends;

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
    ButtonLineends = document.getElementById('toggle-lineends');
    lineends = getValue('lineends');
    if (! lineends) {
        toggleLineends(ButtonLineends);
    }
    ButtonLineends.addEventListener('click', function (event) {
        lineends = !lineends;
        setValue('lineends', lineends);
        toggleLineends(event.target);
    });

    // enable transitions
    setTimeout(function () {
        document.documentElement.classList.add('transition');
    }, 10);

}