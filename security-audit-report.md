# Circumcision London WordPress — Security Audit Report

**Classification:** Confidential — internal pre-production review  
**Date:** 29 October 2026  
**Engagement type:** Read-only discovery and reporting (no remediation applied)  
**Auditor role:** Senior DevOps / Application Security / ethical penetration testing (authorized, non-destructive)

---

## 1. Executive summary

The replacement WordPress site is a custom Gutenberg theme on ServerlessWP (Vercel), with a separate LocalWP copy used for day-to-day editing. The custom theme is relatively small and well structured: there are **no custom REST or `admin-ajax` endpoints**, database credentials are environment-driven, file editing is disabled on the serverless build, and staging is correctly marked `noindex`.

The highest-priority issues before moving to the client domain are:

1. **WordPress core on git and staging is `7.1`**, which is behind the 6 October 2026 (`7.1.3`) and 28 October 2026 (`7.1.4`) security releases. Staging HTML advertises this version. LocalWP is `7.1.3` and is also behind `7.1.4`. `DISALLOW_FILE_MODS` means dashboard auto-updates cannot install the patch; the update must go through git.
2. **`WP_HOME` / `WP_SITEURL` are taken from the request host**, including an unvalidated `injectHost` header. That is a classic host-header / password-reset poisoning pattern. It was **not** exercised on staging (cache-poisoning risk).
3. **WordPress admin, XML-RPC, REST user listing, and author archives are publicly reachable** on staging. Combined with a login form and a leaked admin slug via `/?author=1`, this is the standard WordPress brute-force and phishing foothold.
4. **HTML responses lack CSP, `X-Frame-Options`, and `X-Content-Type-Options`**. The public booking page embeds a third-party payment iframe, so missing framing protection is more than a hygiene issue.
5. **Serverless SQLite + public `init` page-seeding** can write the database and flush rewrite rules on anonymous traffic after a version bump. That is a poor fit for a clinic production site if editors and visitors share one SQLite file.

No live API keys, database passwords, or cloud tokens were found in the Desktop git repository. No Critical confirmed exploit was demonstrated. **Do not treat “no Critical” as production-ready.**

---

## 2. Scope, environments, and limitations

### In scope

| Environment | Location | Role |
|---|---|---|
| A. Desktop git repository | `C:\Users\HP\Desktop\Projects\circumcision-london-wp` | Primary source of truth |
| B. LocalWP | `C:\Users\HP\Local Sites\circumcision-london-wp\app\public` | Local runtime / editor copy |
| C. Vercel staging | `https://circumcision-london-wp.vercel.app/` | Public staging |

### Out of scope

- `https://www.circumcisioninlondon.co.uk/` — **not scanned, probed, or modified**
- Vercel control plane, Blob internals, Cliniko, Google, WhatsApp, and other third-party infrastructure
- Authenticated WordPress admin sessions
- Patient records, private media contents, comment bodies, and account identifiers (recorded as exposure risk only)
- Destructive, brute-force, or state-changing tests

### Git state at audit start (preserved)

- Branch: `main` tracking `origin/main` (`561ed91f`)
- Dirty tree: **untracked only** `.vercel/README.txt` and `.vercel/project.json`
- No reset, stash, commit, push, or application-file edits were performed
- The only file written is this report

### Limitations

- `node_modules/serverlesswp` was not present locally, so Node-side stripping of `injectHost` could not be confirmed from installed package files
- LocalWP database contents were not queried (to avoid account/PII inspection)
- Plugin vulnerability checks used public advisories; absence of a CVE is not a proof of safety
- Host-header injection, XML-RPC methods, customizer changeset disclosure, and form posts were **not** executed (see §12)

---

## 3. Methodology

1. Inventory git branch/status and repository layout.
2. Static review of `wp-config.php`, Vercel/Netlify config, custom theme PHP/JS, mu-plugins, and content-sync/media helpers.
3. Secret and hygiene scan of tracked/untracked files (values redacted).
4. Version identification for WordPress, plugins, and `serverlesswp`, then advisory lookup.
5. Read-only comparison with LocalWP (paths, versions, theme hashes, extra files).
6. Low-impact GET/HEAD requests to staging only. No POST, no login, no `injectHost`, no XML-RPC method calls, no form submit, no media download of clinical content.

Alignment: OWASP Top 10 (2021), relevant ASVS themes (V2 session, V5 validation, V14 config), and WordPress security practice.

---

## 4. Prioritized vulnerability register

