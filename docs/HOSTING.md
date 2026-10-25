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

## 5. ตั้งค่าฝั่งโฮสต์เพื่อความเร็ว (งาน J — โค้ดทำถึงที่สุดแล้ว ที่เหลืออยู่ที่โฮสต์)

> คะแนนที่วัดใน workspace ผ่าน `scripts/perf.py` (พร็อกซีจำลอง Brotli + cache header) ไม่ใช่ความเร็วโฮสต์จริง — พอขึ้นโฮสต์แล้วให้วัดซ้ำด้วย PageSpeed Insights / `scripts/perf.py --base https://<staging>` แล้วเทียบตารางใน HANDOFF รอบที่ 12

ตรวจกับ support (หรือที่ cPanel/แผงควบคุม) ว่ามีครบไหม ถ้าไม่มีให้ขอเปิด — ลำดับตามผลต่อคะแนน:

1. **Brotli (หรือ gzip อย่างน้อย)** สำหรับ HTML, CSS, JS, SVG, JSON — ถ้าไม่บีบอัด หน้าแรกจะหนักกว่าที่วัดไว้ ~4–5 เท่า (HTML ~115 KB → ~20 KB)
2. **Cache header ของไฟล์ static**: `Cache-Control: public, max-age=31536000, immutable` สำหรับ `/wp-content/**`, `/wp-includes/**` (CSS/JS มี `?ver=` ต่อท้าย เปลี่ยนเวอร์ชันแล้วลิงก์เปลี่ยนเอง · ฟอนต์/รูป/SVG ไม่มี ver จึงห้ามเขียนทับไฟล์เดิมด้วยเนื้อหาใหม่ — ถ้าเปลี่ยนโลโก้หรือฟอนต์ให้ตั้งชื่อไฟล์ใหม่) · ครอบคลุม `.woff2`, `.webp`, `.svg`, `.css`, `.js`
3. **HTTP/2 หรือ HTTP/3** — หน้าแรกมี ~75 request (รูปส่วนใหญ่ lazy) ถ้าเป็น HTTP/1.1 จะช้าลงชัดเจน
4. **Rcache** ต้องแคชหน้า HTML ของคนที่ไม่ล็อกอิน และ **ห้ามแคชแยกตาม cookie ภาษา** (ภาษาอยู่ใน URL อยู่แล้ว → ตั้ง `define( 'PLL_COOKIE', false );` ใน wp-config) · หน้าที่มี query string (`?pillar[]=…`, `?s=…`) แคชหรือไม่ก็ได้ แต่ต้องไม่ทำให้ผลกรองของคนหนึ่งไปโผล่ให้อีกคน
5. **Speculation Rules (prerender เมื่อ hover)** ทำให้เซิร์ฟเวอร์เรนเดอร์หน้าล่วงหน้าเมื่อผู้ใช้เอาเมาส์ค้างบนลิงก์ ~200 ms ถ้า Rcache แคชหน้านั้นไว้แล้วแทบไม่มีต้นทุน ถ้าโหลดเซิร์ฟเวอร์สูงให้ปิดด้วย `add_filter( 'hri_speculation', '__return_null' );` (ไฟล์ใน mu-plugin หรือ functions ของ child) หรือลดเป็น `prefetch`
6. **PHP**: เปิด OPcache · ext-intl (ตัดคำไทยของค้นหา ดู HANDOFF ข้อ 4.y) · Imagick หรือ GD ที่รองรับ WebP (แปลงรูปตอนอัปโหลด งาน I)
7. **Cloudflare (ถ้าจะใช้)**: เปิด Brotli, HTTP/3, Auto Minify ปิดไว้ (เรา minify เองแล้ว), อย่าเปิด Rocket Loader/Mirage (เลื่อนสคริปต์ของเมนู/ตัวกรองจนอาจใช้งานไม่ได้ และกระทบ LCP) · ถ้าอยู่หลัง Cloudflare ต้องตั้ง `HRI_IP_HEADER` ตาม `LEAD-SETUP.md`
8. **ไฟล์วิดีโอ hero** (ตอนมี): ต้องเสิร์ฟด้วย `Accept-Ranges: bytes` (เล่นได้ไม่ต้องโหลดทั้งไฟล์) ขนาดแนะนำ ≤ 2 MB สำหรับ 10 วินาที, H.264 MP4 + พื้นที่ `poster` ถ้าทำเพิ่ม

ตัวเลือกที่ **ไม่ได้ทำ** (ตัดสินใจแล้ว): ไม่ใช้ปลั๊กอินแคช/ปรับความเร็ว (WP Rocket ฯลฯ) — ไม่มีงบ และ Rcache ทำหน้าที่เดียวกัน · ไม่ทำ critical CSS แยกต่อหน้า (ทดลอง inline CSS ทั้งหมดแล้วหน้าแรกไม่ดีขึ้น 92 → 87)

## 6. SEO / AI SEO บนโฮสต์ (งาน D)

1. **ติดตั้ง The SEO Framework** (`autodescription`) แล้วเปิดใช้ → เปิด wp-admin ครั้งแรกจะตั้งค่าเอง หรือ `wp hri seo setup` · ตั้ง Permalink เป็น "ชื่อโพสต์" (sitemap/llms.txt ต้องการ)
2. **`/robots.txt` และ `/llms.txt` ต้องถึง WordPress** — WordPress สร้างให้แบบ virtual ห้ามมีไฟล์จริงชื่อเดียวกันที่ root (ไฟล์ที่ Ruk-com สร้างไว้ให้ต้องลบ) · ถ้า nginx/Rcache ตอบ `.txt` เองโดยไม่ผ่าน PHP ให้ขอให้ส่งต่อ (คำถามข้อ 17)
3. Rcache แคช `/robots.txt`, `/llms.txt`, `/sitemap.xml` ได้ (เปลี่ยนไม่บ่อย; llms.txt ส่ง `Cache-Control: max-age=3600`)
4. หลังสลับโดเมน: ส่ง `https://<โดเมน>/sitemap.xml` (และ `/en/…`, `/ja/…`) เข้า Google Search Console + Bing Webmaster Tools · เปิด IndexNow ที่ Settings → SEO และ AI · ตรวจด้วย `python3 -I scripts/seo-check.py --base https://<staging หรือโดเมน> --robots-url https://<โดเมน>/robots.txt`
