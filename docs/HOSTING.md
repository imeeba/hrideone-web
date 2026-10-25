# HOSTING — Ruk-com WordPress Hosting (ยังไม่เริ่ม: Jimmi สั่ง "ทำ demo ให้เสร็จก่อน ยังไม่ยุ่งกับโฮสต์")

> สถานะ 23 ต.ค. 2569: ยังไม่ได้ถาม support · ยังไม่รู้ว่า Jimmi ใช้แพ็กเกจไหน · เมื่อได้คำตอบให้กรอกตารางข้อ 3 แล้วเลือกวิธีขึ้นเว็บในข้อ 4

## 1. ข้อมูลจากหน้าแพ็กเกจ (https://ruk-com.cloud/wordpress-hosting/, อ่านเมื่อ 23 ต.ค. 2569)

- แพ็กเกจ WP-TH-1 … WP-TH-8 (ไทย) / WP-SG-1 … 8 (สิงคโปร์): 1–30 เว็บ, SSD 10–80 GB · TH-1 = 1 เว็บ 10 GB ฿150/เดือน
- ทุกแพ็กเกจ: SSL ฟรี, Backup ทุกวันเก็บ 90 วัน, สแกนมัลแวร์, **Rcache** (NGINX proxy cache ในหน่วยความจำ), WordPress Firewall, ย้ายเว็บฟรี, DB/อีเมลไม่จำกัด
- Global CDN เป็นบริการแยก (จ่ายเพิ่ม)
- **หน้าเว็บไม่ได้บอก**: PHP version, SSH/SFTP/WP-CLI/Git, staging, HTTP/2/3, Brotli, Imagick/WebP/AVIF, upload/memory/execution limit, cron → ต้องถาม support (ข้อ 2)

## 2. ข้อความถาม support (copy ไปวาง)

> สวัสดีครับ ผมใช้ WordPress Hosting แพ็กเกจ [ชื่อแพ็กเกจ] โดเมน hrideone.com กำลังจะทำเว็บใหม่ (WordPress block theme + Polylang) ขอสอบถามครับ
>
> การเข้าถึงและเว็บทดสอบ
> 1. สร้างเว็บทดสอบบน subdomain (เช่น new.hrideone.com) ได้ไหม นับเป็น 1 เว็บในแพ็กเกจไหม มีระบบ Staging (copy ไป/กลับกับเว็บจริง) ไหม
> 2. มี SSH, SFTP, WP-CLI หรือ Git ไหม ถ้าไม่มี อัปโหลดไฟล์ได้ทางไหน (File Manager / FTP)
> 3. เว็บทดสอบตั้งรหัสผ่าน หรือปิดไม่ให้ Google เก็บได้ไหม
> 4. บริการย้ายเว็บฟรี รับย้ายจากไฟล์ zip / Duplicator / All-in-One WP Migration ได้ไหม หรือย้ายจากเว็บที่ออนไลน์อยู่เท่านั้น
>
> PHP และขีดจำกัด
> 5. PHP เวอร์ชันอะไร เลือกเองได้ไหม (ต้องการ 8.3) เปิด OPcache ไหม
> 6. upload_max_filesize, post_max_size, memory_limit, max_execution_time เท่าไร ปรับเองได้ไหม
> 7. มี Imagick ไหม รองรับ WebP และ AVIF ไหม (ถ้ามี GD ที่รองรับ WebP ก็พอ; ถ้าไม่มีทั้งคู่ ระบบเก็บรูปเป็น JPG/PNG ต่อไปแทนที่จะแปลง และหน้า Tools → ปรับรูปให้เล็ก จะแจ้งว่าใช้ไม่ได้ · AVIF ใช้ได้เมื่อมี Imagick ที่รองรับเท่านั้น: `define('HRI_IMAGE_FORMAT','avif')`)
> 7.1 เปิด PHP extension `intl` ได้ไหม (ใช้ตัดคำไทยในการค้นหา `IntlBreakIterator`; ถ้าไม่มีระบบสลับเป็น n-gram เองได้ แต่ดัชนีใหญ่กว่า) และฐานข้อมูลเป็น MySQL/MariaDB เวอร์ชันอะไร (ต้อง utf8mb4)
> 8. เว็บดาวน์โหลดไฟล์จากเว็บภายนอกได้ไหม (github.com, cdn.jsdelivr.net, script.google.com)
> 9. WP-Cron ใช้ cron จริงของเซิร์ฟเวอร์ได้ไหม
>
> Rcache และความเร็ว
> 10. Rcache แคชหน้าที่มี query string ไหม (เช่น ?pillar[]=leadership, ?e-page-xxx=2)
> 11. ล้างแคชรายหน้าจากโค้ดได้ไหม (hook / header / URL purge) หรือล้างทั้งเว็บจากแผงควบคุมเท่านั้น
> 12. Rcache ข้ามแคชให้คนที่ล็อกอินไหม และดู cookie ไหนบ้าง (เช่น pll_language)
> 13. รองรับ HTTP/2, HTTP/3, Brotli หรือ gzip ไหม
> 14. ตั้ง cache header ไฟล์ css/js/รูป/ฟอนต์ 1 ปีได้ไหม (NGINX ไม่อ่าน .htaccess ต้องให้ทีมตั้งให้ใช่ไหม)
> 15. Global CDN ราคาเท่าไร ใช้ Cloudflare ฟรีวางหน้าโฮสต์ได้ไหม
>
> ความปลอดภัยและอื่นๆ
> 16. WordPress Firewall บล็อก admin-post.php, REST API หรือฟอร์มที่ไม่ใช้ nonce ไหม
> 17. วาง robots.txt และ llms.txt ที่ root โดเมนได้ไหม
> 18. ส่งอีเมลจาก WordPress ได้เลยไหม หรือต้องตั้ง SMTP
> 19. เว็บอยู่ Region ไทยหรือสิงคโปร์

