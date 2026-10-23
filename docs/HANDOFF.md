# HANDOFF — สถานะงานและบริบทสำหรับแชท/คนถัดไป

> อ่านไฟล์นี้ก่อนเริ่มงานทุกครั้ง แล้วอัปเดตก่อนจบแต่ละรอบงาน
> อัปเดตล่าสุด: 2026-10-23 (รอบที่ 7: demo ใช้รูปบทความจาก repo (WebP) + ลิงก์ demo สั้น + แผนรูป/PageSpeed/AI SEO/โฮสต์) · theme 0.9.0 / plugin 0.8.0
>
> **เริ่มแชทใหม่**: ใช้ประโยคในข้อ 9 + prompt ในข้อ 10 · สถานะสั้นๆ: เสร็จแล้ว = design tokens/Style Guide, header/footer, หน้าแรก, หน้าบริการ 4 หน้า, About, Contact + ฟอร์ม Lead, คอร์ส 21 + ตัวกรอง, 3 ภาษา (Polylang), บล็อกจริง 115 เรื่อง × 3 ภาษา + รูปใน repo, demo บน Playground · **ลำดับงานที่เหลือสำหรับ demo**: H (รูปจริงหน้าอื่น) → C (ค้นหา) → I (รูปให้เล็กสุด) → J (PageSpeed) → D (SEO + AI SEO) → G (แปลอัตโนมัติ, ต้องตอบ AUTO-TRANSLATE.md 3.3 ก่อน) · หลัง demo เสร็จ: โฮสต์ ([`HOSTING.md`](HOSTING.md)) → E (ย้ายเว็บ + 301)
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
| ปุ่มภาษา | Desktop ≥1100px = dropdown มุมขวา header · <1100px = แถวปุ่มในเมนูแฮมเบอร์เกอร์ · แสดงเป็นตัวอักษร (TH/EN/日本語) ไม่ใช้ธง |
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

**hri-core modules** (`hri-core.php` โหลดตามลำดับ): `cleanup` (emoji, XML-RPC, comments ปิด) · `images` (ขอบยาวสุด 1920, WebP, quality 78, ตัดขนาดที่ไม่ใช้) · `content-model` · `course-card` · `course-filter` · `languages` · `polylang` · `company` · `leads` · `blog` · `blog-import` · `blocks`

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

**ข้อมูลบริษัท** (`inc/company.php`) — Settings → ข้อมูลบริษัท (option `hri_company`): เบอร์, อีเมล, LINE/Facebook/YouTube/LinkedIn URL, และแยกภาษา TH/EN/JA: ที่อยู่, tagline ใต้โลโก้, หัวข้อ/ข้อความแถบ CTA · อ่านด้วย `hri_company( 'phone' )` (ฟิลด์แยกภาษาคืนภาษาปัจจุบัน ว่าง = ใช้ไทย) · ค่าเริ่มต้นใน `hri_company_defaults()` (จากเว็บเดิม) · ช่องลิงก์ว่าง = ไม่แสดง · `hri_contact_url()` / `hri_is_contact_page()`

**Blocks ของ footer** (server-rendered): `hri/logo` (`variant: color|white|black|mark|mark-white`, `width`, `link` → หน้าแรกของภาษา) · `hri/company-text` (`field`) · `hri/contact-info` (`items`) · `hri/social-links` (ไอคอน FB/YT/LinkedIn จาก core, LINE จาก Simple Icons CC0) · `hri/copyright` (ปีอัตโนมัติ) · `hri/cta-band` (ขอใบเสนอราคา → Contact + แอด LINE; ไม่แสดงในหน้า Contact ทุกภาษา) · CSS ใน `hri-core/assets/company.css`

