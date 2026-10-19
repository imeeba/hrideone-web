# HANDOFF — สถานะงานและบริบทสำหรับแชท/คนถัดไป

> อ่านไฟล์นี้ก่อนเริ่มงานทุกครั้ง แล้วอัปเดตก่อนจบแต่ละรอบงาน
> อัปเดตล่าสุด: 2026-10-19 (หน้าบริการ: In-house, Public, Transformation, Japanese Concept)

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

- Preview (Playground, ดึงจาก `main` ทุกครั้ง): <https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/imeeba/hrideone-web/main/playground/blueprint.json>
  - เติม `&v=<เลขใหม่>` ถ้าเห็นเวอร์ชันเก่า (cache)
  - เปิดมาที่หน้าแรก `/` · Style Guide `/style-guide/` · รวมคอร์ส `/courses/` · `/in-house/` · `/public-training/` · `/transformation-solutions/` · `/japanese-concept/`
  - ภาษาอื่น: `/en/` `/ja/` (หน้าแรก) · `/en/in-house-en/` · `/ja/public-training-ja/` · `/en/transformation-solutions-en/` · `/ja/japanese-concept-ja/` · `/en/courses/negotiation-mastery-en/`
  - ทดสอบ branch อื่น: เอา blueprint มาแทน `main` เป็นชื่อ branch แล้วเปิดแบบ `https://playground.wordpress.net/#<blueprint JSON ที่ encode แล้ว>`
- Local test site: `scripts/local-setup.sh [dir] [port]` → `http://127.0.0.1:8899/wp/` (admin/admin)
  - WordPress 7.1.3 จาก GitHub + SQLite + WP-CLI, symlink theme/plugin จาก repo
  - ใน cloud workspace เข้า wordpress.org/packagist ไม่ได้ → Polylang ใน local ใช้ source จาก GitHub (`git clone --branch <tag> https://github.com/polylang/polylang` + `composer dump-autoload --no-dev`) ไม่มี flags/JS build ฝั่ง admin แต่ PHP ทำงานครบ แล้ว `wp hri languages` + `wp eval-file playground/seed-languages.php`
- Playground: ใช้ built-in browser ได้ เมนู iframe ซ้อนสองชั้น (`document.querySelector('iframe').contentWindow.document.querySelector('iframe')`) ปุ่ม Back/Forward ใน Playground ทดสอบไม่ได้

## 4. สถาปัตยกรรม

```
tokens/tokens.json  ─┐
tokens/theme.base.json ─┴─ node tokens/build.mjs ─→ wp-content/themes/hri-theme/theme.json  (ห้ามแก้ตรง)
wp-content/themes/hri-theme/   หน้าตาเท่านั้น: templates, parts, patterns, CSS, fonts
wp-content/plugins/hri-core/   ฟีเจอร์ทั้งหมด (ไม่ผูกธีม)
playground/                    blueprint.json, seed.php (ข้อมูลตัวอย่าง), sample/*.jpg
scripts/local-setup.sh         สร้างเว็บทดสอบในเครื่อง
```

**Design tokens** 3 ชั้น: primitive (teal/green/neutral ramp) → semantic 12 slug → component (`custom`: radius, shadow, motion, ratio)
- ❗ `primary` #00B0BD ใช้กับพื้น/กราฟิกเท่านั้น (contrast กับขาวไม่ผ่าน) ตัวอักษร/ลิงก์/ปุ่มใช้ `primary-strong` #0F7070
- palette ล็อกไว้ (ปิด custom color) ทีมการตลาดเลือกได้แค่สีใน token
- ฟอนต์ self-hosted: Kanit (TH/Latin), Montserrat (display), M PLUS 1 (JA, โหลดเฉพาะ locale ja หรือหน้า style guide)

