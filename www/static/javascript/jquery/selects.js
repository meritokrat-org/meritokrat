document.addEventListener('DOMContentLoaded', function (e) {
    [...document.querySelectorAll('[data-container][onload]')]
        .forEach((container) => {
            container.dispatchEvent(new CustomEvent('load'));
        });
});

class Locality extends Map {

    container;
    proxyHandler = {
        get(target, prop, receiver) {
            if (target.has(prop)) {
                const hiddenInput = target.get('id');
                const selectElement = target.get(prop);

                selectElement.length = 1;

                return relatedSelect => {
                    const {value} = relatedSelect;

                    const url = new URL('/profile/katottg', window.location.toString());

                    if (relatedSelect.value > 1) {
                        hiddenInput.value = value
                        url.searchParams.set('id', value);
                    }

                    target
                        .api(url.toString())
                        .apply(null, [options => Object.keys(options)
                            .forEach(value => selectElement.add(new Option((options[value]), value)))])
                };
            }

            return Reflect.get(target, prop, receiver);
        }
    }

    constructor(container, selectElement) {
        super();
        this.container = container;

        [...container.querySelectorAll('select,input[type=hidden]')]
            .map(el => [this.retrieveName(el.name), el])
            .forEach(([name, el]) => {
                el.locality = new Proxy(this, this.proxyHandler);
                this.set(name, el);
            });

        this.api('/profile/get_countries')
            .call(null, options => {
                Object.keys(options)
                    .forEach(value => selectElement.add(new Option(options[value], value)));
            })
    }

    api(endpoint, payload = {}) {
        return (callback) => {
            const cached = localStorage.getItem(endpoint);

            if (cached !== null) {
                return callback(JSON.parse(cached));
            }

            fetch(endpoint, payload)
                .then(response => response.json())
                .then(function (json) {
                    localStorage.setItem(endpoint, JSON.stringify(json));
                    callback(json)
                })
        }
    }

    value(value = undefined) {
        this.id(value);
        // if (!this.has('id')) {
        //     const attributes = {type: 'hidden', name: 'locality[id]'};
        //     const name = this.retrieveName(attributes.name);
        //
        //     // this.set('id', document.createElement('input'))
        //
        //     Object
        //         .keys(attributes)
        //         .forEach(key => {
        //             // inputElement.setAttribute(key, attributes[key]);
        //         })
        //
        //     this.container.appendChild(inputElement);
        //     this.set('id', inputElement);
        // }

        // const inputElement = this.get(`id`).setAttribute('value', value);
    }

    retrieveName(name) {
        return String(name).replace(/^\w+\[(\w+)]$/, '$1')
    }

}