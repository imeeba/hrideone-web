# hrideone-web

Rebuild ของเว็บไซต์ [hrideone.com](https://hrideone.com) (HRI DeOne) จาก WordPress + Elementor
เป็น WordPress แบบ Block Theme ที่ clean, lightweight และใช้ปลั๊กอินให้น้อยที่สุด

## เปิดดูทันที (WordPress Playground)

[▶ เปิด Preview ล่าสุดจาก branch `main`](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/imeeba/hrideone-web/main/playground/blueprint.json)

เปิดได้จากทุกอุปกรณ์ ไม่ต้องติดตั้งอะไร หน้าแรกคือ Style Guide และล็อกอินหลังบ้านให้อัตโนมัติ
ข้อมูลที่แก้ใน Playground อยู่แค่ในเบราว์เซอร์นั้นและหายเมื่อปิดแท็บ

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
    parts/ templates/ patterns/
  plugins/hri-core/      Features ที่ไม่ผูกกับธีม (cleanup, images, ... )
playground/
  blueprint.json         ตั้งค่า Playground preview
```

## แก้ Design Token

1. แก้ `tokens/tokens.json`
2. รัน `node tokens/build.mjs`
3. Commit ทั้ง `tokens/` และ `theme.json`

## Plugin stack เป้าหมาย

- Polylang (TH / EN / JA)
- The SEO Framework
- Secure Custom Fields / ACF
- Rcache (ของ Ruk-com, ใช้เฉพาะบนโฮสต์)

## หลักการ

- ไม่มีข้อมูลลับ (API key, Sheet URL) อยู่ใน repo นี้
- Theme ทำเฉพาะหน้าตา feature ทั้งหมดอยู่ใน `hri-core`
- ทดสอบส่วนที่ต้องใช้เซิร์ฟเวอร์จริงบน Cloud Staging แยกจาก Production
