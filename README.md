# hrideone-web

Rebuild ของเว็บไซต์ [hrideone.com](https://hrideone.com) (HRI DeOne) จาก WordPress + Elementor
เป็น WordPress แบบ Block Theme ที่ clean, lightweight และใช้ปลั๊กอินให้น้อยที่สุด

## เปิดดูทันที (WordPress Playground)

[▶ เปิด Preview ล่าสุดจาก branch `main`](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/imeeba/hrideone-web/main/playground/blueprint.json)

เปิดได้จากทุกอุปกรณ์ ไม่ต้องติดตั้งอะไร เปิดมาที่หน้าแรก มี `/in-house/`, `/public-training/`, `/transformation-solutions/`, `/japanese-concept/`, `/about/`, `/contact-us/`, `/blog/`, `/courses/` และ Style Guide (`/style-guide/`)
พร้อมข้อมูลตัวอย่าง ภาษาอังกฤษ `/en/` ญี่ปุ่น `/ja/` (Polylang) และล็อกอินหลังบ้านให้อัตโนมัติ
ข้อมูลที่แก้ใน Playground อยู่แค่ในเบราว์เซอร์นั้นและหายเมื่อปิดแท็บ

> **สถานะงาน การตัดสินใจ และงานถัดไป: [`docs/HANDOFF.md`](docs/HANDOFF.md)** — อ่านก่อนเริ่มงาน

## ทดสอบในเครื่อง

```
scripts/local-setup.sh [โฟลเดอร์] [พอร์ต]   # ค่าเริ่มต้น ../hri-local, 8899
```

ได้ WordPress + SQLite + WP-CLI ที่ symlink theme/plugin จาก repo นี้ เปิด `http://127.0.0.1:8899/wp/` (admin / admin)

## โครงสร้าง

```
tokens/
  tokens.json            Design tokens (source of truth): สี ฟอนต์ ขนาด spacing radius shadow
  theme.base.json        Global styles ที่อ้างอิง token
  build.mjs              รวมสองไฟล์ด้านบนเป็น theme.json
wp-content/
  themes/hri-theme/      Block theme (หน้าตาเท่านั้น)
    theme.json           ไฟล์ที่ build ขึ้นมา ห้ามแก้ตรง
    assets/fonts/        Kanit, Montserrat, M PLUS 1 (self-hosted, OFL)
    assets/css/          base.css, fonts-ja.css, style-guide.css
    assets/images/logo/  โลโก้ SVG (สี / ขาว / ดำ / mark)
    assets/images/home/  ภาพประกอบหน้าแรก (SVG) · placeholder/ รูปชั่วคราว
    assets/js/           hero.js (วิดีโอ hero ≤10 วินาที), slider.js (Gallery แบบสไลด์)
    inc/markup.php       ตัวช่วยพิมพ์ block markup ให้ patterns (hri_bm_*)
    parts/ templates/ patterns/   (หน้าแรก: home-*.php · บริการ: inhouse-*, public-*, transformation-*, japanese-*)
  plugins/hri-core/      Features ที่ไม่ผูกกับธีม: cleanup, images, หลักสูตร (CPT + filter + วิทยากร), ฟอร์ม Lead, ภาษา (Polylang), ข้อมูลบริษัท + blocks ของ footer/บล็อก
playground/
  blueprint.json         ตั้งค่า Playground preview
  seed.php               ข้อมูลตัวอย่าง (คอร์ส 20 รายการ, หน้าแรก, In-house / Public Training / Transformation / Japanese Concept, Style Guide) + นำเข้าบทความจริง
  data/blog-posts.json   บทความเว็บเดิม 115 เรื่อง × TH/EN/JA (สร้างด้วย scripts/wxr-to-blog.py, .gz ไว้อัปโหลดที่ Tools → นำเข้าบทความ)
  seed-home.php          สร้างเนื้อหาหน้าจาก patterns ตามภาษา (hri_seed_page_content(), ใช้ร่วมกันสองไฟล์)
  seed-languages.php     คำแปลตัวอย่าง EN / JA (หลังตั้งค่า Polylang)
scripts/local-setup.sh   สร้างเว็บทดสอบในเครื่อง
scripts/wxr-to-blog.py   แปลงไฟล์ Export (WXR) ของเว็บเดิมเป็น playground/data/blog-posts.json
scripts/measure.py       ตรวจ overflow / tap target ≥44px / contrast / PHP notice / JS error (Playwright) ดู HANDOFF ข้อ 8
docs/HANDOFF.md          สถานะงานและบริบท
docs/AUTO-TRANSLATE.md   สเปกแปลอัตโนมัติ TH → EN/JA (ยังไม่ได้ทำ)
```

## แก้ Design Token

1. แก้ `tokens/tokens.json`
2. รัน `node tokens/build.mjs`
3. Commit ทั้ง `tokens/` และ `theme.json`

## Plugin stack เป้าหมาย

- Polylang (TH / EN / JA) — ฟรี
- The SEO Framework — ฟรี
- Rcache (ของ Ruk-com, ใช้เฉพาะบนโฮสต์)

Custom fields ของหลักสูตรเขียนเองใน `hri-core` (ไม่ใช้ ACF)

## หลักการ

- ไม่มีข้อมูลลับ (API key, Sheet URL) อยู่ใน repo นี้
- Theme ทำเฉพาะหน้าตา feature ทั้งหมดอยู่ใน `hri-core`
- ทดสอบส่วนที่ต้องใช้เซิร์ฟเวอร์จริงบน Cloud Staging แยกจาก Production