**hri-core modules** (`hri-core.php` โหลดตามลำดับ): `cleanup` (emoji, XML-RPC, comments ปิด) · `images` (ขอบยาวสุด 1920, WebP, quality 78, ตัดขนาดที่ไม่ใช้) · `content-model` · `course-card` · `course-filter` · `languages` · `polylang` · `company` · `blocks`

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

## 6. ข้อมูลที่เป็นของสมมติ (ต้องแทนด้วยของจริง)

- `playground/seed.php`: ชื่อ/slug คอร์สมาจากเว็บจริง แต่ **ระดับผู้เรียน ระยะเวลา รูปแบบ หัวข้อ** และสถานะ "เปิดรับสมัคร" ของ Public เป็นค่าสมมติ เนื้อหาคอร์สเป็นข้อความตัวอย่าง
- หน้าบริการ: เนื้อหาเรียบเรียง/ย่อจากเว็บเดิม (ดึงข้อความเต็มไม่ได้ ใช้สรุปจากหน้าเว็บ) ต้องให้ Jimmi/การตลาดตรวจ · ส่วนที่ผมเขียนเพิ่มเอง (ไม่มีในเว็บเดิม): หัวข้อ/lead ของ hero บางหน้า, chips จุดเด่น, ขั้นตอน In-house 4 ข้อ (สรุปจาก FAQ เดิม), ขั้นตอนสมัคร Public 3 ข้อ, FAQ ของ Public ทั้งหมด (ส่งหลายคน/รุ่นเต็ม), ข้อ "เหมาะกับการส่งพนักงานบางส่วน…", แถบชวนจัด In-house, คำอธิบาย Japanese Working Philosophy และ Cross-cultural Communication (เว็บเดิมไม่มีหน้า), "3 วัน · ประเทศญี่ปุ่น" ของ Part 3 (เว็บเดิมแบ่งเป็น Day 1–3), กล่อง "สัญญาณที่องค์กรควรทบทวนกลยุทธ์"
- หน้าบริการ: รูป hero/รูปประกอบเป็นรูปตัวอย่าง · รูปจริงจากเว็บเดิม: In-house `/wp-content/uploads/2026/09/782.webp`, Public `/wp-content/uploads/2026/09/1920x1080-01-1024x576.webp`, โลโก้พาร์ทเนอร์ `/wp-content/uploads/2026/09/logo-partner-01…08.png` (Transformation) · DO·WA·KEI เว็บเดิมมีวิดีโอใน section Business Outcomes (ยังไม่ได้ใส่)
- ลิงก์ "สมัคร/ขอใบเสนอราคา" ทุกปุ่มไป `/contact-us/` ซึ่งยังไม่มี (รอทำหน้า Contact + ฟอร์ม Lead) · ยังไม่มีปุ่ม "สมัคร" แยกต่อคอร์ส Public
- คำแปล EN/JA ของหน้าบริการเป็น machine translation (ใน `hri-theme/languages/*.po`) — JA ต้องให้คนตรวจ
- รูปคอร์สเป็นภาพนามธรรมที่สร้างเอง (`playground/sample/`)
- ที่อยู่ภาษาไทยใน `hri_company_defaults()` เรียบเรียงเองจากที่อยู่ภาษาอังกฤษ (ชื่อบริษัทคงเป็นอังกฤษ) ต้องให้ Jimmi ตรวจ · ข้อความแถบ CTA มี "(ข้อความตัวอย่าง)" · LinkedIn ยังไม่มี URL (ช่องว่าง = ไม่แสดง)
- คำแปล EN/JA ทั้งหมด (ชื่อ term, excerpt, ข้อความ UI ใน .po, `seed-languages.php`) เป็น machine translation — JA ต้องให้คนญี่ปุ่นตรวจ
- เมนูยังเป็นของเว็บเดิม หน้า Blog, About, Contact ยังไม่มี (ปุ่มชี้ไป `/blog/` `/contact-us/` ซึ่งยัง 404)
- หน้าแรก: หัวข้อ/ข้อความ Hero ("พัฒนาผู้นำ ขับเคลื่อนองค์กรสู่อนาคต") เขียนใหม่ เว็บเดิมไม่มี · ข้อความแนะนำบริษัทและสาส์นผู้บริหารเรียบเรียงจากเว็บเดิม (สาส์นยังไม่ครบทั้งย่อหน้า ดึงต้นฉบับเต็มไม่ได้) ต้องให้ Jimmi/การตลาดตรวจ
- หน้าแรก: บรรทัดเล็กเหนือ "Our Services" ใช้ "What We Do" (Jimmi สั่งให้หัวข้อใน section นี้เป็นอังกฤษล้วน ไม่แปล; คำนี้ผมตั้งเอง รอยืนยัน) · ข้อความ 01–03 ในหลอดไฟมาจากรูป reference ของ Jimmi
- หน้าแรก: โลโก้ลูกค้า 40 ช่อง, รูปผู้บริหาร 2 รูป, รูป Gallery, รูปพื้น Hero เป็น placeholder/รูปตัวอย่าง (workspace โหลดรูปจาก hrideone.com ไม่ได้) · บทความ 3 เรื่องใน seed ใช้ชื่อ/slug จากเว็บเดิม (รวม slug พิมพ์ผิด `why-success-ompanies-…` เพื่อ 301 ทีหลัง) เนื้อหาเป็นข้อความตัวอย่าง