| ID | Title | Severity | Confidence | Status | Environments |
|---|---|---|---|---|---|
| CIL-SEC-001 | WordPress core behind 7.1.3 and 7.1.4 security releases | **High** | High | Confirmed | Git, staging (`7.1`); LocalWP (`7.1.3`) |
| CIL-SEC-002 | Site URL derived from `Host` / `injectHost` | **High** | High (code); unverified on staging | Confirmed in source | Git, staging runtime (same `wp-config.php`) |
| CIL-SEC-003 | Anonymous `init` can insert pages and flush rewrites | **High** | High | Confirmed in source | Git, LocalWP theme (same files), staging after version bump |
| CIL-SEC-004 | Serverless SQLite concurrent-write / integrity risk | **High** | Medium | Confirmed architecture; not exploited | Staging (ServerlessWP), intended production if SQLite is kept |
| CIL-SEC-005 | Admin login, XML-RPC, user enumeration exposed | **Medium** | High | Confirmed on staging | Staging; source has no hardening |
| CIL-SEC-006 | Default WordPress salts committed | **Medium** | High | Confirmed | Git / staging config; LocalWP has unique salts |
| CIL-SEC-007 | Missing CSP / framing / nosniff on public HTML | **Medium** | High | Confirmed on staging | Staging, `vercel.json` |
| CIL-SEC-008 | Broad HTML allowlist in `cil_rich_text()` / `wp_kses_post` | **Medium** | Medium | Confirmed allowlist; XSS suspected for editors | Git, LocalWP, staging |
| CIL-SEC-009 | Blog media sideload not confined with `realpath()` | **Medium** | High | Confirmed missing check | Git / LocalWP theme |
| CIL-SEC-010 | Callback form sends patient DOB through `mailto:` | **Medium** | High | Confirmed | All (theme JS) |
| CIL-SEC-011 | `.gitignore` does not ignore `.env` or `.vercel/` | **Low** | High | Confirmed | Git |
| CIL-SEC-012 | Tracked localhost TLS private key and test installer credentials | **Low** | High | Confirmed | Git `test/` |
| CIL-SEC-013 | WordPress fingerprinting (`readme.html`, generator, stack header) | **Low** | High | Confirmed on staging | Staging |
| CIL-SEC-014 | JSON-LD encoded without `JSON_HEX_TAG` | **Low** | Medium | Confirmed encoding; breakout unverified | Theme |
| CIL-SEC-015 | Optional Google Maps Embed API key emitted in iframe `src` | **Low** | High | Confirmed code; key not present on homepage | Theme Customizer |
| CIL-SEC-016 | Content-apply CLI scripts bootstrap WordPress without CLI guard | **Low** | High | Confirmed; not in Vercel `includeFiles` | Git `tools/` |

**Confirmed Critical:** 0  
**Confirmed High:** 4  
**Confirmed Medium:** 6  
**Confirmed Low:** 6  
**Informational items** are in §8–§9 and are not counted as vulnerabilities.

No CVSS scores are assigned for WordPress 7.1.3/7.1.4: the vendor published those releases **without CVE IDs or CVSS**. Inventing scores would be misleading.

---

## 5. Detailed findings

### CIL-SEC-001 — WordPress core behind security releases

