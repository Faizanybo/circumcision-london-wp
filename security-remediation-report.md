# Circumcision London WordPress — Security Remediation Report

**Date:** 29 October 2026  
**Source of truth:** Desktop git `C:\Users\HP\Desktop\Projects\circumcision-london-wp`  
**Branch:** `main` at `561ed91f` plus uncommitted remediation (not committed, not pushed, not deployed)  
**Original audit:** `security-audit-report.md` (CIL-SEC-001 through CIL-SEC-016)

This is an implementation report for the audit findings. It is not a production-readiness certificate. Staging still serves the previous deployment. LocalWP still runs WordPress 7.1.3 and the previous theme. Architecture-dependent risks stay open.

---

## 1. Executive summary

Safe, narrowly scoped remediations were applied in the Desktop repository only.

| Outcome | Count | IDs |
|---|---|---|
| Fixed in source (not live until deploy / LocalWP copy) | 11 | 001, 002, 003, 008, 009, 011, 013, 014, 015, 016, and 012 packaging |
| Partially fixed | 3 | 005, 006, 007, 010 (see register; 010 counted here) |
| Deferred / requires approval | 2 | 004 (SQLite), plus remaining 005 login wall, 006 salt rotation, 007 CSP enforce, 010 backend |

Nothing was committed, pushed, or deployed. Production `https://www.circumcisioninlondon.co.uk/` was not touched. `.vercel/README.txt` and `.vercel/project.json` remain on disk and are now gitignored (still untracked; identifiers not reproduced).

**Re-evaluation rule used here:** a finding is “fixed” only when the defective source path is closed *and* a safe check was actually run. Live staging, LocalWP runtime of the new theme, CSP compatibility, salt rotation, and database migration were **not** verified as passing.

---

## 2. Environment snapshot after remediation

| Environment | WordPress | Theme remediations | Notes |
|---|---|---|---|
| Desktop git | **7.1.4** (`wp/wp-includes/version.php`) | Present | Uncommitted |
| LocalWP `circumcision-london-wp.local` | **7.1.3** | **Absent** (`inc/security.php` not in LocalWP theme) | Site responds HTTP 200; it is the previous copy |
| Vercel staging | **7.1** (generator still advertised) | **Absent** | HEAD 29 Oct 2026 still missing nosniff/CSP/XFO on HTML; `/readme.html` 200; `/wp-json/wp/v2/users` 200 with `X-WP-Total: 1` |