## 7. งานถัดไป (เรียงตามที่เสนอ)

0. **สถานะล่าสุด (19 ต.ค. 2569 บ่าย):** หน้าบริการ 4 หน้าเสร็จ merge เข้า `main` (theme 0.5.0 / plugin 0.4.1) รอ Jimmi ตรวจเนื้อหา ข้อ 6 · งานที่น่าทำต่อ: หน้า Contact + ฟอร์ม Lead (ทุก CTA ชี้ไปที่นั่น), ใส่รูปจริง
0. (ก่อนหน้า) หน้าแรกเสร็จและ merge เข้า `main` แล้ว (theme/plugin 0.4.x; รอบล่าสุด: ลบการ์ดบริการ ย้ายปุ่ม CTA ไปใต้ข้อ 01–03 ของหลอดไฟ, โลโก้มือถือแถวละ 5 = 8 แถว) · Jimmi ยังไม่ได้ระบุว่างานถัดไปคืออะไร → ให้ถามว่าจะเริ่มข้อไหนก่อน (ข้อเสนอที่ยังไม่อนุมัติ: แก้ tap target header/footer ให้ ≥44px ตามหัวข้อ "ปัญหานอกขอบเขต" และสร้างหน้า Transformation Solutions / Japanese Concept ที่ปุ่ม CTA ชี้ไปแต่ยัง 404)
1. **ใส่รูปจริงหน้าแรก** — โลโก้ลูกค้า 40 รายการ (เว็บเดิม `/wp-content/uploads/2026/03/Website-Customer-logo-01…40.webp`), รูปผู้บริหาร, รูป Gallery, รูป/วิดีโอ Hero (ทำในหน้าแรกแต่ละภาษา หรือให้ Jimmi แนบไฟล์มา)
   - หน้า Contact / About / Blog / Transformation / Japanese Concept ยังไม่มี: CTA, เมนู และปุ่มหน้าแรกชี้ไปหน้าที่ยัง 404
