# ฟอร์ม Lead — วิธีตั้งค่า (สำหรับ Jimmi / คนดูแลเซิร์ฟเวอร์)

ฟอร์ม (`hri/lead-form`) ทำงานได้ทันทีโดยไม่ต้องตั้งค่าอะไร: ทุกคนที่กรอกจะถูก
1. **บันทึกในหลังบ้าน** เมนู "ผู้สนใจ (Lead)" (เก็บถาวร เปิดดู/ลบได้ ไม่หายแม้อีเมลหรือ Google มีปัญหา)
2. **ส่งอีเมลแจ้งทีม** ไปที่อีเมลใน Settings → ข้อมูลบริษัท (หรือค่า `HRI_LEAD_NOTIFY`)
3. **ส่งเข้า Google Sheet** ถ้าตั้ง `HRI_LEAD_WEBHOOK_URL` (ข้อ 1 ด้านล่าง)

ข้อมูลลับ **ห้ามอยู่ใน repo** — ใส่ใน `wp-config.php` ของเซิร์ฟเวอร์เท่านั้น

```php
define( 'HRI_LEAD_WEBHOOK_URL',   'https://script.google.com/macros/s/XXXX/exec' );
define( 'HRI_LEAD_WEBHOOK_TOKEN', 'สุ่มข้อความยาว ๆ' );          // ค่าเดียวกับใน Apps Script
define( 'HRI_LEAD_NOTIFY',        'sales@hrideone.com' );        // (ไม่บังคับ)
define( 'HRI_TURNSTILE_SITE_KEY', '0x...' );                     // (ไม่บังคับ) Cloudflare Turnstile
define( 'HRI_TURNSTILE_SECRET',   '0x...' );
```

## 1. Google Sheet + Apps Script (ฟรี)

1. สร้าง Google Sheet ใหม่ ตั้งชื่อแท็บ `Leads` แถวแรกเป็นหัวคอลัมน์:
   `เวลา | ชื่อ | บริษัท | ตำแหน่ง | โทร | อีเมล | สนใจเรื่อง | ข้อความ | ภาษา | หน้าที่ส่ง | Lead ID`
2. Extensions → Apps Script วางโค้ดนี้ (แล้วตั้ง Script Properties ชื่อ `TOKEN` = ค่าเดียวกับ `HRI_LEAD_WEBHOOK_TOKEN`):

```js
function doPost(e) {
  const d = JSON.parse(e.postData.contents);
  if (d.token !== PropertiesService.getScriptProperties().getProperty('TOKEN')) {
    return ContentService.createTextOutput('forbidden');
  }
  SpreadsheetApp.getActive().getSheetByName('Leads').appendRow([
    d.timestamp, d.name, d.company, d.position, d.phone, d.email,
    d.interest, d.message, d.lang, d.source, d.lead_id,
  ]);
  return ContentService.createTextOutput('ok');
}
```
3. Deploy → New deployment → Web app → Execute as: **Me**, Who has access: **Anyone** → คัดลอก URL (`…/exec`) ใส่ `HRI_LEAD_WEBHOOK_URL`
4. ทดสอบ: กรอกฟอร์มบนเว็บ → ต้องมีแถวใหม่ใน Sheet (ในหลังบ้าน แถวนั้นจะมีช่อง "ส่งเข้า Google Sheet = sent")

## 2. กันสแปม

- ตอนนี้มี honeypot + จำกัด 5 ครั้ง/10 นาที/ผู้เข้าชม อยู่แล้ว
- อยากได้ Turnstile (ฟรี): สร้าง site ใน Cloudflare → ใส่สอง key ข้างบน ฟอร์มจะแสดงกล่องตรวจสอบเอง

## 3. หมายเหตุ

- ฟอร์มส่งไป `wp-admin/admin-post.php` (ไม่ใช้ `/wp-json` เพราะบางโฮสต์/ปลั๊กอินความปลอดภัยบล็อกไว้) และไม่ใช้ nonce → หน้าที่มีฟอร์ม cache ได้
- ตัวเลือก "สนใจเรื่อง" ดึงรายชื่อคอร์สจากเว็บอัตโนมัติ (ภาษาตามหน้า) · เปิดหน้าติดต่อพร้อมเลือกคอร์สไว้: `/contact-us/?hri_course=<slug คอร์ส>` หรือบริการ: `?interest=inhouse|public|transformation|japanese|other` (ห้ามใช้ `?course=` เพราะ WordPress ตีความเป็นคอร์สเอง)
- PDPA: เก็บ checkbox ยินยอม + เวลาที่ยินยอมไว้ในแต่ละรายการ · ควรกำหนดระยะเวลาลบข้อมูลตามนโยบายบริษัท (ลบได้ที่เมนูผู้สนใจ)
