/**
 * Strip client-controlled Host injection and fingerprinting headers
 * before PHP runs, and after the WordPress response is returned.
 */

function headerValue(headers, name) {
    if (!headers) {
        return '';
    }
    const want = name.toLowerCase();
    for (const key of Object.keys(headers)) {
        if (key.toLowerCase() === want) {
            return headers[key];
        }
    }
    return '';
}

function requestPath(event) {
    if (!event) {
        return '';
    }
    return String(event.path || event.rawPath || event.url || '');
}

function sanitizeWordpressEvent(event) {
    if (!event || !event.headers) {
        return event;
    }
    const headers = event.headers;
    const platformHost = headerValue(headers, 'host');

    for (const name of Object.keys(headers)) {
        const lower = name.toLowerCase();
        if (lower === 'expect' || lower === 'injecthost') {
            delete headers[name];
        }
    }

    // Re-inject only the platform Host. Clients cannot set injectHost.
    if (platformHost) {
        headers.injectHost = platformHost;
    }

    return event;
}

function fingerprintBlockResponse(event) {
    if (/\/readme\.html(?:\?|$)/i.test(requestPath(event))) {
        return {
            statusCode: 404,
            headers: {
                'content-type': 'text/plain; charset=utf-8',
                'x-content-type-options': 'nosniff',
            },
            body: '',
        };
    }
    return null;
}

function sanitizeWordpressResponse(response) {
    if (!response || !response.headers) {
        return response;
    }
    for (const name of Object.keys(response.headers)) {
        if (name.toLowerCase() === 'x-serverlesswp-binary') {
            delete response.headers[name];
        }
    }
    return response;
}

module.exports = {
    sanitizeWordpressEvent,
    fingerprintBlockResponse,
    sanitizeWordpressResponse,
};