2. **ค้นหาทั้งเว็บ** (ภาษาไทย, synonym map)
3. **ฟอร์ม Lead → Google Sheet** — REST endpoint ใน hri-core + Google Apps Script, honeypot/Turnstile, checkbox PDPA, ห้ามใช้ nonce ในหน้าที่ถูก cache
4. **AI แปลภาษา** — ต้องใช้ API key (จ่ายตามใช้) ต้องบอก Jimmi เรื่องค่าใช้จ่ายก่อน
5. **SEO / AI-SEO** — The SEO Framework (ฟรี) + schema เอง (EducationalOrganization, Course/CourseInstance, FAQPage, Article, BreadcrumbList), hreflang (Polylang ใส่ให้แล้ว ตรวจกับ SEO plugin), robots/llms.txt
6. **ย้ายเนื้อหา + 301 redirects** จากเว็บเดิม (`/public-training/*`, `/courses/*`)
7. **คำถามที่ต้องถาม Ruk-com**: เวอร์ชัน PHP, Imagick/WebP, SSH/WP-CLI, Rcache กับ query string (สำคัญกับ filter), memory limit, outbound HTTPS ไป Google
8. Cloud staging (เช่น `new.hrideone.com`) ทีหลัง
9. (ถาม Jimmi) Polylang Pro ถ้าอยากให้ slug ภาษาต่างๆ เหมือนกัน (`/en/in-house/`) — เสียเงิน

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
- Hook ทดสอบหลังแก้: `python3 measure.py` (overflow/tap/contrast/PHP/JS ที่ 1280/768/375 × `/`,`/en/`,`/ja/`) — สคริปต์อยู่ใน scratchpad ของแชทเก่า ไม่อยู่ใน repo ให้เขียนใหม่ด้วย Playwright (Chromium อยู่ที่ /opt/pw-browsers)
- Production/Ruk-com: พิจารณา `define( 'PLL_COOKIE', false );` ใน wp-config เพื่อไม่ให้ cookie ภาษาไปรบกวน Rcache (ภาษาอยู่ใน URL แล้ว)

### ปัญหาที่เจอนอกขอบเขต (ยังไม่แก้)

- Header desktop: ลิงก์เมนูสูง 25px, ปุ่มภาษา (summary) สูง 34px, รายการใน dropdown ภาษา 43px · ปุ่มแฮมเบอร์เกอร์ (<1100px) 24×24 — ต่ำกว่าเกณฑ์ 44px (ผ่าน WCAG 2.5.8 AA 24px แต่ไม่ผ่าน 44px ที่ตั้งไว้)
- Footer: ลิงก์เมนูสูง 43px ที่จอ 375 (ปัดเศษ) และคำสั้น (Blog/About) กว้าง <44px
- Course filter (`hri/course-filter`): label checkbox สูง 34–35px, ปุ่ม "ตัวกรอง" บนมือถือ 43px — ต่ำกว่า 44px
- การ์ดคอร์ส: ชื่อคอร์สยาวตัดกลางคำ ("Transformatio-n Mastery") ในการ์ดแคบ 3 คอลัมน์ เพราะ `overflow-wrap` ของ `[lang|="th"]` — ควรใช้ `hyphens`/ลดขนาดหัวข้อในการ์ด

## 9. วิธีทำงานที่ประหยัด usage

- หนึ่งแชทต่อหนึ่งงานใหญ่ เริ่มด้วย: "ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web อ่าน docs/HANDOFF.md แล้วทำ <งาน>"
- ตรวจด้วย JS วัดค่า (ขนาด/overflow/การแสดงผล) ก่อน ใช้ screenshot เท่าที่จำเป็น
- จบงานแล้วอัปเดตไฟล์นี้ (ข้อ 5–8) แล้ว push
- Commit message ปิดท้ายด้วยบรรทัด attribution ตามที่ระบบกำหนด

## 10. Prompt เริ่มแชทใหม่ (copy ไปวางได้เลย)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md ให้ครบก่อน (ข้อ 2 ข้อกำหนด, ข้อ 4 สถาปัตยกรรม, ข้อ 7–8 งานถัดไป/ข้อควรระวัง) แล้วทำงานนี้: <ใส่งาน>

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน HANDOFF แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub, Playground ด้วย branch + blueprint · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดต HANDOFF ข้อ 4–8 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, ลิงก์ preview, อะไรให้ผมตรวจ/ตัดสินใจ)
```
