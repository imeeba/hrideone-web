# MONITORING — เว็บล่ม, SSL, รายงานความปลอดภัย

ระบบมี 2 ส่วน ทำงานเสริมกัน

| ส่วน | อยู่ที่ไหน | ทำอะไร |
|---|---|---|
| **Better Stack** (ฟรี) | นอกเซิร์ฟเวอร์ | เช็กเว็บทุก 3 นาที ล่มเมื่อไหร่ส่งอีเมลทันที + เตือน SSL/โดเมนใกล้หมด + หน้า status |
| **hri-core → Tools → สุขภาพเว็บ** | ใน WordPress | widget บน Dashboard + อีเมลรายงานทุกวันจันทร์ 9:00 + อีเมลด่วนเมื่อ SSL ≤ 14 วัน หรือพบช่องโหว่ |

เว็บที่ล่มส่งอีเมลบอกตัวเองไม่ได้ จึงต้องมี Better Stack อยู่ข้างนอก

## 1. ตั้ง Better Stack (Jimmi ทำ ~5 นาที)

1. สมัครที่ https://betterstack.com (แผน Free) → เลือก **Uptime**
2. **Create monitor**
   - URL: `https://new.hrideone.com/` (ตอน go-live เพิ่ม `https://hrideone.com/`)
   - Alert when: *URL becomes unavailable* · Check frequency: **3 minutes**
   - เปิดการเตือน **SSL certificate expiration** (30 วัน) และ **Domain expiration** ถ้ามีให้เลือก
3. **Create monitor** อีกตัวแบบ *URL doesn't contain keyword* → keyword `HRI DeOne` (จับกรณีหน้าขาว/หน้า error ที่ยังตอบ 200)
4. **On-call / Notifications**: อีเมล Jimmi + อีเมลสำรอง (ต้องมีก่อน go-live)
5. **Status page** → สร้าง → คัดลอกลิงก์ → วางใน wp-admin **Tools → สุขภาพเว็บ → ลิงก์หน้า status**

## 2. รายงานใน WordPress (`inc/health-report.php`)

ตรวจ 7 เรื่อง: ช่องโหว่ที่รู้จัก (WordPress + ทุกปลั๊กอิน + ธีมที่ไม่ใช่ของเรา จาก [WPVulnerability.net](https://www.wpvulnerability.net/) ฟรี ไม่ต้องมี key) · SSL เหลือกี่วัน · อัปเดตที่ค้าง · backup ล่าสุด (UpdraftPlus หรือ All-in-One WP Migration) · ล็อกอินผิดใน 7 วัน (Loginizer) + แอดมินที่ยังไม่เปิด 2FA (Two Factor) · Site Health · การตั้งค่า (แก้ไฟล์จาก admin, debug, https, ซ่อนจาก Google)

- ผู้รับ: ตั้งใน Tools → สุขภาพเว็บ (ว่าง = อีเมล admin ของ WordPress) · ปุ่ม **ส่งรายงานทดสอบตอนนี้**
- ตารางเวลาใช้ WP-Cron: ทำงานเมื่อมีคนเข้าเว็บ ถ้า Site Health บอกว่า cron ไม่ทำงาน ให้ตั้ง cron ใน DirectAdmin เรียก `https://<เว็บ>/wp-cron.php` ทุก 15 นาที แล้วใส่ `define( 'DISABLE_WP_CRON', true );`
- อีเมลส่งด้วย `wp_mail()` ของโฮสต์ ถ้าไม่ถึง (ตกไป spam/ไม่ส่ง) ให้ใช้ SMTP ของโดเมน (ปลั๊กอินฟรี เช่น WP Mail SMTP)
