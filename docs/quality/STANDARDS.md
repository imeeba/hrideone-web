# STANDARDS — มาตรฐานคุณภาพของเว็บ HRI DeOne

> นิยามว่า "เว็บที่ดี" ของโปรเจกต์นี้คืออะไร วัดด้วยอะไร เกณฑ์ผ่านเท่าไร และตอนนี้ผลเป็นอย่างไร
> ใช้เป็น **Definition of Done** ของทุกรอบงาน และเป็นหลักฐานที่ส่งให้ลูกค้าดูได้
> สร้าง 2026-10-26 (Phase 1) · อัปเดตผลในข้อ 3 ทุกครั้งที่ปิดงาน (ดู [`HANDOFF.md`](../HANDOFF.md) ข้อ 9)

## 1. หลักการ

- **ทุกเกณฑ์มีรหัส** (เช่น `PERF-1`) เพื่อให้ CI, scorecard และ prompt อ้างถึงได้ตรงกัน
- **แยก 3 ช่วงเวลา**
  - **Dev gate** — วัดได้ตอนนี้ใน local / Playground ต้องผ่านก่อน merge ทุกครั้ง
  - **Pre-launch** — ต้องผ่านบน staging ของ Ruk-com ก่อนสลับโดเมนจริง
  - **Post-launch** — วัดจากผู้ใช้จริงบน production (ต้องวางระบบเก็บข้อมูลไว้ก่อน launch เพื่อให้มี baseline ตั้งแต่วันแรก)
- **Lab ≠ Field**: ตัวเลขจาก `perf.py` ใน workspace ใช้เทียบก่อน/หลังเท่านั้น ไม่ใช่ความเร็วที่ผู้ใช้เจอ ตัวตัดสินจริงคือ Core Web Vitals จากผู้ใช้จริง (CrUX / Search Console)
- **เกณฑ์ธุรกิจสำคัญที่สุด** — เว็บเร็วและสวยแต่ lead ไม่เพิ่ม = ไม่สำเร็จ
- เกณฑ์ที่ตั้งใจเข้มกว่ามาตรฐาน ระบุไว้ชัด (เช่น tap target 44px)

## 2. เกณฑ์

### BIZ — ผลทางธุรกิจ

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| BIZ-1 | จำนวน lead ที่ส่งสำเร็จ / เดือน | **รอ Jimmi + ลูกค้า** (เทียบ baseline เว็บเดิม) | CPT `hri_lead` + Google Sheet, event `generate_lead` | Post |
| BIZ-2 | Conversion rate หน้าคอร์ส → ส่งฟอร์ม | รอตั้ง | analytics funnel | Post |
| BIZ-3 | คลิก CTA หลัก (ขอใบเสนอราคา, LINE, สมัคร Public) | รอตั้ง | analytics event | Post |
| BIZ-4 | Lead จากแต่ละภาษา (TH/EN/JA) | รายงานแยกได้ | analytics + ฟิลด์ภาษาใน lead | Post |
| BIZ-5 | ทุกหน้าหลักมี CTA หลักเหนือ fold + CTA ท้าย section | ครบทุกหน้า | ตรวจ pattern / screenshot | Dev |

### PERF — ความเร็ว

| รหัส | เกณฑ์ | มาตรฐาน | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|---|
| PERF-1 | Lighthouse mobile ทุกแบบหน้า | — | gate **≥90** · เป้า ≥95 | `scripts/perf.py` (exit 1 ถ้า <90) | Dev |
| PERF-2 | CLS (lab) | Core Web Vitals | **<0.1** | `perf.py` + Playwright | Dev |
| PERF-3 | น้ำหนักหน้าแรก (ไม่รวมวิดีโอ) | — | **<500 KB** | `perf.py` | Dev |
| PERF-4 | ไม่มี JS บล็อก render · โหลด JS เฉพาะหน้าที่ใช้ · ไม่มี jQuery หน้าเว็บ | — | ครบ | ตรวจโค้ด | Dev |
| PERF-5 | Lighthouse บน staging จริง (Brotli, HTTP/2+, Rcache) | — | ≥90 ทุกหน้า | `perf.py --base https://<staging>` | Pre |
| PERF-6 | LCP / INP / CLS ของผู้ใช้จริง (เปอร์เซ็นไทล์ที่ 75) | Core Web Vitals | LCP ≤2.5 s · INP ≤200 ms · CLS ≤0.1 | Search Console → Core Web Vitals, PageSpeed Insights (field) | Post |
| PERF-7 | TTFB หน้า cache | — | ≤0.8 s | PageSpeed Insights / WebPageTest | Pre/Post |

