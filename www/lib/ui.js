const ui = (() => ({
    Combobox(element) {
        const container = element.closest('.combobox');

        const $createOption = (context) => {
            const option = document.createElement('option');
            const {value = '0', text = '&mdash;'} = context || {};
            option.value = value;
            option.innerHTML = text;

            return option;
        };

        return {
            element,
            clear() {
                this.element.options.length = 0;

                return this;
            },
            append(option) {
                this.element.appendChild($createOption(option));

                return this;
            },
            setData(data, {value, text} = {value: 'value', text: 'text'}) {
                this
                    .clear()
                    .append();
                [...data].forEach(({id: value, title: text}) => {
                    this.append({value, text});
                });

                return this;
            },
            getValue() {
                return this.element.value.toString();
            },
            setValue(value) {
                const {element: target} = this;

                [...target.options]
                    .filter(({value: optionValue}) => optionValue.toString() === value.toString())
                    .forEach(option => option.selected = 'selected');

                target.dispatchEvent(new Event('change'));

                return this;
            },
            setVisible(state) {
                container.classList.toggle('d-none', !state);

                return this;
            },
            dependOn(control, affect) {
                control.element.addEventListener('change', e => {
                    const promise = new Promise(function (resolve, reject) {
                        affect(parseInt(control.getValue()), resolve, reject);
                    });

                    promise
                        .then(({data, value}) => {
                            this
                                .setVisible(true)
                                .setData(data)
                                .setValue(value);
                        })
                        .catch(() => {
                            this
                                .setData([])
                                .setValue(0)
                                .setVisible(false);
                        });
                });

                return this;
            },
        };
    },
}))();