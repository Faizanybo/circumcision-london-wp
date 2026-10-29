const { handler: wordpressHandler } = require('serverlesswp/wordpress');
const {
    sanitizeWordpressEvent,
    fingerprintBlockResponse,
    sanitizeWordpressResponse,
} = require('./request-guard');

async function handler(event, context, callback) {
    const blocked = fingerprintBlockResponse(event);
    if (blocked) {
        return blocked;
    }
    const response = await wordpressHandler(sanitizeWordpressEvent(event), context, callback);
    return sanitizeWordpressResponse(response);
}

exports.handler = handler;
