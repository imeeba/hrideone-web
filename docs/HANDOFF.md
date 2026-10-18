# HANDOFF — สถานะงานและบริบทสำหรับแชท/คนถัดไป

> อ่านไฟล์นี้ก่อนเริ่มงานทุกครั้ง แล้วอัปเดตก่อนจบแต่ละรอบงาน
> อัปเดตล่าสุด: 2026-10-18

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
- Local test site: `scripts/local-setup.sh [dir] [port]` → `http://127.0.0.1:8899/wp/` (admin/admin)
  - WordPress 7.1.3 จาก GitHub + SQLite + WP-CLI, symlink theme/plugin จาก repo
  - ใน cloud workspace เข้า wordpress.org ไม่ได้ → ติดตั้งปลั๊กอินจาก wordpress.org ใน local ไม่ได้ (ต้องทดสอบใน Playground)
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

**hri-core modules** (`hri-core.php` โหลดตามลำดับ): `cleanup` (emoji, XML-RPC, comments ปิด) · `images` (ขอบยาวสุด 1920, WebP, quality 78, ตัดขนาดที่ไม่ใช้) · `content-model` · `course-card` · `course-filter` · `languages` · `blocks`

**Content model**
- CPT `course` (archive `/courses/`)
- Taxonomies (ไม่มีหน้า public): `hri_program` (inhouse/public), `hri_pillar`, `hri_topic` (hierarchical), `hri_level`, `hri_format`
- Meta (meta box คลาสสิก): `hri_duration_days`, `hri_audience`, และเฉพาะ Public: `hri_batch_no`, `hri_reg_status` (open|full|closed), `hri_start_date`, `hri_end_date`, `hri_location`, `hri_price`

**Blocks** (server-rendered, `block.json` + `render.php`, `autoRegister`, ไม่มี build step)
- `hri/course-filter` — ค้นหา + checkbox filter; state อยู่ใน URL (`program[] pillar[] topic[] level[] format[] duration[] q pg`), AND ข้ามกลุ่ม OR ในกลุ่ม; ทำงานได้โดยไม่มี JS, `view.js` แค่ fetch แล้วสลับ `[data-hri-results]`; attribute `program` ล็อกประเภท (ซ่อนกลุ่มประเภท, นับเฉพาะประเภทนั้น); ระยะเวลาแสดงเฉพาะช่วงที่มีคอร์ส; 12 ต่อหน้า
- `hri/course-facts` — ตารางข้อมูลในหน้าคอร์สเดี่ยว
- `hri/language-switcher` — `variant: dropdown|list`; ใช้ `pll_the_languages()` ถ้ามี Polylang ไม่งั้น fallback แสดง TH เป็นปัจจุบัน และ EN/JA จาง กดไม่ได้

**Header** (`parts/header.html`): ชื่อเว็บ · navigation 7 รายการ (+ language-switcher list อยู่ใน nav) · language-switcher dropdown
- CSS ใน `base.css` บังคับเมนูแฮมเบอร์เกอร์ที่ <1100px (core ทำแค่ <600px)

## 5. เสร็จแล้ว

- [x] Design tokens + Style Guide (Jimmi รับแล้ว)
- [x] Content model คอร์ส + การ์ด + หน้าคอร์สเดี่ยว
- [x] Course filter (ทดสอบ desktop/mobile, no-JS, พารามิเตอร์แปลก, XSS ไม่สะท้อน)
- [x] หน้า `/in-house/` และ `/public-training/` (block เดียวกัน ล็อกประเภท)
- [x] Thumbnail 3:2 + รูปตัวอย่าง
- [x] Header ใหม่ + ปุ่มภาษา (ตรวจที่ 1280/1100/1024/768/375 ผ่านการจำลองจอ)

## 6. ข้อมูลที่เป็นของสมมติ (ต้องแทนด้วยของจริง)