### A11Y — การเข้าถึง

| รหัส | เกณฑ์ | มาตรฐาน | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|---|
| A11Y-1 | Contrast ตัวอักษร | WCAG 2.2 AA 1.4.3 | ≥4.5:1 (ตัวใหญ่ ≥3:1) | `scripts/measure.py` | Dev |
| A11Y-2 | พื้นที่กด | WCAG 2.5.8 (AA = 24px) · **โปรเจกต์ใช้ 44px** (ระดับ AAA 2.5.5) | ≥44×44 px (ยกเว้นลิงก์กลางประโยค) | `measure.py` | Dev |
| A11Y-3 | ไม่มี horizontal overflow ที่ 1280 / 1220 / 1024 / 768 / 375 | WCAG 1.4.10 Reflow | 0 | `measure.py` | Dev |
| A11Y-4 | ตรวจอัตโนมัติด้วย axe-core | WCAG 2.2 AA | 0 serious / critical | **ยังไม่มี** → Phase 2 | Dev |
| A11Y-5 | ใช้งานด้วยคีย์บอร์ดได้ครบ (เมนู, ตัวกรอง, ฟอร์ม, slider, FAQ) + focus มองเห็นได้ + skip link | WCAG 2.1.1, 2.4.7 | ครบ | สคริปต์ Playwright กด Tab **ยังไม่มี** → Phase 2 | Dev |
| A11Y-6 | รูปมี alt ที่มีความหมาย (โลโก้ลูกค้า = ชื่อบริษัท) · ไอคอนตกแต่ง `aria-hidden` | WCAG 1.1.1 | ครบ | axe + ตรวจ seed | Dev |
| A11Y-7 | เคารพ `prefers-reduced-motion` (วิดีโอ hero, slider) | WCAG 2.3.3 | ครบ | ตรวจด้วย Playwright | Dev |

### SEO — การค้นหา และ AI search

| รหัส | เกณฑ์ | มาตรฐาน | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|---|
| SEO-1 | ทุก URL ใน sitemap: 200, title, description, canonical = ตัวเอง, ไม่ noindex, `html lang` ถูก | Google Search Essentials | 0 ปัญหา | `scripts/seo-check.py` | Dev |
| SEO-2 | hreflang ครบ + ชี้กลับ + x-default | Google hreflang | 0 ปัญหา | `seo-check.py` | Dev |
| SEO-3 | JSON-LD: 1 `@graph`/หน้า, id อ้างอิงถึงจริง, field ครบตามประเภท | schema.org | 0 ปัญหา | `seo-check.py` | Dev |
| SEO-4 | JSON-LD ผ่าน validator ภายนอก | Google Rich Results Test, validator.schema.org | 0 error | ตรวจมือ (workspace เข้าไม่ได้) | Pre |
| SEO-5 | robots.txt อนุญาตบอต AI search · ทุกลิงก์ใน llms.txt ตอบ 200 | — | ผ่าน | `seo-check.py` | Dev |
| SEO-6 | URL เก่าทั้งหมด 301 ไปหน้าใหม่ · ไม่มีลิงก์ภายในเสีย | — | 0 ลิงก์เสีย · 0 404 จาก URL เก่า | crawl + รายการ URL เว็บเดิม (งาน E) | Pre |
| SEO-7 | Index coverage, impressions, clicks ไม่ตกหลังย้าย (เทียบ 4 สัปดาห์ก่อนย้าย) | — | ไม่ลดเกิน 10% หลัง 8 สัปดาห์ | Search Console + Bing Webmaster | Post |

### SEC — ความปลอดภัย