Official current 7.1 security release confirmed from [WordPress 7.1.4](https://wordpress.org/news/2026/10/wordpress-7-1-4/) (28 October 2026). Next major is 7.2 (planned 8 December 2026). No later 7.1.x was published at the time of this work.

---

## 3. Finding register (re-evaluated)

| ID | Original severity / confidence | Status | Live? |
|---|---|---|---|
| CIL-SEC-001 | High / High | **Fixed in git** | No — staging 7.1, LocalWP 7.1.3 |
| CIL-SEC-002 | High / High | **Fixed in git** | No — not deployed; live injectHost test not run |
| CIL-SEC-003 | High / High | **Fixed in git** | No — write test not run |
| CIL-SEC-004 | High / Medium | **Deferred** | Architecture unchanged |
| CIL-SEC-005 | Medium / High | **Partially fixed** | XML-RPC/REST/authors in theme only; login still public; not deployed |
| CIL-SEC-006 | Medium / High | **Partially fixed** | Placeholders removed; env values **not** set; sessions not rotated |
| CIL-SEC-007 | Medium / High | **Partially fixed** | Headers + CSP Report-Only in repo; not deployed; CSP not enforced |
| CIL-SEC-008 | Medium / Medium | **Fixed in git** (style kept on purpose) | Not deployed; editor XSS not tested |
| CIL-SEC-009 | Medium / High | **Fixed in git** | Isolated path tests passed; no Content Sync apply |
| CIL-SEC-010 | Medium (privacy) / High | **Partially fixed** | DOB removed from `mailto:`; no secure backend |
| CIL-SEC-011 | Low / High | **Fixed** | `git check-ignore` verified |
| CIL-SEC-012 | Low / High | **Partially fixed** | Excluded from Vercel bundle; key still tracked |
| CIL-SEC-013 | Low / High | **Fixed in git** | Staging still serves `readme.html` and generator |
| CIL-SEC-014 | Low / Medium | **Fixed in git** | Unit test passed |
| CIL-SEC-015 | Low / High | **Fixed** (documentation) | Keyless fallback unchanged |
| CIL-SEC-016 | Low / High | **Fixed in git** | CLI guard present; `tools/` excluded from bundle |

---

## 4. Per-finding detail

### CIL-SEC-001 — WordPress core outdated

- **Original:** High / High
- **Status:** Fixed in git. **Unresolved on staging and LocalWP.**
- **Root cause:** Tracked core was 7.1. `DISALLOW_FILE_MODS` blocks dashboard updates. Windows `core.autocrlf` made `npm run wp:update` skip checksum-mismatched files in the working tree.
- **What changed:** Official ServerlessWP updater was run against an LF checkout (`git -c core.autocrlf=false checkout-index`) with Git `unzip` on PATH. 120 files that differed from 7.1 were copied into `wp/`. Custom theme, custom plugins, `wp-config.php`, mu-plugins, and uploads were not replaced. `$wp_version = '7.1.4'`.
- **Security rationale:** 7.1.4 closes stored XSS in blocks / Interactivity API, unpublished Customizer changeset discovery, notes authorization, and an upgrade-window RCE weakness.
- **Tests:** `version.php` contains `7.1.4`. `php -l` on 39 changed core PHP files: no syntax errors. Isolated theme tests unrelated to core also passed.
- **Remaining risk:** Staging and LocalWP are still outdated. Windows Git will re-save LF core files as CRLF when it touches them; future `wp:update` runs should use an LF tree. Upgrade-window RCE remains mitigated here by `DISALLOW_FILE_MODS` (not re-tested).
- **Manual:** Copy/update LocalWP core to 7.1.4 after review. Deploy git to staging after approval. Do not use Dashboard → Updates on Vercel.

### CIL-SEC-002 — Host-header / `injectHost` poisoning

- **Original:** High / High
- **Status:** Fixed in git. **Unverified on staging (live header test not run).**
- **Root cause:** `WP_HOME` / `WP_SITEURL` were `https://` + unvalidated `injectHost` / `HTTP_HOST`.
- **Changed files:** `wp/wp-config.php`, `api/request-guard.js`, `api/vercel.js`, `api/index.js`, `.env.example`
- **Security rationale:** Node strips client `injectHost` and `Expect`, then sets `injectHost` to the platform `Host` only. PHP uses `WP_HOME` / `WP_SITEURL` from the environment when set; otherwise only allowlisted hosts (`localhost`, hosts from `WP_HOME`, `WP_ALLOWED_HOSTS`, `*.vercel.app`, `*.local`). Untrusted hosts do not define site URLs.
- **Tests:** `node test/request-guard.js` passed (client `injectHost` discarded; platform Host reapplied). PHP syntax of `wp-config.php` passed. **No** staging `injectHost` request was sent.
- **Remaining risk:** Until deploy, staging is still poisonable in source terms. `*.vercel.app` is a broad preview allowlist; production must set `WP_HOME` so an arbitrary `*.vercel.app` cannot become the canonical URL. Do not set `WP_HOME` to the production domain on staging.
- **Manual (Vercel staging):** leave `WP_HOME` empty **or** set it to `https://circumcision-london-wp.vercel.app`. Optional `WP_ALLOWED_HOSTS`. LocalWP uses its own `wp-config.php` and is unaffected until copied.

### CIL-SEC-003 — Anonymous page seeding / rewrite flush

- **Original:** High / High
- **Status:** Fixed in git. Write-based validation **not** run.
- **Root cause:** `init` seeded pages and flushed rewrites for anonymous traffic when the seed version option was missing or stale.
- **Changed files:** `inc/setup-pages.php`, `inc/pages/home-page.php`
- **Security rationale:** `cil_is_page_setup_request()` now allows only admin, WP-CLI, or `after_switch_theme`. Public `init` returns immediately. A 60-second option lock (`_cil_seed_lock`) reduces duplicate inserts. Existing pages, slugs, IDs, and Gutenberg content are still never overwritten by default.
- **Tests:** PHP syntax passed. Seeding was **not** executed against LocalWP, staging, or production.
- **Remaining risk:** First wp-admin load or theme activation after a version bump will still write (authorized). SQLite races during that admin write remain CIL-SEC-004. Fresh environments need an administrator or CLI visit to create missing pages — they will not appear from anonymous traffic.
- **Manual:** After deploy, open wp-admin once (or run WP-CLI) if any managed page is missing. Do not bump `CIL_SERVICE_PAGES_VERSION` on a live site without that admin step.

### CIL-SEC-004 — Serverless SQLite concurrency

- **Original:** High / Medium
- **Status:** **Deferred. Requires approval.** No database, credential, or data change was made.
- **Root cause:** Staging/production-if-unchanged uses SQLite in object storage. Concurrent writers can lose updates or corrupt the file. `wp-config.php` already disables cron on wp-admin/login and limits revisions for that reason.
- **Changed files:** none (plan only)
- **Security rationale:** An in-place migration during this task would risk patient-facing content and editor work. The defect is architectural.
- **Tests:** None (contention tests need approval).
- **Remaining risk:** **Open.** Any remaining admin seeding, comments, cron, or overlapping editor saves on SQLite can still fail. This finding **blocks production readiness** if SQLite is kept for the client domain.

#### Migration plan (do not execute without approval)

1. **Decide the target:** MySQL 8 / MariaDB, or TiDB if the existing `tidb-compatibility` plugin will be used. ServerlessWP already prefers MySQL when `DATABASE`, `USERNAME`, `PASSWORD`, and `HOST` are all set.
2. **Inventory:** export a list of posts, pages, users (IDs only), options, and media attachment counts from a **backup copy** of the SQLite file, not from live Blob.
3. **Backup:** snapshot the Vercel Blob / S3 SQLite object and the media store. Record the object key and time. Keep the snapshot until the first successful production week.
4. **Provision** an approved multi-writer database. Do not reuse LocalWP `root` credentials. Enable TLS (`MYSQL_CLIENT_FLAGS` is already set unless `SKIP_MYSQL_SSL`).
5. **Migrate offline:** restore the SQLite snapshot to a disposable machine; convert with a maintained sqlite-to-mysql path or WordPress export/import; load into the new database. Do not point staging at the new database until verification.
6. **Verify:** post/page counts, `page_on_front`, permalinks, users can log in, media URLs still resolve via Offload Media / Blob, Cliniko booking page, callback form markup.
7. **Cut staging:** set the four MySQL env vars on a preview deployment first; confirm SQLite is **not** selected (ServerlessWP order: MySQL if all four are set). Keep Blob for **media**, not for the database, unless policy says otherwise.
8. **Rollback:** unset MySQL env vars and restore the SQLite Blob object / previous env. DNS/production cutover is a separate approval.
9. **After cutover:** disable SQLite plugin if unused; keep `WP_POST_REVISIONS` reasonable; turn cron back on if MySQL can accept it.
10. **Do not** run this against production or shared staging in the same change window as the 7.1.4 deploy unless explicitly approved.

### CIL-SEC-005 — Login, XML-RPC, REST users, author enumeration

- **Original:** Medium / High
- **Status:** Partially fixed in git. **Login/admin remain publicly routed.**
- **Root cause:** No XML-RPC disable, unauthenticated `/wp/v2/users` listing, `?author=` redirects to `/author/<slug>/`.
- **Changed files:** `inc/security.php` (required from `functions.php`)
- **Security rationale:** `xmlrpc_enabled` false and methods emptied (no Jetpack/app XML-RPC in this repo). Anonymous REST user routes require `list_users`. Public `?author=` and author archives 301 to home. Logged-in editors keep default REST user callbacks so Gutenberg `/wp/v2/users` and `/wp/v2/users/me` still work. REST is **not** disabled globally.
- **Tests:** PHP syntax passed. Staging GET `/wp-json/wp/v2/users` is still 200 with `X-WP-Total: 1` because this is not deployed. XML-RPC POST was **not** sent.
- **Remaining risk:** `wp-login.php` and `wp-admin/` are still on the Vercel catch-all. Code cannot add a safe IP allowlist without lockout risk.
- **Manual (Vercel dashboard — do not enable without approval):**
  1. Vercel → project → **Firewall**.
  2. Add a custom rule that challenges or denies `/wp-login.php` and `/wp-admin` **HTML/PHP** from the public internet.
  3. Allow the clinic’s editor IPs **or** use Vercel Password Protection / SSO **only if** it is scoped so the **public site** stays public. Project-wide Deployment Protection would hide the whole marketing site — do not turn that on for production.
  4. Leave `/wp-admin/*.css`, `*.js`, images cacheable for the login page, or the login form will look broken.
  5. Confirm a logged-in editor can open Gutenberg, media, and `/wp-json/wp/v2/users/me` after the rule.
  6. Enable WordPress 2FA via an approved plugin in a later change; not done here.

### CIL-SEC-006 — Default authentication salts

- **Original:** Medium / High
- **Status:** Partially fixed. Placeholders removed from tracked `wp-config.php`. **No secrets generated or stored.**
- **Root cause:** Eight `AUTH_*` / `*_SALT` defines were the placeholder phrase.
- **Changed files:** `wp/wp-config.php`, `.env.example`
- **Security rationale:** Values come from the environment. Empty values keep WordPress’s database-stored fallback (same as today when placeholders were detected). Unique env values are stronger. **Rotating salts logs everyone out.**
- **Tests:** `git grep` of `wp-config.php` has no placeholder phrase (core `wp-config-sample.php` still has the upstream sample, which is unused). Secrets were not printed.
- **Remaining risk:** Staging still runs on old config until deploy. After deploy, empty env salts still use DB fallback until you set unique values.
- **Manual (staging — after approval; do not rotate production):**
  1. Generate eight phrases from `https://api.wordpress.org/secret-key/1.1/salt/` (do not commit them).
  2. Vercel → Project → Settings → Environment Variables → Preview (and Development if used): `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`.
  3. Redeploy. Expect every staging login cookie to die.
  4. LocalWP already has unique salts in **its** `wp-config.php`. Do not overwrite them with the git file without copying those LocalWP salts into LocalWP env instead. Git `wp-config.php` is for Vercel.
  5. Production rotation needs a separate approved window.

### CIL-SEC-007 — Missing security headers / Netlify HTML cache

- **Original:** Medium / High
- **Status:** Partially fixed. CSP is **report-only**. Not live on staging.
- **Root cause:** HTML responses had HSTS + staging `noindex` only. Netlify `/*` cached HTML/admin for 10 days.
- **Changed files:** `vercel.json`, `netlify.toml`, `inc/security.php`
- **Security rationale:** `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `X-Frame-Options: SAMEORIGIN`, `Permissions-Policy`, and `Content-Security-Policy-Report-Only` covering self, GTM/GA, Vercel Blob media, Google Maps, Gravatar, Cliniko (`beverley-clinic.au1.cliniko.com`), `mailto:` form-action. Staging `X-Robots-Tag` / theme `noindex` for `*.vercel.app` is unchanged. Vercel static 10-day cache still applies only to theme/plugin/core static extensions. Netlify catch-all HTML cache removed; remaining Netlify cache is theme/plugin/includes static paths. `license.txt` still served (licensing).
- **Tests:** `vercel.json` parses as JSON. Staging HEAD on 29 Oct 2026 still **lacks** these headers (old deploy). CSP enforce **not** enabled.
- **Remaining risk:** Report-only does not block clickjacking/XSS. A future enforce pass may break Gutenberg (`unsafe-inline` / `unsafe-eval` already allowed for that reason), Cliniko payment iframe, maps, or Blob video. Wildcard `https://*.google.com` is broad.
- **Manual:** After deploy, `curl -sI` homepage and `/book/` (or equivalent booking URL). Check Cliniko iframe, maps, video, and a Gutenberg editor session. Only then consider dropping `-Report-Only` (separate approval). Vercel HSTS is platform-level — do not remove it.

### CIL-SEC-008 — Broad rich HTML allowlist

- **Original:** Medium / Medium
- **Status:** Fixed in git, with an explicit keep of inline `style`.
- **Root cause:** `cil_rich_text()` allowed `svg`/`path`/`rect` and any kses default URL protocol. Block attribute `html` had no `sanitize_callback`.
- **Changed files:** `inc/content.php`, `inc/blocks.php`
- **Security rationale:** SVG tags removed (icons already go through `cil_icon()`, not rich HTML). Protocols limited to `http`, `https`, `mailto`, `tel`. `cil/rich-html` sanitizes on save. **Inline `style` kept** because clinic copy depends on it (`color:var(--blue-deep)`, privacy-link styling, lists). Removing `style` would change the live design.
- **Tests:** PHP syntax passed. No editor payload test (would need a non-admin account and approval).
- **Remaining risk:** Editors can still store CSS in `style`. Administrators with `unfiltered_html` bypass kses in the editor. This is defense-in-depth, not a substitute for 7.1.4 and role hygiene.

### CIL-SEC-009 — Blog media sideload path traversal

- **Original:** Medium / High
- **Status:** Fixed in git.
- **Root cause:** Fixture `file` was concatenated and copied without `realpath()` confinement.
- **Changed files:** `inc/content-sync-blog.php`, `test/blog-sideload-path.php`
- **Security rationale:** `cil_blog_resolve_fixture_media_path()` rejects NUL, `..` segments, non-image extensions (`jpg|jpeg|png|gif|webp`), missing files, and any `realpath()` result outside the fixtures directory. SHA-256 compare is unchanged.
- **Tests:** `php test/blog-sideload-path.php` → `OK blog-sideload-path` (legitimate jpg allowed; `../secret.php`, in-tree php, outside jpg rejected). Content Sync / Blog pack **not** applied.
- **Remaining risk:** Privileged apply can still import allowed images that hash-match. That is the intended workflow.

### CIL-SEC-010 — Callback form DOB in `mailto:`

- **Original:** Medium (privacy) / High
- **Status:** Partially fixed. **No server-side processor.**
- **Root cause:** Theme JS put every FormData field, including `dob`, into a `mailto:` URL. UI implied the clinic had the enquiry.
- **Changed files:** `template-parts/callback-form.php`, `assets/js/theme.js`
- **Security rationale:** The DOB field remains on screen (required) so the layout and promise to collect it are not silently dropped. It is **excluded** from the email draft. Helper text and the success status state that DOB is not emailed and should be given when the clinic calls. Name, phone, and message still go through the visitor’s mail client. No new backend, persistence, or third party.
- **Tests:** Code inspection only. The form was **not** submitted. LocalWP still has the old JS. Staging still has the old JS.
- **Remaining risk:** Name/phone/message still travel via `mailto:` (mail client, history, logs). DOB is still collected in the browser (not sent). This is not a clinic-controlled submission, not rate-limited, and not a DPA-covered processor.
- **Manual / approval for a real fix:** nonce + rate limit + `wp_mail` or a DPA-covered form vendor; store the minimum; privacy-notice update. Do not build that in this change.

### CIL-SEC-011 — `.gitignore` gaps

- **Original:** Low / High
- **Status:** Fixed.
- **Root cause:** `.env*`, `.vercel/`, dumps, `debug.log`, uploads were not ignored.
- **Changed files:** `.gitignore` (existing rules kept, including `test/auth.json`)
- **Tests:**

```
git check-ignore -v .env .env.local .vercel/project.json .vercel/README.txt test/auth.json wp/wp-content/debug.log dump.sql
```

All matched as ignored. `test/test-key.pem` matches `*.pem` for **new** adds; it remains **tracked**.
- **Remaining risk:** Already-tracked secrets would not be untracked automatically (none found in this pass besides the test key). `.vercel/` files still exist locally.
- **`.vercel/` status:** `README.txt` and `project.json` are present on disk, untracked, now ignored. Not deleted. IDs not printed.

### CIL-SEC-012 — Tracked test TLS key and installer credentials

- **Original:** Low / High
- **Status:** Partially fixed (packaging). Files **not** deleted or untracked.
- **Root cause:** `test/test-key.pem` and `test/installer.php` (hardcoded test user password, gated on `SERVERLESSWP_TESTING=1`) are in git. They were already outside Vercel `includeFiles` (`wp/**` only).
- **Changed files:** `test/installer.php` (comment only), `vercel.json` `excludeFiles` `{test/**,tools/**,.git/**,.vercel/**}`
- **Security rationale:** Keep Playwright/e2e working. Make the bundle exclusion explicit. Do not reuse the test password or key anywhere live.
- **Tests:** `git ls-files` still lists `test/test-key.pem` and `test/installer.php`. Key/password values not printed. Deploy artifact listing was not downloaded from Vercel.
- **Remaining risk:** Anyone with git history has the test key and password. Harmless if never copied to staging. `*.pem` ignore only affects untracked PEMs.

### CIL-SEC-013 — Fingerprinting

- **Original:** Low / High
- **Status:** Fixed in git. Staging still fingerprints.
- **Root cause:** `readme.html` 200, generator meta, `X-Serverlesswp-Binary`.
- **Changed files:** `vercel.json` (404 `/readme.html`), `api/request-guard.js` (404 + strip header), `inc/security.php` (`remove_action( 'wp_head', 'wp_generator' )`, empty `the_generator`)
- **Security rationale:** `license.txt` is still served (required licensing text). Static assets and Gutenberg routes are unchanged. Catch-all still goes to `api/vercel.js`.
- **Tests:** Guard unit test 404s `/readme.html` and strips the binary header. Staging HEAD `/readme.html` is still 200 (old deploy). Staging homepage still sends `X-Serverlesswp-Binary: false`.
- **Remaining risk:** `Server: Vercel` remains. Version can still leak from other endpoints until 7.1.4 is deployed and generator is gone.

### CIL-SEC-014 — JSON-LD encoding

- **Original:** Low / Medium
- **Status:** Fixed in git.
- **Root cause:** `wp_json_encode` without `JSON_HEX_TAG` inside `<script type="application/ld+json">`.
- **Changed files:** `inc/security.php` (`cil_json_ld_encode()`), `functions.php` (`cil_inner_page_head()`), `test/json-ld-encode.php`
- **Tests:** `php test/json-ld-encode.php` → `OK json-ld-encode` (`</script>` absent; `\u003C` present). Schema fields not changed.
- **Remaining risk:** None material if `cil_json_ld_encode()` stays on that output path.

### CIL-SEC-015 — Google Maps Embed API key in iframe URL

- **Original:** Low / High
- **Status:** Fixed (documentation). Integration unchanged.
- **Root cause:** Optional Customizer key is concatenated into a public iframe `src`.
- **Changed files:** `functions.php` Customizer copy; `inc/content.php` docblock already described visibility and the keyless fallback.
- **Security rationale:** A key in an iframe URL is visible to every visitor. Restrict it in Google Cloud to **Maps Embed API** and HTTP referrers for the real site (and staging if used). Leave empty to keep the keyless maps URL. No key was invented or moved into frontend JS.
- **Tests:** Code review. Staging homepage previously had no embed key (audit). Not re-scraped for a key.
- **Manual:** Google Cloud Console → Credentials → API restrictions: Maps Embed API only → Website restrictions: `https://www.circumcisioninlondon.co.uk/*` and, if needed, `https://circumcision-london-wp.vercel.app/*`. Do not enable billing-unbounded APIs on this key.

### CIL-SEC-016 — Content CLI scripts without CLI guard

- **Original:** Low / High
- **Status:** Fixed in git.
- **Root cause:** `tools/content/apply-*.php` and `create-location-pages.php` loaded `wp-load.php` with no SAPI check.
- **Changed files:** `tools/content/cli-only.php`; require added at the top of each apply script and `create-location-pages.php`; `vercel.json` `excludeFiles` includes `tools/**`. `includeFiles` remains `wp/**` plus serverlesswp assets.
- **Tests:** `php tools/content/cli-only.php` as CLI → exit 0. PHP syntax of all apply scripts passed. Scripts were **not** run against any database.
- **Remaining risk:** DocumentRoot must never be the repo root. Bundle exclusion depends on Vercel `includeFiles` staying `wp/**`.

---

## 5. Files changed (repository)

### New

- `wp/wp-content/themes/circumcision-london/inc/security.php`
- `api/request-guard.js`
- `tools/content/cli-only.php`
- `.env.example`
- `test/json-ld-encode.php`
- `test/blog-sideload-path.php`
- `test/request-guard.js`
- `security-remediation-report.md` (this file)
- `security-audit-report.md` (from the prior audit; not modified in this pass)

### Application / config

- `wp/wp-config.php`
- `api/vercel.js`, `api/index.js`
- `vercel.json`, `netlify.toml`, `.gitignore`
- Theme: `functions.php`, `inc/setup-pages.php`, `inc/pages/home-page.php`, `inc/content.php`, `inc/blocks.php`, `inc/content-sync-blog.php`, `template-parts/callback-form.php`, `assets/js/theme.js`
- `tools/content/apply-*.php`, `tools/content/create-location-pages.php`
- `test/installer.php` (comment only)

### WordPress core

- 120 files copied from the official 7.1.4 update (admin, includes, blocks, Interactivity API, cert bundle, `version.php`, etc.). Theme, plugins, and `wp-config.php` were not overwritten by a generic install.
- `git diff --shortstat` at report time: **147 files changed, 7635 insertions, 5782 deletions** (core + theme/config). Untracked files listed above are extra.

### Preserved / not committed

- `.vercel/README.txt`, `.vercel/project.json` (on disk, gitignored, untracked)
- `test/test-key.pem` (still tracked)
- LocalWP files (not modified)
- No production or Vercel dashboard changes

---

## 6. Tests

### Passed

| Check | Result |
|---|---|
| `wp/wp-includes/version.php` | `$wp_version = '7.1.4'` |
| Official 7.1.4 announcement | Current 7.1 security release as of 28 Oct 2026 |
| `php -l` custom PHP (theme, config, tools, tests) | 0 failures |
| `php -l` 39 changed core PHP files | 0 failures |
| `php test/json-ld-encode.php` | `OK json-ld-encode` |
| `php test/blog-sideload-path.php` | `OK blog-sideload-path` |
| `php tools/content/cli-only.php` | exit 0 on CLI |
| `node --check` `api/request-guard.js`, `api/vercel.js`, `api/index.js` | OK |
| `node test/request-guard.js` | `OK request-guard` |
| `JSON.parse(vercel.json)` | OK |
| `git check-ignore` for `.env`, `.vercel/*`, `test/auth.json`, dumps, `debug.log` | ignored |
| Staging GET homepage (pre-deploy baseline) | 200, HSTS present, `X-Robots-Tag: noindex, nofollow` still present |
| LocalWP GET `http://circumcision-london-wp.local/` | 200 (previous theme / 7.1.3) |

### Failed

None of the executed checks failed.

### Not possible / not run (do not treat as passing)

- Deployed staging verification of any code fix (not deployed)
- Browser E2E of the **new** theme (LocalWP does not contain `inc/security.php` or the other theme edits; no browser automation against the git tree)
- Gutenberg open/save/reopen, media library, video/PDF, navigation, responsive layout of the remediations
- WordPress bootstrap of the Desktop `wp/` tree (no serverless SQLite/MySQL env in this shell)
- Live `injectHost` / cache-poisoning test
- XML-RPC POST, login, password tests
- REST user listing **after** deploy
- Content Sync / blog media apply
- Callback form submit / real `mailto:`
- CSP violation monitoring / enforce
- SQLite write-contention
- Secret rotation
- Production domain
- Download of the Vercel serverless file list

---

## 7. LocalWP validation steps (after you copy the theme — not done)

LocalWP is a **separate** copy. Do not assume it matches git. Suggested sequence **after you decide to copy**:

1. Back up `C:\Users\HP\Local Sites\circumcision-london-wp\app\public`.
2. Update LocalWP core to **7.1.4** with Local’s WordPress updater (or copy only core files, never LocalWP `wp-config.php` from git — git config is serverless-oriented and would break Local MySQL).
3. Copy **only** `wp-content/themes/circumcision-london` from the Desktop repo over the LocalWP theme. Do not copy `wp-config.php`, `test/`, or `.vercel/`.
4. Open `http://circumcision-london-wp.local/` — homepage, header dropdowns, mobile nav.
5. Open a Gutenberg page, change a `cil/rich-html` block, save, reload. Confirm media insert still stores attachment IDs.
6. View-source: no `<meta name="generator" content="WordPress …">`. JSON-LD still present.
7. Logged-out: `/wp-json/wp/v2/users` should be 401/403, not a user list. `/?author=1` should 301 to `/`. `/xmlrpc.php` GET may still 405; do not POST.
8. Logged-in editor: `/wp-json/wp/v2/users/me` must still work.
9. Callback form: fill test data, submit **without sending the email**; confirm the draft body has no DOB line.
10. Do not run Content Sync apply. Do not paste git `AUTH_*` into LocalWP if Local already has unique salts.

---

## 8. Git commands to review the diff

Nothing has been committed. From the repo root:

```bat
git status
git diff --stat
git diff -- wp/wp-config.php api/ vercel.json netlify.toml .gitignore
git diff -- wp/wp-content/themes/circumcision-london
git diff -- tools/content
git diff -- wp/wp-includes/version.php
git check-ignore -v .env .vercel/project.json test/auth.json
```

Untracked files to inspect: `.env.example`, `api/request-guard.js`, `inc/security.php`, `tools/content/cli-only.php`, the three `test/*.php`/`test/request-guard.js` helpers, and both security reports.

Do not `git add .vercel`. Do not commit unless asked.

---

## 9. Changes that require approval before they happen

| Item | Why |
|---|---|
| Deploy / DNS / production cutover | Out of scope; would publish 7.1.4 and headers |
| Set `WP_HOME` to the client domain | Wrong host on staging would break previews and canonicals |
| Generate/rotate production salts | Logs everyone out |
| Set staging env salts | Logs staging editors out; needs a window |
| MySQL/MariaDB/TiDB migration | CIL-SEC-004 |
| Vercel Firewall / IP allowlist / SSO on `/wp-login.php` or `/wp-admin` | Lockout risk |
| Enforce CSP (drop Report-Only) | May break Cliniko, maps, Gutenberg, video |
| Callback form backend / third-party processor | Privacy / DPA |
| Live `injectHost` or cache-poisoning test | Can poison shared staging cache |
| Content Sync or blog media apply on shared env | Writes |
| Untrack `test/test-key.pem` | Breaks e2e unless keys are generated in CI |
| Copy this `wp-config.php` onto LocalWP | Would break Local MySQL and HTTPS assumptions |

---

## 10. Remaining risks that prevent production readiness

1. **CIL-SEC-004 SQLite** is unchanged. Do not put the client domain on ServerlessWP SQLite with overlapping editors or form writes.
2. **Staging and LocalWP are still on old code.** Git fixes are inert until deploy and a careful LocalWP copy.
3. **`wp-login.php` / `wp-admin` are still public** on the Vercel catch-all until a dashboard rule is approved.
4. **Auth salts are not unique in env yet.** After deploy they stay at WordPress DB fallback until set.
5. **CSP is report-only** and untested in a real browser session with Cliniko, maps, video, and Gutenberg.
6. **Callback form is still not a clinic-controlled processor.** DOB is out of `mailto:`; other personal data is not.
7. **`WP_HOME` is not pinned to the production host** (correct for this repo; required before DNS cutover, with approval).
8. **Windows CRLF vs wordpress.org LF** will keep making `wp:update` skip files if run directly in the working tree. Use an LF export.
9. **Test TLS key remains in git history.**
10. **Plugin gap vs LocalWP:** do not copy Repuso or LocalWP HTML/video backups into git (audit §7 / §9).

The site is **not** production-ready. Treat CIL-SEC-004, undeployed 7.1.4, public wp-admin, unset salts, and the mailto enquiry path as open.

---

## 11. Document control

- Implementation limited to the Desktop git worktree
- No commit, push, deploy, secret rotation, or cloud-resource change
- No live exploit payloads, XML-RPC method calls, or staging cache-poisoning tests
- Secret values, Vercel project/org IDs, test passwords, and PEM material are omitted