- `playground/seed.php`: ชื่อ/slug คอร์สมาจากเว็บจริง แต่ **ระดับผู้เรียน ระยะเวลา รูปแบบ หัวข้อ** และสถานะ "เปิดรับสมัคร" ของ Public เป็นค่าสมมติ เนื้อหาคอร์สเป็นข้อความตัวอย่าง
- ข้อความแนะนำหน้า In-house / Public Training มีวงเล็บ "(ข้อความตัวอย่าง)"
- รูปคอร์สเป็นภาพนามธรรมที่สร้างเอง (`playground/sample/`)
- เมนูยังเป็นของเว็บเดิม หน้า Transformation, Japanese Concept, Blog, About, Contact ยังไม่มี

## 7. งานถัดไป (เรียงตามที่เสนอ)

1. **Polylang ใน Playground** — ติดตั้ง Polylang (ฟรี) ผ่าน blueprint, สร้างภาษา TH/EN/JA, ตั้ง TH เป็น default, ทำให้ language-switcher สลับได้จริง, คอร์ส/taxonomy แปลได้; ทดสอบใน Playground (local ติดตั้งไม่ได้)
2. **Footer + หน้าแรก** — patterns: Hero, Stats, Logo wall, Knowledge/บทความ; footer ตอนนี้เป็นของเดิม
3. **ค้นหาทั้งเว็บ** (ภาษาไทย, synonym map)
4. **ฟอร์ม Lead → Google Sheet** — REST endpoint ใน hri-core + Google Apps Script, honeypot/Turnstile, checkbox PDPA, ห้ามใช้ nonce ในหน้าที่ถูก cache
5. **AI แปลภาษา** — ต้องใช้ API key (จ่ายตามใช้) ต้องบอก Jimmi เรื่องค่าใช้จ่ายก่อน
6. **SEO / AI-SEO** — The SEO Framework (ฟรี) + schema เอง (EducationalOrganization, Course/CourseInstance, FAQPage, Article, BreadcrumbList), hreflang, robots/llms.txt
7. **ย้ายเนื้อหา + 301 redirects** จากเว็บเดิม (`/public-training/*`, `/courses/*`)
8. **คำถามที่ต้องถาม Ruk-com**: เวอร์ชัน PHP, Imagick/WebP, SSH/WP-CLI, Rcache กับ query string (สำคัญกับ filter), memory limit, outbound HTTPS ไป Google
9. Cloud staging (เช่น `new.hrideone.com`) ทีหลัง

## 8. ข้อควรระวัง / สิ่งที่เคยพลาด

- `paginate_links` ใส่ class `page-numbers` ให้ `<ul>` ด้วย → CSS ต้องเจาะ `a.page-numbers, span.page-numbers`
- รูปใน `aspect-ratio` box ต้องมี `overflow:hidden` + `img{display:block}` ไม่งั้นรูปดันกล่องสูงเกิน
- Navigation block ของ core ใส่ `items-justified-right` → ใน overlay ต้องใช้ selector ที่เจาะจงกว่าเพื่อจัดชิดซ้าย
- ถ้าเมนูเปิดค้างแล้วขยายจอเป็น desktop เมนูเต็มจอจะค้าง (พฤติกรรม core)
- `curl` กับ URL ที่มี `[]` ต้องใช้ `-g`
- blueprint ใช้ `post_name__in` (ไม่ใช่ `name__in`)
- ไม่มีข้อมูลลับใน repo (API key, Sheet URL)

## 9. วิธีทำงานที่ประหยัด usage

- หนึ่งแชทต่อหนึ่งงานใหญ่ เริ่มด้วย: "ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web อ่าน docs/HANDOFF.md แล้วทำ <งาน>"
- ตรวจด้วย JS วัดค่า (ขนาด/overflow/การแสดงผล) ก่อน ใช้ screenshot เท่าที่จำเป็น
- จบงานแล้วอัปเดตไฟล์นี้ (ข้อ 5–8) แล้ว push
- Commit message ปิดท้ายด้วยบรรทัด attribution ตามที่ระบบกำหนด