| รหัส | เกณฑ์ | มาตรฐาน | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|---|
| SEC-1 | ไม่มีข้อมูลลับใน repo (API key, Sheet URL, Turnstile secret) | OWASP A02/A05 | 0 | secret scan (gitleaks) **ยังไม่มี** → Phase 2 | Dev |
| SEC-2 | ฟอร์ม Lead: sanitize ทุกฟิลด์, honeypot, rate limit, (Turnstile เมื่อมี key) | OWASP A03, A04 | ครบ | ตรวจโค้ด + ทดสอบส่งฟอร์ม | Dev |
| SEC-3 | Output ใน PHP escape ทุกจุด · ไม่มี SQL ดิบที่รับ input | WordPress Coding Standards (Security) | 0 error | PHPCS + WPCS **ยังไม่มี** → Phase 2 | Dev |
| SEC-4 | WordPress core / Polylang / TSF เป็นเวอร์ชันล่าสุด · ไม่มี vuln ที่รู้จัก | — | 0 vuln | WPScan / Patchstack | Pre/Post (รายเดือน) |
| SEC-5 | Security headers: HSTS, X-Content-Type-Options, Referrer-Policy, frame-ancestors | OWASP Secure Headers | ครบ (ไม่บังคับ CSP เต็ม) | securityheaders.com | Pre |
| SEC-6 | XML-RPC ปิด · ไม่เปิดเผยรายชื่อผู้ใช้ทาง REST | WP hardening | ครบ | curl ตรวจ | Pre |
| SEC-7 | บัญชี admin ใช้ 2FA · ทีมการตลาดใช้ role Editor ไม่ใช่ Administrator | — | ครบ | ตรวจหลังบ้าน | Pre |

### PRIV — ข้อมูลส่วนบุคคล

| รหัส | เกณฑ์ | อ้างอิง | เป้า | ช่วง |
|---|---|---|---|---|
| PRIV-1 | ฟอร์มมีช่องยินยอม PDPA (บังคับติ๊ก) | พ.ร.บ.คุ้มครองข้อมูลส่วนบุคคล 2562 | มี | Dev |
| PRIV-2 | มีประกาศความเป็นส่วนตัว (privacy notice) ที่ลิงก์จากฟอร์ม | PDPA ม.23 (แจ้งวัตถุประสงค์ตอนเก็บข้อมูล) | **รอตัดสินใจ** — ดูข้อ 5 | Pre |
| PRIV-3 | ถ้าใส่ analytics / heatmap: แบนเนอร์ cookie แบบปฏิเสธได้ และไม่โหลดก่อนยินยอม | PDPA + แนวปฏิบัติ cookie | ครบ (ถ้าใส่) | Pre |
| PRIV-4 | Lead เก็บเฉพาะฟิลด์ที่จำเป็น · กำหนดระยะเวลาเก็บ · ใครเข้าถึง Google Sheet ได้ | PDPA หลักการใช้ข้อมูลน้อยที่สุด | กำหนดแล้ว | Pre |

> หมายเหตุ: ข้อ PRIV เป็นแนวทางทั่วไป ไม่ใช่คำปรึกษาทางกฎหมาย ควรให้ฝ่ายกฎหมายของลูกค้ายืนยัน

### REL — ความเสถียร และการกู้คืน

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| REL-1 | Uptime | ≥99.9% / เดือน | UptimeRobot หรือ Better Stack (ฟรี) เช็คหน้าแรก + หน้าฟอร์มทุก 5 นาที | Post |
| REL-2 | Backup อัตโนมัติ | ทุกวัน เก็บ ≥30 วัน (Ruk-com ให้ 90 วัน) | แผงควบคุม Ruk-com | Pre |
| REL-3 | **ทดสอบกู้คืนจริง** อย่างน้อย 1 ครั้งก่อน launch และทุก 6 เดือน | กู้ได้ภายใน 4 ชม. (RTO), ข้อมูลหายไม่เกิน 24 ชม. (RPO) | บันทึกผลใน `docs/quality/reports/` | Pre/Post |
| REL-4 | ฟอร์ม Lead ส่งถึงจริง (synthetic test) | สำเร็จทุกครั้ง | สคริปต์ส่ง lead ทดสอบรายวัน + แจ้งเตือนถ้าไม่ถึง Sheet | Post |
| REL-5 | ไม่มี PHP notice / warning / fatal ในหน้าเว็บ · ไม่มี JS error | 0 | `measure.py` (dev) · error log (prod) | Dev/Post |
| REL-6 | SSL ไม่หมดอายุ · โดเมนต่ออายุอัตโนมัติ | แจ้งเตือนล่วงหน้า ≥14 วัน | uptime monitor | Post |