## 3. คำตอบจาก support (กรอกเมื่อได้)

| ข้อ | คำตอบ | ผลต่อแผน |
|---|---|---|
| แพ็กเกจที่ใช้ | | จำนวนเว็บ = ทำ staging แยกได้ไหม |
| 1–4 | | วิธีขึ้นเว็บ (ข้อ 4) |
| 5–9 | | รูป (WebP/AVIF), การนำเข้า, ฟอร์ม, แปลอัตโนมัติ |
| 10–15 | | PageSpeed, ตัวกรองคอร์ส, cache ภาษา |
| 16–19 | | ฟอร์ม Lead, SEO/AI SEO |

## 4. วิธีขึ้นเว็บที่เป็นไปได้ (เลือกหลังได้คำตอบ)

1. **ทีละส่วนผ่าน wp-admin** (ไม่ต้องมี SSH): ติดตั้ง WordPress ใหม่ → อัปโหลด `hri-theme` / `hri-core` เป็น zip → ติดตั้ง Polylang → กดปุ่ม "ตั้งค่าภาษา" (แจ้งเตือนใน wp-admin) → Tools → นำเข้าบทความ (`playground/data/blog-posts.json.gz`, โหมด "คัดลอกรูปเข้าคลังสื่อ" ดึงรูปจาก repo ผ่าน jsDelivr) → หน้า/คอร์สสร้างจาก seed (ตอนนี้ seed รันได้แค่ WP-CLI/Playground → ต้องทำหน้า "รัน seed" ใน wp-admin หรือใช้วิธี 2)
2. **ย้ายทั้งก้อน**: ประกอบเว็บให้เสร็จใน local/Playground → แพ็กด้วยปลั๊กอินย้ายเว็บ (Duplicator ฟรี / All-in-One WP Migration มีเพดานขนาดไฟล์ในรุ่นฟรี) → แตกบน subdomain ทดสอบ → หรือส่งให้ Ruk-com ย้ายให้ (ถ้ารับไฟล์)
3. หลังเว็บทดสอบผ่าน: สลับโดเมนจริง + 301 (prompt E ใน HANDOFF) + ส่ง sitemap เข้า Search Console / Bing

ข้อควรรู้: ฐานข้อมูลของ local/Playground เป็น SQLite ส่วนโฮสต์ใช้ MySQL → ย้ายทั้งก้อนต้องใช้ปลั๊กอินที่ export เป็น SQL/ไฟล์กลาง ไม่ใช่ copy ไฟล์ DB ตรงๆ
