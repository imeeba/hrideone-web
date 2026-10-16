# hrideone-web

Rebuild ของเว็บไซต์ [hrideone.com](https://hrideone.com) (HRI DeOne) จาก WordPress + Elementor
เป็น WordPress แบบ Block Theme ที่ clean, lightweight และใช้ปลั๊กอินให้น้อยที่สุด

## โครงสร้าง (แผน)

```
wp-content/
  themes/hri-theme/      Block theme: theme.json (design tokens), templates, patterns, blocks
  plugins/hri-core/      Features: CPT/taxonomy, course filter, search, lead form → Google Sheet,
                         image optimization, schema/AI SEO, AI translation
playground/
  blueprint.json         เปิดดูเว็บได้ทันทีผ่าน WordPress Playground
```

## Plugin stack เป้าหมาย

- Polylang (TH / EN / JA)
- The SEO Framework
- Secure Custom Fields / ACF
- Rcache (ของ Ruk-com, ใช้เฉพาะบนโฮสต์)

## การพัฒนา

- พัฒนาและพรีวิวผ่าน WordPress Playground
- ทดสอบส่วนที่ต้องใช้เซิร์ฟเวอร์จริงบน Cloud Staging แยกจาก Production
- ไม่มีข้อมูลลับ (API key, Sheet URL) อยู่ใน repo นี้