### I18N — 3 ภาษา

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| I18N-1 | ทุกหน้า / คอร์ส / บทความมีครบ TH / EN / JA | 100% | สคริปต์นับคำแปลจาก Polylang **ยังไม่มี** (ตอนนี้นับผ่าน `seo-check.py` 143 URL ต่อภาษา) | Dev |
| I18N-2 | ไม่มีข้อความ UI ที่ไม่ได้แปล (.po ครบ) | 0 untranslated | `msgfmt --statistics` | Dev |
| I18N-3 | คำแปล JA ผ่านคนญี่ปุ่นตรวจ · EN ผ่านคนตรวจ | ครบก่อน index ภาษานั้น | สถานะ 🤖/✅ (งาน G) | Pre |
| I18N-4 | ปุ่มภาษาไปหน้าแปลของหน้านั้น (ไม่ใช่หน้าแรก) เมื่อมีคำแปล | 100% | `seo-check.py` (hreflang) | Dev |

### EDIT — ทีมการตลาดแก้เองได้

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| EDIT-1 | ข้อความ ตัวเลข รูป ของทุกหน้าแก้ใน editor ได้โดยไม่แตะโค้ด | ครบ | ตรวจแต่ละหน้า | Dev |
| EDIT-2 | Block ทุกหน้า valid ใน editor (ไม่ขึ้น "This block contains unexpected content") | 0 invalid | `wp.blocks.parse()` (วิธีใน [`PITFALLS.md`](../PITFALLS.md)) | Dev |
| EDIT-3 | สีถูกล็อกตาม token · ทีมการตลาดเลือกได้แค่ palette | ครบ | `theme.json` | Dev |
| EDIT-4 | มีคู่มือ webmaster แบบเว็บ ตรงกับฟีเจอร์ปัจจุบันเสมอ | อัปเดตภายใน PR เดียวกับที่เปลี่ยนฟีเจอร์ | drift check ใน CI **ยังไม่มี** → Phase 5 | Pre |

### MAINT — ดูแลต่อได้

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| MAINT-1 | ปลั๊กอินภายนอกน้อยที่สุด | ≤3 (Polylang, The SEO Framework, Rcache) | blueprint / หลังบ้าน | Dev |
| MAINT-2 | `php -l` ผ่านทุกไฟล์ | 0 error | CI **ยังไม่มี** → Phase 2 | Dev |
| MAINT-3 | PHPCS (WordPress-Extra) | 0 error (warning ยอมได้แต่ต้องลดลง) | CI → Phase 2 | Dev |
| MAINT-4 | ไฟล์ build ตรงกับต้นฉบับ (`theme.json` จาก tokens, `*.min.css` จาก CSS) | ตรงกัน | CI รัน build แล้ว `git diff --exit-code` → Phase 2 | Dev |
| MAINT-5 | รองรับ PHP 8.1–8.3 · WordPress ≥6.6 | ผ่าน | CI matrix → Phase 2 | Dev |
| MAINT-6 | เอกสารเริ่มงาน (`HANDOFF.md`) สั้น อ่านจบเร็ว | ≤150 บรรทัด | `wc -l` | Dev |

### UX — ประสบการณ์ใช้งาน (โดยไม่ต้องใช้ผู้ใช้จริงทุกขั้น)

| รหัส | เกณฑ์ | เป้า | วัดด้วย | ช่วง |
|---|---|---|---|---|
| UX-1 | Heuristic review 10 ข้อของ Nielsen ต่อ flow หลัก (หาคอร์ส → อ่าน → ส่งฟอร์ม, ขอใบเสนอราคา In-house) | ไม่มีปัญหาระดับ 3–4 ค้าง | review โดย AI + Jimmi บันทึกใน `reports/` | Pre |
| UX-2 | งานหลักทำได้ภายในกี่คลิกจากหน้าแรก (หาคอร์ส Public ที่เปิดรับ, ส่งฟอร์ม) | ≤3 คลิก | สคริปต์ Playwright | Dev |
| UX-3 | ฟอร์มแจ้ง error ชัดที่ฟิลด์ + ข้อความสำเร็จ (`role="alert"` / `role="status"`) | ครบ | ทดสอบฟอร์ม | Dev |
| UX-4 | พฤติกรรมจริง: rage click, dead click, scroll depth ของหน้าคอร์ส | ติดตามรายเดือน | Microsoft Clarity (ฟรี, ต้องผ่าน PRIV-3) | Post |

