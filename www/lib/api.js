const api = (() => ({
    ppo: {
        getChildren(value) {
            return fetch(`/api/ppo/children?id=${value}`)
                .then(r => r.json());
        },
    },
}))();