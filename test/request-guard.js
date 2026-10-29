/**
 * Isolated checks for api/request-guard.js (no WordPress).
 *
 *   node test/request-guard.js
 */
const {
    sanitizeWordpressEvent,
    fingerprintBlockResponse,
    sanitizeWordpressResponse,
} = require('../api/request-guard');

let fail = 0;
function assert(cond, msg) {
    if (!cond) {
        process.stderr.write('FAIL: ' + msg + '\n');
        fail += 1;
    }
}

const event = {
    path: '/wp-login.php',
    headers: {
        Host: 'circumcision-london-wp.vercel.app',
        injectHost: 'evil.example',
        InjectHost: 'also-evil.example',
        Expect: '100-continue',
    },
};
sanitizeWordpressEvent(event);
assert(!Object.keys(event.headers).some((k) => k.toLowerCase() === 'expect'), 'Expect must be stripped');
assert(event.headers.injectHost === 'circumcision-london-wp.vercel.app', 'injectHost must be the platform Host');
assert(!Object.keys(event.headers).some((k) => k !== 'injectHost' && k.toLowerCase() === 'injecthost'), 'client injectHost variants must be removed');

const blocked = fingerprintBlockResponse({ path: '/readme.html' });
assert(blocked && blocked.statusCode === 404, 'readme.html must 404');
assert(!fingerprintBlockResponse({ path: '/license.txt' }), 'license.txt is not blocked here');

const response = sanitizeWordpressResponse({
    statusCode: 200,
    headers: { 'X-Serverlesswp-Binary': 'false', 'Content-Type': 'text/html' },
});
assert(!Object.keys(response.headers).some((k) => k.toLowerCase() === 'x-serverlesswp-binary'), 'fingerprint header must be stripped');
assert(response.headers['Content-Type'] === 'text/html', 'other headers must remain');

if (fail) {
    process.exit(1);
}
process.stdout.write('OK request-guard\n');
process.exit(0);
