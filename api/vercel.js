const { handler: wordpressHandler } = require('serverlesswp/wordpress');

function stripExpectHeader(event) {
    if (!event || !event.headers) {
        return event;
    }
    for (const name of Object.keys(event.headers)) {
        if (name.toLowerCase() === 'expect') {
            delete event.headers[name];
        }
    }
    return event;
}

async function handler(event, context, callback) {
    return wordpressHandler(stripExpectHeader(event), context, callback);
}

module.exports = handler;
module.exports.handler = handler;
