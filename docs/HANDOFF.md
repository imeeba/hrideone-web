# HANDOFF — สถานะงานและบริบทสำหรับแชท/คนถัดไป

> อ่านไฟล์นี้ก่อนเริ่มงานทุกครั้ง แล้วอัปเดตก่อนจบแต่ละรอบงาน
> อัปเดตล่าสุด: 2026-10-27 (รอบที่ 14 อัปเดต 27 ต.ค.: ทดสอบ API จริงยังไม่ผ่าน ดูข้อ 4.w · **ค้างรอบที่ 14**: ทดสอบแปลอัตโนมัติกับ key ที่มีเครดิต — เช็กลิสต์อยู่ท้ายข้อ 4.w · ถ้า key เคยถูกวางในแชท ให้เพิกถอนแล้วสร้างใหม่ · รอบที่ 14: **แปลอัตโนมัติ TH→EN/JA (งาน G)** branch `feature/auto-translate` **merge เข้า `main` แล้ว 27 ต.ค. 2569 โดยยังไม่ผ่านการทดสอบกับ API จริง** (key ใช้ได้ แต่บัญชีไม่มีเครดิต) ดูข้อ 4.w · จุดย้อนกลับ branch `backup/pre-merge-translate-2026-10-27` (= main เดิม f13270a) · รอบที่ 13: **SEO + AI SEO (งาน D)** **merge เข้า `main` แล้ว 25 ต.ค. 2569** (fast-forward) — จุดย้อนกลับ branch `backup/pre-merge-seo-2026-10-25` (= main เดิม 90f3c33; ย้อนด้วย `git push -f origin backup/pre-merge-seo-2026-10-25:main`) · รอบที่ 12: **PageSpeed (งาน J)** · รอบที่ 11: **รูปใน WordPress ให้เล็กสุด (งาน I)** · รอบที่ 10: **ค้นหาทั้งเว็บภาษาไทย** · รอบที่ 9: **รูปจริงจากเว็บเดิม** · **merge เข้า `main` แล้วทั้งสองรอบ 25 ต.ค. 2569** — จุดย้อนกลับ branch `backup/pre-merge-2026-10-25` (= main เดิม b07d21a)) · theme 0.13.0 / plugin 0.14.0
>
> **ก่อนเริ่มงานใหม่**: ถ้า Jimmi อนุมัติแล้ว ให้ merge `feature/real-images` เข้า `main` ก่อน (prompt ทุกตัวในข้อ 10 เริ่มจาก `main`) — ยังไม่อนุมัติ = ถาม Jimmi ก่อน อย่า merge เอง
>
> **เริ่มแชทใหม่**: ใช้ประโยคในข้อ 9 + prompt ในข้อ 10 · สถานะสั้นๆ: เสร็จแล้ว = design tokens/Style Guide, header/footer, หน้าแรก, หน้าบริการ 4 หน้า, About, Contact + ฟอร์ม Lead, คอร์ส 21 + ตัวกรอง, 3 ภาษา (Polylang), บล็อกจริง 115 เรื่อง × 3 ภาษา + รูปใน repo, demo บน Playground, ไอคอนชุดเดียวทั้งเว็บ (Phosphor Light) · **ลำดับงานที่เหลือสำหรับ demo**: ~~H (รูปจริงหน้าอื่น)~~ ทำแล้วรอบที่ 9 (รอ merge) → ~~C (ค้นหา)~~ ทำแล้วรอบที่ 10 (รอ merge) → ~~I (รูปให้เล็กสุด)~~ ทำแล้วรอบที่ 11 (merge แล้ว) → ~~J (PageSpeed)~~ ทำแล้วรอบที่ 12 (merge แล้ว) → ~~D (SEO + AI SEO)~~ ทำแล้วรอบที่ 13 (merge แล้ว) → G (แปลอัตโนมัติ) ทำแล้วรอบที่ 14 (branch `feature/auto-translate` รอทดสอบ Claude จริง + merge) · หลัง demo เสร็จ: โฮสต์ ([`HOSTING.md`](HOSTING.md)) → E (ย้ายเว็บ + 301)
>
> **ทิศทางตอนนี้ (Jimmi, 23 ต.ค.)**: ทำ **demo บน GitHub (Playground) ให้เสร็จทั้งเว็บก่อน** ยังไม่ยุ่งกับโฮสต์ · รูปให้ดึง **เฉพาะที่เว็บใหม่ใช้จริง** ไม่เอาคลังสื่อทั้งหมดของเว็บเดิม · Jimmi จะให้ **Sonnet** ทำงานต่อจาก prompt ในข้อ 10

## 1. โปรเจกต์คืออะไร

