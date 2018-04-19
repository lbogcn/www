import qs from 'qs';

export default (http) => {

    let resourceUrl = (resource, model, method) => {
        if (!model.id) {
            return resource;
        }

        switch (method.toUpperCase()) {
            case 'GET':
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                return `${resource}/${model.id}`;
        }

        return resource;
    };

    let call = (method, resource, model = {}, config = {}) => {
        model = Object.assign({}, model);
        config = Object.assign({}, config);

        method = method.toLocaleLowerCase();

        let url = resourceUrl(resource, model, method);

        if (['get', 'delete'].includes(method)) {
            if (method === 'get' && config.params) {
                url += '?' + qs.stringify(config.params);
                config.params = null;
            }

            return http[method](url, config);
        } else {
            return http[method](url, model, config);
        }
    };

    return {
        get(resource, model = {}, config = {}) {
            return call('get', resource, model, config);
        },
        post(resource, model = {}, config = {}) {
            return call('post', resource, model, config);
        },
        put(resource, model = {}, config = {}) {
            return call('put', resource, model, config);
        },
        patch(resource, model = {}, config = {}) {
            return call('patch', resource, model, config);
        },
        delete(resource, model = {}, config = {}) {
            return call('delete', resource, model, config);
        },
    }
}