## 3. ผลล่าสุด

สัญลักษณ์: ✅ ผ่าน · ⚠️ ผ่านบางส่วน / มีข้อสังเกต · ❌ ยังไม่มีหรือไม่ผ่าน · ⏳ วัดได้หลัง launch / ต้องมี staging · ❓ รอตัดสินใจ

| รหัส | สถานะ | ผล / หลักฐาน (วันที่ · ที่มา) |
|---|---|---|
| BIZ-1–4 | ❓⏳ | ยังไม่มี analytics หรือ event ในเว็บ (ตั้งใจ ยังไม่ได้ตัดสินใจว่าใช้อะไร) · lead เก็บใน CPT `hri_lead` แล้ว |
| BIZ-5 | ✅ | หน้าบริการทุกหน้ามีปุ่มหลักใน hero + CTA ท้าย section (ARCHITECTURE ข้อ 4 "หน้าบริการ") |
| PERF-1 | ⚠️ | 25 ต.ค. 69 รอบที่ 12: mobile หน้าแรก 94 · รวมคอร์ส 100 · คอร์สเดี่ยว 94 · บล็อก 97 · บทความ 98 · ติดต่อ 98 · desktop 100 ทุกหน้า → ผ่าน gate 90 แต่ **2 หน้ายังไม่ถึงเป้า 95** · **ยังไม่ได้วัดซ้ำหลังรอบที่ 13** (เพิ่ม JSON-LD ~2–4 KB/หน้า) |
| PERF-2 | ✅ | CLS 0–0.002 ทุกหน้าเมื่อโหลดรูปจริง (Playwright) · 0.061 ใน Lighthouse ของหน้าคอร์สเดี่ยวเกิดจากรูปเสียใน sandbox |
| PERF-3 | ✅ | หน้าแรก 183 KB |
| PERF-4 | ✅ | JS ทุกไฟล์ `defer`, โหลดเฉพาะหน้าที่มีบล็อก, ไม่มี jQuery |
| PERF-5–7 | ⏳ | รอ staging ([`HOSTING.md`](../HOSTING.md) ข้อ 5) |
| A11Y-1–3 | ✅ | 25 ต.ค. 69: `measure.py` 3 ภาษา × 5 ความกว้าง × 17 หน้า = 0 ปัญหา · ข้อยกเว้นที่ตั้งใจ: ลิงก์กลางประโยคในย่อหน้า PDPA ของฟอร์ม · ⚠️ ป้าย `#767C82` ใน `/style-guide/` 4.47:1 (หน้า noindex) |
| A11Y-4–5 | ❌ | ยังไม่มีเครื่องมือ (Phase 2) · มี skip link และ `:focus` ใน `base.css` แล้วแต่ยังไม่ได้ทดสอบเป็นระบบ |
| A11Y-6 | ⚠️ | โลโก้ลูกค้ามี alt ครบ · alt โลโก้ช่อง 10 รอตรวจ (BACKLOG 0.2) |
| A11Y-7 | ✅ | วิดีโอ hero และ slider รองรับ reduced-motion (ทดสอบรอบที่ 12) |
| SEO-1–3, 5 | ✅ | 25 ต.ค. 69 รอบที่ 13: `seo-check.py` ผ่าน 429 URL (345 บทความ + 60 คอร์ส + 24 หน้า) + robots.txt + 88 ลิงก์ใน llms.txt |
| SEO-4 | ⏳ | ยังไม่ได้ผ่าน validator ภายนอก |
| SEO-6 | ❌ | ~44 ลิงก์ในบทความชี้ URL เว็บเดิมที่ไม่มีแล้ว · รอ redirect mapping (งาน E) |
| SEO-7 | ⏳ | ต้องเก็บ baseline Search Console ของเว็บเดิม **ก่อนย้าย** |
| SEC-1 | ⚠️ | ไม่มีข้อมูลลับใน repo ตามที่ตรวจด้วยมือ · ยังไม่มี secret scan อัตโนมัติ |
| SEC-2 | ⚠️ | sanitize ครบ, honeypot, rate limit 5 ครั้ง / 10 นาที / IP จริง (ทดสอบ 25 ต.ค.) · Turnstile ปิดอยู่จนกว่าจะใส่ key · ไม่ใช้ nonce โดยตั้งใจ (หน้า cache ได้) ยอมรับความเสี่ยงนี้แทนด้วย honeypot + rate limit |
| SEC-3 | ❌ | ยังไม่ได้รัน PHPCS (Phase 2) |
| SEC-4, 5, 7 | ⏳ | รอ staging |
| SEC-6 | ⚠️ | XML-RPC ปิดใน `inc/cleanup.php` แล้ว · REST users ยังไม่ได้ตรวจ |
| PRIV-1 | ✅ | ช่องยินยอมบังคับติ๊กใน `hri/lead-form` |
| PRIV-2 | ❓ | ฟอร์มยังไม่มีลิงก์ประกาศความเป็นส่วนตัว และ footer ตัดสินใจไม่มีหน้า Privacy Policy → ต้องทบทวน (ข้อ 5) |
| PRIV-3–4 | ❓ | ขึ้นกับการตัดสินใจเรื่อง analytics และนโยบายเก็บ lead |
| REL-1, 4, 6 | ⏳ | ยังไม่มี monitoring |
| REL-2 | ✅ | Ruk-com backup ทุกวัน เก็บ 90 วัน (ตามแพ็กเกจ, [`HOSTING.md`](../HOSTING.md)) |
| REL-3 | ❌ | ยังไม่เคยทดสอบกู้คืน |
| REL-5 | ✅ | `measure.py` 25 ต.ค.: 0 PHP notice / JS error |
| I18N-1 | ✅ | 143 URL ต่อภาษาเท่ากันทั้ง 3 ภาษา (`seo-check.py` 25 ต.ค.) |
| I18N-2 | ⚠️ | ยังไม่ได้วัดด้วย `msgfmt --statistics` |
| I18N-3 | ❌ | EN / JA ทั้งหมดเป็น machine translation ยังไม่มีคนตรวจ ([`OPEN-QUESTIONS.md`](../OPEN-QUESTIONS.md)) |
| I18N-4 | ✅ | hreflang ครบและชี้กลับ (`seo-check.py`) |
| EDIT-1–3 | ✅ | หน้าแรก + หน้าบริการเป็น block ธรรมดาในหน้า, markup valid ใน editor (ตรวจ 12 หน้า), palette ล็อก |
| EDIT-4 | ❌ | ยังไม่มีคู่มือ webmaster (Phase 5) |
| MAINT-1 | ✅ | 2 ปลั๊กอินใน blueprint (Polylang, The SEO Framework) + Rcache บนโฮสต์ |
| MAINT-2–5 | ❌ | ยังไม่มี CI สำหรับโค้ด (มีแค่ workflow รูปและลิงก์ demo) |
| MAINT-6 | ✅ | 26 ต.ค. 69: `HANDOFF.md` 67 บรรทัด / 11 KB (เดิม 636 บรรทัด / 206 KB) |
| UX-1–3 | ⚠️ | ฟอร์มมี `role="alert"` / `role="status"` แล้ว · ยังไม่ได้ทำ heuristic review และสคริปต์นับคลิก |
| UX-4 | ⏳ | หลัง launch |