- **Severity / confidence:** High / High
- **Environments:** Desktop repo and staging: `7.1`. LocalWP: `7.1.3`. Current vendor security release: **7.1.4** (28 October 2026).
- **Evidence:**
  - `wp/wp-includes/version.php` line 19: `$wp_version = '7.1';`
  - Staging homepage: `<meta name="generator" content="WordPress 7.1" />`
  - LocalWP `wp-includes/version.php` line 19: `$wp_version = '7.1.3';`
  - [WordPress 7.1.3](https://wordpress.org/news/2026/10/wordpress-7-1-3-maintenance-and-security-release/) (6 Oct 2026): stored XSS, WXR second-order SQL injection, unauthenticated disclosure of comments on private/unpublished posts, and related issues.
  - [WordPress 7.1.4](https://wordpress.org/news/2026/10/wordpress-7-1-4/) (28 Oct 2026): stored XSS in block markup / Interactivity API / several core blocks, unauthenticated discovery of unpublished Customizer changesets, notes authorization issue, and an upgrade-window RCE weakness.
- **Attack scenario:** An editor or contributor who can save block markup may trigger stored XSS fixed in 7.1.4. Unauthenticated customizer-changeset disclosure can leak unpublished copy. The upgrade-window RCE is **likely mitigated here** because `DISALLOW_FILE_MODS` is true, so wp-admin core upgrades should not run.
- **Status:** Confirmed outdated version. Individual 7.1.4 bugs were **not** exploited.
- **Remediation (not applied):** Update WordPress via the ServerlessWP git workflow to **7.1.4** (or current) in the Desktop repo, redeploy staging, and align LocalWP. Do not rely on Dashboard → Updates.
- **Safe validation:** Compare `version.php` and the public generator tag after the git-based update.
- **Approval needed?** Updating is a change. Exploiting the XSS/changeset issues needs a separate test plan.

### CIL-SEC-002 — Site URL taken from `Host` / `injectHost`

- **Severity / confidence:** High / High for the code path
- **File:** `wp/wp-config.php` lines 108–117
- **Evidence:**

```php
$_SERVER['HTTPS'] = 'on';
$headers = getallheaders();
if (isset($headers['injectHost'])) {
  $_SERVER['HTTP_HOST'] = $headers['injectHost'];
}
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST']);
```

`api/vercel.js` only strips the `Expect` header. No allowlist exists in this repository. WordPress documentation warns that defining `WP_SITEURL` from `HTTP_HOST` can enable host-header issues.

- **Attack scenario:** If the platform forwards a client `injectHost` or a spoofed `Host` into PHP, generated absolute URLs (canonicals, password-reset links, `home_url()`, JSON-LD, asset URLs) point at an attacker domain. Impact includes phishing of reset tokens and HTML cache poisoning.
- **Status:** Confirmed code. **Not tested on staging** (could poison Vercel cache for other visitors).
- **Remediation:** Pin `WP_HOME` / `WP_SITEURL` to an environment variable for the canonical host. If a platform header is required, allowlist known hosts in PHP **and** overwrite/strip `injectHost` in `api/vercel.js` before PHP runs.
- **Safe validation:** In a disposable preview deployment only, send `injectHost` on a unique cache-busted path and inspect whether generated URLs change. Do not do this on the shared staging URL.
- **Approval needed?** Yes, before any staging header-injection test.

### CIL-SEC-003 — Unauthenticated `init` page seeding and rewrite flush

- **Severity / confidence:** High / High
- **Files:**
  - `wp/wp-content/themes/circumcision-london/inc/setup-pages.php` — `cil_is_page_setup_request()` 43–54, `cil_ensure_service_pages()` 209–318, hooked on `init` at 341
  - `wp/wp-content/themes/circumcision-london/inc/pages/home-page.php` — `cil_ensure_homepage()` 141–214
- **Evidence:** Public requests skip the seeder only when `cil_service_pages_version` already equals `CIL_SERVICE_PAGES_VERSION` (`0.12.0`). Otherwise the seeder calls `wp_insert_post()`, `update_option( 'permalink_structure', ... )`, `kses_remove_filters()`, and `flush_rewrite_rules()`. Homepage seeding inserts a published Home page if `page_on_front` is empty.
- **Attack scenario:** First visitor after a version bump (or a missing option on a fresh SQLite/MySQL) triggers database writes with no capability or nonce. Concurrent requests can race inserts. `flush_rewrite_rules()` on ServerlessWP SQLite is an availability/integrity risk (see CIL-SEC-004). Content is theme-catalog, not attacker-chosen, so this is unauthorized persistence and DoS, not arbitrary HTML injection.
- **Status:** Confirmed.
- **Remediation:** Run seeders only on `after_switch_theme`, WP-CLI, or `manage_options`. Fail closed for front-end `init`.
- **Safe validation:** On a disposable environment, delete the option and request the homepage twice concurrently; watch for duplicate pages and rewrite flushes. Do not do this on production.
- **Approval needed?** Yes for a live write test.

### CIL-SEC-004 — Serverless SQLite concurrency / integrity

- **Severity / confidence:** High / Medium
- **Evidence:** Project `readme.md` states SQLite + object storage can fail under competing writes and recommends MySQL for multiple editors or frequent submissions. `wp-config.php` 132–155 enables SQLite when `HTTP_X_SERVERLESSWP_SQLITE_FILE` is set, stores the working file under `/tmp`, and disables cron on `wp-admin` / `wp-login.php` specifically because of DB races.
- **Attack scenario:** Overlapping public hits (CIL-SEC-003), cron, comment/REST writes, or two editors saving can lose updates or corrupt the SQLite file. For a CQC-registered clinic this is an integrity and availability issue, not a remote code execution.
- **Status:** Confirmed architecture. Data loss was not demonstrated.
- **Remediation:** Use MySQL/MariaDB (or TiDB) for the client production domain. Keep SQLite for throwaway previews only.
- **Safe validation:** Vendor guidance plus a controlled two-writer test in a branch preview — not on shared staging.
- **Approval needed?** Yes for a write-contention test.

### CIL-SEC-005 — Login, XML-RPC, and user enumeration

- **Severity / confidence:** Medium / High
- **Endpoints (staging, GET only):**
  - `GET /wp-login.php` → 200, login form, `X-Frame-Options: SAMEORIGIN`
  - `GET /wp-admin/` → 302 to `wp-login.php?redirect_to=...`
  - `GET /xmlrpc.php` → 405 (script is routed; POST methods not invoked)
  - `GET /wp-json/wp/v2/users` → 200, **one user object** (id, name, slug, link, avatar URLs)
  - `GET /?author=1` → 301 to `/author/<admin-slug>/` (slug redacted)
  - `GET /wp-cron.php` → 200
  - `GET /wp-json/wp/v2/comments` → 200, `X-WP-Total: 1` (body not inspected)
- **Theme:** no `xmlrpc_enabled` filter, no REST user restriction, and `functions.php` still styles `is_author()`.
- **Attack scenario:** Attacker learns a valid admin slug, then attacks `wp-login.php` or XML-RPC `system.multicall` (not performed). For a clinic, compromise of wp-admin is full site takeover (content, media, users).
- **Status:** Confirmed exposure. Credential guessing was not attempted.
- **Remediation:** Restrict `/wp-login.php` and `/wp-admin/` (Vercel Firewall / IP allowlist / SSO). `add_filter( 'xmlrpc_enabled', '__return_false' );` disable author archives; hide users from unauthenticated REST (`rest_authentication_errors` or `rest_prepare_user`).
- **Safe validation:** Repeat the GET checks above after hardening; they should 404/403 or 401 without leaking slugs.
- **Approval needed?** Any login, XML-RPC POST, or password testing requires explicit approval.

### CIL-SEC-006 — Default authentication keys and salts

- **Severity / confidence:** Medium / High
- **File:** `wp/wp-config.php` lines 69–76 — all eight `AUTH_KEY` / `*_SALT` values are the placeholder `put your unique phrase here`. They are not loaded from env (unlike `DB_*`).
- **LocalWP:** unique salts are present (values not recorded).
- **Attack scenario:** WordPress detects the placeholder and stores fallback salts in the options table, so this is not instant cookie forgery from the git copy. Anyone with database/option backup access obtains the real salts. Recovery mode treats this as insecure. Rotating later invalidates all sessions.
- **Status:** Confirmed in repo. Runtime cookie-forgery **not** demonstrated.
- **Remediation:** Generate unique keys per environment via env vars. Never commit them. Rotate staging keys after they are first set.
- **Safe validation:** Site Health “secure keys” check; confirm `wp-config.php` no longer contains the placeholder phrase.
- **Approval needed?** Rotation logs everyone out — schedule it.

### CIL-SEC-007 — Missing security headers on public HTML

- **Severity / confidence:** Medium / High
- **Evidence (staging homepage GET):**
  - Present: `Strict-Transport-Security: max-age=63072000; includeSubDomains; preload` (good)
  - Present: `X-Robots-Tag: noindex, nofollow` on `*.vercel.app` (good)
  - **Absent** on HTML: `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
  - REST JSON *does* send `X-Content-Type-Options: nosniff`
  - `wp-login.php` sends `X-Frame-Options: SAMEORIGIN`
  - `vercel.json` sets long `Cache-Control` only for static theme/plugin/core assets; HTML is `public, max-age=0, must-revalidate`
- **Attack scenario:** Missing `X-Frame-Options`/CSP `frame-ancestors` allows clickjacking, including over the Cliniko booking iframe (`allow="payment"`). Missing CSP reduces defense-in-depth against any future XSS (CIL-SEC-001, CIL-SEC-008). Missing nosniff on HTML is lower risk than on user-uploaded content.
- **Status:** Confirmed missing headers. Not an exploit by itself.
- **Remediation:** Add headers in `vercel.json` (and Netlify if used): at least `X-Frame-Options: SAMEORIGIN` or CSP `frame-ancestors 'self'`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, and a CSP that allows Cliniko and the media Blob host.
- **Safe validation:** `curl -sI` the homepage after deploy.
- **Approval needed?** No for adding headers; yes for a CSP that might break booking/maps.

### CIL-SEC-008 — Broad HTML allowlist in theme rendering

- **Severity / confidence:** Medium / Medium
- **Files:** `inc/content.php` `cil_rich_text()` 68–125; `inc/blocks.php` `cil/rich-html` 1840–1858; `template-parts/trust-strip.php` 81 (`wp_kses_post`); many template parts echo `cil_rich_text()`.
- **Evidence:** `wp_kses` allows `style` on many tags, `svg`/`path`/`rect`, and `a[href,target,style]`. Gutenberg block attributes are rendered server-side through this helper. `wp_kses` does strip `on*` and `javascript:` in `href`.
- **Attack scenario:** A user who can edit those blocks (Author/Editor, or a compromised admin session) stores markup that survives the allowlist (CSS exfiltration via `style`, SVG surprises). Administrators on a single-site install often have `unfiltered_html`, which raises the residual risk mainly for lower roles and for defense-in-depth against CIL-SEC-001 block XSS.
- **Status:** Confirmed allowlist. XSS payload not demonstrated.
- **Remediation:** Drop `style` and SVG unless required; tighten to `p/a/strong/em/br/ul/ol/li`; add `sanitize_callback` on block attributes; keep `esc_url` protocols to `http`, `https`, `mailto`, `tel`.
- **Safe validation:** In a staging editor account (not production), save a `style`/`svg` payload in `cil/rich-html` and see whether it renders. Requires an extra test account.
- **Approval needed?** Yes if using a non-admin role.

### CIL-SEC-009 — Blog media sideload path not confined

- **Severity / confidence:** Medium / High
- **File:** `inc/content-sync-blog.php` `cil_blog_sideload_media()` 151–187
- **Evidence:** `$abs = cil_content_fixtures_dir() . '/' . ltrim( str_replace( '\\', '/', (string) $media['file'] ), '/' );` then `is_file` / `hash_file` / `copy`. No `realpath()` prefix check. `..` in `file` can walk out of the theme. Apply path is capability-gated (`manage_options` + nonce + typed `APPLY`) in `inc/content-sync.php` 866–950.
- **Attack scenario:** An admin applying a poisoned Blog pack, or a compromised git fixture, copies an arbitrary readable file (for example `wp-config.php`) into the Media Library if the SHA-256 matches.
- **Status:** Confirmed missing containment. Full exploit needs privileged apply.
- **Remediation:** `realpath()` must stay under `cil_content_fixtures_dir()`; allowlist image extensions.
- **Safe validation:** Code review plus a local CLI apply using a `../` fixture path in a disposable copy — not on staging.
- **Approval needed?** Yes for any apply that writes media.

### CIL-SEC-010 — Callback form sends patient DOB via `mailto:`

- **Severity / confidence:** Medium (privacy) / High
- **Files:** `template-parts/callback-form.php` 21–59; `assets/js/theme.js` 424–459
- **Evidence:** No `action`, nonce, or server handler. JS builds `mailto:info@beverleyclinic.co.uk?...` including FormData fields `name`, `phone`, `dob`, `message`. Comment in JS: “No back end is wired up yet.” Field label is **Patient DOB**.
- **Attack scenario:** Date of birth for a medical enquiry is special-category-adjacent personal data. It travels through the visitor’s mail client, mail logs, and URL-encoded body. No rate limit or server-side validation. The UI claims the message was handled. This is not RCE and not CSRF against the clinic inbox.
- **Status:** Confirmed.
- **Remediation:** When a backend exists: nonce, rate limit, `wp_mail` or a DPA-covered form processor, store the minimum data, do not put DOB in query strings. Until then, state clearly that the form only opens a local mail draft, and consider dropping DOB from this public form.
- **Safe validation:** Inspect the generated `mailto:` in a browser without sending (not done against staging).
- **Approval needed?** Yes before submitting a real enquiry.

### CIL-SEC-011 — `.gitignore` gaps for `.env` and `.vercel/`

- **Severity / confidence:** Low / High
- **Evidence:** `.gitignore` ignores `node_modules/`, `.serverless`, `test/auth.json`, screenshots, `memory/`, `wp-public/`, etc. It does **not** ignore `.env*`, `.vercel/`, `*.pem`, `*.sql`, `debug.log`, or `wp-content/uploads/`. `.vercel/README.txt` says the folder must not be shared; `.vercel/project.json` is currently **untracked** (IDs not reproduced here).
- **Attack scenario:** `git add .` would commit Vercel project/org identifiers and, later, any `.env` created for LocalWP or CI.
- **Status:** Confirmed hygiene gap. No live `.env` present.
- **Remediation:** Ignore `.vercel/`, `.env*`, dumps, and debug logs. Keep `test/auth.json` ignored.
- **Safe validation:** `git check-ignore -v .vercel/project.json .env`
- **Approval needed?** No (gitignore-only).

### CIL-SEC-012 — Tracked test TLS key and installer credentials

- **Severity / confidence:** Low / High
- **Files:** `test/test-key.pem` (PKCS#8 private key, CN=localhost); `test/installer.php` (creates user `admin` with a **hardcoded test password**, gated on `SERVERLESSWP_TESTING=1`); matching password in `test/global-setup.js` / e2e specs.
- **Attack scenario:** The key is useless off localhost if it was never reused. If the same key or the test password were copied into staging/production, that would be credential reuse. `installer.php` is not in Vercel `includeFiles` (`wp/**` only) and exits unless the testing env flag is set.
- **Status:** Confirmed. Values not printed.
- **Remediation:** Keep test-only. Never reuse the password or key. Optionally git-crypt or generate the PEM locally and ignore it.
- **Safe validation:** Confirm `test/` is absent from the serverless bundle.
- **Approval needed?** No.

### CIL-SEC-013 — Public fingerprinting artifacts

- **Severity / confidence:** Low / High
- **Staging:** `GET /readme.html` 200; `GET /license.txt` 200; generator `WordPress 7.1`; response header `X-Serverlesswp-Binary`; `Server: Vercel`.
- **Attack scenario:** Aids targeted exploit selection (pairs with CIL-SEC-001). Not directly exploitable.
- **Remediation:** Do not route `readme.html` / `license.txt`; remove generator via `remove_action( 'wp_head', 'wp_generator' )`; consider stripping `X-Serverlesswp-Binary` in the Node handler.
- **Safe validation:** Repeat the GETs; expect 404.
- **Approval needed?** No.

### CIL-SEC-014 — JSON-LD without `JSON_HEX_TAG`

- **Severity / confidence:** Low / Medium
- **File:** `functions.php` `cil_inner_page_head()` 1347–1353
- **Evidence:** `wp_json_encode( ..., JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )` inside `<script type="application/ld+json">`.
- **Attack scenario:** If a schema string ever contains `</script>`, the browser breaks out of the script. Today the graph is built from PHP catalogs, not post content.
- **Remediation:** Add `JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT`.
- **Safe validation:** Unit-level encode of a string containing `</script>`.
- **Approval needed?** No.

### CIL-SEC-015 — Google Maps Embed API key in iframe URL

- **Severity / confidence:** Low / High
- **Files:** `functions.php` `cil_customize_maps()` 1435–1449; `inc/content.php` `cil_maps_embed_url()` 180–193
- **Evidence:** Theme mod `cil_google_maps_embed_key` is concatenated into a public iframe `src`. Homepage HTML on staging had **no** embed key (fallback maps URL).
- **Attack scenario:** Quota theft if a key is later stored without HTTP-referrer restriction.
- **Remediation:** Restrict the key to Maps Embed API + production referrer, or keep the keyless fallback.
- **Safe validation:** View-source on `/` and location pages after a key is added.
- **Approval needed?** No.

### CIL-SEC-016 — Content CLI scripts without CLI-only guard

- **Severity / confidence:** Low / High
- **Files:** `tools/content/apply-*.php`, `tools/content/create-location-pages.php` — `require wp-load.php` then `wp_insert_post()` / updates with no `php_sapi_name() === 'cli'` check.
- **Evidence:** Vercel `includeFiles` is `{wp/**,node_modules/serverlesswp/{wordpress-assets,php-files}/**}`, so `tools/` is not in the serverless bundle.
- **Attack scenario:** Dangerous only if DocumentRoot is the repo root or someone copies these under `wp/`.
- **Remediation:** Exit unless CLI; keep `tools/` out of deploy artifacts.
- **Safe validation:** Confirm production bundle file list.
- **Approval needed?** No.

---

## 6. Repository and deployment configuration risks

### What is deployed

- Catch-all `/(.*)` → `api/vercel.js` (`vercel.json` 15–17). That includes `wp-login.php`, `wp-admin/`, `xmlrpc.php`, `wp-cron.php`, `wp-json/`, and PHP under `wp/`.
- Static CSS/JS/images under `wp-admin`, `wp-includes`, and `wp-content/{plugins,themes,mu-plugins}` are served with 10-day cache.
- `DISALLOW_FILE_EDIT` and `DISALLOW_FILE_MODS` are true — correct for serverless (uploads/plugins cannot persist on the lambda filesystem) and **also** the reason core/plugin security updates must come from git.
- Database and S3 credentials are env-only (`DATABASE`, `USERNAME`, `PASSWORD`, `HOST`, `S3_KEY_ID`, `S3_ACCESS_KEY`). Values not present in git.
- `WP_DEBUG` is `false` in both git and LocalWP.
- MySQL SSL is on unless `SKIP_MYSQL_SSL` is set.
- Staging `*.vercel.app` gets `noindex,nofollow` via `cil_robots()` / `cil_staging_robots_header()`. **This will not apply on the client domain** — intended.
- `netlify.toml` sets `Cache-Control: max-age=864000` on `/*` (including HTML and admin). The live site is Vercel, but that Netlify rule would be unsafe if used.

### Public staging checks that were **not** findings

| Check | Result |
|---|---|
| `GET /.git/HEAD` | 404 |
| `GET /.env` | 404 |
| `GET /wp-content/debug.log` | 404 |
| `GET /wp-config.php.bak` | 404 |
| `GET /phpinfo.php` | 404 |
| `GET /wp-config.php` | 200, **0 bytes** (PHP executed, not downloaded) |
| `GET /wp-includes/version.php` | 200, 0 bytes (not source disclosure) |
| Homepage secret-like strings (`AKIA`, `sk_live`, `AIza`, PEM headers) | none |

### Media store

- `content-fixtures/media/media-config.json` `base_url` is a public Vercel Blob URL (documented in-file as not a secret).
- `cil_media_url()` rejects `..` and limits key charset (`inc/media.php` 247–253).
- Aftercare MP4/PDF keys are intentionally public clinic assets. Bucket listing of *other* objects was not tested (could leak unpublished files if the store is listable). **Do not download clinical videos in a later test without approval.**

---

## 7. Dependency and plugin findings

| Component | Git / staging | LocalWP | Advisory status |
|---|---|---|---|
| WordPress | **7.1** | **7.1.3** | **Outdated.** 7.1.4 is the current security release (CIL-SEC-001). |
| PHP | Not bannered on staging | LocalWP stack (not probed) | Not verified remotely |
| Theme `circumcision-london` | `CIL_VERSION` 0.13.28 / style.css 0.12.0 | Same hashes on key PHP files | Custom; no CVE database |
| `serverlesswp` npm | lockfile **0.7.0** | n/a | No CVE identified in this pass |
| WP Offload Media Lite | **3.3.1** (15 Apr 2026) | **Not installed** | Historical CVE-2023-29197 patched in 3.2.2; unserialize issue patched in 3.2.6. **No unpatched CVE found for 3.3.1.** REST routes use `manage_options`. |
| SQLite Database Integration | **3.0.1** | **Not installed** | No CVE found. Upstream has 3.0.2+ (bugfix, not a confirmed vuln). |
| TiDB Compatibility | **1.0.2** | **Not installed** | Last tested to WP 6.8.1; concatenates WP_Query clauses (no extra SQLi identified). Only relevant if TiDB is used. |
| Default Twenty * themes | Present in `wp/` | Present | Unused if custom theme stays active; still shipped |
| Repuso “social-testimonials-and-reviews-widget” | **Absent** | Installed, readme **Stable tag 6.0.3** | Historical CVEs through 5.29 (e.g. CVE-2025-62071, CVSS 4.3) are **patched in 5.30+**. 6.0.3 is not a confirmed vulnerable version. **Do not copy this plugin to git/staging unless required.** |

`DISALLOW_FILE_MODS` prevents installing or updating plugins on staging through wp-admin. That is good against malware installs and bad against forgotten core updates.

---

## 8. Security strengths already present

- No custom `register_rest_route` or `wp_ajax_*` in the theme or `tidb-compatibility`.
- Content Sync apply: `manage_options`, `check_admin_referer`, typed `APPLY`, slug allowlist, SHA-256 verify; SEO apply does not rewrite `post_content`.
- Media helpers: attachment type checks, PDF-only documents, media-key charset and `..` rejection, `esc_url_raw` on base URL.
- Templates generally use `esc_html` / `esc_attr` / `esc_url`. Theme JS uses `textContent`, not `innerHTML`.
- Cliniko `postMessage` handler origin-checks, accepts string payloads only, regex-limits resize, caps height at 20000px.
- Section shortcode allowlists slugs before `get_template_part`.
- Staging `noindex` is locked to `*.vercel.app` with a hostname regex, not a naive suffix check.
- HTTPS forced in `wp-config.php`; HSTS present on Vercel; login cookie `secure; HttpOnly`.
- DB and object-storage secrets from environment; `serverless.yml` warns not to commit `PASSWORD`.
- `test/auth.json` gitignored; installer gated on `SERVERLESSWP_TESTING`.
- `.git`, `.env`, `debug.log`, and `wp-config.php` source were not publicly downloadable on staging.

---

## 9. Environment comparison (Desktop git vs LocalWP vs staging)

| Item | Desktop git | LocalWP | Staging |
|---|---|---|---|
| WordPress | 7.1 | 7.1.3 | 7.1 (generator) |
| Custom theme key files | baseline | **same SHA-256** for `functions.php`, `style.css`, `setup-pages.php`, `content-sync.php`, `media.php`, `content.php`, `header.php`, `footer.php` | built from git |
| Extra theme files | 2 HTML backups under `content-fixtures/backups/` | **126 files only on LocalWP** (page HTML backups, extra images, `.mov` videos) | git set only |
| `injectHost` / dynamic `WP_HOME` | yes | **no** (standard LocalWP config) | yes |
| Unique `AUTH_*` salts | **placeholders** | **unique** (redacted) | placeholders + WP DB fallback |
| `DISALLOW_FILE_MODS` | true | not set | true |
| `WP_DEBUG` | false | false | false (no debug output seen) |
| Plugins | Offload Media, SQLite integration, TiDB compatibility | **Repuso reviews widget only** | git plugin set |
| mu-plugins | ServerlessWP loaders | none | ServerlessWP |
| Uploads directory | not in git | present (contents not listed) | object storage / Blob |
| Admin | n/a | local only | publicly reachable |

**Do not copy LocalWP plugins, backups, or videos into git without review.** Those HTML backups and `.mov` files increase accidental-PII and deploy-size risk.

---

## 10. False positives / unverified items

| Item | Why it is not a confirmed vulnerability |
|---|---|
| Missing CSP | Hardening gap (CIL-SEC-007), not a working exploit |
| `callout urgent` / medical copy | Content issue, not a security defect |
| Offload Media `unserialize` | Settings are env-defined; historical issue patched in 3.2.6 |
| TiDB SQL concatenation | Uses WP_Query clauses, not raw `$_GET` |
| SQLite plugin 3.0.1 vs 3.0.2 | Newer bugfix exists; not shown to be a CVE |
| Repuso 6.0.3 | Installed only on LocalWP; known CVEs are in ≤5.29 |
| `GET /wp-config.php` 200 | Empty body; PHP ran |
| Upgrade-window RCE in 7.1.4 | Likely blocked by `DISALLOW_FILE_MODS`; not tested |
| Host-header exploit on Vercel | Code confirmed; live header test skipped |
| Public aftercare videos | Intentional clinical education media; store ACL not verified |

---

## 11. Recommended remediation order

Do not implement until this report is approved. Suggested sequence:

1. **Update WordPress to 7.1.4** in the Desktop repo (and LocalWP) via the ServerlessWP update workflow; redeploy staging. Re-check the generator tag.
2. **Pin `WP_HOME` / `WP_SITEURL`** to env; strip/allowlist `injectHost` in the Node handler.
3. **Move production data to MySQL** (or equivalent). Treat SQLite as preview-only.
4. **Restrict wp-admin / wp-login** at Vercel; disable XML-RPC; close REST user listing and author archives.
5. **Generate unique salts from env**; rotate after first production cutover.
6. **Add security headers** (frame ancestors, nosniff, referrer, then CSP).
7. **Stop seeding pages on public `init`.**
8. **Tighten `cil_rich_text()`** and confine blog sideload paths.
9. **Replace mailto callback** before handling real patient DOB; complete a DPIA/privacy review.
10. **Ignore `.vercel/` and `.env*`**; keep LocalWP-only plugins and backups out of git.
11. Delete or stop serving `readme.html`; remove the generator meta.

---

## 12. Pre-production security checklist

- [ ] WordPress 7.1.4+ in git, LocalWP, and staging
- [ ] `WP_HOME` / `WP_SITEURL` pinned to `https://www.circumcisioninlondon.co.uk` (or the agreed canonical host)
- [ ] Unique `AUTH_KEY` / salts per environment, from secrets manager / Vercel env
- [ ] MySQL (or other multi-writer DB) selected for production; SQLite not used for the client domain
- [ ] `DISALLOW_FILE_EDIT` / `DISALLOW_FILE_MODS` still on
- [ ] `WP_DEBUG` false; no `WP_DEBUG_DISPLAY`
- [ ] wp-admin restricted; XML-RPC off; REST users hidden; author archives redirected
- [ ] CSP + `frame-ancestors` compatible with Cliniko and the media host
- [ ] HSTS retained; HTTP→HTTPS verified on the **new** hostname (not tested here)
- [ ] Noindex removed **only** for the real domain; staging stays noindex
- [ ] Callback/booking flows covered by a processor under a DPA; no DOB in `mailto:` URLs
- [ ] Media bucket listing disabled; unpublished objects not world-readable
- [ ] Default Twenty themes not switchable by untrusted roles
- [ ] Repuso plugin and LocalWP HTML/video backups not shipped unless required
- [ ] `.vercel/` and `.env*` gitignored; no dumps or `debug.log` in git
- [ ] `tools/content/*.php` not in the serverless bundle
- [ ] Application passwords / unused admin users reviewed **in a controlled admin session**
- [ ] Backup and restore drill on MySQL, not only Blob SQLite
- [ ] Privacy notice matches actual processors (Cliniko, Vercel, Blob, email)
- [ ] Incident contact and admin 2FA decided before DNS cutover

---

## 13. Tests that require explicit approval before execution

These were **not** run:

1. Sending `injectHost` or a spoofed `Host` to staging (cache poisoning / URL rewrite).
2. POST to `xmlrpc.php` (`system.listMethods`, pingback, `system.multicall`).
3. Any login, password spray, or credential stuffing.
4. Submitting the callback form or Cliniko booking.
5. Uploading files or applying Content Sync / Blog pack.
6. Fetching `/wp-json/wp/v2/users/<id>` details beyond the collection shape, or printing slugs/emails.
7. Downloading aftercare videos/PDFs or listing the Blob store.
8. Exercising unpublished Customizer changeset disclosure (7.1.4).
9. Authenticated tests (privilege, XSS as Contributor, WXR export SQLi from 7.1.3).
10. Load, fuzz, or availability tests against Vercel.
11. Anything against `https://www.circumcisioninlondon.co.uk/`.

---

## 14. Document control

- Report path: `security-audit-report.md` (repository root)
- No application code, WordPress settings, plugins, or deployment config were modified
- Secret values, Vercel identifiers, test passwords, TLS key material, admin slugs, and comment bodies are omitted or redacted