**Logo** — `hri-theme/assets/images/logo/`: `logo.svg` (สี, พื้นสว่าง), `logo-white.svg` (พื้นเข้ม/รูป), `logo-black.svg`, `mark.svg`, `mark-white.svg` · trace จากไฟล์ JPG ที่ Jimmi ส่งมา (สี #097A76 / #13B4BC / #0B6E73, ตัวอักษร #272322) · แสดงใน Style Guide หัวข้อ 00 · ถ้าได้ไฟล์ต้นฉบับ vector (AI/EPS/PDF) ควรแทนที่

**Footer** (`parts/footer.html`): แถบ CTA → 4 คอลัมน์ (แบรนด์: โลโก้ขาว+tagline+โซเชียล · หลักสูตรและบริการ · บริษัท · ติดต่อเรา) → แถบล่าง © ปี · 4/2/1 คอลัมน์ที่ ≥1100 / ≥600 / มือถือ (CSS `base.css` ส่วน Footer) · ไม่แสดงเลขผู้เสียภาษี, ไม่มี Privacy Policy, ไม่มีแผนที่ (Jimmi ตัดสินแล้ว) · contrast วัดแล้ว: หัวข้อ accent 6.6:1, ข้อความ 12:1, ปุ่ม 5.9:1
- ข้อความที่พิมพ์ใน header/footer (label เมนู, heading, paragraph, button) = Polylang strings กลุ่ม "HRI header & footer" แปลที่ Languages → Translations (`hri_part_strings()` ใน `polylang.php` แทนที่ตอน render) ทีมการตลาดแก้ข้อความไทยใน Site Editor ได้ แต่แก้แล้วต้องแปลใหม่ (ข้อความเปลี่ยน = string ใหม่)
- navigation ที่ไม่มีชื่อได้ aria-label "เมนูหลัก" / "เมนูท้ายเว็บ" (`functions.php`)

**Header** (`parts/header.html`): โลโก้ `hri/logo` (สี, กว้าง 196) · navigation 7 รายการ (+ language-switcher list อยู่ใน nav) · language-switcher dropdown
- CSS ใน `base.css` บังคับเมนูแฮมเบอร์เกอร์ที่ <1100px (core ทำแค่ <600px)

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
- หน้าแรก: โลโก้ลูกค้า 40 ช่อง, รูปผู้บริหาร 2 รูป, รูป Gallery, รูปพื้น Hero เป็น placeholder/รูปตัวอย่าง (workspace โหลดรูปจาก hrideone.com ไม่ได้) 

### ข้อมูลที่ต้องตัดสินใจ/ยืนยัน (จากรอบที่ 3) — ค่าชั่วคราวอยู่ใน seed ย้อนกลับได้
- **ข้อมูลรุ่น/วันที่/ราคา/ลิงก์ Google Form ของ Public 5 คอร์ส** มาจากเว็บเดิม ณ 19 ต.ค. 2569 ต้องให้ทีมงานยืนยัน: New Manager "รุ่น 21 หรือ 22" (ใช้ 22 ตามเมนูเว็บเดิม) และ "7 สัปดาห์ หรือ 7 ครั้งใน 8 สัปดาห์" (ใช้วันที่ 5 ครั้ง/ตัวอย่าง ต้องตรวจ); Lean "รุ่น 2 หรือ 3" (ใช้ 3) และวันที่เก่าในเนื้อหาเว็บเดิม; Early Bird ของ Negotiation / Next Director / New Manager เลยกำหนดแล้ว (ซ่อนอัตโนมัติ), Oxygen ยังใช้ได้
- **รีวิว**: ใส่เฉพาะ Negotiation (3 รายการ เรียบเรียงใหม่ ต้องยืนยันกับชื่อจริง) · Oxygen เว็บเดิมคัดลอกรีวิวของ Negotiation มา → **ไม่ใส่**
- Next Director: Pain point ข้อ 3 เนื้อหาเว็บเดิมไม่ตรงหัวข้อ → เขียนใหม่ ต้องให้การตลาดตรวจ
- LINE OA: `@hrideone` vs `@deoneacademy` (เว็บเดิมมีทั้งสอง) · เบอร์โทรหลายรูปแบบ · สะกดชื่อ ดร.ชัยพัชร์ (Chaipat Lertlucktaweekul)
- หน้าย่อยเว็บเดิมรวมเป็นหน้าเดียวแล้ว → ต้อง 301 ตอนย้ายเว็บ · DO·WA·KEI ยังเป็น section (ถ้าจะเป็นคอร์สแยกต้องถาม)
- Team Simulation / Japanese Working Philosophy / Cross-cultural Communication: ข้อความสั้นที่ผมเขียนเอง (มีป้ายตัวอย่าง)
- About: พันธกิจ/วิสัยทัศน์/สาส์น/ไทม์ไลน์ เรียบเรียงจาก CONTENT-GAP ไม่ใช่ข้อความเต็มต้นฉบับ · ปีในไทม์ไลน์ยกเว้น 1993 ยังไม่ระบุ · Social Impact เป็น placeholder
- คำแปล EN/JA ทั้งหมดเป็น machine translation — JA ต้องให้คนญี่ปุ่นตรวจ

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

0. **งานที่ Jimmi ต้องทำเอง**: (ก) สร้าง Google Sheet + Apps Script + ใส่ค่าใน wp-config ตาม `LEAD-SETUP.md`; (ข) สร้าง Turnstile (ฟรี) แล้วใส่ key; (ค) ตอบคำถามในหัวข้อ "ข้อมูลที่ต้องตัดสินใจ" ข้อ 6; (ง) ส่งไฟล์รูป/วิดีโอจริง (บางอย่างโหลดจาก hrideone.com จาก workspace ไม่ได้); (จ) ตอบคำถามแปลอัตโนมัติใน [`AUTO-TRANSLATE.md`](AUTO-TRANSLATE.md) ข้อ 3.3 (อย่างน้อยผู้ให้บริการ) ก่อนเริ่มงาน G; (ช) ถาม Ruk-com ตาม `HOSTING.md` ข้อ 2 เมื่อพร้อมขึ้นโฮสต์
1. ~~เนื้อหา In-house อีก 14 คอร์ส~~ — **ทำแล้วในรอบที่ 4** (ครบ 15 คอร์ส) เหลือให้การตลาดตรวจ/เติมตามหัวข้อ "ข้อมูลที่ต้องตรวจ (จากรอบที่ 4)" ในข้อ 6 · ส่วนที่เว็บเดิมไม่มีและควรเติมเพื่อ convert: outline, กลุ่มเป้าหมาย, ระยะเวลา, วิทยากร, รีวิว ของแต่ละคอร์ส (แก้ใน editor ได้ ใช้ pattern "Course page — In-house")
2. **รูป/วิดีโอจริงของหน้าอื่น (ไม่ใช่บล็อก)** → prompt H · โลโก้ลูกค้า 40, รูปผู้บริหาร 2, Gallery, รูป hero หน้าบริการ, โลโก้พาร์ทเนอร์ 8, รูปคอร์ส (thumbnail เว็บเดิม `thumbnail-IH-03.webp` ฯลฯ), รูปวิทยากร, วิดีโอแนวตั้งใน Outcomes · **ไฟล์ export มีแต่รูปของบทความ** (1,578 attachment ผูกกับโพสต์ทั้งหมด) → ต้องหา URL รูปจากหน้าเว็บเดิมทีละหน้า แล้วใส่ใน list ให้ `demo-media.py`/workflow ดึง (ขยายสคริปต์ให้รับ list) · ดึงเฉพาะรูปที่หน้าใหม่ใช้จริง
2.1 **รูปใน WordPress ให้เล็กสุด** → prompt I (แปลงต้นฉบับเป็น WebP/AVIF ตอนอัปโหลด, ขนาด `hri-card`, ขอบยาวสุด 1600)
2.2 **PageSpeed** → prompt J (เป้า Lighthouse mobile ≥90 ทุกหน้า) · วัดจริงต้องรอโฮสต์ แต่ทำ/วัดใน local ได้ก่อน
3. ~~นำเข้าบทความ~~ — **ทำแล้วในรอบที่ 6** (เว็บเดิมมี 115 เรื่อง ไม่ใช่ ~150 และมี EN/JA ครบอยู่แล้ว) · ตอนย้ายจริง: export ใหม่จากเว็บเดิม → `scripts/wxr-to-blog.py` → Tools → นำเข้าบทความ แบบ "คัดลอกรูปเข้าคลังสื่อ" บน staging
4. **ค้นหาทั้งเว็บ** (ภาษาไทย, synonym map)
5. **SEO / AI-SEO** → prompt D (ขยายแล้ว) — The SEO Framework (ฟรี) + schema เอง (EducationalOrganization, Course/CourseInstance + offers/Early Bird, FAQPage, BlogPosting, BreadcrumbList, Person สำหรับวิทยากร), hreflang, robots.txt (AI crawlers), llms.txt, Bing/IndexNow
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

### ปัญหาที่เจอนอกขอบเขต (ยังไม่แก้)

> tap target ของ header/footer/ตัวกรอง/หน้าคอร์ส/ฟอร์ม และชื่อคอร์สตัดกลางคำ แก้แล้วในรอบที่ 5 (ข้อ 5)

- **ตัวกรองคอร์สบน desktop สูงกว่าหน้าจอ** (`/courses/` ที่ 1280: ~1390px, หลังเพิ่ม tap target 44px; ก่อนหน้า ~1150px ก็สูงกว่า viewport 900px อยู่แล้ว) แต่ `.hri-filter` เป็น `position: sticky` → ส่วนล่างของตัวกรอง (ระยะเวลา, รูปแบบ) เข้าถึงไม่ได้จนกว่าจะเลื่อนไปท้ายรายการคอร์ส · ข้อเสนอ: `max-height: calc(100vh - 2 * <top>)` + `overflow-y: auto` ที่ `.hri-filter` (ระวัง box-shadow ring ที่ `.hri-filter` ถูกตัด) หรือเลิก sticky
- `/style-guide/` ป้าย `#767C82` 4.47:1 (ต้อง 4.5) (Blog UI แก้แล้วในรอบที่ 6)
- ลิงก์ที่อยู่เดี่ยวๆ ในย่อหน้าของเนื้อหาบทความ (เช่น "ดูรายละเอียดหลักสูตร TEN X" ใน `why-success-ompanies-fail-in-15-years` ทั้ง 3 ภาษา) สูง 24px: ผ่าน WCAG 2.5.8 AA แต่ไม่ถึง 44px · ไม่ได้แก้ CSS เพราะ selector แยกลิงก์เดี่ยวกับลิงก์กลางประโยคไม่ได้แม่น → ทางเลือก: ให้การตลาดเปลี่ยนเป็น Button block ในบทความที่เป็น CTA
- ลิงก์ inline ในย่อหน้า PDPA ของฟอร์ม (`hri/lead-form`) สูง ~24px **คงไว้ตั้งใจ**: WCAG 2.5.8 ยกเว้นลิงก์ที่อยู่กลางประโยค และถ้าเพิ่ม padding ระยะบรรทัดจะห่างผิดปกติ (ผ่านเกณฑ์ AA 24px ที่ตั้งไว้ ไม่ถึง 44px) · `measure.py` ข้ามลิงก์ประเภทนี้
- การ์ดบริการ/หัวข้อ (`.hri-card__title` ของ theme) ยังไม่ได้ตรวจเรื่องคำยาวตัดกลางคำ (รอบที่ 5 แก้เฉพาะการ์ดคอร์ส)

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

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**B. นำเข้าบทความจากเว็บเดิม — ทำแล้ว (รอบที่ 6) ไม่ต้องใช้ prompt นี้อีก**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: วางแผนและทำเครื่องมือ/ขั้นตอนนำเข้าบทความ ~150 เรื่องจาก hrideone.com (WordPress Export/Import คง slug รวม slug พิมพ์ผิดเดิม) ตรวจรูปปก หมวด Business/Sales/Coaching เปลี่ยน 'ไม่มีหมวดหมู่' เป็นชื่อจริง ทำ pagination /blog/page/N ให้ตรงเดิม และเสนอวิธีแปล EN/JA บางเรื่อง + ชื่อหมวด EN/JA (ถามผมก่อนถ้าต้องเสียเงิน)

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**C. ค้นหาทั้งเว็บ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: ทำค้นหาทั้งเว็บภาษาไทย (หน้าผลลัพธ์ search.html, ค้นทั้งคอร์ส/บทความ/หน้า, synonym map แก้ได้จากหลังบ้าน, ไม่ใช้ปลั๊กอินเสียเงิน) ใช้ Polylang กรองภาษา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
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

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**E. ย้ายเนื้อหา + 301**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: ทำรายการ 301 redirects จากโครงสร้าง URL เว็บเดิม (/public-training/*, /courses/*, หน้าย่อยบริการเช่น /transformation-solutions/business-strategy/ → anchor ของหน้าหลัก, /blog/page/N) เป็นไฟล์/โมดูลใน hri-core ที่แก้ได้จากหลังบ้านแบบไม่ต้องเขียนโค้ด พร้อมเช็คลิสต์ย้ายเว็บ

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**F. แก้ tap target + การ์ดคอร์ส — ทำแล้ว (รอบที่ 5) ไม่ต้องใช้ prompt นี้อีก เก็บไว้เป็นตัวอย่างรูปแบบ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 6–8) แล้วทำงานนี้: แก้ปัญหาใน HANDOFF หัวข้อ 'ปัญหานอกขอบเขต': tap target ≥44px ของ header/footer/course filter และชื่อคอร์สตัดกลางคำในการ์ดแคบ ตรวจ 1280/768/375 × TH/EN/JA

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
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

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความ UI ใหม่แปลได้ TH/EN/JA (gettext ตามวิธีใน HANDOFF ข้อ 4/8) · สี/ขนาดใช้ token เท่านั้น · ไม่เพิ่ม plugin และไม่มี build step · ทีมการตลาดต้องใช้งาน/ตั้งค่าจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo · ห้ามส่งข้อมูล Lead ไป provider
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · ตามข้อ 6 ของ AUTO-TRANSLATE.md · python3 -I scripts/measure.py ต้องผ่านเหมือนเดิม · screenshot เฉพาะกล่อง "การแปล" และหน้า "งานแปล"
ปิดงาน: อัปเดต AUTO-TRANSLATE.md (สถานะ, สิ่งที่ต่างจากสเปก, ข้อ 7 ค่าใช้จ่ายถ้าทดสอบจริง) + HANDOFF (ข้อ 4 สถาปัตยกรรม, ข้อ 5, ข้อ 7) + README · bump Version plugin/theme · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**H. รูปจริงของหน้าอื่นใน demo (ไม่ใช่บล็อก)**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8 และรอบที่ 7 ในข้อ 5) แล้วทำงานนี้: ใส่รูปจริงจากเว็บเดิมในหน้าอื่นของ demo (หน้าแรก: hero, โลโก้ลูกค้า 40 + ชื่อบริษัทเป็น alt, รูปผู้บริหาร 2, Gallery · หน้า In-house/Public/Transformation/Japanese Concept: hero + รูปประกอบ + โลโก้พาร์ทเนอร์ 8 · About · รูปคอร์ส thumbnail · รูปวิทยากร)
1. ไล่หน้าเว็บเดิม (https://hrideone.com/ , /in-house/ , /public-training/ , /transformation-solutions/ , /japanese-concept/ , /about/ , /courses/<slug>/) หา URL รูปจริงที่ตรงกับตำแหน่งในเว็บใหม่ ทำตาราง ตำแหน่งในเว็บใหม่ → URL รูปเดิม ไว้ใน playground/data/page-media.json (ดึงเฉพาะรูปที่ใช้จริง)
2. ขยาย scripts/demo-media.py ให้อ่าน page-media.json ด้วย (ย่อ/แปลงแบบเดียวกัน ลง playground/media/) แล้วรัน workflow "Demo media" (gh api .../actions/workflows/demo-media.yml/dispatches) รอจบแล้ว git pull
3. แก้ seed (seed.php / seed-home.php / seed-course-data.php) ให้ใช้รูปจาก jsDelivr ตาม manifest แทนรูปตัวอย่าง (ใน Playground ห้ามดาวน์โหลดรูปเข้าคลังสื่อทีละรูป เพราะช้า ใช้ URL ตรงเหมือนบล็อก หรือหาวิธีที่ไม่ทำให้ Playground บูตช้าเกิน ~2 นาที)
4. รูปไหนหาไม่เจอให้คง placeholder แล้วจดใน HANDOFF ข้อ 6
เกณฑ์ผ่าน: เปิดลิงก์ demo แล้วรูปขึ้นทุกตำแหน่งในตาราง (ตรวจ naturalWidth > 0 ด้วย JS ใน Playground จริงผ่าน built-in browser) · ไม่มี URL hrideone.com เหลือใน seed · measure.py 0 ปัญหา · เวลาบูต Playground จดไว้

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**I. รูปใน WordPress ให้เล็กสุด (ก่อนย้ายเว็บ)**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2, 4, 6–8) แล้วทำงานนี้ใน hri-core/inc/images.php:
1. แปลงไฟล์ต้นฉบับที่อัปโหลด (JPG/PNG) เป็น WebP ตั้งแต่ตอนอัปโหลด แล้วลบไฟล์เดิม (WordPress ปกติเก็บต้นฉบับไว้เสมอ และ image_editor_output_format แปลงแค่ขนาดย่อย) · ถ้า wp_image_editor_supports avif ให้เลือก AVIF ได้ด้วยค่าคงที่ใน wp-config (ค่าเริ่มต้น WebP)
2. ขอบยาวสุดตั้งค่าได้ (HRI_IMAGE_MAX_EDGE ค่าเริ่มต้นเปลี่ยนเป็น 1600) · quality คง 78 ตั้งค่าได้
3. เพิ่มขนาด hri-card 800×533 (crop 3:2) ให้การ์ดคอร์ส/บทความ/บทความที่เกี่ยวข้องใช้ขนาดนี้ · รูปในบทความใช้ large + srcset/sizes ถูกต้อง
4. ทดสอบกับรูปตัวอย่าง + รูปที่มีตัวหนังสือ (เอาจาก playground/media) เทียบ quality 70 กับ 78 ให้ผมดู 2–3 คู่
เกณฑ์ผ่าน: อัปโหลด JPG แล้วใน uploads ไม่มี .jpg เหลือ · ขนาดไฟล์ก่อน/หลังจดใน HANDOFF · การ์ดโหลดรูป ≤ ~80KB · โหมด download ของ Tools → นำเข้าบทความ ยังทำงาน · measure.py 0 ปัญหา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**J. PageSpeed (เป้า Lighthouse mobile ≥90 ทุกหน้า)**

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

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```