**สรุป 26 ต.ค. 69**: ด้านที่ "วัดได้ตอนนี้" (ความเร็ว, layout/contrast, SEO, 3 ภาษาเชิงโครงสร้าง) อยู่ในเกณฑ์ดีแล้ว · ช่องว่างใหญ่คือ (1) ยังวัดผลธุรกิจไม่ได้ (2) การตรวจทั้งหมดยังรันด้วยมือ ไม่มี CI กันคุณภาพตก (3) accessibility เชิงลึก, security ของโค้ด, การกู้คืน และคู่มือ ยังไม่มี

## 4. แผนปิดช่องว่าง

| Phase | งาน | ปิดเกณฑ์ | โมเดลที่เหมาะ |
|---|---|---|---|
| 0 ✅ | แยก HANDOFF เป็นไฟล์ตามหัวข้อ | MAINT-6 | Opus |
| 1 ✅ | ไฟล์นี้ | — | Opus |
| 2 | **CI quality gate** (`.github/workflows/quality.yml`): ติดตั้ง WP + SQLite แบบ `local-setup.sh` → `measure.py` + `seo-check.py` + `perf.py` + axe-core + เช็คคีย์บอร์ด + `php -l` + PHPCS + gitleaks + build tokens/minify แล้ว `git diff --exit-code` · ไม่ผ่าน = merge ไม่ได้ · ผล JSON เก็บเป็น artifact | A11Y-4–5, SEC-1, SEC-3, MAINT-2–5, I18N-2 + ทำให้ Dev gate ทุกข้อรันอัตโนมัติ | Sonnet (Opus review workflow) |
| 3 | **Scorecard**: สคริปต์รวม JSON ของ CI → `docs/quality/reports/<วันที่>/` + หน้า HTML สรุปผ่าน/ไม่ผ่านตามรหัสในข้อ 2 + กราฟแนวโน้ม เผยแพร่บน GitHub Pages (ต่อจาก `demo-site/`) | หลักฐานให้ลูกค้า | Sonnet |
| 4 | **Analytics + conversion** (หลังตัดสินใจข้อ 5): event `generate_lead`, คลิก CTA/LINE, ภาษา · โหลดหลัง `load` + หลังยินยอม · Clarity | BIZ-1–4, UX-4, PRIV-3 | Sonnet, Opus review เรื่อง privacy |
| 5 | **คู่มือ webmaster แบบเว็บ**: Markdown ใน `docs/manual/` → GitHub Pages + Help tab ในหลังบ้าน · ตารางฟิลด์สร้างจาก `block.json` / meta ที่ลงทะเบียน · screenshot หลังบ้านด้วย Playwright · `manual-map.yml` + CI เตือนเมื่อโค้ดเปลี่ยนแต่คู่มือไม่เปลี่ยน | EDIT-4 | Sonnet (สคริปต์ screenshot: Haiku) |
| 6 | **หลัง launch**: uptime monitor, synthetic lead test, health endpoint (มี token, อ่านอย่างเดียว), runbook "อาการ → เช็ค → ทำ → แจ้งใคร", ผู้ช่วย AI ที่อ่าน repo + คู่มือ + health | REL-1, 4, 6, SEC-4 | Opus ออกแบบ, Sonnet ลงมือ |

