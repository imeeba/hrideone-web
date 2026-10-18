# HANDOFF — สถานะงานและบริบทสำหรับแชท/คนถัดไป

> อ่านไฟล์นี้ก่อนเริ่มงานทุกครั้ง แล้วอัปเดตก่อนจบแต่ละรอบงาน
> อัปเดตล่าสุด: 2026-10-18 (Footer)

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
  - เปิดมาที่ `/in-house/` · หน้าแรก = Style Guide (`/style-guide/`) · รวมคอร์ส `/courses/` · `/public-training/`
  - ภาษาอื่น: `/en/` `/ja/` · `/en/in-house-en/` · `/ja/public-training-ja/` · `/en/courses/negotiation-mastery-en/`
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

**Header** (`parts/header.html`): ชื่อเว็บ · navigation 7 รายการ (+ language-switcher list อยู่ใน nav) · language-switcher dropdown
- CSS ใน `base.css` บังคับเมนูแฮมเบอร์เกอร์ที่ <1100px (core ทำแค่ <600px)

## 5. เสร็จแล้ว

- [x] Design tokens + Style Guide (Jimmi รับแล้ว)
- [x] Content model คอร์ส + การ์ด + หน้าคอร์สเดี่ยว
- [x] Course filter (ทดสอบ desktop/mobile, no-JS, พารามิเตอร์แปลก, XSS ไม่สะท้อน)
- [x] หน้า `/in-house/` และ `/public-training/` (block เดียวกัน ล็อกประเภท)
- [x] Thumbnail 3:2 + รูปตัวอย่าง
- [x] Header ใหม่ + ปุ่มภาษา (ตรวจที่ 1280/1100/1024/768/375 ผ่านการจำลองจอ)
- [x] Footer ใหม่ + โลโก้ SVG 5 แบบ + หน้าข้อมูลบริษัท + แถบ CTA (ทดสอบ 1280/768/375, TH/EN/JA ใน Playground)
- [x] Polylang TH/EN/JA: ปุ่มภาษาสลับได้จริง (dropdown desktop + list ในเมนู mobile ทดสอบใน Playground ด้วย Polylang จาก wordpress.org), คอร์ส/หน้าแปลได้, ชื่อ term EN/JA, ตัวกรองนับต่อภาษา, ข้อความ UI EN/JA, ตัวอย่างคำแปลครบทุกคอร์ส

## 6. ข้อมูลที่เป็นของสมมติ (ต้องแทนด้วยของจริง)

- `playground/seed.php`: ชื่อ/slug คอร์สมาจากเว็บจริง แต่ **ระดับผู้เรียน ระยะเวลา รูปแบบ หัวข้อ** และสถานะ "เปิดรับสมัคร" ของ Public เป็นค่าสมมติ เนื้อหาคอร์สเป็นข้อความตัวอย่าง
- ข้อความแนะนำหน้า In-house / Public Training มีวงเล็บ "(ข้อความตัวอย่าง)"
- รูปคอร์สเป็นภาพนามธรรมที่สร้างเอง (`playground/sample/`)
- ที่อยู่ภาษาไทยใน `hri_company_defaults()` เรียบเรียงเองจากที่อยู่ภาษาอังกฤษ (ชื่อบริษัทคงเป็นอังกฤษ) ต้องให้ Jimmi ตรวจ · ข้อความแถบ CTA มี "(ข้อความตัวอย่าง)" · LinkedIn ยังไม่มี URL (ช่องว่าง = ไม่แสดง)
- คำแปล EN/JA ทั้งหมด (ชื่อ term, excerpt, ข้อความ UI ใน .po, `seed-languages.php`) เป็น machine translation — JA ต้องให้คนญี่ปุ่นตรวจ
- เมนูยังเป็นของเว็บเดิม หน้า Transformation, Japanese Concept, Blog, About, Contact ยังไม่มี

## 7. งานถัดไป (เรียงตามที่เสนอ)

1. **หน้าแรก** — patterns: Hero, Stats, Logo wall, Knowledge/บทความ (ข้อความต้องแปลได้: ใช้ block ที่อ่าน `hri_company()`/gettext หรือ Polylang strings) · พิจารณาเปลี่ยนชื่อเว็บใน header เป็น `hri/logo` (ต้องถาม Jimmi เพราะ header รับไปแล้ว) · ตั้ง Site Icon จาก `mark.svg`
   - หน้า Contact / About ยังไม่มี: CTA และเมนูชี้ไป `/contact-us/` `/about/` ซึ่งยัง 404
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
- Production/Ruk-com: พิจารณา `define( 'PLL_COOKIE', false );` ใน wp-config เพื่อไม่ให้ cookie ภาษาไปรบกวน Rcache (ภาษาอยู่ใน URL แล้ว)

## 9. วิธีทำงานที่ประหยัด usage

- หนึ่งแชทต่อหนึ่งงานใหญ่ เริ่มด้วย: "ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web อ่าน docs/HANDOFF.md แล้วทำ <งาน>"
- ตรวจด้วย JS วัดค่า (ขนาด/overflow/การแสดงผล) ก่อน ใช้ screenshot เท่าที่จำเป็น
- จบงานแล้วอัปเดตไฟล์นี้ (ข้อ 5–8) แล้ว push
- Commit message ปิดท้ายด้วยบรรทัด attribution ตามที่ระบบกำหนด