รื้อ [hrideone.com](https://hrideone.com) (HRI DeOne — สถาบันฝึกอบรมผู้บริหาร) จาก WordPress + Elementor
มาเป็น WordPress Block Theme ที่เบา ใช้ปลั๊กอินน้อยที่สุด โค้ดเฉพาะของเว็บอยู่ใน repo นี้

- เจ้าของงาน: Jimmi (คุยภาษาไทย ตอบเป็นภาษาไทย)
- คนแก้เนื้อหาหลังส่งมอบ: ทีมการตลาด → ทุกอย่างต้องแก้ได้จาก editor โดยไม่ต้องเขียนโค้ด
- โฮสต์ปลายทาง: Ruk-com Managed WordPress (มี Rcache = NGINX proxy cache, shared hosting ไม่มี SSH/Git/Composer)
- **ห้ามแตะ Production** ทำงานบน Playground / local / staging เท่านั้น
- ไม่มีงบปลั๊กอินเสียเงิน ถ้าอะไรต้องเสียเงินให้ถาม Jimmi ก่อน

## 2. ข้อกำหนดที่ตกลงแล้ว

| เรื่อง | ข้อตกลง |
|---|---|
| ชื่อแบรนด์ | HRI DeOne |
| หน้าตา | ตามหน้าแรกเว็บเดิม: ขาว + เขียว/ฟ้าอมเขียวหลายเฉด Style Guide ต้องเรียบที่สุด (ไม่มีเหลือง/น้ำตาล เทาให้น้อยเฉด) |
| ภาษา | TH (หลัก) / EN / JA แปลครบทุกหน้า ใช้ AI ช่วยได้ (JA ควรให้คนตรวจ) |
| ฟอร์ม | เก็บ Lead อย่างเดียว ส่งเข้า Google Sheet ไม่มีระบบจ่ายเงิน |
| Public Training | 1 หลักสูตร = 1 รายการ แสดงเฉพาะ **รุ่นล่าสุดที่เปิดอยู่** (เลขรุ่น วันที่ สถานะ) ไม่มีหลายรุ่นพร้อมกัน |
| ตัวกรองคอร์ส | ประเภท, หมวด, หัวข้อ, ระดับผู้เรียน, รูปแบบ, ระยะเวลา + ค้นหาชื่อ (Jimmi อนุมัติชุดนี้) |
| รูป thumbnail | สัดส่วน 3:2 ทั้งเว็บ (token `ratio.thumb`) |
| ปุ่มภาษา | Desktop ≥1220px = dropdown มุมขวา header · <1220px = แถวปุ่มในเมนูแฮมเบอร์เกอร์ · แสดงเป็นตัวอักษร (TH/EN/日本語) ไม่ใช้ธง |
| ตรวจงาน | ต้องเช็กให้แสดงผลครบทุกอุปกรณ์ (desktop / tablet / mobile) |

## 3. ดูผลงาน

- **ลิงก์ demo แบบสั้น**: <https://imeeba.github.io/hrideone-web/> (เปิดแล้วไป Playground เอง · `?stay` = แสดงรายการหน้า · `?page=/blog/` = เปิดหน้านั้น) — ไฟล์ `demo-site/index.html`, deploy ด้วย `.github/workflows/pages.yml` · GitHub Pages เปิดแล้ว (Source = GitHub Actions, Jimmi เปิด 23 ต.ค.) · deploy ใหม่เองเมื่อแก้ `demo-site/` หรือสั่ง `gh api -X POST repos/imeeba/hrideone-web/actions/workflows/pages.yml/dispatches -f ref=main`
- Preview (Playground, ดึงจาก `main` ทุกครั้ง): <https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/imeeba/hrideone-web/main/playground/blueprint.json>
  - เติม `&v=<เลขใหม่>` ถ้าเห็นเวอร์ชันเก่า (cache)
  - เปิดมาที่หน้าแรก `/` · Style Guide `/style-guide/` · รวมคอร์ส `/courses/` · `/in-house/` · `/public-training/` · `/transformation-solutions/` · `/japanese-concept/`
  - Blog: `/blog/` · `/en/blog-en/` · `/ja/ブログ/` · หมวด `/category/business/` (EN `business-en`, JA `business-ja`)
  - ภาษาอื่น: `/en/` `/ja/` (หน้าแรก) · `/en/in-house-en/` · `/ja/public-training-ja/` · `/en/transformation-solutions-en/` · `/ja/japanese-concept-ja/` · `/en/courses/negotiation-mastery-en/`
  - ทดสอบ branch อื่น: เอา blueprint มาแทน `main` เป็นชื่อ branch แล้วเปิดแบบ `https://playground.wordpress.net/#<blueprint JSON ที่ encode แล้ว>`
- Local test site: `scripts/local-setup.sh [dir] [port]` → `http://127.0.0.1:8899/wp/` (admin/admin)
  - WordPress 7.1.3 จาก GitHub + SQLite + WP-CLI, symlink theme/plugin จาก repo
  - ใน cloud workspace เข้า wordpress.org/packagist ไม่ได้ → Polylang ใน local ใช้ source จาก GitHub (`git clone --branch <tag> https://github.com/polylang/polylang` + `composer dump-autoload --no-dev`) ไม่มี flags/JS build ฝั่ง admin แต่ PHP ทำงานครบ แล้ว `wp hri languages` + `wp eval-file playground/seed-languages.php`
- Playground: ใช้ built-in browser ได้ เมนู iframe ซ้อนสองชั้น (`document.querySelector('iframe').contentWindow.document.querySelector('iframe')`) ปุ่ม Back/Forward ใน Playground ทดสอบไม่ได้

## 4. สถาปัตยกรรม

```
(รูปหน้าอื่น) scripts/crawl-images.py (workflow Crawl old site images) → playground/data/crawl/pages.json → playground/data/site-media.json (ช่องรูป→รูป, เขียนมือ) ─ scripts/site-media.py (workflow Site media) ─→ playground/site-media/<id>.webp · seed อ่าน site-media.json (`hri_seed_media*` ใน seed-home.php)
(รูป demo) playground/data/blog-posts.json ─ scripts/demo-media.py (GitHub Actions: demo-media.yml) ─→ playground/media/YYYY/MM/*.webp + manifest.json
tokens/tokens.json  ─┐
tokens/theme.base.json ─┴─ node tokens/build.mjs ─→ wp-content/themes/hri-theme/theme.json  (ห้ามแก้ตรง)
wp-content/themes/hri-theme/   หน้าตาเท่านั้น: templates, parts, patterns, CSS, fonts
wp-content/plugins/hri-core/   ฟีเจอร์ทั้งหมด (ไม่ผูกธีม)
playground/                    blueprint.json, seed.php (ข้อมูลตัวอย่าง), sample/*.jpg
scripts/local-setup.sh         สร้างเว็บทดสอบในเครื่อง
```

**Design tokens** 3 ชั้น: primitive (teal/green/neutral ramp) → semantic 12 slug → component (`custom`: radius, shadow, motion, ratio, tap = พื้นที่กดขั้นต่ำ 44px)
- ❗ `primary` #00B0BD ใช้กับพื้น/กราฟิกเท่านั้น (contrast กับขาวไม่ผ่าน) ตัวอักษร/ลิงก์/ปุ่มใช้ `primary-strong` #0F7070
- palette ล็อกไว้ (ปิด custom color) ทีมการตลาดเลือกได้แค่สีใน token
- ฟอนต์ self-hosted: Kanit (TH/Latin), Montserrat (display), M PLUS 1 (JA, โหลดเฉพาะ locale ja หรือหน้า style guide)

**hri-core modules** (`hri-core.php` โหลดตามลำดับ): `cleanup` (emoji, XML-RPC, comments ปิด) · `icons` (ไอคอน ดูด้านล่าง) · `images` + `images-optimize` (ดู "รูปในคลังสื่อ" ด้านล่าง) · `content-model` · `course-card` · `course-filter` · `languages` · `polylang` · `company` · `leads` · `blog` · `blog-import` · `blocks`

**รูปในคลังสื่อ** (รอบที่ 11) — `inc/images.php` + `inc/images-optimize.php`
- **แปลงตอนรับไฟล์**: ทุกทางที่ไฟล์เข้าคลังสื่อ (อัปโหลดใน wp-admin, sideload จากโค้ด, ตัวนำเข้าบทความโหมด download) ผ่าน filter `wp_handle_upload` → JPG/PNG ถูกหมุนตาม EXIF, ย่อให้ขอบยาวสุด ≤ `HRI_IMAGE_MAX_EDGE` (1600), บันทึกเป็น WebP (หรือ AVIF) แล้ว **ลบไฟล์เดิมทิ้ง** (ไม่เก็บต้นฉบับ — Jimmi สั่ง 25 ต.ค.) · ไม่ทำกับ GIF/SVG/WebP เดิม · ถ้าแปลงไม่ได้ (เซิร์ฟเวอร์ไม่มี WebP, ไฟล์เสีย) หรือผลไม่เล็กกว่าเดิม → เก็บไฟล์เดิม อัปโหลดไม่ล้ม · PNG โปร่งใสยังโปร่งใส · ปิดไม่ให้แปลงเฉพาะครั้ง: filter `hri_image_convert_upload`
- ค่าใน wp-config (ไม่บังคับ): `HRI_IMAGE_FORMAT` `webp`(ค่าเริ่มต้น)|`avif` (ใช้เมื่อเซิร์ฟเวอร์รองรับ ไม่งั้นกลับเป็น WebP) · `HRI_IMAGE_MAX_EDGE` (1600) · `HRI_IMAGE_QUALITY` (78)
- ขนาดย่อย: thumbnail, medium, large, **`hri-card` 800×533 ครอป 3:2** (เลือกใน editor ได้: "การ์ด (3:2)") · ตัด medium_large/1536/2048 · ใช้ `hri-card` ที่: การ์ดคอร์ส (`course-card.php`), การ์ดบล็อก (`blog-loop`, `home-knowledge` ผ่าน `sizeSlug`), บทความที่เกี่ยวข้อง · หน้าบทความ/หน้าคอร์สเดี่ยวใช้ `large` · ก่อนหน้านี้บล็อก Featured Image ไม่มี `sizeSlug` จึงโหลดไฟล์เต็ม 1600px ทุกการ์ด
- `sizes` ของการ์ด: filter `wp_get_attachment_image_attributes` แทนค่ากลางของ WordPress (`(max-width: 800px) 100vw, 800px`) ด้วย `(min-width: 1100px) 33vw, (min-width: 600px) 50vw, 100vw` (การ์ดคอร์สส่ง `sizes` เองเพราะอยู่ข้างตัวกรอง)
- **รูปที่อยู่ในคลังสื่อแล้ว**: `wp hri images optimize [--dry-run] [--limit=N] [--ids=1,2]` หรือ Tools → ปรับรูปให้เล็ก (ทีละ 10 รูป หน้าโหลดต่อเอง) · ทำ: แปลงไฟล์หลัก, ลบขนาดย่อยเก่า/backup/`original_image`, สร้างขนาดย่อยใหม่, **แก้ URL ของไฟล์เก่าในเนื้อหาและ excerpt ของทุกโพสต์** (รวมแบบ JSON-escaped `\/` ในคอมเมนต์บล็อก) · รูปที่ WordPress ย่อเป็น `-scaled.webp` ให้เองแต่ยังเก็บ JPG ต้นฉบับใหญ่ไว้ (`original_image`) = ลบต้นฉบับ + ย้ายลิงก์ไปไฟล์ที่ย่อแล้ว · รันซ้ำได้ (รอบสองไม่เหลืองาน) · **ลบไฟล์ถาวร สำรองก่อนรัน**
- Demo บน Playground **ไม่เปลี่ยน**: รูปใน demo เป็น URL (`_hri_thumbnail_url`, jsDelivr) ไม่ได้อยู่ในคลังสื่อ ผลของงานนี้เห็นตอนมีรูปในคลังสื่อจริง (อัปโหลดเอง / ตอนย้ายเว็บงาน E)

**ความเร็ว (รอบที่ 12)** — `hri-core/inc/performance.php` + `scripts/perf.py`
- **วัด**: `python3 -I scripts/perf.py [--runs 3] [--only home,post] [--form-factors mobile]` = Lighthouse (ต้อง `npm i lighthouse` ที่ไหนก็ได้ เช่น `/tmp/lh`) 6 แบบหน้า × mobile/desktop, ผ่าน `scripts/perf-proxy.mjs` (Brotli + cache header ยาวเหมือนโฮสต์จริง — `php -S` ส่งไฟล์ดิบทำให้น้ำหนักหน้าเกินจริง ~5 เท่า; พร็อกซีเขียน URL `127.0.0.1:8899` ในหน้าเป็นของตัวเองด้วย ไม่งั้น CSS/ฟอนต์ไม่ผ่านพร็อกซี) · exit 1 ถ้า mobile <90 หรือ CLS ≥0.1 · **รูปของ demo มาจาก jsDelivr ซึ่ง workspace เข้าไม่ได้** → รูปเสียในผล Lighthouse (CLS ของหน้าคอร์สเดี่ยว 0.06 เป็นเพราะรูปเสีย; ตรวจ CLS จริงด้วย Playwright + `route` ชี้ไฟล์ใน `playground/` ได้ 0–0.002 ทุกหน้า)
- **ฟอนต์**: `scripts/subset-fonts.py` ทำ Kanit latin 300–600 + Montserrat latin ใหม่โดยไม่เก็บ hinting (134 → 68 KB รวม 6 ไฟล์ ตัวอักษรครบเท่าเดิมตาม unicode-range; **ห้ามรันกับ latin-ext** สคริปต์ข้ามให้แล้ว) · ตัด Kanit 700 ทิ้ง (ทั้งเว็บใช้ 8 ตัวอักษรใน style guide; ตัวหนา 700 จะใช้ 600) · preload แค่ 2 ไฟล์ตามภาษาหน้า: ไทย = thai-300 + thai-600, EN = latin-300 + latin-600, JA = ไม่ preload · fallback `Kanit Fallback` (Arial ปรับ `size-adjust` 101.2% / ascent 108.7% / descent 39%) ใน `base.css` และ stack ของ token `body`/`display` → ตอน swap ฟอนต์ตัวอักษรละตินไม่ขยับ
- **CSS**: `node scripts/minify-css.mjs` (esbuild ผ่าน `npx`) เขียน `*.min.css` ข้างไฟล์ต้นฉบับทุกไฟล์ (commit ไว้เพราะโฮสต์ไม่มี build) · PHP โหลด `.min.css` ถ้ามี (SCRIPT_DEBUG = ไฟล์อ่านง่าย): theme `hri_enqueue_theme_style()`, plugin `hri_register_plugin_style()` · **แก้ CSS แล้วต้องรัน minify ใหม่และ commit** (ลืม = หน้าเว็บยังใช้ของเก่า) · ทั้งสองฟังก์ชันลงทะเบียน `path` ให้ WordPress พิมพ์ไฟล์เล็กรวมกัน ≤40 KB (ตัวกรอง `hri_inline_css_limit`) เป็น `<style>` ใน head: ไฟล์ของ hri-core + บล็อก core เล็กๆ ไม่เสีย request; `base.min.css` (36 KB) ยังเป็นไฟล์แคชได้ · ทดลองแล้ว: inline ทั้งหมดไม่ช่วย (หน้าแรก 92 → 87)
- **รูป**: `<img>` ที่ไม่มี width/height เติมตอน render ผ่าน `the_content` (priority 11, ก่อน `wp_filter_content_tags`) → core ใส่ `loading="lazy"` / `fetchpriority="high"` ให้ต่อ (core ไม่ lazy รูปที่ไม่มีขนาด: เดิมโลโก้ลูกค้า 40 รูปบนหน้าแรกโหลดทั้งหมดตอนเปิด) · หาขนาดจาก: filter `hri_image_dimensions` → ตาราง option `hri_image_dims` (demo เท่านั้น สร้างจาก `scripts/image-dims.py` → `playground/data/image-dims.json`, blueprint ใส่ให้) → ไฟล์ใน uploads/theme/plugin (`wp_getimagesize` หรือ viewBox ของ SVG) · **ไม่แก้ markup ที่เก็บใน DB** (เติม width/height ตอนเก็บ block ทำให้ editor validate ไม่ผ่าน — ดูข้อ 8) · ปกบทความ/คอร์สที่เป็น URL ภายนอก (`blog.php`) ใช้ตัวเดียวกัน + รูปแรกของหน้าเดี่ยว eager + fetchpriority high
- **วิดีโอ hero**: PHP (`hri_defer_hero_video()`) ย้าย `src` → `data-hri-src`, เอา `autoplay` ออก, `preload="none"` · `hero.js` ตั้ง src หลัง `load` + idle เฉพาะจอ ≥782px และไม่ใช่ Save-Data / reduced-motion (สองกรณีหลังเริ่มได้ด้วยปุ่มเล่นเท่านั้น) · จอแคบ = ลบ `<video>` และไม่มีปุ่ม · ไม่มี JS = ไม่โหลดวิดีโอ (เหลือพื้น overlay + ข้อความ) · ทดสอบด้วยวิดีโอ webm จริง (desktop โหลดหลัง load, mobile ไม่ขอไฟล์, reduced-motion ขอเมื่อกดเล่น) — **ยังไม่มีวิดีโอจริงในเว็บ** และยังไม่มี poster (ถ้าต้องการ: ตั้ง `poster` ในตัวกรอง `hri_defer_hero_video` หรือใช้รูปสำรองของ Cover)
- **Speculation Rules** (ของ core): ตั้งเป็น `prerender` + `moderate` (เปิดหน้าล่วงหน้าเมื่อเมาส์ค้างที่ลิงก์ ~200 ms) ตัวกรอง `hri_speculation` (คืน `null` = ปิด) · core ข้ามทุก URL ที่มี query string อยู่แล้ว (ตัวกรองคอร์ส/ค้นหา) · เพิ่มข้อยกเว้น `/wp-json/*`, `/feed/*`
- **ตัวกรองคอร์สบนมือถือ**: `<details>` เริ่มเปิด (ไม่มี JS ต้องเห็นตัวกรอง) แล้ว `view.js` ปิดทีหลังทำให้ CLS 0.26 → มี inline script สั้นๆ หลัง `</details>` ปิดก่อน paint
- ที่ตรวจแล้วว่าเข้าเกณฑ์อยู่แล้ว ไม่ต้องแก้: ไม่มี jQuery ฝั่งหน้าเว็บ · JS ทุกไฟล์ `defer` และโหลดเฉพาะหน้าที่มีบล็อกนั้น · Turnstile โหลดเฉพาะหน้าที่มี `hri/lead-form` และยังปิดอยู่จนกว่าจะใส่ key · ไม่มี YouTube/GA4/GTM ในเว็บ (แผนที่ในหน้า Contact เป็น iframe `loading="lazy"`)

**Content model**
- CPT `course` (archive `/courses/`)
- Taxonomies (ไม่มีหน้า public): `hri_program` (inhouse/public), `hri_pillar`, `hri_topic` (hierarchical), `hri_level`, `hri_format`
- Meta (meta box คลาสสิก): `hri_duration_days`, `hri_audience`, และเฉพาะ Public: `hri_batch_no`, `hri_reg_status` (open|full|closed), `hri_start_date`, `hri_end_date`, `hri_location`, `hri_price`

**Blocks** (server-rendered, `block.json` + `render.php`, `autoRegister`, ไม่มี build step)
- `hri/course-filter` — ค้นหา + checkbox filter; state อยู่ใน URL (`program[] pillar[] topic[] level[] format[] duration[] q pg`), AND ข้ามกลุ่ม OR ในกลุ่ม; ทำงานได้โดยไม่มี JS, `view.js` แค่ fetch แล้วสลับ `[data-hri-results]`; attribute `program` ล็อกประเภท (ซ่อนกลุ่มประเภท, นับเฉพาะประเภทนั้น); ระยะเวลาแสดงเฉพาะช่วงที่มีคอร์ส; 12 ต่อหน้า
- `hri/course-facts` — ตารางข้อมูลในหน้าคอร์สเดี่ยว
- `hri/language-switcher` — `variant: dropdown|list`; ใช้ `pll_the_languages()` (ลิงก์ไปหน้าแปลของหน้านั้น ถ้าไม่มีคำแปลไปหน้าแรกของภาษานั้น; หน้าที่มี filter จะพก query string ไปด้วย) ถ้าไม่มี Polylang fallback แสดง TH เป็นปัจจุบัน และ EN/JA จาง กดไม่ได้

**ภาษา (Polylang ฟรี)** — `inc/languages.php` + `inc/polylang.php`
- ภาษา: TH (default, `th`) · EN (`en_US`) · JA (`ja`) — นิยามใน `hri_languages()`
- URL: TH ไม่มี prefix · `/en/…` · `/ja/…` · หน้าแรก `/` `/en/` `/ja/` · ไม่ redirect ตามภาษาเบราว์เซอร์ (cache-friendly)
- ตั้งค่าด้วย `hri_polylang_setup()` (idempotent): `wp hri languages` / ปุ่มในแจ้งเตือน wp-admin (โฮสต์ไม่มี WP-CLI) / blueprint (runPHP ต้อง `define('PLL_ADMIN', true)` ก่อน wp-load เพราะถ้ายังไม่มีภาษา Polylang ไม่ start ฝั่ง front)
- แปลได้: page, post, `course` (บังคับด้วย filter `pll_get_post_types` ไม่ต้องติ๊กใน settings)
- **Taxonomy คอร์สไม่แปลด้วย Polylang** — ใช้ term ชุดเดียว slug เดียวทุกภาษา (filter URL, lock In-house/Public, CSS ใช้ได้เหมือนกัน) ชื่อ EN/JA เก็บใน term meta `hri_name_en` / `hri_name_ja` (ช่องในหน้าแก้ term) แสดงตามภาษาที่ front end เท่านั้น; Polylang copy/sync term ให้คอร์สแปล (`pll_copy_taxonomies`)
- ⇒ ตัวนับในตัวกรองนับจากรายการคอร์สของภาษาปัจจุบันเสมอ (`hri_program_course_ids()`) ห้ามใช้ `$term->count` (รวมทุกภาษา)
- Sync ระหว่างคำแปล: ระยะเวลา, รุ่น, สถานะ, วันเริ่ม/สิ้นสุด, รูป, menu_order · copy ครั้งแรกแล้วแปลเอง: กลุ่มเป้าหมาย, สถานที่, ราคา
- เมนู header: ลิงก์ภายใน (`/in-house/`) แปลงเป็นหน้าแปลของภาษาปัจจุบันตอน render (`hri_localize_url()`), label เป็น Polylang string กลุ่ม "HRI header menu" (Languages → Translations)
- ข้อความใน code: gettext ภาษาต้นทางเป็นไทย → `hri-core/languages/hri-core-{en_US,ja}.po/.mo/.l10n.php`, `hri-theme/languages/{en_US,ja}.*` (ของ theme ชื่อไฟล์เป็น locale อย่างเดียว) ข้อความไทยใน template ย้ายไปเป็น pattern PHP (`archive-course-header`, `single-course-back`, `single-course-cta`, `not-found`, `no-results`)
- แก้ข้อความ: แก้ .po → `wp i18n make-mo <dir>` + `wp i18n make-php <dir>`; เพิ่มข้อความใหม่ → `wp i18n make-pot . languages/hri-core.pot --skip-js --skip-block-json` แล้ว `wp i18n update-po`
- Polylang ฟรีไม่ให้ slug ซ้ำข้ามภาษา → ข้อมูลตัวอย่างใช้ `-en` / `-ja` ต่อท้าย ของจริงตั้ง slug เองได้ (slug ซ้ำได้ = Polylang Pro)

**ไอคอน** (รอบที่ 8) — **Phosphor Icons น้ำหนัก Light (MIT ไม่ต้องใส่เครดิต)** ทั้งเว็บ · Jimmi อยากได้แนว Flaticon UIcons Thin แต่ license ฟรีบังคับใส่เครดิตบนเว็บ → Jimmi เลือกชุดที่หน้าตาคล้ายกันแทน · Light ใกล้ UIcons Thin ที่สุดที่ 16–24px (Phosphor Thin จางเกินที่ 16px)
- ที่มาเดียว: `scripts/build-icons.py <โฟลเดอร์ @phosphor-icons/core>` (`npm pack @phosphor-icons/core`) → `hri-core/assets/icons/<ชื่อ>.svg` + `hri-core/assets/icons.css` (ตัวแปร `--hri-icon-<ชื่อ>` แบบ data URI ~1.7 KB พิมพ์ inline ทุกหน้า + ใน editor) · เพิ่มไอคอน = เพิ่มใน `ICONS` ของสคริปต์แล้วรันใหม่ (ห้ามแก้ไฟล์ผลลัพธ์ตรง)
- PHP: `hri_icon( 'globe', $class, $label )` (`inc/icons.php`) = SVG inline สีตามตัวอักษร ขนาด 1.25em (ไม่มี label = `aria-hidden`) · CSS: `content:""; background-color: currentColor; mask: var(--hri-icon-check) center / contain no-repeat;` · JS (slider, hero): SVG ส่งมาใน `hriL10n.<name>.icons` จาก `hri_enqueue_script()` (ไม่มี plugin = ใช้ SVG เดิมใน JS)
- ที่ใช้: ✓ ป้าย/checklist, + FAQ (หมุนเป็น × ตอนเปิด), +/− กลุ่มตัวกรองมือถือ, → ลิงก์การ์ดบริการ + ปุ่มหน้าบล็อก (ซ่อนตัวอักษร → ของ core), ← "หลักสูตรทั้งหมด", ‹ › หน้าตัวกรองคอร์ส (label "หน้าก่อนหน้า/หน้าถัดไป"), ลูกโลก + ลูกศรของปุ่มภาษา, เมนูแฮมเบอร์เกอร์/ปิด (แทน SVG ของ core ใน `render_block_core/navigation`), ก่อน/ถัดไปของ slider, เล่น/หยุดวิดีโอ hero · **ไม่เปลี่ยน**: โลโก้โซเชียล (โลโก้แบรนด์), จุดของรายการปัญหาและจุด slider (bullet), infographic หลอดไฟ

**ข้อมูลบริษัท** (`inc/company.php`) — Settings → ข้อมูลบริษัท (option `hri_company`): เบอร์, อีเมล, LINE/Facebook/YouTube/LinkedIn URL, และแยกภาษา TH/EN/JA: ที่อยู่, tagline ใต้โลโก้, หัวข้อ/ข้อความแถบ CTA · อ่านด้วย `hri_company( 'phone' )` (ฟิลด์แยกภาษาคืนภาษาปัจจุบัน ว่าง = ใช้ไทย) · ค่าเริ่มต้นใน `hri_company_defaults()` (จากเว็บเดิม) · ช่องลิงก์ว่าง = ไม่แสดง · `hri_contact_url()` / `hri_is_contact_page()`

**Blocks ของ footer** (server-rendered): `hri/logo` (`variant: color|white|black|mark|mark-white`, `width`, `link` → หน้าแรกของภาษา) · `hri/company-text` (`field`) · `hri/contact-info` (`items`) · `hri/social-links` (ไอคอน FB/YT/LinkedIn จาก core, LINE จาก Simple Icons CC0) · `hri/copyright` (ปีอัตโนมัติ) · `hri/cta-band` (ขอใบเสนอราคา → Contact + แอด LINE; ไม่แสดงในหน้า Contact ทุกภาษา) · CSS ใน `hri-core/assets/company.css`

**Logo** — `hri-theme/assets/images/logo/`: `logo.svg` (สี, พื้นสว่าง), `logo-white.svg` (พื้นเข้ม/รูป), `logo-black.svg`, `mark.svg`, `mark-white.svg` · trace จากไฟล์ JPG ที่ Jimmi ส่งมา (สี #097A76 / #13B4BC / #0B6E73, ตัวอักษร #272322) · แสดงใน Style Guide หัวข้อ 00 · ถ้าได้ไฟล์ต้นฉบับ vector (AI/EPS/PDF) ควรแทนที่

**Footer** (`parts/footer.html`): แถบ CTA → 4 คอลัมน์ (แบรนด์: โลโก้ขาว+tagline+โซเชียล · หลักสูตรและบริการ · บริษัท · ติดต่อเรา) → แถบล่าง © ปี · 4/2/1 คอลัมน์ที่ ≥1100 / ≥600 / มือถือ (CSS `base.css` ส่วน Footer) · ไม่แสดงเลขผู้เสียภาษี, ไม่มี Privacy Policy, ไม่มีแผนที่ (Jimmi ตัดสินแล้ว) · contrast วัดแล้ว: หัวข้อ accent 6.6:1, ข้อความ 12:1, ปุ่ม 5.9:1
- ข้อความที่พิมพ์ใน header/footer (label เมนู, heading, paragraph, button) = Polylang strings กลุ่ม "HRI header & footer" แปลที่ Languages → Translations (`hri_part_strings()` ใน `polylang.php` แทนที่ตอน render) ทีมการตลาดแก้ข้อความไทยใน Site Editor ได้ แต่แก้แล้วต้องแปลใหม่ (ข้อความเปลี่ยน = string ใหม่)
- navigation ที่ไม่มีชื่อได้ aria-label "เมนูหลัก" / "เมนูท้ายเว็บ" (`functions.php`)

**Header** (`parts/header.html`): โลโก้ `hri/logo` (สี, กว้าง 196) · navigation 7 รายการ (+ language-switcher list อยู่ใน nav) · language-switcher dropdown
- CSS ใน `base.css` บังคับเมนูแฮมเบอร์เกอร์ที่ <1220px (core ทำแค่ <600px)

**หน้าแรก** — page `home` (TH) + คำแปล `home-en` / `home-ja` (Polylang) ตั้งเป็น front page · Style Guide ย้ายไปอยู่แค่ `/style-guide/`
- เนื้อหาเป็น block ธรรมดาในหน้า (ไม่ใช่ pattern อ้างอิง) → ทีมการตลาดแก้ข้อความ ตัวเลข รูป ของแต่ละภาษาใน editor ได้ตรงๆ
- Patterns (หมวด HRI DeOne, ใส่ซ้ำได้): `hri/home` (ทุก section) และ `hri/home-{hero,intro,highlight,services,stats,clients,message,gallery,knowledge}` ลำดับใน `hri_home_sections()` (`functions.php`) · ข้อความใน pattern เป็น gettext ของ theme → seed สร้างหน้า EN/JA จาก pattern ด้วยไฟล์แปล (`playground/seed-home.php`)
- Hero = Cover block style **Hero** (`is-style-hero`): พื้นหลังเป็นรูปหรือวิดีโอที่อัปโหลด (MP4/WebM, ปิดเสียง วนซ้ำ) · `assets/js/hero.js` เล่นแค่ 10 วินาทีแรกแล้ววน (วิดีโอสั้นกว่านั้นวนตามความยาวจริง) · ปุ่มหยุด/เล่น 44px · prefers-reduced-motion = หยุดที่เฟรมแรก · overlay `primary-deep` 70% (ตัวขาวผ่าน AA แม้รูปพื้นหลังขาวล้วน: 4.9:1) · ไม่รองรับ YouTube (Jimmi เลือกแบบอัปโหลดไฟล์)
- Gallery style **Slider** (`is-style-slider`): scroll-snap (เลื่อนได้แม้ไม่มี JS) + `assets/js/slider.js` ปุ่มก่อน/ถัดไป, จุด, ลูกศรคีย์บอร์ด, รูปข้างเอียงแบบ 3D ที่ ≥782px (ตามเว็บเดิม) · 1 รูป = 1 สไลด์ (รูปเว็บเดิมเป็นภาพ collage อยู่แล้ว)
- Gallery style **Logos** (`is-style-logos`): แสดงโลโก้ลูกค้าทั้งหมดพร้อมกัน 10/8/5 ต่อแถว (≥1100 / ≥600 / มือถือ; 40 โลโก้ = 4 / 5 / 8 แถว) · ใส่ชื่อบริษัทเป็น alt ทุกโลโก้
- ปุ่ม style **Outline light** (ปุ่มขอบขาวบนพื้นเข้ม)
- `hri/course-highlight` (hri-core): การ์ดคอร์ส Public ที่ `open` เรียงวันเริ่มเร็วสุดก่อน (ไม่มีวันที่ไปท้าย) ตามภาษาปัจจุบัน · attribute `count` (ค่าเริ่ม 4), `program` · ไม่มีคอร์ส = ไม่แสดงอะไร
- บทความ = Query Loop ของ core 3 เรื่องล่าสุด (Polylang กรองภาษาเอง) · ปุ่ม "ดูบทความทั้งหมด" → `/blog/`
- บริการ: infographic หลอดไฟ 01–03 (`services-bulb.svg`, ตัวเลขอยู่ในรูป) + หัวข้อ/คำอธิบายเป็น block แปลได้ (`hri-svcmap`): desktop ≥1000px หลอดไฟกลาง 01/03 ซ้าย 02 ขวาตามแถบสี · จอเล็กหลอดไฟบน รายการเรียง 01→03 · ข้อความจากรูป reference ที่ Jimmi ส่งมา · แต่ละข้อมี CTA ปุ่ม "ดูรายละเอียด" ลิงก์ไปหน้าบริการเต็ม (/in-house/, /transformation-solutions/, /japanese-concept/) · หัวข้อใน section นี้เป็นอังกฤษล้วน (ไม่แปล)
- SVG ใน `<img>` อ่าน CSS variable ไม่ได้ จึงใช้ค่า hex เดียวกับ token · `hero.svg` = พื้นหลัง hero ชั่วคราว · placeholder ใน `assets/images/placeholder/` (photo, portrait, logo)
- ลิงก์ภายในในเนื้อหา (button, paragraph, heading, list, image, cover, media-text) แปลงด้วย `hri_localize_url()` ตอน render ทุกภาษา (`polylang.php`) → พิมพ์ `/in-house/` ได้เหมือนกันทุกภาษา
- Favicon: `<link rel="icon">` จาก `mark.svg` เมื่อยังไม่ได้ตั้ง Site Icon (Settings → General ต้องเป็นรูป PNG ≥512px)
- CSS ทั้งหมดอยู่ใน `base.css` ส่วน "Home" · script โหลดเฉพาะหน้าที่มี block นั้น (`render_block` ใน `functions.php`)

**หน้าบริการ** (In-house, Public Training, Transformation Solutions, Japanese Concept) — สร้างแบบเดียวกับหน้าแรก: เนื้อหาเป็น block ธรรมดาในหน้า (แก้ใน editor ได้ทุกภาษา) ประกอบจาก section patterns
- ลำดับ section ของทุกหน้าอยู่ใน `hri_page_sections()` (`functions.php`, key = slug หน้าไทย) · pattern รวม `hri/inhouse`, `hri/public`, `hri/transformation`, `hri/japanese` (ใส่ในหน้าว่าง) + pattern ย่อยทีละ section (`hri/inhouse-hero` ฯลฯ)
- `inc/markup.php` = ตัวช่วยพิมพ์ block markup มาตรฐานของ core (`hri_bm_*`: section, head, pagehero, cards, steps, details/FAQ, problems, ctaband, service) → pattern สั้นและ markup valid ใน editor (ตรวจแล้วทั้ง 12 หน้า)
- Component ใหม่ (CSS `base.css` ส่วน "Service pages"): page hero ข้อความ+รูป (`hri-pagehero`, chips จุดเด่น), การ์ด (`hri-cards`/`hri-card`: เลข, kicker, หัวข้อ, ข้อความ, checklist, ลิงก์ →), ขั้นตอน (`hri-steps--3/4/5`), รูปคู่ข้อความ (`hri-split`), กล่องปัญหา (`hri-problems`), FAQ = Details block (`hri-faq__item`, เปิด/ปิดได้ไม่ใช้ JS), แถบ CTA เข้มกลางหน้า (`hri-pagecta`), โลโก้พาร์ทเนอร์ 4 ต่อแถว (`hri-logos--4`), DO·WA·KEI อักษร 道 和 敬 (`hri-kanji`, ใส่ `<span lang="ja">`)
- หัวข้อ section ที่เป็นประโยค (แปลแล้วยาว) ใส่ class `hri-title-sentence` = ไม่บังคับตัวพิมพ์ใหญ่
- Conversion: ทุกหน้ามีปุ่มหลักใน hero (ขอใบเสนอราคา / นัดคุย / สมัคร → `/contact-us/`) + ปุ่มรองเลื่อนไปเนื้อหา (`#courses`, `#scope`, `#do-wa-kei`), จุดเด่นสั้นใต้ปุ่ม, CTA ท้ายแต่ละ section, FAQ ตอบข้อกังวล, In-house: ลิงก์ Leadership/Business ไปรายการคอร์สที่กรองแล้ว (`/in-house/?pillar%5B%5D=leadership#courses`), Public: แถบชวนจัด In-house, Technology Strategy ลิงก์ไปคอร์ส Lean
- In-house: hero → Leadership+Business → Why HRI DeOne → How it works → รายการคอร์ส (`#courses`) → โลโก้ลูกค้า (ใช้ `home-clients`) → FAQ · Public: hero → คอร์สที่เปิด (`#courses`) → ทำไมต้อง Public → ขั้นตอนสมัคร → แถบ In-house → FAQ
- Transformation: hero → 4 Scope (การ์ดลิงก์ anchor) → Business (`#business-strategy`) / Technology / People / Culture Strategy → พาร์ทเนอร์ 8 โลโก้ · Japanese Concept: hero → 3 โปรแกรม → DO·WA·KEI (`#do-wa-kei`) → ความท้าทาย 4 ข้อ → Journey 3 ขั้น (`#journey`) → Business Outcomes → แถบปิด "Build something that outlasts you." (+ปุ่ม LINE จาก `hri_company('line_url')`)
- ไม่ได้ทำหน้าย่อยของเว็บเดิม (`/transformation-solutions/business-strategy/` ฯลฯ, `/japanese-concept/do-wa-kei/`, `/in-house/leadership-development/` ฯลฯ) เนื้อหาย่อลงมาอยู่ใน section ของหน้าหลักแล้ว → ตอนย้ายเว็บให้ 301 หน้าย่อยไป anchor ของหน้าหลัก หรือสร้างหน้าย่อยทีหลัง
- `hri_localize_url()` เก็บ query string และ `#anchor` ไว้ตอนแปลงลิงก์ภายในเป็นภาษาอื่น (เดิมตัดทิ้ง)
- Seed: `hri_seed_page_content( $slug, $locale )` (`seed-home.php`) render patterns ตามภาษา แทนที่รูป placeholder ด้วยรูปตัวอย่าง (คง alt) — ใช้ทั้งหน้าแรกและ 4 หน้าบริการ

### 4.w แปลอัตโนมัติ TH → EN/JA (รอบที่ 14, branch `feature/auto-translate`, merge เข้า `main` แล้ว 27 ต.ค. 2569 — ยังไม่ทดสอบกับ API จริง)

สเปก/คำตอบของ Jimmi: [`AUTO-TRANSLATE.md`](AUTO-TRANSLATE.md) (provider = Claude · ลูกค้าเชื่อม API ของตัวเอง ใช้ตามโควต้าบัญชีนั้น ไม่มีเพดานในเว็บ · ยังไม่มีผู้ตรวจ · JA = เผยแพร่แต่ noindex จนกด "ตรวจแล้ว" · บทความเดิม 115×3 ภาษาไม่แตะ)
- **ไฟล์** (`hri-core/inc/translate/`): `segments.php` (แตกหน้าไทยเป็น segment: `title`, `excerpt`, `m:<meta>`, `b:<hriId>:<n>`; ใส่ `metadata.hriId` ให้ block ข้อความ — ผ่าน editor โดยไม่ขึ้น invalid) · `providers.php` (`anthropic` = Messages API, โมเดลตั้งต้น `claude-sonnet-5-5`; `fake` = ใส่ prefix `[EN] `/`[JA] ` ไว้ทดสอบ; ตรวจว่า markup/แท็กกลับมาเหมือนเดิม) · `glossary.php` (อภิธานในตัว + ชื่อคอร์ส/วิทยากรที่แปลแล้ว + ที่บันทึกใน Settings) · `translate.php` (คิว WP-Cron, ตรวจว่าคนแก้หรือยัง, outdated + ข้อเสนอ, noindex flag, WP-CLI `wp hri translate run <id> [--lang=] [--adopt]`) · `admin.php` (กล่อง "การแปล", คอลัมน์, หน้า "งานแปล", Settings → การแปล, อีเมลสรุปรายวันเมื่อใส่ผู้ตรวจ)
- **เปิดใช้**: ใน `wp-config.php` `define('HRI_TRANSLATE_PROVIDER','anthropic'); define('HRI_TRANSLATE_API_KEY','sk-ant-…');` (ไม่ตั้ง = โมดูลปิดเงียบๆ ไม่มี notice; ตั้ง `fake` ไว้ลองใน local) · ติ๊กภาษาที่จะแปลรายหน้าในกล่อง "การแปล" (page/course/trainer เปิดเป็นค่าตั้งต้น, บทความต้องติ๊กเอง)
- **พฤติกรรมสำคัญ**: เผยแพร่/อัปเดตหน้าไทย → แปลเบื้องหลังใน ~1 นาที (บันทึกถี่ = งานเดียว) · แก้หน้าแปลด้วยมือ → ระบบจำ ไม่ทับ · ไทยเปลี่ยนใน segment ที่คนแก้ → ⚠️ ต้องตัดสิน (ใช้ข้อเสนอเครื่อง/เก็บที่แก้) · แทรก/ลบ/ย้าย block ตามโครงไทย · ข้อความที่คนเขียนเองในส่วนที่ไทยลบแล้ว = "orphan" เก็บไว้ให้ตัดสิน · หน้าแปลมือเดิมไม่มี `_hri_tr` ไม่ถูกแตะ จนกดปุ่ม "ใช้การแปลอัตโนมัติ" (adopt) · error: transient/bad_output ลองใหม่ 3 ครั้ง, quota/auth/config ขึ้น ❌ ทันทีพร้อมปุ่ม "ลองใหม่"
- **meta**: ต้นทาง `_hri_tr_langs`, `_hri_tr_job_{lang}` · หน้าแปล `_hri_tr` (สถานะ machine/reviewed/outdated + segment), `_hri_tr_noindex` · option `hri_tr_settings`, `hri_tr_glossary`, `hri_tr_usage` (นับ token รายเดือน ไว้ดูเฉยๆ)
- **ทดสอบแล้วใน local (provider fake + จำลอง HTTP)**: 22 กรณี engine (คนแก้/outdated/แทรกลบ/noindex JA/ตรวจไม่ได้ขณะ outdated/รอบสองไม่เสียเงิน/accept/keep) + บันทึกถี่ 5 ครั้ง → 1 งาน, คอร์ส+วิทยากร+meta แปลถูก, slug คงที่, block parse + serialize ตรงกัน, HTTP 429/401/529/เนื้อเสียแมปถูก kind, retry 3 ครั้งแล้ว ❌, ไม่มี key = ปิด · หน้าหลังบ้านโหลดไม่มี error, ปุ่ม (nonce/สิทธิ์) ทำงาน · seo-check ผ่าน (llms.txt ข้ามหน้า noindex แล้ว)
- **⚠️ สถานะ 27 ต.ค. 2569**: merge เข้า `main` ก่อนผ่านการทดสอบกับ API จริง · ทดสอบแล้ว: key ยิง `api.anthropic.com` ได้ (ตอบ HTTP 400 `Your credit balance is too low` = key ถูกต้องแต่บัญชีไม่มีเครดิต) · ระบบจัดการ error โควต้าถูกต้อง (ขึ้น ❌ ไม่ retry วน) · ยังไม่เคยได้คำแปลสำเร็จ · คอร์ส 25 (มีคำแปลมือเดิม) ระบบปฏิเสธ `--adopt` เพราะโครงบล็อกไม่ตรง (พฤติกรรมถูกต้อง)
- **ยังไม่ได้ทดสอบ (ต้องใช้ API key จริงที่มีเครดิต)**: ตัวเชื่อม Claude เขียนจากเอกสาร API แต่ไม่เคยยิงจริง (ใน sandbox ไม่มี key/เน็ต) · การจัดการโควต้าหมดจริง · คุณภาพ/ราคาจริง (ดู AUTO-TRANSLATE.md §7) · ข้อความหลังบ้านของโมดูลนี้ยังเป็นไทยอย่างเดียว (ไม่มีใน .po EN/JA) · ยังไม่ได้ลองใน Playground จริง

**เช็กลิสต์ทดสอบแปลอัตโนมัติ (ทำเมื่อมี key ที่มีเครดิต)**
1. สร้าง key ใหม่ใน Anthropic Console (Settings → API Keys) และตรวจว่าบัญชีมีเครดิต/เปิด Billing ก่อน — ห้ามวาง key ในแชทหรือใน repo
2. ตั้งค่าใน `wp-config.php` ของเว็บทดสอบ (local หรือ staging): `HRI_TRANSLATE_PROVIDER` = `anthropic`, `HRI_TRANSLATE_API_KEY` = key นั้น
3. ตั้งเว็บ local ใหม่ได้ด้วย `scripts/local-setup.sh` + Polylang 3.9.1 + `wp hri languages` + `wp eval-file playground/seed-languages.php` (ถ้า `/home/claude/hri-local` หายไปแล้ว)
4. สร้างหน้าไทยทดสอบที่มีทุกชนิด block (heading, paragraph ตัวหนา/ลิงก์, list, quote) และคอร์สไทยใหม่ (อย่าใช้คอร์สตัวอย่างที่มีคำแปลมือ — จะโดนปฏิเสธเพราะโครงบล็อกไม่ตรง)
5. รัน `wp hri translate run <id> --lang=en,ja` แล้วตรวจ: block valid ใน editor, แท็ก inline ครบ, JA เป็น noindex จนกว่า reviewed, ไม่ทับงานคนแก้, ยอด token ใน `hri_tr_usage`
6. บันทึกผลและยอดจริงลง `AUTO-TRANSLATE.md` ข้อ 7 แล้วอัปเดตข้อ 4.w ให้ตรงกับผล
7. รัน `python3 -I scripts/measure.py` และ `python3 -I scripts/seo-check.py` ผ่าน แล้วเพิกถอน key ทดสอบ

### 4.z SEO + AI SEO (รอบที่ 13)

- **ปลั๊กอิน**: The SEO Framework (TSF, ฟรี, slug `autodescription`) = title / meta description / canonical / Open Graph / sitemap แยกภาษา (`/sitemap.xml` `/en/sitemap.xml` `/ja/sitemap.xml`) · ติดตั้งใน blueprint แล้ว · ตั้งค่าด้วย `hri_seo_setup()` (`wp hri seo setup`, blueprint, หรืออัตโนมัติครั้งแรกที่แอดมินเข้า wp-admin) · TSF ตัวเดียวนอกจาก Polylang · **ไม่มี TSF เว็บยังได้ JSON-LD, x-default, robots.txt, llms.txt** (title/description/sitemap ถอยไปใช้ของ WordPress)
- **ไฟล์**: `hri-core/inc/seo.php` (TSF + noindex + hreflang + title หน้าแรก + "อัปเดตล่าสุด") · `inc/schema.php` (JSON-LD) · `inc/ai-seo.php` (robots.txt, llms.txt, IndexNow, Settings → SEO และ AI) · `scripts/seo-check.py` (ตัวตรวจ) · `scripts/local-router.php` (ให้ `php -S` ส่ง `/wp/sitemap.xml` `/wp/llms.txt` เข้า WordPress — `local-setup.sh` ใช้เอง)
- **JSON-LD**: 1 สคริปต์ / หน้า = `@graph` เดียว (TSF ปิด schema ของตัวเองถาวร กันซ้ำ) · ทุกหน้า: EducationalOrganization (ข้อมูลจาก Settings → ข้อมูลบริษัท), WebSite + SearchAction, WebPage (หรือ AboutPage / ContactPage / CollectionPage / SearchResultsPage), BreadcrumbList (หน้าแรกไม่พิมพ์ เพราะมีข้อเดียว) · บทความ: BlogPosting · คอร์ส: Course + hasCourseInstance + Offer (ราคา, Early Bird จนถึงกำหนด) + instructor (Person จากวิทยากรของคอร์ส) · FAQPage จาก Details block `hri-faq__item` · node id = `<url>#<ชื่อ>` · filter `hri_schema_graph`
- **hreflang**: Polylang พิมพ์ th/en/ja, เราเพิ่ม `x-default` (= ไทย) และตัดทิ้งทั้งชุดถ้าคำแปลใดเป็น noindex · หน้า noindex (`style-guide`, `sample-page`, `privacy-policy` + คำแปล, filter `hri_seo_noindex_slugs`) ออกจาก sitemap ด้วย · คอร์สที่ลิงก์ไปเว็บอื่น (`hri_external_url`) ออกจาก sitemap
- **title หน้าแรก**: tagline ของ WordPress เป็นภาษาเดียว → ฝั่งหน้าเว็บแทนด้วยข้อความใน .po ("HRI DeOne | Executive training and organizational development" ฯลฯ) · พิมพ์ title หน้าแรกเองใน TSF ได้ (ชนะค่านี้) · หน้า Blog ไม่มีข้อความให้ TSF สร้าง description → ใช้ข้อความสำรองใน .po
- **robots.txt** (virtual, ไม่มีไฟล์จริง): บอตค้นหา/ตอบคำถาม AI = อนุญาตเสมอ (OAI-SearchBot, ChatGPT-User, Claude-SearchBot, Claude-User, PerplexityBot, Perplexity-User, Bingbot, Googlebot, Applebot) · บอตเทรนโมเดล (GPTBot, ClaudeBot, Google-Extended, Applebot-Extended, CCBot, Bytespider) = สวิตช์เดียวใน Settings → SEO และ AI **ค่าเริ่มต้น "อนุญาต"** · ผลค้นหาภายใน (`?s=`, `/search/`) ห้ามคลาน · ต้องเปิดที่ `/robots.txt` ของโดเมน (WordPress ตอบเฉพาะติดตั้งที่ root; local ที่ `/wp/` ดูที่ `/wp/?robots=1`)
- **/llms.txt** (virtual): สรุปบริษัท + ลิงก์ หน้าแรก/บริการ 4 หน้า/About/Contact/รวมคอร์ส/บล็อก + **ทุกคอร์ส พร้อมคำอธิบายหนึ่งบรรทัด** × 3 ภาษา · สร้างจากเนื้อหาจริง cache 1 วัน ล้างเองเมื่อบันทึกหน้า/คอร์ส/ข้อมูลบริษัท · filter `hri_llms_page_slugs`, `hri_llms_txt`
- **IndexNow**: ปิดไว้ (เปิดที่ Settings → SEO และ AI) · ส่งเฉพาะเมื่อ `wp_get_environment_type() = production`, ไม่ใช่ Playground/localhost · key สร้างเองเมื่อเปิด เก็บใน option (ไม่อยู่ใน repo) ไฟล์ key ที่ `/<key>.txt` · ส่งตอน publish/แก้ post, page, course ที่ไม่ noindex
- **"อัปเดตล่าสุด"**: บทความที่แก้หลังเผยแพร่เกิน 1 วันแสดงต่อท้ายวันที่ (ค่าเดียวกับ `dateModified`) · ตัวนำเข้าบทความตั้ง modified = วันที่เผยแพร่ถ้า export เก่าไม่มีค่า (`hri_blog_import_keep_modified`) และ `wxr-to-blog.py` เก็บ `post_modified` จริงจาก export ใหม่ → ตอนย้ายจริงจึงไม่ขึ้น "อัปเดต" ทุกเรื่อง
- **สรุปบนสุดของหน้าคอร์ส**: หน้าคอร์สมี excerpt + ตารางข้อมูล (`hri/course-summary`) อยู่บนสุดอยู่แล้ว ใช้เป็นทั้ง meta description และ `Course.description` จึงไม่เพิ่มย่อหน้าซ้ำ · excerpt ทั้ง 63 รายการ (21 คอร์ส × 3 ภาษา) มีครบแล้ว วัดแล้วยาวมัธยฐาน TH 85 / EN 100 / JA 40 ตัวอักษร ไม่เกิน 129 (พอดีกับ meta description) — ส่วนใหญ่ตอบ "คืออะไร" แต่ "เหมาะกับใคร" มาจากช่องกลุ่มเป้าหมาย (`hri_audience`) ซึ่งบางคอร์สยังว่าง
- **ผลทดสอบ (25 ต.ค. 2569, local SQLite)**: seo-check.py ผ่าน 429 URL (345 บทความ + 60 คอร์ส + 24 หน้า; 143 ต่อภาษา) + robots.txt + 88 ลิงก์ใน llms.txt · measure.py 1280/375 × 3 ภาษา × 9 หน้า = 54 รายการ 0 ปัญหา · **ยังไม่ได้วัด perf.py ซ้ำ** (JSON-LD ~2–4 KB ต่อหน้า) และ **ยังไม่ได้ทดสอบ blueprint บน Playground จริง** (เพิ่มขั้นติดตั้ง TSF + `hri_seo_setup()`; ทดสอบ branch ตามข้อ 3) · ยังไม่ได้ validate JSON-LD กับ validator ของ Google/schema.org (เข้าไม่ได้จาก workspace — ตรวจโครงสร้างเองด้วยสคริปต์)
- **ตรวจ**: `python3 -I scripts/seo-check.py [--base http://127.0.0.1:8899/wp]` ไล่ทุก URL ใน sitemap (title, description, canonical, noindex, html lang, hreflang ครบ+ชี้กลับ, JSON-LD ครบตามประเภท, id อ้างอิงถึง node จริง) + robots.txt + ทุกลิงก์ใน llms.txt · exit 1 ถ้ามีปัญหา · ใช้ stdlib อย่างเดียว

### 4.y ค้นหาทั้งเว็บ (รอบที่ 10)

- **ไฟล์**: `hri-core/inc/search.php` (เครื่องยนต์ + ดัชนี + WP-CLI), `inc/search-admin.php` (Settings → การค้นหา), blocks `hri/search-form` (`variant: dropdown|inline|page`) และ `hri/search-results`, CSS `assets/search.css` + `assets/search-results.css`, theme: `templates/search.html`, pattern `hri/search-hero`, header (ไอคอนค้นหา ≥1220px / ช่องค้นหาใน hamburger <1220px; จุดสลับ header ขยับจาก 1100 → 1220px เพราะเมนูญี่ปุ่นยาวสุดและไม่พอที่ 1100–1209px), 404 ใช้ฟอร์มเดียวกัน · ไอคอน `magnifying-glass` เพิ่มผ่าน `scripts/build-icons.py`
- **ค้นอะไร**: course, post, page ของภาษาปัจจุบัน (Polylang `lang`; ภาษาหลักรวมโพสต์ที่ยังไม่ตั้งภาษา) · ไม่ค้นวิทยากร/Lead/CPT ที่ไม่มีหน้า public · หน้าที่ไม่รวม: หน้า blog, `style-guide`, `sample-page`, `privacy-policy` และคำแปล (filter `hri_search_excluded_slugs`)
- **ดัชนี = ตารางของเราเอง 2 ตาราง** (สร้างด้วย `CREATE TABLE` ธรรมดา ใช้ได้ทั้ง MySQL และ SQLite): `{prefix}hri_search_docs` (1 แถว/โพสต์: ภาษา, ประเภท, สถานะเปิด/ปิดรับ, menu_order, วันที่, `txt` = ข้อความตัดช่องว่างไว้ตรวจคำต่อเนื่อง) และ `{prefix}hri_search_idx` (โพสต์ × คำ: ตำแหน่ง bitmask 1 ชื่อ/2 ชื่อ term/4 excerpt/8 เนื้อหา + จำนวนครั้ง) · เปลี่ยนโครงสร้าง = แก้ `HRI_SEARCH_DB_VERSION` (drop/create แล้วสร้างใหม่เอง)
- **ตัดคำไทย**: ถ้ามี ext-intl → ICU `IntlBreakIterator` (ภาษาไทยมีพจนานุกรมในตัว); ไม่มี → n-gram (ไทย = คู่ตัวอักษร, ญี่ปุ่น/จีน = คู่ตัวอักษร + คันจิตัวเดียว) · ดัชนีจำว่าสร้างด้วยโหมดไหน ถ้าโหมดของโฮสต์เปลี่ยนจะสร้างใหม่เองตอนค้นครั้งแรก (บังคับ n-gram: `define('HRI_SEARCH_TOKENIZER','ngram')`) · อังกฤษ: lowercase + ยุบพหูพจน์ · ตัว stopword ไทย/อังกฤษ/ญี่ปุ่นสั้นๆ ("ผู้" ไม่ใช่ stopword)
- **ค้นยังไง**: ทุกคำต้องตรงก่อน (AND) ถ้าไม่มีหน้าไหนตรงทุกคำ แสดงหน้าที่ตรงมากคำที่สุดพร้อมข้อความบอก · คำไทยที่พิมพ์ติดกันแล้ว ICU แยกเป็นท่อนที่ยาว ≥3 ตัวทุกท่อน ("หลักสูตรผู้นำ") ต้องเจอทุกท่อน และถ้าเจอติดกันได้คะแนนเพิ่ม (+500); ถ้ามีท่อนสั้น (<3 ตัว เช่นชื่อทับศัพท์ที่ถูกตัดครึ่ง) และคำ CJK ต้องปรากฏติดกันเป็นชิ้นเดียว · ถ้าไม่เจอเลยและเป็นไทย/CJK → ลองหาข้อความต่อเนื่องใน `txt` (เจอคำทับศัพท์/ชื่อที่ตัดคำต่างกันในประโยค) · คะแนน: ชื่อ 1000 > ชื่อ term 300 > excerpt 100 > เนื้อหา (≤10) · คอร์ส: จำนวนคำตรง → เปิดรับสมัครก่อน full/closed → คะแนน → menu_order · บทความ/หน้า: คะแนน → ใหม่สุด · synonym ได้คะแนน ×0.9 เทียบคำที่พิมพ์เอง
- **Synonym**: Settings → การค้นหา · หนึ่งบรรทัดหนึ่งกลุ่ม คั่นด้วย `=` (`เจรจา = negotiation = 交渉`) · ค่าเริ่มต้น 25 กลุ่มจากชื่อคอร์ส/หมวดจริง (`hri_search_default_synonyms()`) · เก็บใน option `hri_search_synonyms` (ว่าง = ใช้ค่าเริ่มต้น; ปุ่ม "คืนค่าเริ่มต้น") · ใช้ตอนค้น ไม่ต้องสร้างดัชนีใหม่
- **สร้างดัชนีใหม่ไม่ลบของเดิม**: `hri_search_rebuild()` ทำใหม่ทีละโพสต์ (แทนแถวเดิม) แล้วลบแถวของโพสต์ที่หายไปตอนท้าย → ค้นได้ตลอดระหว่างสร้าง · ความคืบหน้าเก็บใน option `hri_search_build` ถ้าถูกตัดด้วยเวลา (ค้นครั้งแรกทำ ≤20 วินาที/คำขอ) คำขอถัดไปทำต่อจากเดิม (ทดสอบแล้ว: n-gram 37 วินาที เสร็จใน 2 คำขอ) · ถ้าเปลี่ยนวิธีตัดคำ ระหว่างสร้างผลค้นอาจไม่ครบชั่วคราว (แถวเก่า/ใหม่ปนกัน) จนเสร็จ
- **ดัชนีอัปเดตเอง**: บันทึก/เปลี่ยนสถานะ/ลบโพสต์, เปลี่ยน term, เปลี่ยน `hri_reg_status`/`hri_audience` → จัดคิวแล้วทำตอน `shutdown` · สร้างใหม่ทั้งหมด: ปุ่มใน Settings (ทีละ 40 โพสต์ ใช้ meta refresh ไม่ต้องมี JS), `wp hri search reindex [--tokenizer=icu|ngram]`, ค้นครั้งแรกถ้ายังไม่มีดัชนี (ทำในคำขอนั้น ≤25 วินาที มี lock) · Playground: ขั้นสุดท้ายใน `blueprint.json` เรียก `hri_search_rebuild()`
- **เครื่องมือ**: `wp hri search query "<คำ>" --lang=th|en|ja --limit=5` (แสดงผล + เวลา)
- **หน้าผลลัพธ์**: `?s=` (หรือ `/search/<คำ>/`) · สรุปจำนวน · ลิงก์ประเภท (ทั้งหมด/หลักสูตร/บทความ/หน้าเว็บ + จำนวน) · มุมมองรวมแสดงประเภทละ 6 พร้อมลิงก์ "ดูทั้งหมด" → `?type=…` (12 ต่อหน้า, `?pg=`) · คอร์สใช้ `hri_course_card()` (รูปปกตามที่มี) บทความ/หน้าเป็นข้อความล้วน แสดงตัวอย่างตรงที่เจอคำ (เริ่มที่ต้นคำ) และไฮไลต์คำด้วย `<mark>` · มือถือ (≤640px) การ์ดคอร์สเป็นแถวเดียว (รูปเล็ก + ชื่อ + สถานะ) · ไม่เจอเลย → หน้า 200 + หลักสูตรแนะนำ 3 ใบ (เปิดรับสมัครก่อน) + ลิงก์ดูหลักสูตรทั้งหมด (`/courses/` ของภาษานั้น)/ติดต่อเรา (ไม่ได้ใช้ pattern `no-results` เพราะนั่นเป็นของ query loop) · ไม่มี JS ในหน้าผลลัพธ์ · JS เดียวคือ `search-form/view.js` (โฟกัสช่อง/ปิดด้วย Esc/คลิกนอก ของ dropdown ในเฮดเดอร์ ~1 KB)
- **ความปลอดภัย**: คำค้นผ่าน `sanitize_text_field` ตัด tag, ตัดเหลือ 100 ตัวอักษร, `?s[]=` ถือว่าว่าง, ทุกที่ที่แสดงใช้ `esc_*`, SQL ใช้ `$wpdb->prepare` + `esc_like`, `?type=` เป็น whitelist

### 4.x เพิ่มในรอบที่ 3 (19 ต.ค. 2569)

**Lead form + Contact** — block `hri/lead-form` + `inc/leads.php` (hri-core) · CPT `hri_lead` (เห็นใน admin "ผู้สนใจ (Lead)", สร้างเองไม่ได้) · ส่งด้วย `admin-post.php` (`hri_ajax=1` = JSON, ไม่มี JS = redirect กลับ `?hri_lead=ok|error#hri-lead`) ไม่ใช้ REST/nonce (หน้าถูก cache) · honeypot `hri_website`, rate limit 5 ครั้ง/10 นาที, ต้องติ๊ก PDPA, Turnstile ถ้าตั้งค่า · ตั้งค่าใน wp-config.php (ห้ามใส่ใน repo): `HRI_LEAD_WEBHOOK_URL`, `HRI_LEAD_WEBHOOK_TOKEN`, `HRI_LEAD_NOTIFY`, `HRI_TURNSTILE_SITE_KEY`, `HRI_TURNSTILE_SECRET` → วิธีทำ Apps Script/Sheet/Turnstile: [`docs/LEAD-SETUP.md`](LEAD-SETUP.md) · เติมคอร์สที่สนใจอัตโนมัติจาก attr `interest`, `?hri_course=<slug>` (อย่าใช้ `?course=` ชนกับ query var ของ CPT `course`), `?interest=`, หรือบริบทหน้าคอร์ส · patterns: `contact-*` (hero, main, map, group), `inhouse-enquiry` · ข้อมูลบริษัท (ชื่อนิติบุคคล, เลขภาษี) ใน Settings → ข้อมูลบริษัท · LINE ใช้ @hrideone ชั่วคราว (แก้ที่เดียวใน Settings)

**โมเดลคอร์สขยาย** (`inc/content-model.php`, `course-card.php`, `trainers.php`) — meta ใหม่: `hri_price_early`, `hri_early_deadline` (เลยวันแล้ว Early Bird ซ่อนเอง), `hri_price_note`, `hri_schedule_text`, `hri_time_text`, `hri_certificate`, `hri_register_url` (ปุ่ม "สมัครเลย" ทุกจุดชี้ที่นี่ รุ่นใหม่ = แก้ช่องเดียว), `hri_external_url` (คอร์สที่มีเว็บแยก เช่น TEN X: การ์ดลิงก์ออก + redirect หน้าเดี่ยว), `hri_trainers` · CPT `trainer` (วิทยากร, แปลได้, ไม่มีหน้า public) · blocks: `hri/course-register`, `hri/course-summary`, `hri/course-trainers`, `hri/lead-form` · `hri_course_cta()` เลือกปุ่มตามประเภท/สถานะ · การ์ดคอร์สแสดง "รุ่นที่ N · วันที่"

**หน้าคอร์ส** — `templates/single-course.html` (hero + `post-content` + pattern `single-course-enrol`: ตารางข้อมูล, ปุ่มสมัคร, ฟอร์ม) · เนื้อหาในตัวคอร์สสร้างจาก `hri_course_page_markup( $data, 'public'|'inhouse' )` (`inc/course-page.php`) = core blocks ล้วน แก้ใน editor ได้ · patterns `course-public` / `course-inhouse` เป็นต้นแบบ (ข้อความตัวอย่าง) สำหรับคอร์สใหม่ · section: intro, pain points (+ "คอร์สนี้ช่วยอย่างไร"), outcomes, audience, quote, program, why-HRI (In-house), trainers, gallery, reviews, CTA, FAQ

**About** (`/about/`) — `hri_page_sections()['about']` : about-hero, about-mission, about-values, about-message (เต็ม), about-timeline, home-stats, home-clients, about-impact, contact-group, home-knowledge, about-cta

**Blog** — `templates/home.html` (หน้า posts), `archive.html` (หมวด), `single.html` (หมวด, วันที่, รูปปก, เนื้อหา, บทความที่เกี่ยวข้อง, CTA) · patterns `blog-hero`, `blog-loop`, `blog-cta` · blocks `hri/blog-filter` (ปุ่มหมวด, ไม่แสดงหมวด default/ว่าง), `hri/related-posts` (3 เรื่อง หมวดเดียวกันก่อน) · หน้า `blog` เป็น `page_for_posts` (คำแปล: EN slug `blog-en`, JA slug `ブログ` = ตรงกับเว็บเดิม)
- **บทความจริงจากเว็บเดิม** (รอบที่ 6): 115 เรื่อง × TH/EN/JA = 345 โพสต์ ผูกคำแปลด้วย Polylang · slug เดิมทุกเรื่อง (รวม `why-success-ompanies-…`) · เว็บเดิมใช้ **WPML + Elementor** (export ไม่มีข้อมูลภาษา: แยกภาษาจากตัวอักษรในชื่อ, จับคู่คำแปลด้วย `post_date` ที่ WPML copy ให้ตรงกัน = 115 ชุดพอดี)
  - `scripts/wxr-to-blog.py <export.xml>` → `playground/data/blog-posts.json` (+ `.json.gz` สำหรับอัปโหลด) · ล้าง HTML ของ Elementor เป็น core blocks (paragraph, heading, list, image, quote, table, separator, details สำหรับ FAQ toggle) · ตรวจแล้ว: ข้อความไม่หาย, 9,959 block valid ใน editor, ไม่มี Classic block · ต้องมี `beautifulsoup4`
  - หมวด: เหลือ 3 หมวด Business 55 / Coaching 48 / Sales 12 · 63 เรื่องที่เว็บเดิมเป็น "ไม่มีหมวดหมู่" จัดหมวดใหม่ตามชื่อเรื่องใน `CATEGORY_MAP` ของสคริปต์ (Jimmi สั่ง "จัดลงหมวดที่มีอยู่") · ชื่อหมวดเป็นอังกฤษทุกภาษา (ตามเว็บเดิม) slug `business` / `business-en` / `business-ja`
  - `hri-core/inc/blog-import.php`: `hri_blog_import( $posts, [images => remote|download, offset, limit] )` idempotent (หาโพสต์จาก `_hri_source_id` = ID เว็บเดิม) · `wp hri import-blog <file> [--images=download]` · **Tools → นำเข้าบทความ** (อัปโหลด `.json.gz`, ทำทีละ 5 เรื่องต่อ request แล้วหน้าโหลดต่อเอง; ทดสอบ local 115 เรื่อง ~100 วินาที)
  - รูป: `remote` = ใช้รูปที่ hrideone.com (Playground/local; ปกเก็บใน meta `_hri_thumbnail_url` แสดงผ่าน filter `has_post_thumbnail` / `post_thumbnail_html` ใน `blog.php`) · `download` = คัดลอกปก+รูปในเนื้อหาเข้าคลังสื่อ (ไม่ซ้ำ หาจาก `_hri_source_url`) แก้ URL/ID ใน image block → **ต้องรันบน staging ตอนเว็บเดิมยังออนไลน์**
  - slug ชนข้ามภาษา (Polylang ฟรี): 4 เรื่อง EN ได้ `-en` (`10-ways-to-build-trust-in-the-workplace-en`, `10-popular-courses-for-senior-executives-en`, `7-ways-to-overcome-promotion-anxiety-en`, `4-challenges-in-changing-corporate-culture-en`) slug เดิมเก็บใน `_wp_old_slug` · `_wp_old_slug` ของเว็บเดิม (145 รายการ) นำเข้าด้วย → WordPress redirect เอง
- `hri-core/inc/blog.php`: 12 เรื่องต่อหน้าทั้ง blog และหน้าหมวด (`HRI_POSTS_PER_PAGE`, เต็มแถวในกริด 3/2/1 คอลัมน์) · 301 จาก URL เว็บเดิม: `?e-page-<id>=N` (Elementor, 15/หน้า) → `/page/M/` ที่มีเรื่องแรกของหน้าเดิม · `/en/blog/` → `/en/blog-en/` · `/en/<slug>/` ของเรื่องที่ได้ `-en` · `/en|ja/category/business/` → `business-en|ja` · หมวด "ไม่มีหมวดหมู่" (`/category/ไม่มีหมวดหมู่/`, `uncategorized-en-us`, `uncategorized-ja`) → หน้าบล็อกภาษานั้น · ทำใน `parse_request` เพราะ Polylang จะ redirect `/en/<slug ไทย>/` ไปโพสต์ไทยก่อน
- Blog UI: ปุ่มหมวด/ป้ายหมวดใช้ `primary-strong` (เดิม `primary` contrast 2.64:1) · pagination 44px + หน้าปัจจุบันทึบ · ทั้งการ์ดกดได้ (title link `::after` คลุมการ์ด, ป้ายหมวดอยู่บนสุด) · excerpt ตัดที่ 3 บรรทัดด้วย `line-clamp` (ภาษาไทย/ญี่ปุ่นไม่มีเว้นวรรค `excerptLength` จึงไม่ย่อ)

**Seed ข้อมูลตัวอย่าง** — `playground/seed-course-data.php` (วิทยากร 4 คน, เนื้อหา Public 5 คอร์ส + TEN X, In-house ครบ 15 คอร์ส; TH/EN/JA) · `seed-home.php`: `hri_seed_course_body( $data|'sample', $program, $locale )` · `hri_seed_inhouse_courses()` มีเนื้อหาจริง (เรียบเรียงใหม่) ของทั้ง 15 คอร์ส In-house: `excerpt` + `page` = `intro` (หัวข้อ, ย่อหน้า), `problems` 4 ข้อ (ชื่อ, ปัญหา, "คอร์สนี้ช่วยอย่างไร"), `outcomes` 5 ข้อ (ชื่อ, คำอธิบาย) · ชื่อหัวข้อ problems/outcomes เป็นอังกฤษเหมือนกันทุกภาษา · `'sample'` (ข้อความ placeholder ที่แปลแล้ว) ใช้เป็น fallback ถ้าคอร์สไหนไม่มีข้อมูลใน `hri_seed_inhouse_courses()` และเป็นต้นแบบใน pattern "Course page — In-house" สำหรับคอร์สใหม่

## 5. เสร็จแล้ว

- [x] Design tokens + Style Guide (Jimmi รับแล้ว)
- [x] Content model คอร์ส + การ์ด + หน้าคอร์สเดี่ยว
- [x] Course filter (ทดสอบ desktop/mobile, no-JS, พารามิเตอร์แปลก, XSS ไม่สะท้อน)
- [x] หน้า `/in-house/` และ `/public-training/` (block เดียวกัน ล็อกประเภท)
- [x] Thumbnail 3:2 + รูปตัวอย่าง
- [x] Header ใหม่ + ปุ่มภาษา (ตรวจที่ 1280/1100/1024/768/375 ผ่านการจำลองจอ)
- [x] Footer ใหม่ + โลโก้ SVG 5 แบบ + หน้าข้อมูลบริษัท + แถบ CTA (ทดสอบ 1280/768/375, TH/EN/JA ใน Playground)
- [x] หน้าแรก TH/EN/JA: Hero (รูป/วิดีโอ ≤10 วินาที), แนะนำบริษัท, หลักสูตรยอดฮิต (อัตโนมัติ), บริการ 3 ด้าน (infographic หลอดไฟ SVG + ปุ่ม CTA ไปหน้าบริการ), สถิติ, โลโก้ลูกค้า, สาส์นผู้บริหาร, Gallery slider, คลังความรู้ · header ใช้โลโก้ SVG · ตรวจ 1280/768/375 × 3 ภาษา (ไม่ล้นจอ, contrast AA ผ่านทุกข้อความ, ไม่มี PHP notice/JS error) · block valid ใน editor ทั้ง 3 ภาษา · ทดสอบวิดีโอ 15 วินาทีวนที่ 10 วินาที, ปุ่มหยุด, reduced motion
- [x] หน้าบริการ TH/EN/JA (19 ต.ค.): In-house + Public Training เพิ่ม Introduction/เนื้อหาขายจากเว็บเดิม (hero, จุดเด่น, ขั้นตอน, FAQ, CTA) · หน้า Transformation Solutions และ Japanese Concept ใหม่ (เนื้อหาจากเว็บเดิมรวมหน้าย่อย) · ตรวจ 1280/768/375 × 3 ภาษา × 4 หน้า: ไม่ล้นจอ, contrast AA, tap target ≥44px (ส่วนใหม่ทั้งหมด), ไม่มี PHP notice/JS error, block valid ใน editor ครบ 12 หน้า
- [x] Polylang TH/EN/JA: ปุ่มภาษาสลับได้จริง (dropdown desktop + list ในเมนู mobile ทดสอบใน Playground ด้วย Polylang จาก wordpress.org), คอร์ส/หน้าแปลได้, ชื่อ term EN/JA, ตัวกรองนับต่อภาษา, ข้อความ UI EN/JA, ตัวอย่างคำแปลครบทุกคอร์ส
- [x] รอบที่ 14c (27 ต.ค. 2569, branch `feature/public-faq` แตกจาก `main`, **ยังไม่ merge**): **FAQ หน้าคอร์ส Public ให้ครบเท่าเว็บเดิม** (หน้ารวม `/public-training/` เว็บเดิมไม่มี FAQ — FAQ ที่ขึ้นหน้านั้นเป็นของเราเอง)
  - `playground/seed-course-data.php` (TH/EN/JA): Negotiation Mastery 4→5 (+ช่องทางสมัคร) · The Next Director 5→6 (+Guest Speaker ต่างจากวิทยากรหลักอย่างไร) · The New Manager 3→5 (+"3 ชม./ครั้งพอไหม", +ใบประกาศ) · Oxygen Leadership ไม่มี FAQ (เว็บเดิมก็ไม่มี) · Lean ตรงแล้ว
  - The New Manager: ตั้ง `hri_certificate = 1` (เว็บเดิมบอกทุกคนได้ใบประกาศ) · **ข้อมูลไม่ตรงกันที่ต้องให้ Jimmi เช็ค:** เว็บเดิมบอก "7 ครั้ง ตลอด 8 สัปดาห์" แต่วันที่ในเว็บเรา (5 ก.พ.–5 มี.ค. 2570) ครอบ ~4 สัปดาห์ → คำตอบ FAQ จึงไม่ระบุจำนวนสัปดาห์
  - schema FAQPage เพิ่มตามจำนวนข้อ (ตรวจแล้ว 5/6/5) · ข้อมูลเก็บในเนื้อหาหน้า → เว็บที่ seed ไว้แล้วต้อง seed คอร์สใหม่
- [x] รอบที่ 12 (25 ต.ค. 2569, branch `feature/pagespeed` — **merge เข้า `main` แล้ว 25 ต.ค. 2569** (fast-forward) จุดย้อนกลับ `a22691f` = `main` ก่อน merge: `git push -f origin a22691f:main`): **PageSpeed (งาน J)** — รายละเอียดการทำงานอยู่ในข้อ 4 "ความเร็ว (รอบที่ 12)"
  - **ผล (Lighthouse mobile, ค่ากลาง 3 รอบ, ผ่านพร็อกซี Brotli — local ไม่ใช่ความเร็วโฮสต์ ใช้เทียบก่อน/หลังเท่านั้น):**

    | หน้า | คะแนน ก่อน → หลัง | LCP ก่อน → หลัง | น้ำหนัก (KB) ก่อน → หลัง | CLS ก่อน → หลัง |
    |---|---|---|---|---|
    | หน้าแรก | 92 → **94** | 3.0 → 2.7 s | 230 → 183 | 0.001 → 0.000 |
    | รวมคอร์ส | 97 → **100** | 2.4 → 1.7 s | 185 → 147 | 0.007 → 0.000 |
    | คอร์สเดี่ยว | 91 → **94** | 2.9 → 2.7 s | 228 → 182 | 0.074 → 0.061* |
    | บล็อก | 97 → **97** | 2.4 → 2.3 s | 184 → 145 | 0.000 → 0.000 |
    | บทความ | 96 → **98** | 2.6 → 2.3 s | 184 → 144 | 0.001 → 0.000 |
    | ติดต่อเรา | 97 → **98** | 2.4 → 2.2 s | 186 → 148 | 0.001 → 0.000 |

    desktop ทุกหน้า 100 · TBT 0–2 ms · \* CLS ที่เหลือเกิดจากรูปเสียใน sandbox (CDN ถูกบล็อก) — โหลดรูปจริงด้วย Playwright แล้ว CLS ≤0.002 ทุกหน้า · **เป้า 95 ยังไม่ถึง 2 หน้า (หน้าแรกและคอร์สเดี่ยว 94)**: เหลือ CSS บล็อก core ที่ไม่ตัดได้ (navigation 20 KB, cover 20 KB, gallery 16 KB) และ LCP เป็นหัวข้อ h1 ที่รอ CSS + ฟอนต์ · เกณฑ์อื่นผ่าน: ทุกหน้า ≥90, CLS <0.1, หน้าแรกไม่รวมวิดีโอ 183 KB (<500 KB)
  - ก่อนแก้ (baseline ครั้งแรก ไม่ผ่านพร็อกซี): หน้าแรก 77 / รวมคอร์ส 85 / คอร์สเดี่ยว 77 / บทความ 86 — ตัวเลขสูงเกินจริงเพราะ `php -S` ไม่บีบอัด จึงสร้างพร็อกซีแล้ววัดใหม่
  - ตรวจ: `measure.py` 81 รอบ 0 ปัญหา · รูปไม่ผิดสัดส่วนหลังเติม width/height (Playwright เทียบสัดส่วนที่แสดงกับ attribute ทุกรูปทุกหน้าที่ 375/1280) · ไม่มี `<img>` ไร้ขนาดในหน้าหลักทั้ง 12 หน้า (เดิม 41–51 รูปต่อหน้า) · วิดีโอ hero 3 กรณี · blueprint JSON ถูกต้อง แต่ **ยังไม่ได้เปิดบน Playground จริง** (ต้องรอ merge เข้า `main` แล้วเปิดลิงก์ demo — ขั้นใหม่คือเขียน `hri-image-dims.json` + เก็บ option)
  - สิ่งที่ Jimmi ต้องทำ/ตัดสินใจ: (1) ตั้งค่าฝั่งโฮสต์ตาม `HOSTING.md` ข้อ 5 (Brotli, cache header, HTTP/2+, Rcache ไม่แยกตาม cookie ภาษา) (2) prerender `moderate` เพิ่มงาน PHP ตอนผู้ใช้ hover — ถ้าโฮสต์หนักให้ปิดด้วย `hri_speculation` (3) ใส่ GA4/GTM ตอนไหน (ยังไม่ได้ทำ ถ้าใส่ต้องโหลดหลัง `load`)
- [x] รอบที่ 11 (25 ต.ค. 2569, branch `feature/image-optimize`): **รูปใน WordPress ให้เล็กสุด (งาน I)** — ดูสถาปัตยกรรมที่ข้อ 4 "รูปในคลังสื่อ"
  - ทำ: แปลง JPG/PNG → WebP ตอนรับไฟล์ + ลบต้นฉบับ · `HRI_IMAGE_MAX_EDGE` 1920 → 1600 · ขนาด `hri-card` 800×533 + `sizes` ตามกริด · `wp hri images optimize` + Tools → ปรับรูปให้เล็ก · เก็บกวาด `original_image` ที่ WordPress เก็บไว้
  - **ตัวเลข (local, GD + WebP, ไม่มี Imagick/AVIF)**: รูปจริงจาก repo 3 ใบ (ถอดรหัสเป็น PNG แล้วอัปโหลดผ่านระบบ): รูปถ่าย 1600×1067 → ไฟล์หลัก 60.1 KB / `hri-card` 23.2 KB / large 30.8 KB / medium 6.5 KB · อินโฟกราฟิกตัวหนังสือ 1024² → 51.8 KB / การ์ด 24.2 KB · รูปคำคม 870×485 → 26.8 KB / การ์ด 25.7 KB → **การ์ดละ ~23–26 KB (เกณฑ์ ≤ 80 KB ผ่าน)** · รูปสังเคราะห์ 3000×2000 JPG 748 KB → 18.9 KB · รูปเก่า 13 ไฟล์ (9 MB) → 239 KB ด้วยคำสั่งแปลงรูปเดิม
  - **quality 70 vs 78** (3 คู่ ซูม 2×: รูปถ่าย, อินโฟกราฟิกตัวหนังสือ, ภาพคน): 70 เล็กกว่า 9–14% (51.8 vs 60.1 KB, 47.3 vs 51.8 KB, 23.9 vs 26.8 KB) ตัวหนังสือคมเท่ากัน ต่างเล็กน้อยที่พื้นผิวรูปถ่าย · **ข้อจำกัดของการเทียบ**: ต้นฉบับ JPG ของเว็บเดิมไม่มีในเครื่อง ใช้รูปใน `playground/media` (WebP ที่บีบมารอบหนึ่งแล้ว) ผลจริงกับ JPG ต้นฉบับอาจต่างเล็กน้อย → **คง 78** (ตามที่ตกลงในรอบที่ 7) · ถ้าอยากเล็กลงอีก ~10% ตั้ง `HRI_IMAGE_QUALITY` 72
  - ตรวจ: อัปโหลด JPG/PNG/EXIF หมุน/PNG โปร่งใส/ไฟล์เล็กมาก ใน uploads ไม่เหลือ .jpg/.png · ไฟล์เสีย, GIF, ไม่มี image editor → ไม่ล้ม เก็บไฟล์เดิม · ตัวนำเข้าบทความโหมด download (รูปในเนื้อหา + ปก) ได้ WebP ทั้งหมด · คำสั่งแปลงรูปเดิม: JPG หลัก, `-scaled.webp` + `original_image`, ลิงก์ในเนื้อหาแบบปกติและ JSON-escaped, รันซ้ำ, หน้า Tools ใน wp-admin จริง (13 รูป หลายรอบ) · markup การ์ดคอร์ส/บล็อก: `hri-card` + srcset + sizes ถูกต้อง
  - `python3 -I scripts/measure.py` เต็มชุด (หลังเปลี่ยน, Polylang 3.9.1 + seed ใหม่) = **81 รอบ (3 ภาษา × 1280/768/375 × 9 หน้า รวมหน้าค้นหา) 0 ปัญหา** (overflow, tap target, contrast, PHP notice, JS error) · จุดย้อนกลับก่อน merge: `main` เดิม `14fe8de` (`git push -f origin 14fe8de:main`)
  - ยังไม่ได้ทดสอบ: Imagick/AVIF (workspace ไม่มี) · MySQL จริง · ขนาดเวลาแปลงรูปใหญ่บนโฮสต์ shared (ทีละ 10 รูป/คำขอ ถ้า timeout ให้ใช้ WP-CLI หรือลดเหลือ 5)
- [x] รอบที่ 10 (24 ต.ค. 2569, branch `feature/search` แตกจาก `feature/real-images` — **merge เข้า `main` แล้ว 25 ต.ค. 2569**): **ค้นหาทั้งเว็บ (งาน C)**
  - ทำ: ดูสถาปัตยกรรมละเอียดที่ข้อ 4.y · เลือกวิธีทำดัชนีเอง (Jimmi อนุญาตให้ตัดสินใจเอง ไม่ถามก่อน) จาก 3 ทาง:
    - **A. ตารางดัชนีคำของเราเอง (เลือก)** — ข้อดี: ใช้ได้ทั้ง MySQL/SQLite เหมือนกันทุกบรรทัด, ตัดคำไทยจริง (ICU หรือ n-gram), จัดอันดับ ชื่อ > excerpt > เนื้อหา, กรองภาษา/สถานะเปิดรับ, ค้น ~10 ms; ข้อเสีย: โค้ดเยอะ (~1,400 บรรทัด), ต้องดูแลดัชนี (ทำอัตโนมัติ + ปุ่มสร้างใหม่), ดัชนีใหญ่ ~270 คำ/โพสต์ (429 โพสต์ = 114k แถว)
    - B. เก็บข้อความตัดคำแล้วใน meta/คอลัมน์ + `LIKE` — ข้อดี: ง่ายสุด; ข้อเสีย: ไม่มีอันดับที่ดี, ช้าลงตามจำนวนโพสต์ (full scan), คำที่ตัดคำต่างกันพลาด
    - C. FULLTEXT ของ MySQL / FTS5 ของ SQLite — ข้อดี: เร็ว มีอันดับในตัว; ข้อเสีย: ต้องเขียนสองชุดตามฐานข้อมูล (Playground/SQLite กับ Ruk-com/MySQL คนละแบบ), parser ภาษาไทยของ MySQL ไม่มี (ต้องตัดคำก่อนอยู่ดี), InnoDB FULLTEXT มี stopword/ความยาวคำขั้นต่ำที่ทำให้คำไทยสั้นหาย
  - Synonym (Settings → การค้นหา), หน้าไม่เจอ, ฟอร์มใน header (desktop = ไอคอน, มือถือ/แท็บเล็ต = ในเมนู), ฟอร์มใน 404 · คำแปล EN/JA ทุกข้อความใหม่ (machine translation — JA ให้คนญี่ปุ่นตรวจ) · วัดด้วย `measure.py` เพิ่ม 2 หน้า (`/?s=…` ผลลัพธ์ + ไม่เจอ)
  - **ชุดทดสอบ + ผลที่ควรได้ (demo 21 คอร์ส + บทความ; ICU และ n-gram ได้เท่ากัน ยกเว้นที่ระบุ)** — รันด้วย `wp hri search query "<คำ>" --lang=<ภาษา>`:
    | คำค้น | ภาษา | คอร์ส | บทความ | หน้า | หมายเหตุ |
    |---|---|---|---|---|---|
    | เจรจา | th | 3 | 16 | 0 | Negotiation Mastery, Negotiation Masterclass มาก่อน (Complaint Handling Excellence ตรงในเนื้อหา) |
    | ผู้นำ | th | 14 | 67 | 5 | Proactive/High-Performance Leadership ฯลฯ |
    | negotiation | en | 2 | 11 | 0 | Negotiation Mastery, Negotiation Masterclass |
    | leadership | en | 14 | 66 | 5 | |
    | コーチング | ja | 2 | 52 | 2 | Coaching Mastery มาก่อน (n-gram ได้คอร์ส 3) |
    | negotiation | th | 3 | 16 | 0 | synonym ข้ามภาษา → เจอเนื้อหาไทย |
    | เจรจา | en | 2 | 11 | 0 | EN ไม่เจอผลภาษาไทย (กรองภาษา) |
    | หลักสูตรผู้นำ (พิมพ์ติดกัน) | th | 10 | 54 | 5 | ตัดเป็น หลักสูตร + ผู้นำ (ก่อนแก้ ได้แค่ 2 รายการ) |
    | zzxqvw, ซซซซซซ | th | 0 | 0 | 0 | หน้า 200 + หลักสูตรแนะนำ |
    ผ่านทั้งหมด · คำที่ ICU ตัดต่างกันในประโยค (เช่น ชื่อทับศัพท์ "โนวา") เจอด้วยตัวสำรองค้นข้อความต่อเนื่อง
  - **คำค้นแปลก**: `<script>`, `%`, 500 ตัวอักษร, `?s[]=a`, `?s=`, `' " \ OR 1=1`, `&lt;b&gt;`, `?type=zzz&pg=-5`, `?pg` เกินจำนวนหน้า → HTTP 200 ทุกกรณี ไม่มี PHP notice ไม่สะท้อน HTML (แสดงเป็นข้อความ escape) · แก้/ลบ/draft โพสต์แล้วดัชนีตามภายในคำขอเดียวกัน (ทดสอบข้ามโปรเซส)
  - **เวลา (local, SQLite, 429 โพสต์ ดัชนี 114k แถว, `php -S` ตัวเดียว)**: query ในเครื่องยนต์ 8–15 ms (เกณฑ์ ≤100 ms ผ่าน) · ทั้งหน้าผลลัพธ์ (รวม WordPress + render การ์ด) 110–220 ms · สร้างดัชนีทั้งหมด 16–17 วินาที (ICU) / 37 วินาที (n-gram) บน SQLite (ช้าเพราะ SQLite integration เขียนทีละโพสต์ ~35–40 ms) — MySQL น่าจะเร็วกว่ามากแต่ยังไม่ได้วัด
  - **ผลวัดรอบที่ 10 (ก่อนแก้ตามการตรวจซ้ำ ผ่าน; ผลหลังแก้ตามการตรวจซ้ำ: วัดซ้ำเต็มชุดอีกครั้ง 81 รอบ 0 ปัญหาเช่นกัน; จุดสลับ header 1220px ตรวจแยกด้วย Playwright ที่ 1190–1280 × TH/EN/JA: เมนูอยู่บรรทัดเดียวทุกภาษาตั้งแต่ 1220px)**: `python3 -I scripts/measure.py` เต็มชุด = 81 รอบ (1280/768/375 × TH/EN/JA × 9 หน้า รวมหน้าค้นหาที่มีผลและไม่มีผล) → **0 ปัญหา** (overflow, tap target ≥44px, contrast AA, PHP notice, JS error) · ช่องค้นหาใน header/เมนูมือถือรวมอยู่ในทุกหน้าที่วัด
- [x] รอบที่ 9 (24 ต.ค. 2569, branch `feature/real-images` — **merge เข้า `main` แล้ว 25 ต.ค. 2569**): **รูปจริงจากเว็บเดิมแทน placeholder**
  - flow: crawl เว็บเดิมด้วย Playwright บน GitHub (35 หน้า, screenshot ติดเลขกรอบรูป) → จับคู่ช่องรูปด้วยตา → `site-media.json` (97 รูป) → `scripts/site-media.py` แปลงตามชนิด: logo ≤240px q85 · cover 3:2 960×640 q76 · square (ครอปรอบหน้า) 640 q80 · photo ≤1000 q76 · hero ≤1600 q70 → **รวม 1.6 MB, ไฟล์ใหญ่สุด 115 KB** · ดูผลจับคู่: `playground/data/site-media-sheet-1…4.jpg`
  - ใส่แล้ว: hero หน้าแรก (1), โลโก้ลูกค้า 40 (หน้าแรก/About/In-house) + พาร์ทเนอร์ 8 (Transformation) พร้อม alt ชื่อบริษัท, ผู้บริหาร 2 (หน้าแรก+About), Gallery หน้าแรก 4, รูป hero ของ About/In-house/Public/Transformation/Japanese + "ทำไมต้อง Public" (6), ปกคอร์ส 20 จาก 21 (ภาพ hero ของหน้าคอร์สเดิม ครอป 3:2), วิทยากร 4, Gallery ไฮไลต์ของคอร์ส (Oxygen ใช้ของจริง 6 รูป คอร์สอื่นหมุนจากสระรูปอบรมจริงของ HRI 10 รูป)
  - **วิธีใส่รูป (เพื่อให้เปิดเร็ว)**: ปกคอร์ส/วิทยากร = URL ใน meta `_hri_thumbnail_url` (+`_alt`, `_w`, `_h`) ไม่อัปโหลดเข้าคลังสื่อ → Playground บูตเร็ว, `<img>` มี width/height กัน layout กระโดด (ใช้ filter เดิมใน `blog.php` ขยายให้ทุก post type) · รูปในเนื้อหา = core/image ชี้ URL jsDelivr ตรง (ไม่มี width/height ใน markup เพราะ core เก็บเป็น style แล้ว editor จะ validate ไม่ผ่าน) · **ตอนขึ้นโฮสต์จริง** ให้ดึงรูปเข้าคลังสื่อ (WordPress ทำหลายขนาด/srcset ให้) ด้วยโหมด download ของ importer หรือ sideload ใน seed
  - **ไม่ได้ใส่ / ยังเป็น placeholder**: About "Social Impact" gallery 4 รูป (เว็บเดิมไม่มีรูปกิจกรรมเพื่อสังคม ต้องขอจาก Jimmi) · ปก TEN X (เว็บแยก ใช้รูปตัวอย่างเดิม) · วิดีโอ (hero, DO·WA·KEI, แนวตั้งใน Outcomes) · รูปประกอบ Pain point/Outcomes ของคอร์ส (เว็บเดิมมี 4 รูปต่อคอร์ส แต่หน้าใหม่ไม่มีช่องรูป) · รูปรีวิว/อวตาร (เว็บเดิมเป็นรูปกลาง)
  - **ควรให้ Jimmi ตรวจ**: ปกคอร์สบางใบมืด/ซ้ำกัน (เว็บเดิมใช้รูปเดียวกัน: B2B Selling = Complaint Handling, Strategic = Consultative Selling) · Gallery ของคอร์สที่ไม่ใช่ Oxygen ใช้รูปอบรม HRI ทั่วไปไม่ใช่รูปของรุ่นนั้น · hero ของ Public/In-house เป็นภาพ gradient นามธรรมจากเว็บเดิม · alt ของโลโก้ ผมอ่านจากตัวโลโก้เอง (ช่อง 10 = การไฟฟ้าส่วนภูมิภาค ไม่แน่ใจ 100%) · สิทธิ์ใช้รูป: ผู้บริหาร/ผู้เข้าอบรม/โลโก้ลูกค้าเป็นรูปที่เปิดเผยบนเว็บเดิมอยู่แล้ว (ใช้ใน demo ได้) ก่อนขึ้นเว็บจริงควรให้ลูกค้ายืนยัน · รูป stock ต้องเป็น license ของลูกค้า
  - แก้เพิ่ม: ปุ่มก่อน/ถัดไปของ slider หดเหลือ 28px บนมือถือเมื่อสไลด์ >5 → จุดขึ้นแถวใหม่ที่ ≤520px (`base.css`)
  - ตรวจ: `measure.py` 63 รอบ (TH/EN/JA) + about/transformation/japanese/oxygen = 0 ปัญหา · ไม่มี placeholder เหลือ (ยกเว้น Social Impact 4) · รูปทุกใบโหลดได้ (naturalWidth>0) บน local WP + Polylang · ทดสอบ local ใช้ `HRI_SITE_MEDIA_BASE` ชี้ไปโฟลเดอร์ `playground/site-media` (ไม่ผ่าน jsDelivr)
  - **ดู branch นี้ใน Playground**: เปิดลิงก์ในข้อ 3 แต่แทน `main` ด้วย `feature/real-images` ใน blueprint (ทุก URL ใน `playground/blueprint.json` ชี้ `main` — เปลี่ยนก่อน merge ไม่ได้ผ่านลิงก์สั้น) หรือ merge แล้วเปิดลิงก์เดิม
- [x] รอบที่ 8 (24 ต.ค. 2569, ตรง `main`): Jimmi ส่ง screenshot 3 รูป (hero คอร์ส Lean, FAQ, ที่อยู่ footer) ให้หาจุดแปลก แล้วสั่งแก้ 4 ข้อ + เปลี่ยนไอคอนทั้งเว็บ
  - ปุ่ม "สอบถามหลักสูตรนี้" ใน hero คอร์สเป็นปุ่มทึบเหมือนปุ่มหลัก → ปุ่มขอบ: ปุ่มที่ `hri_course_buttons()` พิมพ์เองไม่ผ่าน parser → core ไม่โหลด CSS ของ Button และ variation outline ของ theme.json · แก้: enqueue `wp-block-button(s)` ในฟังก์ชัน + กฎ `.is-style-outline` ใน `base.css` (ค่าเดียวกับ theme.json, hover เหมือนหน้าแรก)
  - ป้าย (รุ่น/วันที่/ราคา) ชิดปุ่มแค่ ~2px: สาเหตุเดียวกัน (ลิงก์ปุ่มเป็น inline → padding ล้นกล่อง) แก้แล้วห่าง 16px
  - FAQ: คำตอบติดเส้นคั่นล่าง (core ตั้ง margin ของลูกตัวสุดท้ายเป็น 0) → `padding-block-end` ที่ `.hri-faq__item[open]` (ทุกหน้าที่มี FAQ)
  - ที่อยู่: คำสั้นท้ายบรรทัด (≤5 ตัว เช่น "5", "10330") ไม่ตกบรรทัดเดี่ยว → `hri_company_lines_html()` ใช้ใน `hri/company-text` และ `hri/contact-info` (ทีมการตลาดแก้ที่อยู่ใน Settings ได้ตามเดิม)
  - ไอคอน Phosphor Light ทั้งเว็บ (ข้อ 4 "ไอคอน") · ป้ายในหน้าคอร์สลด padding ซ้ายให้ 3 ป้ายยังอยู่แถวเดียวที่ 1280 เหมือนเดิม
  - Jimmi ยืนยันแล้ว: วันที่ Lean "24 ก.พ. – 8 เม.ย. 2570 (7 สัปดาห์)" ใช้ตามเดิม · ที่อยู่ "118 บล็อก 28X ชั้น 4 ยูนิต X406" ถูกต้อง · ภาพ 2 แก้เฉพาะระยะ FAQ (ไม่แก้ TOYOTA/Toyota และคำว่า "บันทึกความรู้") · ✓ ในป้ายวันที่/ราคาคงไว้ (เปลี่ยนแค่หน้าตาไอคอน)
  - ตรวจ: `measure.py` 63 รอบ + about/blog/transformation/japanese/คอร์ส Lean 45 รอบ = 0 ปัญหา · screenshot ทุกจุดที่เปลี่ยนไอคอน (desktop/มือถือ, เมนูเปิด, dropdown ภาษา)
- [x] รอบที่ 7 (23 ต.ค. 2569, ตรง `main`): **demo ใช้รูปบทความจาก repo ของเราเอง**
  - `scripts/demo-media.py` + workflow **Demo media** (`.github/workflows/demo-media.yml`, รันบนเครื่อง GitHub เพราะ workspace ของ Claude เข้า hrideone.com / jsDelivr ไม่ได้; รันเองเมื่อ `blog-posts.json` เปลี่ยน หรือกด Run workflow) ดาวน์โหลด **เฉพาะรูปที่บทความใช้** (ปก + รูปในเนื้อหา ไม่ใช่คลังสื่อ 1,578 ไฟล์) ย่อขอบยาวสุด 1600px แปลง WebP q78 → `playground/media/YYYY/MM/*.webp` (ชื่อไฟล์ ASCII) + `manifest.json` (URL เดิม → ไฟล์, ขนาด) แล้วแก้ `blog-posts.json(.gz)` ให้ชี้ `https://cdn.jsdelivr.net/gh/imeeba/hrideone-web@main/playground/media/…`
  - ผล: **527 รูป 185.8 MB → 25.4 MB** (ค่ากลาง 35 KB/รูป, ใหญ่สุด 920 KB) · 11 รูปเป็น **404 บนเว็บเดิมอยู่แล้ว** (ตรวจด้วยเบราว์เซอร์) → `missing` ใน manifest และ image block ถูกตัดออกจากบทความ (เรื่อง `why-you-must-training-with-deone-academy`, `cross-functional-management-cfm`, `people-transformation-with-green-yellow-red-idea` (TenX 4 รูป), `why-do-managers-need-to-develop-leadership-skills` 3 รูป รวม EN/JA)
  - `[video]` shortcode (เรื่องงานเปิดตัว 3 ภาษา) → **Video block** · วิดีโอ MP4 ยังเล่นจาก hrideone.com ใน demo (ไม่ได้ย้ายเข้า repo: ไฟล์ใหญ่) poster มาจาก repo แล้ว · importer โหมด download คัดลอกทั้งวิดีโอและ poster เข้าคลังสื่อ (ทดสอบแล้ว)
  - importer โหมด download รับรูป/วิดีโอจากโดเมนใดก็ได้ที่ไม่ใช่เว็บตัวเอง → ตอนย้ายจริงดึงรูปจาก repo ได้แม้เว็บเดิมปิดแล้ว
  - ตรวจใน **Playground จริง** (built-in browser): บูตจากศูนย์ถึงหน้า `/blog/` ~80–90 วินาที · การ์ด 12 ใบรูปโหลดจาก jsDelivr ครบ · หน้าเรื่องงานเปิดตัว 12/12 รูป + วิดีโอเล่นได้ · block ทั้ง 345 เรื่อง valid ใน editor (9,959 block)
  - ลิงก์ demo สั้น https://imeeba.github.io/hrideone-web/ (GitHub Pages, ใช้งานได้แล้ว) · `docs/HOSTING.md` (ข้อมูล Ruk-com + คำถาม support 19 ข้อ + วิธีขึ้นเว็บ)
- [x] รอบที่ 6 (23 ต.ค. 2569, branch `feature/blog-import`): บทความจริงจากเว็บเดิม 115 เรื่อง × TH/EN/JA (ตรงกับ https://hrideone.com/blog/ ทุก slug ทุกลำดับ 8 หน้า × 15 และตรงทุกหมวด) + เครื่องมือนำเข้า + 301 URL เดิมของบล็อก + Blog UI (contrast, tap target, การ์ดกดทั้งใบ, excerpt 3 บรรทัด) · ตรวจ: 345 โพสต์ ผูกคำแปลครบ 115 ชุด ภาษา/หมวด/วันที่ถูกทุกโพสต์, นำเข้าซ้ำไม่สร้างซ้ำ, โหมด download (ทดสอบด้วยรูป local), หน้า Tools ครบทั้งชุด, redirect ทุกแบบ, `measure.py` blog/หมวด/บทความ × 3 ภาษา × 3 จอ + หน้าเดิมทั้งหมด
- [x] รอบที่ 5 (23 ต.ค. 2569, branch `fix/tap-targets`): tap target ≥44px ทุกจุดที่ค้างไว้ + ชื่อคอร์สในการ์ดไม่ตัดกลางคำ + `scripts/measure.py`
  - token ใหม่ `custom.tap` = 44px (`tokens.json` → `--wp--custom--tap`)
  - Header: ลิงก์เมนู (25px→44), ปุ่มภาษา (34→44), รายการ dropdown/list ภาษา (43/35→44), ปุ่มเปิด/ปิดเมนู (24×24→44×44 ไอคอนคง 24), ลิงก์ในเมนูเต็มจอ · Footer: ลิงก์เมนู (กว้างขั้นต่ำ 44 ด้วย) · ตัวกรอง: label checkbox (35→44), ปุ่ม "ตัวกรอง" มือถือ (43→44), pagination (39→44) · หน้าคอร์ส: "← หลักสูตรทั้งหมด" (25→44, class `hri-backlink`), PDPA checkbox 20→24px และ label สูง 44
  - ชื่อคอร์สในการ์ด: เดิมชื่ออังกฤษยาว ("Transformation", "Communication") ถูกตัดกลางคำที่ ≥1280px (การ์ดแคบ 3 คอลัมน์ข้างตัวกรอง) ทั้งหน้า TH/JA (`overflow-wrap`) และล้นกล่องในหน้า EN · แก้: ขนาดหัวข้อตามความกว้างการ์ด (`container-type` + `cqi`, อยู่ระหว่าง medium–x-large) การ์ดกว้างยังเป็นขนาดเดิม · เพิ่ม `hyphens:auto` เฉพาะ `:lang(en)` · ตรวจ 320–1440px ไม่มีคำตัดกลางคำแล้ว
  - ผลวัด `measure.py`: ก่อนแก้ tap target ต่ำกว่า 44px ใน 63/63 รอบ (ลิงก์เมนู 25px, label ตัวกรอง 35px, ปุ่มแฮมเบอร์เกอร์ 24px ฯลฯ) · หลังแก้ 0/63 รอบ ไม่มี overflow, contrast, PHP notice, JS error
  - ไม่ได้แก้ markup นอกจากเพิ่ม class 2 ตัว (`hri-backlink`, `hri-course-card`) · หน้าตา desktop เปลี่ยนเล็กน้อย: เมนู header ห่างขึ้นเล็กน้อย (Blog/About กว้างขั้นต่ำ 44px), ตัวกรองสูงขึ้น ~240px (ดูข้อ "ปัญหานอกขอบเขต")
- [x] รอบที่ 4 (19 ต.ค. 2569): เนื้อหาจริงคอร์ส In-house อีก 14 คอร์ส (รวม Coaching Mastery = ครบ 15) ดึงจาก `hrideone.com/courses/<slug>/` ได้ครบทุกหน้า เรียบเรียงใหม่ ใส่ใน `hri_seed_inhouse_courses()` TH/EN/JA ตามโครง Coaching Mastery · seed (local + Polylang 3.9 จาก GitHub): 21 คอร์ส + 42 คำแปล · ตรวจ 15 คอร์ส × TH/EN/JA × 1280/768/375 (135 โหลด): ไม่ล้นจอ, contrast AA ผ่านในเนื้อหาใหม่, ไม่มี tap target <44px ในเนื้อหาใหม่, ไม่มี JS error, ไม่มี PHP notice, ไม่มีข้อความ placeholder เหลือ, ไม่มีตัวอักษรไทยหลุดในหน้า EN/JA · ไม่แตะ theme/plugin code (bump Version เป็น 0.8.1 / 0.7.1 เท่านั้น)
- [x] รอบที่ 3 (19 ต.ค. 2569, branch `feature/content-pages`): Lead form + Contact · โมเดลคอร์ส + หน้าคอร์ส Public 5 คอร์ส + TEN X + วิทยากร · หน้าคอร์ส In-house (Coaching Mastery จริง + โครงตัวอย่าง 14 คอร์ส) · About · Blog (listing/หมวด/บทความ + ตัวอย่าง 15 เรื่อง) · ตรวจ 18 หน้า × TH/EN/JA × 1280/768/375: ไม่ล้นจอ, ไม่มี PHP notice, ไม่มี freeform block · ฟอร์ม: prefill/ส่งสำเร็จ(JS และไม่มี JS)/ข้อมูลผิด/honeypot ผ่าน

## 6. ข้อมูลที่เป็นของสมมติ (ต้องแทนด้วยของจริง)

- `playground/seed.php`: ชื่อ/slug คอร์สมาจากเว็บจริง แต่ **ระดับผู้เรียน ระยะเวลา รูปแบบ หัวข้อ** และสถานะ "เปิดรับสมัคร" ของ Public เป็นค่าสมมติ เนื้อหาคอร์สเป็นข้อความตัวอย่าง
- หน้าบริการ: เนื้อหาเรียบเรียง/ย่อจากเว็บเดิม (ดึงข้อความเต็มไม่ได้ ใช้สรุปจากหน้าเว็บ) ต้องให้ Jimmi/การตลาดตรวจ · ส่วนที่ผมเขียนเพิ่มเอง (ไม่มีในเว็บเดิม): หัวข้อ/lead ของ hero บางหน้า, chips จุดเด่น, ขั้นตอน In-house 4 ข้อ (สรุปจาก FAQ เดิม), ขั้นตอนสมัคร Public 3 ข้อ, FAQ ของ Public ทั้งหมด (ส่งหลายคน/รุ่นเต็ม), ข้อ "เหมาะกับการส่งพนักงานบางส่วน…", แถบชวนจัด In-house, คำอธิบาย Japanese Working Philosophy และ Cross-cultural Communication (เว็บเดิมไม่มีหน้า), "3 วัน · ประเทศญี่ปุ่น" ของ Part 3 (เว็บเดิมแบ่งเป็น Day 1–3), กล่อง "สัญญาณที่องค์กรควรทบทวนกลยุทธ์"
- หน้าบริการ: รูป hero/รูปประกอบเป็นรูปตัวอย่าง · รูปจริงจากเว็บเดิม: In-house `/wp-content/uploads/2026/09/782.webp`, Public `/wp-content/uploads/2026/09/1920x1080-01-1024x576.webp`, โลโก้พาร์ทเนอร์ `/wp-content/uploads/2026/09/logo-partner-01…08.png` (Transformation) · DO·WA·KEI เว็บเดิมมีวิดีโอใน section Business Outcomes (ยังไม่ได้ใส่)
- ปุ่มสมัคร Public ไปลิงก์ใน `hri_register_url` (Google Form เดิมของแต่ละรุ่น) ส่วนปุ่มขอใบเสนอราคา/สอบถามไป `/contact-us/` พร้อมเติมคอร์สให้
- คำแปล EN/JA ของหน้าบริการเป็น machine translation (ใน `hri-theme/languages/*.po`) — JA ต้องให้คนตรวจ
- รูปคอร์สเป็นภาพนามธรรมที่สร้างเอง (`playground/sample/`)
- ที่อยู่ภาษาไทยใน `hri_company_defaults()` เรียบเรียงเองจากที่อยู่ภาษาอังกฤษ (ชื่อบริษัทคงเป็นอังกฤษ) ต้องให้ Jimmi ตรวจ · ข้อความแถบ CTA มี "(ข้อความตัวอย่าง)" · LinkedIn ยังไม่มี URL (ช่องว่าง = ไม่แสดง)
- คำแปล EN/JA ทั้งหมด (ชื่อ term, excerpt, ข้อความ UI ใน .po, `seed-languages.php`) เป็น machine translation — JA ต้องให้คนญี่ปุ่นตรวจ
- เมนูชี้ `/blog/` `/about/` `/contact-us/` ที่มีแล้ว (ตัวอย่างใน seed)
- หน้าแรก: หัวข้อ/ข้อความ Hero ("พัฒนาผู้นำ ขับเคลื่อนองค์กรสู่อนาคต") เขียนใหม่ เว็บเดิมไม่มี · ข้อความแนะนำบริษัทและสาส์นผู้บริหารเรียบเรียงจากเว็บเดิม (สาส์นยังไม่ครบทั้งย่อหน้า ดึงต้นฉบับเต็มไม่ได้) ต้องให้ Jimmi/การตลาดตรวจ
- หน้าแรก: บรรทัดเล็กเหนือ "Our Services" ใช้ "What We Do" (Jimmi สั่งให้หัวข้อใน section นี้เป็นอังกฤษล้วน ไม่แปล; คำนี้ผมตั้งเอง รอยืนยัน) · ข้อความ 01–03 ในหลอดไฟมาจากรูป reference ของ Jimmi
- ~~หน้าแรก: โลโก้ลูกค้า 40 ช่อง, รูปผู้บริหาร 2 รูป, รูป Gallery, รูปพื้น Hero เป็น placeholder~~ → ใช้รูปจริงแล้วในรอบที่ 9 (ดูรายการที่ยังขาดในรอบที่ 9)

### ข้อมูลที่ต้องตัดสินใจ/ยืนยัน (จากรอบที่ 13: SEO)
- **บอตเทรนโมเดล AI (GPTBot, ClaudeBot, Google-Extended …)**: ตั้งค่าเริ่มต้นเป็น "อนุญาต" ตามที่ Jimmi สั่ง ลูกค้า (HRI DeOne) ต้องตัดสินใจ — เปลี่ยนได้ที่ Settings → SEO และ AI ไม่กระทบการติดอันดับหรือการถูกอ้างในคำตอบ AI (บอตค้นหาอนุญาตเสมอ)
- **IndexNow**: ปิดไว้ เปิดเมื่อขึ้น production
- **excerpt ของคอร์ส** ใช้เป็น meta description และ `Course.description` · มีครบทุกภาษาแล้ว (ปรับจากคำโปรยเว็บเดิม) ไม่ได้เขียนใหม่ทั้งหมดเพราะข้อมูล "เหมาะกับใคร" ของหลายคอร์สยังว่าง เขียนแล้วจะเป็นการเดา · ทีมการตลาดแก้เฉพาะคอร์สที่อยากให้เจาะกลุ่มชัดขึ้นได้ในช่อง excerpt หรือกล่อง SEO ของ TSF (ไม่กระทบข้อความใน hero ถ้าแก้ที่กล่อง SEO)
- **รูปแชร์ (og:image)**: demo ใช้รูปปกที่เป็น URL ภายนอก (`_hri_thumbnail_url`) ของบทความ/คอร์ส · หน้าอื่นที่ไม่มีรูปปก TSF ใช้รูปแรกในเนื้อหา · ของจริงควรตั้ง Site Icon/รูปแชร์เริ่มต้นใน TSF (SEO → Settings → Social)
- **ข้อความ title หน้าแรก 3 ภาษา** ("Executive training and organizational development", "エグゼクティブ研修・組織開発") ผมตั้งเอง machine translation; ผมทบทวนคำญี่ปุ่นแล้วหนึ่งรอบ (เปลี่ยน 経営者 = เจ้าของกิจการ เป็น エグゼクティブ) แต่ยังไม่ผ่านคนญี่ปุ่น — JA ต้องให้คนตรวจ
- **ที่อยู่/โทรศัพท์ใน JSON-LD** มาจาก Settings → ข้อมูลบริษัท (ที่อยู่ไทยเรียบเรียงเอง, ดูข้อ "ข้อมูลที่ต้องตรวจ") · LinkedIn ว่าง = ไม่อยู่ใน `sameAs`

### ข้อมูลที่ต้องตัดสินใจ/ยืนยัน (จากรอบที่ 3) — ค่าชั่วคราวอยู่ใน seed ย้อนกลับได้
- **ข้อมูลรุ่น/วันที่/ราคา/ลิงก์ Google Form ของ Public 5 คอร์ส** มาจากเว็บเดิม ณ 19 ต.ค. 2569 ต้องให้ทีมงานยืนยัน: New Manager "รุ่น 21 หรือ 22" (ใช้ 22 ตามเมนูเว็บเดิม) และ "7 สัปดาห์ หรือ 7 ครั้งใน 8 สัปดาห์" (ใช้วันที่ 5 ครั้ง/ตัวอย่าง ต้องตรวจ); Lean "รุ่น 2 หรือ 3" (ใช้ 3) และวันที่เก่าในเนื้อหาเว็บเดิม; Early Bird ของ Negotiation / Next Director / New Manager เลยกำหนดแล้ว (ซ่อนอัตโนมัติ), Oxygen ยังใช้ได้
- **รีวิว**: ใส่เฉพาะ Negotiation (3 รายการ เรียบเรียงใหม่ ต้องยืนยันกับชื่อจริง) · Oxygen เว็บเดิมคัดลอกรีวิวของ Negotiation มา → **ไม่ใส่**
- Next Director: Pain point ข้อ 3 เนื้อหาเว็บเดิมไม่ตรงหัวข้อ → เขียนใหม่ ต้องให้การตลาดตรวจ
- LINE OA: `@hrideone` vs `@deoneacademy` (เว็บเดิมมีทั้งสอง) · เบอร์โทรหลายรูปแบบ · สะกดชื่อ ดร.ชัยพัชร์ (Chaipat Lertlucktaweekul)
- หน้าย่อยเว็บเดิมรวมเป็นหน้าเดียวแล้ว → ต้อง 301 ตอนย้ายเว็บ · DO·WA·KEI ยังเป็น section (ถ้าจะเป็นคอร์สแยกต้องถาม)
- Team Simulation / Japanese Working Philosophy / Cross-cultural Communication: ข้อความสั้นที่ผมเขียนเอง (มีป้ายตัวอย่าง)
- About: พันธกิจ/วิสัยทัศน์/สาส์น/ไทม์ไลน์ เรียบเรียงจาก CONTENT-GAP ไม่ใช่ข้อความเต็มต้นฉบับ · ปีในไทม์ไลน์ยกเว้น 1993 ยังไม่ระบุ · Social Impact เป็น placeholder
- คำแปล EN/JA ทั้งหมดเป็น machine translation — JA ต้องให้คนญี่ปุ่นตรวจ

### เรื่องที่ยังไม่ได้ตัดสินใจ (จากรอบที่ 8: ตรวจ screenshot คอร์ส Lean / FAQ / footer)
> Jimmi สั่งแก้เฉพาะข้อที่เป็นบั๊ก ข้อด้านล่างผมเสนอไว้แต่ยังไม่ได้คำตอบ → ยังไม่แก้ อย่าแก้เองโดยไม่ถาม
- ✓ นำหน้าป้ายวันที่และราคาในหน้าคอร์ส (`.hri-chips`) สื่อว่า "จุดเด่น" ไม่เข้ากับวันที่/ราคา → ทางเลือก: ไอคอนปฏิทิน/ราคา หรือไม่มีไอคอน (ตอนนี้คงเป็น ✓ ชุดใหม่)
- ชื่อนิติบุคคลในที่อยู่ไทย "เอช อาร์ ไอ ดีวัน จำกัด" ไม่มีคำว่า "บริษัท" นำหน้า (`hri_company_defaults()` / Settings → ข้อมูลบริษัท)
- หน้าคอร์ส Lean: "TOYOTA" (hero) กับ "Toyota" (FAQ) สะกดต่างกัน · FAQ "ไม่ใช่แค่บันทึกความรู้" อ่านแปลก (เสนอ "ไม่ใช่แค่ความรู้บนกระดาษ") — Jimmi สั่งไม่แก้ในรอบนี้
- ชื่อคอร์ส "Lean Transformation in 7 weeks" ตัว w เล็ก (ตามเว็บเดิม)

### ข้อมูลที่ต้องตรวจ (จากรอบที่ 6: บทความ)
- **หมวดของ 63 เรื่องที่เว็บเดิมเป็น "ไม่มีหมวดหมู่"** ผมจัดเองจากชื่อเรื่อง (`CATEGORY_MAP` ใน `scripts/wxr-to-blog.py`) — Business = องค์กร/วัฒนธรรม/กลยุทธ์/HR/ข่าวบริษัท, Sales = ขาย/เจรจา, Coaching = ภาวะผู้นำ/ผู้จัดการ/พัฒนาตนเอง · ให้การตลาดตรวจ แก้ได้ 2 ทาง: แก้ใน wp-admin ทีละเรื่อง หรือแก้ map แล้วรันสคริปต์ + นำเข้าซ้ำ (การนำเข้าซ้ำจะทับที่แก้ใน wp-admin)
- เรื่องที่ไม่มีรูปปกในเว็บเดิม: `why-do-managers-need-to-develop-leadership-skills` (และ EN/JA ของเรื่องนี้) → การ์ดไม่มีรูป
- excerpt: ใช้ excerpt เดิม หรือ meta description ของ Rank Math ถ้ามี (ส่วนใหญ่) ที่เหลือ WordPress ตัดจากเนื้อหาเอง
- ลิงก์ในเนื้อหาที่ชี้ไปหน้าเก่า (เช่น `/courses/negotiation-skills/`, `/coaching/executive-coaching`) แปลงเป็น path ภายในแล้วแต่ **ยังไม่ได้ redirect** → รวมในงาน 301 (ข้อ 7 รายการ 6)
- รูปใน Playground/local ยังโหลดจาก hrideone.com (ถ้าเว็บเดิมปิด/ย้ายโดเมนรูปจะหาย) → ตอนย้ายจริงใช้โหมด download

### ข้อมูลที่ต้องตรวจ (จากรอบที่ 4: เนื้อหา In-house 15 คอร์ส) — ค่าชั่วคราวอยู่ใน `hri_seed_inhouse_courses()` แก้/ย้อนกลับได้
- **ที่มาของข้อความ**: ดึงจากหน้าเว็บเดิมทุกคอร์ส (14 หน้าดึงได้ครบ ไม่มีหน้าไหนดึงไม่ได้) แต่ผ่านเครื่องมืออ่านหน้าเว็บที่สรุปข้อความให้ ไม่ใช่ HTML ต้นฉบับ → ถ้อยคำอาจคลาดเคลื่อนจากต้นฉบับ และผมเรียบเรียงใหม่อีกชั้น (ไม่คัดลอกยาว) ต้องให้การตลาดเทียบกับหน้าเว็บเดิมก่อนใช้จริง
- **ข้อความ "คอร์สนี้ช่วยอย่างไร"** (บรรทัดใต้ปัญหาแต่ละข้อ, 4 ข้อ × 15 คอร์ส) **ผมเขียนเองทั้งหมด** เว็บเดิมไม่มี (เหมือนที่ทำไว้ใน Coaching Mastery) · **หัวข้อนำของ intro** (ประโยคตัวหนาเหนือย่อหน้าแรก) ผมตั้งเองเช่นกัน ไม่ใช่ H1 เดิม
- **ชื่อภาษาไทยของคอร์ส (H1 เดิม) ยังไม่ได้ใช้** ชื่อคอร์สในระบบยังเป็นอังกฤษตาม seed.php ถ้าจะใช้ชื่อไทยเต็มต้องตัดสินใจเรื่องแสดงผล (การ์ด/หน้าเดี่ยว) ก่อน — H1 เดิมของแต่ละคอร์ส: high-performance-leadership: ยกระดับภาวะผู้นำเพื่อสร้างผลลัพธ์ที่เหนือกว่า · proactive-leadership: ยกระดับ DNA ผู้นำเชิงรุก · time-and-priority: บริหารเวลาและจัดลำดับความสำคัญ · growth-mindset: ปรับวิธีคิด พลิกวิกฤติเป็นโอกาส · communication: ทักษะการสื่อสารเพื่อกลยุทธ์และผลลัพธ์ · conflict-management: จัดการความขัดแย้ง เปลี่ยนความเห็นต่างให้เป็นพลัง · change-transformation: ผู้นำการเปลี่ยนแปลงและทรานฟอร์มอย่างมืออาชีพ · b2b-selling: พลิกเกมขายองค์กร ด้วยกลยุทธ์ปิดดีลระดับสูง · negotiation-masterclass: เจรจาอย่างเหนือชั้น ปิดดีลแบบ Win-Win · strategic-selling: ยกระดับงานขายสู่ระดับกลยุทธ์ · consultative-selling: อัปเกรดทักษะการขายเชิงที่ปรึกษา · essential-sales: ติดอาวุธขั้นตอนการขาย วางแผนกลยุทธ์อย่างมือโปร · service-excellence: ยกระดับงานบริการ สู่กลยุทธ์มัดใจลูกค้า · complaint-handling: รับมือข้อติชม กู้คืนความเชื่อมั่นอย่างมืออาชีพ (ทุกชื่อขึ้นต้น "หลักสูตร…") · ในข้อความผมสะกด "ทรานส์ฟอร์ม" แต่เว็บเดิมสะกด "ทรานฟอร์ม"
- **High-Performance Leadership**: เว็บเดิมอ้างตัวเลขผลลัพธ์ (เพิ่มได้ถึง 200%, ต้นทุนแรงงานสูญเปล่า 30–40%+, พนักงานเก่ง 20% สร้างกำไร 80%) **ไม่ได้ใส่** เพราะยืนยันที่มาไม่ได้ ถ้าการตลาดมีหลักฐานให้เพิ่มเอง
- **Negotiation Masterclass ปัญหาข้อ 4 "No Preparation Plan"**: เว็บเดิมข้อความใต้หัวข้อนี้ไม่ตรงกัน (พูดเรื่องความเชื่อที่จำกัด ดูเหมือนคัดลอกมาจากคอร์สอื่น) → ผมเขียนใหม่ให้ตรงหัวข้อ (เข้าห้องเจรจาโดยไม่มีแผนเตรียม) ต้องให้การตลาดตรวจ/ยืนยัน
- รายละเอียดเฉพาะที่คงไว้ตามเว็บเดิม ควรยืนยัน: Essential Sales "กระบวนการ 7 ขั้นตอน" + FAB · Negotiation "8 ขั้นตอน" และ "คู่เจรจา 4 รูปแบบ" · Conflict Management "TKI Model" · Change & Transformation "VUCA"
- วิดีโอแนวตั้งในส่วน Outcomes ที่เว็บเดิมมี (เช่น High-Performance, Strategic Selling) **ยังไม่ได้ใส่** (รอไฟล์จริง ดูข้อ 7 รายการที่ 2)
- EN/JA ของ 14 คอร์สนี้เป็น machine translation ชุดเดียวกับที่ระบุไว้ข้างบน — JA ต้องให้คนญี่ปุ่นตรวจ

## 7. งานถัดไป (เรียงตามที่เสนอ)

ส่วนที่ยังขาดเทียบเว็บเดิม: [`docs/CONTENT-GAP.md`](CONTENT-GAP.md) (รอบที่ 3 ทำข้อ 1–5 ไปแล้วในรูปแบบตัวอย่าง) · แต่ละข้อด้านล่างเหมาะกับ **แชทใหม่แยกกัน** (prompt พร้อมวางในข้อ 10)

0.0 ~~Merge `feature/search` → `main`~~ — **ทำแล้ว 25 ต.ค. 2569** (fast-forward, branch `backup/pre-merge-2026-10-25` = `main` เดิม b07d21a ย้อนกลับด้วย `git push -f origin backup/pre-merge-2026-10-25:main` · branch `preview/feature-search` ลบไม่ได้จากเซสชันนี้ (403) — ลบเองบน GitHub ได้) · หลัง merge เช็ค demo จริงที่ https://imeeba.github.io/hrideone-web/ (jsDelivr cache `@main` ได้ถึง ~12 ชม. ใส่ `&v=<เลข>` เพื่อเลี่ยง)
0.1 **ผลทดสอบก่อน merge (25 ต.ค. 2569, เว็บ build ใหม่จาก blueprint บน SQLite):** crawl 620 URL ผ่าน (html lang/h1/title) · measure.py 3 ภาษา × 1280/1220/1024/768/375 × 17 หน้า ไม่มี overflow/tap/contrast/PHP/JS error · ฟอร์ม lead: honeypot, rate limit 5/10 นาที, validate ผ่าน · ค้นหา TH/EN/JA + ไม่พบผล + input แปลก ๆ ผ่าน · แก้ระหว่างทาง: `/en/en/` ซ้ำใน `hri_localize_url`, label ใบประกาศนียบัตรไม่แปล
0.2 **ปัญหาที่รู้แล้ว ยังไม่แก้:** ~44 ลิงก์ในบทความที่นำเข้าชี้ URL เว็บเดิมที่ไม่มีแล้ว (→ งาน E ทำ redirect mapping — ต้องให้ Jimmi ตัดสิน เช่น negotiation-skills→negotiation-mastery, master-of-communication→communication-mastery, embracing-change→change-transformation-mastery, time-and-priority-management→time-and-priority-mastery, และ consulting, coaching, public-training/e-learning, the-sales-master, logical-and-critical-thinking ฯลฯ) · ไม่มี meta description ทุกหน้า (→ งาน D) · `<img>` ~4,000 รูปไม่มี width/height (→ งาน I/J) · `<title>` ของหน้า 404 เป็นอังกฤษ · ยังไม่ทดสอบ MySQL จริง · ต้องถาม Ruk-com เรื่อง ext-intl
0.1 **Merge `feature/real-images` → `main`** (รอบที่ 9, รอ Jimmi ดู) · ดูก่อน merge: แก้ทุก `main` ใน `playground/blueprint.json` เป็น `feature/real-images` แล้วเปิดผ่าน `https://playground.wordpress.net/#<blueprint JSON>` (ลิงก์สั้นใช้ `main` เสมอ) · หลัง merge: Actions "Site media" ไม่ต้องรันใหม่ (ไฟล์อยู่ใน repo แล้ว), jsDelivr `@main` เห็นไฟล์ใหม่ทันทีเพราะเป็น path ใหม่
0.2 **รูปที่ยังขาด (รอบที่ 9)**: Jimmi ส่งรูปกิจกรรม Social Impact (About, 4 รูป) · ยืนยันสิทธิ์ใช้รูปผู้บริหาร/ผู้เข้าอบรม/โลโก้ลูกค้ากับลูกค้าก่อนขึ้นเว็บจริง · ตรวจ alt โลโก้ช่อง 10 · อยากได้ปกคอร์สใหม่แทนรูปที่มืด/ซ้ำไหม · วิดีโอ (hero, DO·WA·KEI, Outcomes) จะใส่เมื่อไร
0. **งานที่ Jimmi ต้องทำเอง**: (ก) สร้าง Google Sheet + Apps Script + ใส่ค่าใน wp-config ตาม `LEAD-SETUP.md`; (ข) สร้าง Turnstile (ฟรี) แล้วใส่ key; (ค) ตอบคำถามในหัวข้อ "ข้อมูลที่ต้องตัดสินใจ" ข้อ 6; (ง) ส่งไฟล์รูป/วิดีโอจริง (บางอย่างโหลดจาก hrideone.com จาก workspace ไม่ได้); (จ) ตอบคำถามแปลอัตโนมัติใน [`AUTO-TRANSLATE.md`](AUTO-TRANSLATE.md) ข้อ 3.3 (อย่างน้อยผู้ให้บริการ) ก่อนเริ่มงาน G; (ช) ถาม Ruk-com ตาม `HOSTING.md` ข้อ 2 เมื่อพร้อมขึ้นโฮสต์
1. ~~เนื้อหา In-house อีก 14 คอร์ส~~ — **ทำแล้วในรอบที่ 4** (ครบ 15 คอร์ส) เหลือให้การตลาดตรวจ/เติมตามหัวข้อ "ข้อมูลที่ต้องตรวจ (จากรอบที่ 4)" ในข้อ 6 · ส่วนที่เว็บเดิมไม่มีและควรเติมเพื่อ convert: outline, กลุ่มเป้าหมาย, ระยะเวลา, วิทยากร, รีวิว ของแต่ละคอร์ส (แก้ใน editor ได้ ใช้ pattern "Course page — In-house")
2. ~~**รูป/วิดีโอจริงของหน้าอื่น (ไม่ใช่บล็อก)** → prompt H~~ — **ทำแล้วในรอบที่ 9 (merge แล้ว)** เหลือวิดีโอ + รูป Social Impact · ของเดิม: โลโก้ลูกค้า 40, รูปผู้บริหาร 2, Gallery, รูป hero หน้าบริการ, โลโก้พาร์ทเนอร์ 8, รูปคอร์ส (thumbnail เว็บเดิม `thumbnail-IH-03.webp` ฯลฯ), รูปวิทยากร, วิดีโอแนวตั้งใน Outcomes · **ไฟล์ export มีแต่รูปของบทความ** (1,578 attachment ผูกกับโพสต์ทั้งหมด) → ต้องหา URL รูปจากหน้าเว็บเดิมทีละหน้า แล้วใส่ใน list ให้ `demo-media.py`/workflow ดึง (ขยายสคริปต์ให้รับ list) · ดึงเฉพาะรูปที่หน้าใหม่ใช้จริง
2.1 ~~**รูปใน WordPress ให้เล็กสุด** → prompt I~~ — **ทำแล้วในรอบที่ 11** (ดูข้อ 4 "รูปในคลังสื่อ") · ตอนย้ายเว็บ (E): sideload รูปจาก `playground/site-media/` แล้วรัน `wp hri images optimize` บน staging
2.2 ~~**PageSpeed** → prompt J~~ — **ทำแล้วในรอบที่ 12** (ทุกหน้า mobile ≥94, ดูข้อ 5) · เหลือ: วัดซ้ำบนโฮสต์จริง/staging ด้วย `scripts/perf.py --base https://…` หลังตั้งค่าตาม `HOSTING.md` ข้อ 5
3. ~~นำเข้าบทความ~~ — **ทำแล้วในรอบที่ 6** (เว็บเดิมมี 115 เรื่อง ไม่ใช่ ~150 และมี EN/JA ครบอยู่แล้ว) · ตอนย้ายจริง: export ใหม่จากเว็บเดิม → `scripts/wxr-to-blog.py` → Tools → นำเข้าบทความ แบบ "คัดลอกรูปเข้าคลังสื่อ" บน staging
4. ~~**ค้นหาทั้งเว็บ** (ภาษาไทย, synonym map)~~ — **ทำแล้วในรอบที่ 10 (merge แล้ว)** ดูข้อ 4.y · เหลือ: ทดสอบบน MySQL จริง (Ruk-com), ขอ Ruk-com เปิด ext-intl (เขียนใน HOSTING.md แล้ว), ให้ Jimmi ตรวจ synonym 25 กลุ่ม
5. ~~**SEO / AI-SEO** → prompt D~~ — **ทำแล้วในรอบที่ 13 (merge แล้ว)** ดูข้อ 4.z · เหลือ: ส่ง sitemap เข้า Search Console/Bing หลังขึ้นโฮสต์จริง, ให้ Jimmi ตัดสินเรื่องบอตเทรนโมเดล (ข้อ 6) · ของเดิม: The SEO Framework (ฟรี) + schema เอง (EducationalOrganization, Course/CourseInstance + offers/Early Bird, FAQPage, BlogPosting, BreadcrumbList, Person สำหรับวิทยากร), hreflang, robots.txt (AI crawlers), llms.txt, Bing/IndexNow
6. **ย้ายเนื้อหา + 301 redirects** (`/public-training/*`, `/courses/*`, หน้าย่อยบริการ, ลิงก์เก่าในเนื้อหาบทความ) · ของบล็อกทำแล้วใน `blog.php` (เว็บเดิมใช้ `?e-page-…=N` ไม่ใช่ `/blog/page/N`)
7. **แปลอัตโนมัติ TH → EN/JA** — สเปกเต็ม: [`docs/AUTO-TRANSLATE.md`](AUTO-TRANSLATE.md) (ทำเองใน hri-core ต่อยอด Polylang ฟรี, แปลทีละ block, ไม่ทับ block ที่คนตรวจแก้, สถานะ 🤖/✅/⚠️, JA noindex จนกว่าตรวจ, จ่าย API ตามใช้ + เพดานงบ) · prompt G ในข้อ 10 · ต้องได้คำตอบข้อ 3.3 ก่อน
8. ~~แก้ tap target ≥44px + ชื่อคอร์สตัดกลางคำในการ์ดแคบ~~ — **ทำแล้วในรอบที่ 5** · ~~Blog UI~~ ทำแล้วในรอบที่ 6 · เหลือเก็บตก: ตัวกรอง sticky สูงกว่าจอ ดูหัวข้อ "ปัญหานอกขอบเขต"
9. **โฮสต์ Ruk-com** — **พักไว้จน demo เสร็จ** · ข้อมูลแพ็กเกจ + ข้อความถาม support 19 ข้อ + วิธีขึ้นเว็บที่เป็นไปได้: [`docs/HOSTING.md`](HOSTING.md)
10. Cloud staging (`new.hrideone.com`) หลังได้คำตอบ support · (ถาม Jimmi) Polylang Pro ถ้าอยากให้ slug เหมือนกันทุกภาษา (เสียเงิน)

**การตัดสินใจ/ข้อสรุปจากรอบที่ 7** (คุยกับ Jimmi 23 ต.ค.)
- slug: TH และ EN เป็นอังกฤษครบแล้ว · JA 115 เรื่องเป็นภาษาญี่ปุ่น (percent-encode) **คงไว้** (ตรงเว็บเดิม, Google แนะนำใช้คำในภาษาของผู้อ่าน) · ถ้า Jimmi อยากเปลี่ยนภายหลัง: slug อังกฤษ + `-ja` (Polylang ฟรีห้ามซ้ำ) + `_wp_old_slug` ให้ redirect
- quality รูป 75–78 (บทความเดิมมีอินโฟกราฟิกตัวหนังสือไทยเยอะ ต่ำกว่า ~70 ขอบตัวอักษรเลอะ) · AVIF ใช้ได้ถ้า Imagick ของโฮสต์รองรับ ไม่งั้น WebP
- คัดลอกรูปไม่กินโทเค็น (เซิร์ฟเวอร์/เครื่อง GitHub ดาวน์โหลดเอง Claude ไม่เห็นรูป) · กินเฉพาะตอนเขียน/ตรวจโค้ด

## 8. ข้อควรระวัง / สิ่งที่เคยพลาด

- `paginate_links` ใส่ class `page-numbers` ให้ `<ul>` ด้วย → CSS ต้องเจาะ `a.page-numbers, span.page-numbers`
- รูปใน `aspect-ratio` box ต้องมี `overflow:hidden` + `img{display:block}` ไม่งั้นรูปดันกล่องสูงเกิน
- Navigation block ของ core ใส่ `items-justified-right` → ใน overlay ต้องใช้ selector ที่เจาะจงกว่าเพื่อจัดชิดซ้าย
- ถ้าเมนูเปิดค้างแล้วขยายจอเป็น desktop เมนูเต็มจอจะค้าง (พฤติกรรม core)
- `curl` กับ URL ที่มี `[]` ต้องใช้ `-g`
- blueprint ใช้ `post_name__in` (ไม่ใช่ `name__in`)
- ไม่มีข้อมูลลับใน repo (API key, Sheet URL)
- Polylang: หลังลิงก์คำแปลของหน้าแรกต้อง `PLL()->model->clean_languages_cache()` ไม่งั้นภาษาจำ page_on_front เดิม; `redirect_lang` ต้องเปิดไม่งั้นหน้าแรก EN เป็น `/en/style-guide-en/`
- Polylang สร้างภาษาใน runPHP ได้เฉพาะเมื่อ `PLL_ADMIN` true (ไม่มีภาษา = ไม่มี `PLL()` ฝั่ง front)
- Theme pattern list ถูก cache ตาม version theme → เพิ่ม pattern ใหม่ให้ bump `Version` ใน style.css (local: `wp eval 'wp_get_theme()->delete_pattern_cache();'`)
- ทั้ง theme ไม่มี `box-sizing: border-box` global → element ที่ตั้ง `min-height`/padding ให้ใส่ `box-sizing: border-box` เอง
- Playground ที่เปิดด้วย `#<blueprint>` เปลี่ยนแค่ hash จะไม่โหลดใหม่ ให้เปลี่ยน query (`?v=2#...`)
- Gallery ของ core ตั้งความกว้าง `figure` ด้วย `:not(#individual-image)` → CSS ที่จะ override ต้องใส่ selector เดียวกัน · ในกล่อง scroll ที่มี padding `%` ของ flex-basis คิดจากความกว้างหลังหัก padding → slider ใช้ `vw`
- `wp i18n make-pot` ของ theme ต้องใส่ `--skip-theme-json` (ไม่งั้นจะโหลด theme-i18n.json จาก svn.wordpress.org ซึ่ง workspace เข้าไม่ได้)
- Screenshot แบบ full page ไม่โหลดรูป `loading="lazy"` → ตั้ง `loading="eager"` ก่อนถ่าย หรือถ่ายทีละ section
- Editor ใน local ใช้ `wait_until: domcontentloaded` (admin ยิงไป wordpress.org ไม่จบ networkidle)
- วันที่บทความใน local เป็นภาษาอังกฤษเพราะไม่มี language pack ไทยของ core (บนโฮสต์จริงติดตั้ง ภาษาไทย แล้วจะเป็นเดือนไทย)
- ลบ/ย้าย component ใน pattern แล้วต้อง seed หน้าใหม่ (page content เก็บใน DB): local ใช้ `wp eval-file playground/seed.php` แล้ว `seed-languages.php` · ระวังตอนลบ CSS ทั้งบล็อก — กฎ selector ที่ใช้ร่วมกัน (เช่น `.hri-post`) อยู่ปนกับของที่ลบ
- เพิ่ม/แก้ข้อความใน pattern ของ theme: `wp i18n make-pot . languages/hri-theme.pot --skip-js --skip-theme-json --skip-block-json` → `wp i18n update-po` → ใส่ msgstr (อย่าให้ polib เขียนไฟล์ใหม่ทั้งไฟล์ diff จะบวม) → `make-mo` + `make-php`
- อย่าตั้ง className ของ section ซ้ำกับ class ของ block ใน hri-core (เช่น `hri-courses` = layout ของ course filter ทำให้หน้าเพี้ยน) ใช้ `hri-courses-section`
- **ตรวจหลังแก้ทุกครั้ง: `python3 -I scripts/measure.py`** (อยู่ใน repo แล้ว) · วัด overflow แนวนอน, tap target <44px, contrast AA, PHP notice, JS error ที่ 1280/768/375 × TH/EN/JA × `/`, `/courses/`, `/in-house/`, `/public-training/`, คอร์ส In-house 1 + Public 1, `/contact-us/` = 63 รอบ (~5 นาที) · exit code 1 ถ้ามีปัญหา
  - ตัวเลือก: `--base http://127.0.0.1:8899/wp` (ค่าเริ่มต้น) · `--pages /about/,/blog/` (ใส่ path ภาษาไทย สคริปต์ต่อ `-en`/`-ja` ให้เอง ยกเว้น `/` และ `/courses/`) · path ที่ EN/JA ไม่ใช้ `-en`/`-ja` เขียนแบบ `th|en|ja` เช่น `/category/sales/|/category/sales-en/|/category/sales-ja/` (`/blog/` ตั้งไว้ใน `PATH_OVERRIDE` แล้ว) · `--widths 1280,375` · `--langs th` · `--json out.json` · `--verbose`
  - ต้องมี Playwright (Chromium อยู่ที่ /opt/pw-browsers) · รันด้วย `python3 -I`
  - ตรวจ tap target ใน 3 สถานะ: ปกติ, เปิด `<details>` ทั้งหมด (ตัวกรองมือถือ, dropdown ภาษา), เปิดเมนูแฮมเบอร์เกอร์ · checkbox ใน label วัดที่ label · ลิงก์ inline กลางประโยคและ honeypot ข้าม · ลิงก์การ์ดที่ใช้ `::after` คลุมทั้งการ์ด (stretched link) วัดทั้งการ์ด · ข้อความบน Cover block (hero รูป/วิดีโอ) ข้ามการวัด contrast เพราะขึ้นกับ overlay (ตรวจด้วยมือ 4.9:1)
  - สคริปต์ **ไม่จับ** ปัญหาด้านหน้าตา (เช่น องค์ประกอบที่ควรซ่อนแต่โผล่) → ยังต้องถ่าย screenshot ดูเฉพาะจุดที่แก้ · เจอมาแล้วในรอบที่ 5: กฎ `display:flex` ที่เพิ่มให้ `summary` ตัวกรองทับ `display:none` ของ desktop
- **class `.hri-card` / `.hri-card__title` ซ้ำกันระหว่าง theme (การ์ดหน้าบริการ, `base.css`) กับ plugin (การ์ดคอร์ส, `courses.css`)** · theme โหลดทีหลัง จึงทับขนาดหัวข้อของ plugin (การ์ดคอร์สเคยเป็น x-large เพราะเหตุนี้) → ถ้าจะแก้เฉพาะการ์ดคอร์สให้ใช้ `.hri-course-card` (เพิ่ม class นี้ใน `course-card.php` แล้ว) ถ้าแก้ `.hri-card` ตรงๆ จะกระทบการ์ดหน้าบริการด้วย
- `container-type` ทำให้ element นั้นเป็น containing block ของลูกที่ `position:absolute` → อย่าใส่ที่ `.hri-card__body` (ลิงก์ `::after` คลุมทั้งการ์ดจะถูกตัดแค่ในส่วน body) ใส่ที่ตัวการ์ด `.hri-course-card` เท่านั้น
- Tap target: ใช้ `var(--wp--custom--tap)` (token `tap` = 44px) เป็น `min-block-size`/`min-inline-size` + `inline-flex` + `align-items:center` + `box-sizing:border-box` · ตัวอักษร/ไอคอนขนาดเดิม แค่พื้นที่กดขยาย
- Production/Ruk-com: พิจารณา `define( 'PLL_COOKIE', false );` ใน wp-config เพื่อไม่ให้ cookie ภาษาไปรบกวน Rcache (ภาษาอยู่ใน URL แล้ว)
- `wp eval-file` ต้องใช้ path แบบ absolute · seed ใหม่ต้อง `wp eval 'wp_get_theme()->delete_pattern_cache();'` (หรือ bump Version) ก่อน แล้ว `seed.php` → `seed-languages.php`
- Pattern PHP ที่ใช้ helper `hri_bm_*` ต้องส่ง array ตามรูปแบบที่ helper คาด (`hri_bm_cards` รับ associative: title/text/help/kicker/num) ส่งแบบ indexed จะ fatal
- Block ที่ render ด้วย PHP แล้วออก HTML เอง ต้องใส่ class `is-layout-flex wp-block-buttons-is-layout-flex` ให้ `wp-block-buttons` ไม่งั้นปุ่มซ้อนกัน (core ใส่ให้เฉพาะ block ที่ผ่าน parser)
- Constrained layout ของ core ตั้ง `margin-inline:auto` ทับ CSS ของเรา → ถ้าอยากชิดซ้ายต้อง `!important` หรือ selector เจาะจงกว่า (`.hri-banner > .hri-banner__text`)
- `wp_set_post_categories` ในรอบ seed ซ้ำอาจคง Uncategorized ไว้ → ใช้ `wp_set_object_terms( $id, [term_id], 'category' )`
- `wp i18n make-pot` ของ plugin ต้องใส่ `--skip-block-json` (ไม่งั้นยิงไป svn.wordpress.org) และ `update-po` ต้องใช้ path absolute
- Screenshot ใน local: php -S รับทีละ request รูปบางรูปโหลดไม่ทัน (เห็นเป็นกล่องว่าง) ไม่ใช่บั๊ก — ตรวจด้วย `naturalWidth`
- `?course=` ชนกับ query var ของ CPT `course` ทำให้หน้าเพี้ยน → ใช้ `?hri_course=`
- **ปุ่มที่ PHP พิมพ์ HTML เอง** (`wp-block-button__link` นอก parser) ได้แค่สไตล์ element button ของ theme.json: ไม่มี CSS ของ core Button (ลิงก์ inline → padding ล้นกล่อง ทับของข้างล่าง) และไม่มี variation `outline` → `wp_enqueue_style( 'wp-block-button' )` + กฎ outline ใน `base.css` (หน้าอื่นไม่เห็นปัญหาเพราะมี Button block จริงในหน้า)
- ปิดงานแล้วต้องหยุดเซิร์ฟเวอร์ local (`php -S` ที่รันเป็น background task) ด้วย ไม่งั้นแอปของ Jimmi แสดงว่ายังทำงานอยู่
- ไอคอนใน CSS ใช้ `mask` → สีคือ `background-color` ไม่ใช่ `color` · ไอคอนใหม่ต้องเพิ่มใน `scripts/build-icons.py` (ตัวแปร CSS มีเฉพาะที่ตั้ง `True`)
- วัด overflow ด้วย JS ต้องข้ามช่อง honeypot `.hri-lead__hp` (ซ่อนนอกจอโดยตั้งใจ) ไม่งั้นทุกหน้าที่มีฟอร์มโดน flag ผิด
- `scripts/local-setup.sh` เมื่อรันผ่าน pipe (`| tail`) ในเครื่องมือที่มี timeout อาจค้างหลังติดตั้ง+seed เสร็จแล้ว (เซิร์ฟเวอร์ `php -S` ที่สคริปต์เปิดไว้ยังเป็น child) → รันแบบ background หรือเปลี่ยนเป็น `> log 2>&1` แล้วเช็กว่าเสร็จด้วย `curl -sI http://127.0.0.1:8899/wp/` และ `wp post list --post_type=course --format=count` (ได้ 21 หลัง seed)
- **Polylang ไม่สนใจ `'lang'` ใน query ที่ใช้ `name` (slug)** → `get_posts( name + lang )` คืนโพสต์ภาษาอื่นได้ ต้องเช็ก `pll_get_post_language()` เอง (ใช้ใน `blog.php`, `blog-import.php`)
- Polylang redirect `/en/<slug>/` ไปโพสต์ภาษาไทยที่มี slug นั้น (canonical) ก่อน `template_redirect` ของเรา → redirect ของ EN/JA ต้องทำใน `parse_request`
- ปุ่มใน form ห้ามชื่อ `submit` (`submit_button()` ค่าเริ่มต้น) ถ้าจะเรียก `form.submit()` ด้วย JS — ชื่อนี้บังเมธอด
- `upload_max_filesize` ค่าเริ่มต้น 2MB (php -S ใน local) → ไฟล์นำเข้าใช้ `.json.gz` (0.7MB)
- REST API ใน local (php -S) ใช้ `/?rest_route=/wp/v2/...` (`/wp-json/` ไม่ route)
- ตรวจ block valid ทั้งเว็บ: เปิด `post-new.php` แล้วใช้ `wp.blocks.parse()` กับ `content.raw` ของทุกโพสต์ (ดู `isValid` ทุก block รวม innerBlocks)
- regex หา URL ใน**ข้อความ JSON** ต้องหยุดที่ `\"` ด้วย (`[^"\\\s)]+`) ไม่งั้นได้ URL ที่มี `\` ติดท้าย (รอบที่ 7 รูปในเนื้อหา 425 รูปพังเพราะเหตุนี้)
- GitHub API ผ่าน proxy ของ Claude: สั่ง/ดูสถานะ workflow ได้ (`gh api .../actions/workflows/<file>/dispatches`, `.../runs/<id>`) แต่ **อ่าน log ไม่ได้** และ **เปิด GitHub Pages ไม่ได้** · workflow ที่รันนานให้พิมพ์ความคืบหน้า + มีเพดานเวลา + commit ผลแม้ล้ม (`if: always()`)
- gzip ของ Python คนละเวอร์ชันได้ไบต์ต่างกันแม้ข้อความเดียวกัน → อย่าเขียน `.gz` ใหม่ถ้าข้อมูลไม่เปลี่ยน (ไม่งั้น commit วน)
- jsDelivr cache `@main` (ไฟล์ที่แก้ทับชื่อเดิมอาจเห็นของเก่าได้หลายชั่วโมง) → ไฟล์ใหม่ตั้งชื่อใหม่ หรือ purge ที่ `https://purge.jsdelivr.net/gh/imeeba/hrideone-web@main/<path>`
- Playground: เปิดหน้าอื่นใช้ช่อง URL ด้านล่างของ Playground หรือพารามิเตอร์ `&url=/path/` (ตั้ง `location` ของ iframe ตรงๆ ได้หน้าว่าง เพราะ URL มี scope) · iframe ซ้อน 2 ชั้น
- `download_url()` ปฏิเสธพอร์ตที่ไม่ใช่ 80/443/8080 → ทดสอบ download ใน local ต้องเพิ่ม `http_allowed_safe_ports` และใช้ชื่อโฮสต์คนละชื่อกับเว็บ (`localhost` vs `127.0.0.1`) เพราะ importer ข้าม URL ของเว็บตัวเอง
- Polylang จาก GitHub ใช้ tag `3.9` (ตัว `3.9.0-rc1`/beta ก็มี → เลือก tag ที่ไม่มี rc/beta) · seed ภาษา: `wp hri languages` → `wp eval-file <absolute>/playground/seed.php` → `seed-languages.php` ตามลำดับ (ตอนรัน `seed.php` ครั้งแรกใน local-setup ยังไม่มี Polylang เป็นปกติ)

- **รูป (รอบที่ 11)**: `set_quality()` ของ WordPress ไม่มีผลเมื่อบันทึกเป็นนามสกุลใหม่ (PNG→WebP) เพราะ `save()` ตั้ง quality ใหม่จาก filter `wp_editor_set_quality` → ถ้าจะ override ต้องใช้ filter ที่ priority สูงกว่าและเปิดค้างไว้จน `save()` จบ (`hri_image_convert_file()` ทำให้แล้ว) · `wp_unique_filename()` มองว่า `name.jpg` ชน `name.webp` → ต้องลบต้นฉบับก่อนตั้งชื่อ ไม่งั้นได้ `name-1.webp` · ประเภทไฟล์ของ attachment (`post_mime_type`) ไม่เท่ากับไฟล์จริงเสมอ: WordPress ย่อรูปใหญ่เป็น `-scaled.webp` แต่ attachment ยังเป็น `image/jpeg` และเก็บ `original_image` (JPG ใหญ่) ไว้ → ตัดสินจาก `wp_check_filetype( $file )` · `wp_insert_post()` ต้อง `wp_slash()` ถ้า content มี `\/` (ใน test fixture) · บล็อก Featured Image ถ้าไม่ใส่ `sizeSlug` จะใช้ขนาดเต็ม · `wp-cli eval-file` ที่ไม่มี `mysql` ใช้ `wp db query` ไม่ได้ (SQLite) ให้ใช้ `wp eval 'echo get_post_field(...)'`

- **ความเร็ว (รอบที่ 12)**: Lighthouse ผ่าน `php -S` ตรงๆ ให้ตัวเลขผิด (ไม่บีบอัด) ใช้ `scripts/perf.py` ที่มีพร็อกซี · `pyftsubset` ของ glob `kanit-latin-*` จับ `latin-ext` ด้วย → ตัดไฟล์ ext เหลือ ~700 ไบต์ (เคยพลาด, แก้แล้วในสคริปต์) · core ไม่ใส่ `loading="lazy"` ให้ `<img>` ที่ไม่มี width+height · `git rm` ไฟล์ที่ถูกแก้ค้างอยู่ต้องใส่ `-f` · ทดสอบ before/after ด้วย `git worktree add /tmp/x origin/main` แล้วชี้ symlink ของ `hri-local` ไปที่นั่น (เสร็จแล้วชี้กลับ; `php -S` มี realpath cache สลับ symlink แล้วอาจเห็นของเก่า 1–2 request) · `<details open>` ที่ JS ปิดทีหลังบนมือถือ = layout shift ใหญ่ (ปิดด้วย inline script หลังองค์ประกอบ) · ตัวกรอง `wp_speculation_rules_href_exclude_paths` ใช้ pattern ของ URLPattern: `?` ใน pattern เป็นตัวขยาย ห้ามใส่ query string (core ข้ามทุก URL ที่มี query อยู่แล้ว) · `styles_inline_size_limit` เรียงไฟล์เล็กก่อนแล้ว inline จนเต็มงบ (ค่าเริ่มต้น 20 KB, ตอนนี้ 40 KB) · ฟอนต์ตัวเดียวกันที่ถูก subset ใหม่ไม่ต้องเปลี่ยน `unicode-range` (ตัวอักษรที่หายไปทั้งหมดนอก range เดิมอยู่แล้ว: ตัวผสม Vietnamese)

### ปัญหาที่เจอนอกขอบเขต (ยังไม่แก้)

> tap target ของ header/footer/ตัวกรอง/หน้าคอร์ส/ฟอร์ม และชื่อคอร์สตัดกลางคำ แก้แล้วในรอบที่ 5 (ข้อ 5)

- **ตัวกรองคอร์สบน desktop สูงกว่าหน้าจอ** (`/courses/` ที่ 1280: ~1390px, หลังเพิ่ม tap target 44px; ก่อนหน้า ~1150px ก็สูงกว่า viewport 900px อยู่แล้ว) แต่ `.hri-filter` เป็น `position: sticky` → ส่วนล่างของตัวกรอง (ระยะเวลา, รูปแบบ) เข้าถึงไม่ได้จนกว่าจะเลื่อนไปท้ายรายการคอร์ส · ข้อเสนอ: `max-height: calc(100vh - 2 * <top>)` + `overflow-y: auto` ที่ `.hri-filter` (ระวัง box-shadow ring ที่ `.hri-filter` ถูกตัด) หรือเลิก sticky
- `/style-guide/` ป้าย `#767C82` 4.47:1 (ต้อง 4.5) (Blog UI แก้แล้วในรอบที่ 6)
- ลิงก์ที่อยู่เดี่ยวๆ ในย่อหน้าของเนื้อหาบทความ (เช่น "ดูรายละเอียดหลักสูตร TEN X" ใน `why-success-ompanies-fail-in-15-years` ทั้ง 3 ภาษา) สูง 24px: ผ่าน WCAG 2.5.8 AA แต่ไม่ถึง 44px · ไม่ได้แก้ CSS เพราะ selector แยกลิงก์เดี่ยวกับลิงก์กลางประโยคไม่ได้แม่น → ทางเลือก: ให้การตลาดเปลี่ยนเป็น Button block ในบทความที่เป็น CTA
- ลิงก์ inline ในย่อหน้า PDPA ของฟอร์ม (`hri/lead-form`) สูง ~24px **คงไว้ตั้งใจ**: WCAG 2.5.8 ยกเว้นลิงก์ที่อยู่กลางประโยค และถ้าเพิ่ม padding ระยะบรรทัดจะห่างผิดปกติ (ผ่านเกณฑ์ AA 24px ที่ตั้งไว้ ไม่ถึง 44px) · `measure.py` ข้ามลิงก์ประเภทนี้
- การ์ดบริการ/หัวข้อ (`.hri-card__title` ของ theme) ยังไม่ได้ตรวจเรื่องคำยาวตัดกลางคำ (รอบที่ 5 แก้เฉพาะการ์ดคอร์ส)

**จากรอบที่ 9 (รูปจริง)**
- workspace ของ Claude เข้า hrideone.com และ jsDelivr ไม่ได้ (curl/WebFetch ถูกปฏิเสธ) → งานที่ต้องเปิดเว็บเดิม/ดาวน์โหลดรูปให้รันบน GitHub Actions (`crawl-images.yml`, `site-media.yml`, `demo-media.yml`) · workflow ที่ trigger ด้วย `push: paths` รันบน branch ได้ (ไม่ต้องกด Run) แต่ `workflow_dispatch` ใช้ได้เฉพาะ workflow ที่อยู่บน `main` · `gh` ใช้ได้ (`gh run list --branch <b>`) แม้ `gh auth status` จะขึ้น fail
- workflow commit กลับเข้า branch เอง → `git pull --rebase` ก่อน push ทุกครั้ง
- clone แบบ `--depth 1` ได้ refspec เฉพาะ `main` → branch ใหม่ไม่มี tracking (hook เตือน "no remote branch" ทั้งที่ push แล้ว) แก้: `git config --add remote.origin.fetch '+refs/heads/<b>:refs/remotes/origin/<b>' && git fetch origin && git branch --set-upstream-to=origin/<b>`
- screenshot ของ crawl ใหญ่ (16 MB) → ไม่เก็บใน repo (`.gitignore`) ดูย้อนหลังได้จาก commit `ae36993` (`git show ae36993:playground/data/crawl/shots/<ไฟล์>`) · `pages.json` เก็บไว้ (รายการรูปทุกหน้าของเว็บเดิม + URL ต้นฉบับ)
- รูปพื้นหลัง hero ของหน้าคอร์สเดิมไม่มีตัวหนังสือ (ข้อความเป็น HTML) → ใช้เป็นปกได้ · แต่ thumbnail ในหน้ารวมคอร์สเดิม (`thumbnail-IH-*.webp`, `1920x1080-0*.webp`, `Website-Public-page-*`) **มีตัวหนังสือไทยฝัง** ห้ามใช้เป็นปก (หน้า EN/JA)
- `782.webp` ที่ HANDOFF เดิมเขียนว่าเป็นรูป hero In-house จริงๆ เป็นภาพเครื่องหมายคำถาม 3D (รูป CTA ท้ายหน้าคอร์ส) · `1920x1080-01.webp` เป็นแบนเนอร์คอร์ส Negotiation มีตัวหนังสือ ไม่ใช่รูป "ทำไมต้อง Public"
- core/image: อย่าใส่ width/height ใน `<img>` ของ markup ที่ seed สร้าง (core เก็บเป็น attribute แล้ว save เป็น style → editor validate ไม่ผ่าน) · ขนาดใส่ได้เฉพาะรูปที่ PHP พิมพ์เอง (featured image ผ่าน filter ใน `blog.php`)
- ทดสอบ local: `scripts/local-setup.sh` + Polylang 3.9.1 (`git clone --depth 1 --branch 3.9.1 https://github.com/polylang/polylang` ใน plugins แล้ว `composer dump-autoload --no-dev`) + `wp hri languages` + `wp eval-file playground/seed-languages.php` · รูป site-media ใน local: `ln -s <repo>/playground/site-media <local>/site-media` + `wp config set HRI_SITE_MEDIA_BASE http://127.0.0.1:8899/site-media --type=constant` แล้วรัน seed ซ้ำ · รูปบล็อก (jsDelivr) ใน local จะขึ้นเป็นรูปเสีย = ปกติ
- slider ที่มีสไลด์ >5 บนจอ 375: ปุ่มลูกศรเคยหดเหลือ 28px (flex shrink) แก้แล้ว ถ้าเพิ่ม control อื่นในแถวนี้ให้เช็ก tap target อีกครั้ง

**จากรอบที่ 10 (ค้นหา)**
- **ทดสอบกับ MySQL ไม่ได้ใน workspace** (มีแต่ SQLite) → SQL ในดัชนีเขียนแบบมาตรฐาน (`CREATE TABLE`, `IN`, `LIKE` + `esc_like`, ไม่มี FTS/คำสั่งเฉพาะ SQLite) แต่ **ต้องลองบน Ruk-com จริงก่อนเปิดใช้**: `wp hri search reindex` แล้ว `wp hri search query เจรจา` · ถ้าตารางไม่ถูกสร้าง ดู `$wpdb->last_error` · คอลัมน์ `token` เป็น `utf8mb4_bin` (ต้อง MySQL ≥5.5.3)
- **ถ้าโฮสต์ไม่มี ext-intl** ระบบสลับเป็น n-gram เองและสร้างดัชนีใหม่ (ผลไทยดีพอ ดัชนีใหญ่ขึ้น ~2.5 เท่า) · ถาม Ruk-com ว่าเปิด intl ได้ไหม (`php -m | grep intl`)
- ความต่างของ SQLite integration: เขียนดัชนีทีละโพสต์ช้า (~35–40 ms ไม่ว่าจะ batch ขนาดไหน) จึงสร้างดัชนีใน Playground ตอน boot ~20 วินาที (ขั้นสุดท้ายใน blueprint) · อย่าเรียก `hri_search()` ในโปรเซสเดียวกับที่เพิ่งบันทึกโพสต์แล้วหวังเห็นผล (ดัชนีอัปเดตตอน `shutdown`)
- ICU ตัดคำโดยไม่สนบริบทไม่ได้: คำเดียวกันพิมพ์เดี่ยวๆ กับอยู่ในประโยคอาจตัดไม่เหมือนกัน (ชื่อทับศัพท์) → มีตัวสำรอง substring · อย่าลบตัวสำรองนี้
- pattern ของ theme ถูก cache ตามเลขเวอร์ชัน theme → เพิ่ม pattern ใหม่แล้ว WordPress ไม่เห็นจนกว่าจะ bump `Version` ใน `style.css`
- `wp i18n update-po` + polib เขียนไฟล์ใหม่ทั้งไฟล์ (diff บวม) → เติม `msgstr` ด้วยสคริปต์แก้ข้อความตรงๆ (plural ของ JA มีฟอร์มเดียว `msgstr[0]`)
- `curl` กับ `?s[]=a` ต้องใส่ `-g` ไม่งั้น curl ตีความ `[]` เป็น glob

**ปัญหานอกขอบเขตที่เจอในรอบที่ 10 (ยังไม่แก้)**
- **ข้อเสนอแนะ UX ที่ยังไม่ได้ทำ (จากการตรวจซ้ำรอบที่ 10)**: ไอคอนค้นหาข้างปุ่มเมนูบนมือถือ (ตอนนี้ต้องเปิดเมนูก่อน) · "คุณหมายถึง …" เมื่อพิมพ์ผิดเล็กน้อย (negotation → negotiation) · เก็บคำที่ค้นแล้วไม่เจอไว้ให้ทีมการตลาดเห็นใน Settings → การค้นหา (เพื่อเพิ่ม synonym) · "หลักสูตรแนะนำ" ตอนนี้ = คอร์สเปิดรับ 3 ตัวแรกตามลำดับ ไม่ใช่ตามความนิยม (ถ้าต้องการให้ทีมเลือกเอง ทำช่องเลือกใน Settings) · คำอังกฤษหลายคำ (เช่น "TEN X") ยังไม่บังคับให้อยู่ติดกัน และตัวอักษรเดี่ยว ("X") ถูกตัดทิ้ง · เลขหน้าในมุมมองประเภทเดียวยังแสดงทุกหน้า (ควรย่อเมื่อเกิน ~7)
- ตัวกรองคอร์ส (`hri/course-filter` ช่อง `q`) ยังค้นด้วย `LIKE` ของ WordPress → คำไทยยาวหรือ synonym ไม่ทำงานเหมือนค้นทั้งเว็บ · ถ้าอยากให้เหมือนกัน ให้เรียก `hri_search( $q, array( 'types' => array( 'course' ) ) )` แทน
- หน้า 404 ชื่อหน้า (`<title>`) เป็นภาษาอังกฤษ "Page not found" ทุกภาษา (ข้อความของ core)
- ผลค้นหาไม่แสดงตัวอย่างข้อความที่ตรงคำ (แสดง excerpt/ต้นเนื้อหา) และไม่ไฮไลต์คำ — ตั้งใจให้เบา
- **จำกัดความถี่ฟอร์ม Lead ใช้ IP จริงแล้ว** (`hri_lead_ip()`): เชื่อ header proxy เฉพาะเมื่อ peer เป็น IP ภายใน หรือเมื่อตั้ง `HRI_IP_HEADER` ใน wp-config.php (ดู `LEAD-SETUP.md`) · **ต้องตรวจหลังขึ้นโฮสต์จริง**: ถ้าอยู่หลัง Cloudflare/proxy สาธารณะ ต้องตั้ง `HRI_IP_HEADER` ให้ตรงกับโฮสต์ ไม่งั้นทุกคนถูกนับเป็นคนเดียวกัน (5 ครั้ง/10 นาทีรวมทั้งเว็บ) · Turnstile ยังปิดอยู่จนกว่าจะใส่ key

## 9. วิธีทำงานที่ประหยัด usage

- **ใช้ Sonnet**: งานที่ prompt ระบุชัด + มีเกณฑ์ตรวจเป็นตัวเลข ทำได้ใกล้เคียง Opus · งานที่ต้องใช้วิจารณญาณ (จัดหมวด/เขียนเนื้อหา) หรือเจอพฤติกรรมแปลกของ WordPress/Polylang มีโอกาสหยุดที่ "ดูเหมือนใช้ได้" → prompt ในข้อ 10 จึงใส่ **เกณฑ์ผ่าน** ทุกข้อ ห้ามข้าม และให้อ่านข้อ 8 (สิ่งที่เคยพลาด) ก่อนเริ่ม

- หนึ่งแชทต่อหนึ่งงานใหญ่ เริ่มด้วย: "ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web อ่าน docs/HANDOFF.md แล้วทำ <งาน>"
- ตรวจด้วย JS วัดค่า (ขนาด/overflow/การแสดงผล) ก่อน ใช้ screenshot เท่าที่จำเป็น
- จบงานแล้วอัปเดตไฟล์นี้ (ข้อ 5–8) แล้ว push
- Commit message ปิดท้ายด้วยบรรทัด attribution ตามที่ระบบกำหนด

## 10. Prompt เริ่มแชทใหม่ (copy ไปวางได้เลย — 1 งาน = 1 แชท)

**A. เนื้อหา In-house 14 คอร์ส — ทำแล้ว (รอบที่ 4) ไม่ต้องใช้ prompt นี้อีก เก็บไว้เป็นตัวอย่างรูปแบบ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: ดึงเนื้อหาจริงของคอร์ส In-house ที่เหลือ 14 คอร์สจาก https://hrideone.com/courses/<slug>/ (slug ดูใน playground/seed.php) เรียบเรียงใหม่ ไม่คัดลอกยาว ใส่ใน hri_seed_inhouse_courses() ทั้ง TH/EN/JA (ใช้โครง Coaching Mastery) แล้ว seed ตรวจหน้า ข้อมูลไหนไม่แน่ใจให้ flag ใน HANDOFF ข้อ 6

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**B. นำเข้าบทความจากเว็บเดิม — ทำแล้ว (รอบที่ 6) ไม่ต้องใช้ prompt นี้อีก**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: วางแผนและทำเครื่องมือ/ขั้นตอนนำเข้าบทความ ~150 เรื่องจาก hrideone.com (WordPress Export/Import คง slug รวม slug พิมพ์ผิดเดิม) ตรวจรูปปก หมวด Business/Sales/Coaching เปลี่ยน 'ไม่มีหมวดหมู่' เป็นชื่อจริง ทำ pagination /blog/page/N ให้ตรงเดิม และเสนอวิธีแปล EN/JA บางเรื่อง + ชื่อหมวด EN/JA (ถามผมก่อนถ้าต้องเสียเงิน)

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**C. ค้นหาทั้งเว็บ** — ทำแล้วในรอบที่ 10 (เก็บ prompt ไว้อ้างอิง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: ทำค้นหาทั้งเว็บภาษาไทย (หน้าผลลัพธ์ search.html, ค้นทั้งคอร์ส/บทความ/หน้า, synonym map แก้ได้จากหลังบ้าน, ไม่ใช้ปลั๊กอินเสียเงิน) ใช้ Polylang กรองภาษา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**D. SEO / AI-SEO**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: SEO + AI SEO
1. The SEO Framework (ฟรี): title/meta/sitemap/canonical ทั้ง 3 ภาษา · ตรวจ hreflang ของ Polylang ครบทุกคู่ + x-default
2. JSON-LD ทำเองใน hri-core (โมดูลใหม่ inc/schema.php): EducationalOrganization (โลโก้, ที่อยู่/ติดต่อจาก hri_company(), sameAs โซเชียล), WebSite, BreadcrumbList, BlogPosting (headline, datePublished/Modified, image, inLanguage, publisher), Course + hasCourseInstance + offers (ราคา, Early Bird + priceValidUntil จาก meta), Person (วิทยากร), FAQPage จาก Details block (Google ไม่โชว์ rich result FAQ ให้เว็บทั่วไปแล้ว แต่ช่วย AI อ่าน) · ไม่ซ้ำกับ schema ที่ The SEO Framework ใส่เอง
3. robots.txt (virtual, แก้จากหลังบ้านได้): อนุญาต OAI-SearchBot, ChatGPT-User, Claude-SearchBot, PerplexityBot, Bingbot · GPTBot / ClaudeBot / Google-Extended (bot เทรนโมเดล) ให้เป็นตัวเลือกใน Settings ค่าเริ่มต้น "อนุญาต" แล้วจดใน HANDOFF ข้อ 6 ว่าลูกค้าต้องตัดสินใจ
4. /llms.txt (virtual): ชื่อบริษัท สรุปบริการ ลิงก์หน้าสำคัญ 3 ภาษา (หน้าแรก, บริการ 4 หน้า, รวมคอร์ส, บล็อก, ติดต่อ) สร้างจากเนื้อหาจริง ไม่ hard-code
5. IndexNow (ping Bing เมื่อ publish/update) แบบเปิด/ปิดได้ · ปิดไว้ใน local/Playground
6. เนื้อหาเพื่อ AI: เพิ่มใน pattern หน้าคอร์สให้มีย่อหน้าสรุปบนสุด (คืออะไร/เหมาะกับใคร/ได้อะไร) ถ้ายังไม่มี · บทความแสดง "อัปเดตล่าสุด" ถ้าแก้หลังเผยแพร่
เกณฑ์ผ่าน: JSON-LD ทุกแบบหน้าผ่าน schema validator (ตรวจด้วยสคริปต์ใน local ถ้าเข้าเว็บ validator ไม่ได้) · sitemap ครบ 345 บทความ + หน้า + คอร์ส 3 ภาษา · hreflang ครบทุกหน้าที่มีคำแปล · measure.py 0 ปัญหา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**E. ย้ายเนื้อหา + 301**
> หมายเหตุรอบที่ 9: ตอนย้ายต้อง sideload รูปจาก `playground/site-media/` เข้าคลังสื่อ (ปกคอร์ส/วิทยากรใน demo เป็น meta `_hri_thumbnail_url` ให้แปลงเป็น featured image จริง) · URL รูปต้นฉบับของทุกช่องอยู่ใน `site-media.json` (`src`)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: ทำรายการ 301 redirects จากโครงสร้าง URL เว็บเดิม (/public-training/*, /courses/*, หน้าย่อยบริการเช่น /transformation-solutions/business-strategy/ → anchor ของหน้าหลัก, /blog/page/N) เป็นไฟล์/โมดูลใน hri-core ที่แก้ได้จากหลังบ้านแบบไม่ต้องเขียนโค้ด พร้อมเช็คลิสต์ย้ายเว็บ

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**F. แก้ tap target + การ์ดคอร์ส — ทำแล้ว (รอบที่ 5) ไม่ต้องใช้ prompt นี้อีก เก็บไว้เป็นตัวอย่างรูปแบบ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: แก้ปัญหาใน HANDOFF หัวข้อ 'ปัญหานอกขอบเขต': tap target ≥44px ของ header/footer/course filter และชื่อคอร์สตัดกลางคำในการ์ดแคบ ตรวจ 1280/768/375 × TH/EN/JA

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**G. แปลอัตโนมัติ TH → EN/JA** (ตอบ AUTO-TRANSLATE.md ข้อ 3.3 ข้อ 1 ก่อน แล้วแก้บรรทัด "ผู้ให้บริการ" ด้านล่าง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8) และ docs/AUTO-TRANSLATE.md ทั้งไฟล์ แล้วทำงานนี้บน branch feature/auto-translate: ระบบแปลอัตโนมัติ TH → EN/JA ตามสเปก AUTO-TRANSLATE.md ข้อ 4 ทำตามลำดับเฟสในข้อ 5 (ถ้าใหญ่เกินแชทเดียว ทำให้จบเฟสที่ทำได้แล้วปิดงานตามปกติ จดเฟสที่เหลือใน HANDOFF)
ผู้ให้บริการ: <anthropic | openai | deepl> — ทำ adapter ตัวนี้ + fake · ค่าเริ่มต้นอื่นใช้ตามข้อ 3.2

ลำดับ:
1. พิสูจน์ก่อนว่า block editor เก็บ metadata.hriId ไว้หลังเปิด-บันทึก (ทดสอบใน editor จริงใน local) แล้วเลือกวิธีจับคู่ block ตามข้อ 4.2 บอกผลผมสั้นๆ แล้วทำต่อ
2. ทำทีละเฟส ทดสอบด้วย provider fake ตามข้อ 6 ทุกข้อ
3. ห้ามยิง API จริงถ้าผมไม่ได้ให้ key · ถ้าได้ key ใส่ใน wp-config ของ local เท่านั้น ห้าม commit

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความ UI ใหม่แปลได้ TH/EN/JA (gettext ตามวิธีใน HANDOFF ข้อ 4/8) · สี/ขนาดใช้ token เท่านั้น · ไม่เพิ่ม plugin และไม่มี build step · ทีมการตลาดต้องใช้งาน/ตั้งค่าจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo · ห้ามส่งข้อมูล Lead ไป provider
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · ตามข้อ 6 ของ AUTO-TRANSLATE.md · python3 -I scripts/measure.py ต้องผ่านเหมือนเดิม · screenshot เฉพาะกล่อง "การแปล" และหน้า "งานแปล"
ปิดงาน: อัปเดต AUTO-TRANSLATE.md (สถานะ, สิ่งที่ต่างจากสเปก, ข้อ 7 ค่าใช้จ่ายถ้าทดสอบจริง) + HANDOFF (ข้อ 4 สถาปัตยกรรม, ข้อ 5, ข้อ 7) + README · bump Version plugin/theme · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**H. รูปจริงของหน้าอื่นใน demo (ไม่ใช่บล็อก)** — ทำแล้วในรอบที่ 9 (เก็บ prompt ไว้อ้างอิง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8 และรอบที่ 7 ในข้อ 5) แล้วทำงานนี้: ใส่รูปจริงจากเว็บเดิมในหน้าอื่นของ demo (หน้าแรก: hero, โลโก้ลูกค้า 40 + ชื่อบริษัทเป็น alt, รูปผู้บริหาร 2, Gallery · หน้า In-house/Public/Transformation/Japanese Concept: hero + รูปประกอบ + โลโก้พาร์ทเนอร์ 8 · About · รูปคอร์ส thumbnail · รูปวิทยากร)
1. ไล่หน้าเว็บเดิม (https://hrideone.com/ , /in-house/ , /public-training/ , /transformation-solutions/ , /japanese-concept/ , /about/ , /courses/<slug>/) หา URL รูปจริงที่ตรงกับตำแหน่งในเว็บใหม่ ทำตาราง ตำแหน่งในเว็บใหม่ → URL รูปเดิม ไว้ใน playground/data/page-media.json (ดึงเฉพาะรูปที่ใช้จริง)
2. ขยาย scripts/demo-media.py ให้อ่าน page-media.json ด้วย (ย่อ/แปลงแบบเดียวกัน ลง playground/media/) แล้วรัน workflow "Demo media" (gh api .../actions/workflows/demo-media.yml/dispatches) รอจบแล้ว git pull
3. แก้ seed (seed.php / seed-home.php / seed-course-data.php) ให้ใช้รูปจาก jsDelivr ตาม manifest แทนรูปตัวอย่าง (ใน Playground ห้ามดาวน์โหลดรูปเข้าคลังสื่อทีละรูป เพราะช้า ใช้ URL ตรงเหมือนบล็อก หรือหาวิธีที่ไม่ทำให้ Playground บูตช้าเกิน ~2 นาที)
4. รูปไหนหาไม่เจอให้คง placeholder แล้วจดใน HANDOFF ข้อ 6
เกณฑ์ผ่าน: เปิดลิงก์ demo แล้วรูปขึ้นทุกตำแหน่งในตาราง (ตรวจ naturalWidth > 0 ด้วย JS ใน Playground จริงผ่าน built-in browser) · ไม่มี URL hrideone.com เหลือใน seed · measure.py 0 ปัญหา · เวลาบูต Playground จดไว้

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**I. รูปใน WordPress ให้เล็กสุด (ก่อนย้ายเว็บ)** — ทำแล้วในรอบที่ 11 (เก็บ prompt ไว้อ้างอิง)
> หมายเหตุรอบที่ 9: รูปหน้าอื่นอยู่ใน `playground/site-media/` (แปลงแล้ว 1.6 MB) และใน demo เป็น URL ไม่ได้อยู่ในคลังสื่อ — งานนี้ต้องครอบคลุมตอน sideload รูปเหล่านี้เข้าคลังสื่อบนโฮสต์จริงด้วย

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8) แล้วทำงานนี้ใน hri-core/inc/images.php:
1. แปลงไฟล์ต้นฉบับที่อัปโหลด (JPG/PNG) เป็น WebP ตั้งแต่ตอนอัปโหลด แล้วลบไฟล์เดิม (WordPress ปกติเก็บต้นฉบับไว้เสมอ และ image_editor_output_format แปลงแค่ขนาดย่อย) · ถ้า wp_image_editor_supports avif ให้เลือก AVIF ได้ด้วยค่าคงที่ใน wp-config (ค่าเริ่มต้น WebP)
2. ขอบยาวสุดตั้งค่าได้ (HRI_IMAGE_MAX_EDGE ค่าเริ่มต้นเปลี่ยนเป็น 1600) · quality คง 78 ตั้งค่าได้
3. เพิ่มขนาด hri-card 800×533 (crop 3:2) ให้การ์ดคอร์ส/บทความ/บทความที่เกี่ยวข้องใช้ขนาดนี้ · รูปในบทความใช้ large + srcset/sizes ถูกต้อง
4. ทดสอบกับรูปตัวอย่าง + รูปที่มีตัวหนังสือ (เอาจาก playground/media) เทียบ quality 70 กับ 78 ให้ผมดู 2–3 คู่
เกณฑ์ผ่าน: อัปโหลด JPG แล้วใน uploads ไม่มี .jpg เหลือ · ขนาดไฟล์ก่อน/หลังจดใน HANDOFF · การ์ดโหลดรูป ≤ ~80KB · โหมด download ของ Tools → นำเข้าบทความ ยังทำงาน · measure.py 0 ปัญหา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**J. PageSpeed (เป้า Lighthouse mobile ≥90 ทุกหน้า)** — ✅ ทำแล้วรอบที่ 12 (prompt เก็บไว้เป็นประวัติ ถ้าจะทำซ้ำบนโฮสต์จริงให้ใช้ `scripts/perf.py` และข้อ 5 ของ HOSTING.md)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8) แล้วทำงานนี้:
1. ทำ scripts/perf.py: Lighthouse (npm lighthouse + Chromium ที่ /opt/pw-browsers) วัด 6 แบบหน้า (/, /courses/, คอร์สเดี่ยว, /blog/, บทความ, /contact-us/) × mobile/desktop เก็บ JSON + ตารางสรุป · วัด baseline ก่อนแก้ จดใน HANDOFF
2. LCP: รูป hero fetchpriority=high ไม่ lazy · รูปแรกของบทความไม่ lazy · วิดีโอ hero มี poster และโหลดวิดีโอหลัง load / เฉพาะจอ ≥782px / ไม่โหลดเมื่อ Save-Data หรือ prefers-reduced-motion
3. ฟอนต์: preload ≤2 ไฟล์ (Kanit Thai 400 + Latin 400) · ตัดน้ำหนักที่ไม่ใช้ (ตรวจว่าใช้จริงกี่น้ำหนัก) · fallback font ใช้ size-adjust ลด CLS
4. CSS/JS: เช็กว่าโหลด CSS เฉพาะ block ที่ใช้ · minify base.css ใน tokens/build.mjs (ไฟล์ .min แยก ไม่แก้ต้นฉบับ) · JS โหลดเฉพาะหน้าที่ใช้ · ไม่มี jQuery ฝั่งหน้าเว็บ
5. รูปทุกรูปมี width/height · loading=lazy เฉพาะใต้จอแรก · sizes ถูกต้อง
6. Speculative Loading ของ core: ตั้ง prerender eagerness moderate (กดลิงก์แล้วเปิดแทบทันที) ยกเว้น wp-admin/ฟอร์ม
7. สคริปต์ภายนอก: GA4/GTM (ถ้าจะใส่) โหลดหลัง load · Turnstile เฉพาะหน้าที่มีฟอร์ม · ไม่มี YouTube iframe ตอนโหลด (ใช้ภาพก่อนกดเล่น)
8. จดสิ่งที่ต้องทำฝั่งโฮสต์ไว้ใน docs/HOSTING.md (cache header 1 ปี, Brotli, HTTP/3, Rcache, Cloudflare) — ห้ามแตะโฮสต์
เกณฑ์ผ่าน: local ไม่ใช่ตัวแทนความเร็วโฮสต์ จึงวัดคะแนนส่วน LCP/CLS/TBT/น้ำหนักหน้า: mobile ≥90 ทุกหน้า (เป้า 95) · CLS < 0.1 · หน้าแรกไม่รวมวิดีโอ < 500KB · measure.py 0 ปัญหา · ตารางก่อน/หลังใน HANDOFF

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```