## 5. รอ Jimmi ตัดสินใจ (พักไว้ — อย่าเดาแทน)

1. **เป้าหมายธุรกิจ** BIZ-1–3: ตัวเลขเป้าและ baseline จากเว็บเดิม (ถ้าลูกค้ามีข้อมูล)
2. **Privacy notice / PDPA** (PRIV-2–4): จะมีหน้าหรือข้อความประกาศความเป็นส่วนตัวไหม ระยะเวลาเก็บ lead ใครเข้าถึง Sheet
3. **Analytics**: GA4 หรือทางเลือกอื่น · ใส่ Clarity ไหม · ใครเป็นเจ้าของบัญชี (ควรเป็นบัญชีของลูกค้า)
4. **ตาราง escalation และ SLA**: ใครรับเรื่องระดับ P1/P2/P3 ช่องทางไหน (ใช้ใน runbook Phase 6)
5. **2FA / role ของทีมการตลาด** (SEC-7)

## 6. วิธีใช้และอัปเดตไฟล์นี้

- **ก่อนปิดงานทุกรอบ**: รันเครื่องมือของเกณฑ์ที่งานนั้นแตะ แล้วแก้แถวในข้อ 3 ให้มีวันที่ + ค่า (เช่น "27 ต.ค. 69: mobile 95") — ห้ามแก้เป็น ✅ โดยไม่ได้วัดจริง
- **เพิ่มเกณฑ์ใหม่**: ใส่ในข้อ 2 พร้อมรหัสใหม่ (ไม่นำรหัสเก่ามาใช้ซ้ำ) และแถวในข้อ 3
- **เปลี่ยนเป้า**: บันทึกเหตุผลและวันที่ไว้ในคอลัมน์ผลของข้อ 3
- เมื่อ Phase 3 เสร็จ ข้อ 3 จะสร้างจาก `reports/` อัตโนมัติ — ถึงตอนนั้นให้แก้สคริปต์ ไม่ใช่แก้ตารางมือ
