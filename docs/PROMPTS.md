# PROMPTS — prompt เริ่มแชทใหม่ (HANDOFF ข้อ 10)

> แยกออกมาจาก `docs/HANDOFF.md` เมื่อ 2026-10-26 (เนื้อหาเดิมทุกตัวอักษร) · **เลขข้อคงเดิม**: ข้อ 3–4 = [`ARCHITECTURE.md`](ARCHITECTURE.md) · ข้อ 5 = [`history/DONE-LOG.md`](history/DONE-LOG.md) · ข้อ 6 = [`OPEN-QUESTIONS.md`](OPEN-QUESTIONS.md) · ข้อ 7 = [`BACKLOG.md`](BACKLOG.md) · ข้อ 8 = [`PITFALLS.md`](PITFALLS.md) · ข้อ 10 = [`PROMPTS.md`](PROMPTS.md) · ข้อ 1, 2, 9 = [`HANDOFF.md`](HANDOFF.md)

## 10. Prompt เริ่มแชทใหม่ (copy ไปวางได้เลย — 1 งาน = 1 แชท)

**A. เนื้อหา In-house 14 คอร์ส — ทำแล้ว (รอบที่ 4) ไม่ต้องใช้ prompt นี้อีก เก็บไว้เป็นตัวอย่างรูปแบบ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: ดึงเนื้อหาจริงของคอร์ส In-house ที่เหลือ 14 คอร์สจาก https://hrideone.com/courses/<slug>/ (slug ดูใน playground/seed.php) เรียบเรียงใหม่ ไม่คัดลอกยาว ใส่ใน hri_seed_inhouse_courses() ทั้ง TH/EN/JA (ใช้โครง Coaching Mastery) แล้ว seed ตรวจหน้า ข้อมูลไหนไม่แน่ใจให้ flag ใน docs/OPEN-QUESTIONS.md (ข้อ 6)

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**B. นำเข้าบทความจากเว็บเดิม — ทำแล้ว (รอบที่ 6) ไม่ต้องใช้ prompt นี้อีก**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: วางแผนและทำเครื่องมือ/ขั้นตอนนำเข้าบทความ ~150 เรื่องจาก hrideone.com (WordPress Export/Import คง slug รวม slug พิมพ์ผิดเดิม) ตรวจรูปปก หมวด Business/Sales/Coaching เปลี่ยน 'ไม่มีหมวดหมู่' เป็นชื่อจริง ทำ pagination /blog/page/N ให้ตรงเดิม และเสนอวิธีแปล EN/JA บางเรื่อง + ชื่อหมวด EN/JA (ถามผมก่อนถ้าต้องเสียเงิน)

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**C. ค้นหาทั้งเว็บ** — ทำแล้วในรอบที่ 10 (เก็บ prompt ไว้อ้างอิง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: ทำค้นหาทั้งเว็บภาษาไทย (หน้าผลลัพธ์ search.html, ค้นทั้งคอร์ส/บทความ/หน้า, synonym map แก้ได้จากหลังบ้าน, ไม่ใช้ปลั๊กอินเสียเงิน) ใช้ Polylang กรองภาษา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**D. SEO / AI-SEO**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: SEO + AI SEO
1. The SEO Framework (ฟรี): title/meta/sitemap/canonical ทั้ง 3 ภาษา · ตรวจ hreflang ของ Polylang ครบทุกคู่ + x-default
2. JSON-LD ทำเองใน hri-core (โมดูลใหม่ inc/schema.php): EducationalOrganization (โลโก้, ที่อยู่/ติดต่อจาก hri_company(), sameAs โซเชียล), WebSite, BreadcrumbList, BlogPosting (headline, datePublished/Modified, image, inLanguage, publisher), Course + hasCourseInstance + offers (ราคา, Early Bird + priceValidUntil จาก meta), Person (วิทยากร), FAQPage จาก Details block (Google ไม่โชว์ rich result FAQ ให้เว็บทั่วไปแล้ว แต่ช่วย AI อ่าน) · ไม่ซ้ำกับ schema ที่ The SEO Framework ใส่เอง
3. robots.txt (virtual, แก้จากหลังบ้านได้): อนุญาต OAI-SearchBot, ChatGPT-User, Claude-SearchBot, PerplexityBot, Bingbot · GPTBot / ClaudeBot / Google-Extended (bot เทรนโมเดล) ให้เป็นตัวเลือกใน Settings ค่าเริ่มต้น "อนุญาต" แล้วจดใน docs/OPEN-QUESTIONS.md (ข้อ 6) ว่าลูกค้าต้องตัดสินใจ
4. /llms.txt (virtual): ชื่อบริษัท สรุปบริการ ลิงก์หน้าสำคัญ 3 ภาษา (หน้าแรก, บริการ 4 หน้า, รวมคอร์ส, บล็อก, ติดต่อ) สร้างจากเนื้อหาจริง ไม่ hard-code
5. IndexNow (ping Bing เมื่อ publish/update) แบบเปิด/ปิดได้ · ปิดไว้ใน local/Playground
6. เนื้อหาเพื่อ AI: เพิ่มใน pattern หน้าคอร์สให้มีย่อหน้าสรุปบนสุด (คืออะไร/เหมาะกับใคร/ได้อะไร) ถ้ายังไม่มี · บทความแสดง "อัปเดตล่าสุด" ถ้าแก้หลังเผยแพร่
เกณฑ์ผ่าน: JSON-LD ทุกแบบหน้าผ่าน schema validator (ตรวจด้วยสคริปต์ใน local ถ้าเข้าเว็บ validator ไม่ได้) · sitemap ครบ 345 บทความ + หน้า + คอร์ส 3 ภาษา · hreflang ครบทุกหน้าที่มีคำแปล · measure.py 0 ปัญหา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**E. ย้ายเนื้อหา + 301**
> หมายเหตุรอบที่ 9: ตอนย้ายต้อง sideload รูปจาก `playground/site-media/` เข้าคลังสื่อ (ปกคอร์ส/วิทยากรใน demo เป็น meta `_hri_thumbnail_url` ให้แปลงเป็น featured image จริง) · URL รูปต้นฉบับของทุกช่องอยู่ใน `site-media.json` (`src`)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: ทำรายการ 301 redirects จากโครงสร้าง URL เว็บเดิม (/public-training/*, /courses/*, หน้าย่อยบริการเช่น /transformation-solutions/business-strategy/ → anchor ของหน้าหลัก, /blog/page/N) เป็นไฟล์/โมดูลใน hri-core ที่แก้ได้จากหลังบ้านแบบไม่ต้องเขียนโค้ด พร้อมเช็คลิสต์ย้ายเว็บ

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**F. แก้ tap target + การ์ดคอร์ส — ทำแล้ว (รอบที่ 5) ไม่ต้องใช้ prompt นี้อีก เก็บไว้เป็นตัวอย่างรูปแบบ**

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้: แก้ปัญหาใน HANDOFF หัวข้อ 'ปัญหานอกขอบเขต': tap target ≥44px ของ header/footer/course filter และชื่อคอร์สตัดกลางคำในการ์ดแคบ ตรวจ 1280/768/375 × TH/EN/JA

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**G. แปลอัตโนมัติ TH → EN/JA** (ตอบ AUTO-TRANSLATE.md ข้อ 3.3 ข้อ 1 ก่อน แล้วแก้บรรทัด "ผู้ให้บริการ" ด้านล่าง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) และ docs/AUTO-TRANSLATE.md ทั้งไฟล์ แล้วทำงานนี้บน branch feature/auto-translate: ระบบแปลอัตโนมัติ TH → EN/JA ตามสเปก AUTO-TRANSLATE.md ข้อ 4 ทำตามลำดับเฟสในข้อ 5 (ถ้าใหญ่เกินแชทเดียว ทำให้จบเฟสที่ทำได้แล้วปิดงานตามปกติ จดเฟสที่เหลือใน HANDOFF)
ผู้ให้บริการ: <anthropic | openai | deepl> — ทำ adapter ตัวนี้ + fake · ค่าเริ่มต้นอื่นใช้ตามข้อ 3.2

ลำดับ:
1. พิสูจน์ก่อนว่า block editor เก็บ metadata.hriId ไว้หลังเปิด-บันทึก (ทดสอบใน editor จริงใน local) แล้วเลือกวิธีจับคู่ block ตามข้อ 4.2 บอกผลผมสั้นๆ แล้วทำต่อ
2. ทำทีละเฟส ทดสอบด้วย provider fake ตามข้อ 6 ทุกข้อ
3. ห้ามยิง API จริงถ้าผมไม่ได้ให้ key · ถ้าได้ key ใส่ใน wp-config ของ local เท่านั้น ห้าม commit

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความ UI ใหม่แปลได้ TH/EN/JA (gettext ตามวิธีใน docs/ARCHITECTURE.md ข้อ 4 / docs/PITFALLS.md) · สี/ขนาดใช้ token เท่านั้น · ไม่เพิ่ม plugin และไม่มี build step · ทีมการตลาดต้องใช้งาน/ตั้งค่าจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo · ห้ามส่งข้อมูล Lead ไป provider
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · ตามข้อ 6 ของ AUTO-TRANSLATE.md · python3 -I scripts/measure.py ต้องผ่านเหมือนเดิม · screenshot เฉพาะกล่อง "การแปล" และหน้า "งานแปล"
ปิดงาน: อัปเดต AUTO-TRANSLATE.md (สถานะ, สิ่งที่ต่างจากสเปก, ข้อ 7 ค่าใช้จ่ายถ้าทดสอบจริง) + HANDOFF (ข้อ 4 สถาปัตยกรรม, ข้อ 5, ข้อ 7) + README · bump Version plugin/theme · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**H. รูปจริงของหน้าอื่นใน demo (ไม่ใช่บล็อก)** — ทำแล้วในรอบที่ 9 (เก็บ prompt ไว้อ้างอิง)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) + รอบที่ 7 ใน docs/history/DONE-LOG.md แล้วทำงานนี้: ใส่รูปจริงจากเว็บเดิมในหน้าอื่นของ demo (หน้าแรก: hero, โลโก้ลูกค้า 40 + ชื่อบริษัทเป็น alt, รูปผู้บริหาร 2, Gallery · หน้า In-house/Public/Transformation/Japanese Concept: hero + รูปประกอบ + โลโก้พาร์ทเนอร์ 8 · About · รูปคอร์ส thumbnail · รูปวิทยากร)
1. ไล่หน้าเว็บเดิม (https://hrideone.com/ , /in-house/ , /public-training/ , /transformation-solutions/ , /japanese-concept/ , /about/ , /courses/<slug>/) หา URL รูปจริงที่ตรงกับตำแหน่งในเว็บใหม่ ทำตาราง ตำแหน่งในเว็บใหม่ → URL รูปเดิม ไว้ใน playground/data/page-media.json (ดึงเฉพาะรูปที่ใช้จริง)
2. ขยาย scripts/demo-media.py ให้อ่าน page-media.json ด้วย (ย่อ/แปลงแบบเดียวกัน ลง playground/media/) แล้วรัน workflow "Demo media" (gh api .../actions/workflows/demo-media.yml/dispatches) รอจบแล้ว git pull
3. แก้ seed (seed.php / seed-home.php / seed-course-data.php) ให้ใช้รูปจาก jsDelivr ตาม manifest แทนรูปตัวอย่าง (ใน Playground ห้ามดาวน์โหลดรูปเข้าคลังสื่อทีละรูป เพราะช้า ใช้ URL ตรงเหมือนบล็อก หรือหาวิธีที่ไม่ทำให้ Playground บูตช้าเกิน ~2 นาที)
4. รูปไหนหาไม่เจอให้คง placeholder แล้วจดใน docs/OPEN-QUESTIONS.md (ข้อ 6)
เกณฑ์ผ่าน: เปิดลิงก์ demo แล้วรูปขึ้นทุกตำแหน่งในตาราง (ตรวจ naturalWidth > 0 ด้วย JS ใน Playground จริงผ่าน built-in browser) · ไม่มี URL hrideone.com เหลือใน seed · measure.py 0 ปัญหา · เวลาบูต Playground จดไว้

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**I. รูปใน WordPress ให้เล็กสุด (ก่อนย้ายเว็บ)** — ทำแล้วในรอบที่ 11 (เก็บ prompt ไว้อ้างอิง)
> หมายเหตุรอบที่ 9: รูปหน้าอื่นอยู่ใน `playground/site-media/` (แปลงแล้ว 1.6 MB) และใน demo เป็น URL ไม่ได้อยู่ในคลังสื่อ — งานนี้ต้องครอบคลุมตอน sideload รูปเหล่านี้เข้าคลังสื่อบนโฮสต์จริงด้วย

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้ใน hri-core/inc/images.php:
1. แปลงไฟล์ต้นฉบับที่อัปโหลด (JPG/PNG) เป็น WebP ตั้งแต่ตอนอัปโหลด แล้วลบไฟล์เดิม (WordPress ปกติเก็บต้นฉบับไว้เสมอ และ image_editor_output_format แปลงแค่ขนาดย่อย) · ถ้า wp_image_editor_supports avif ให้เลือก AVIF ได้ด้วยค่าคงที่ใน wp-config (ค่าเริ่มต้น WebP)
2. ขอบยาวสุดตั้งค่าได้ (HRI_IMAGE_MAX_EDGE ค่าเริ่มต้นเปลี่ยนเป็น 1600) · quality คง 78 ตั้งค่าได้
3. เพิ่มขนาด hri-card 800×533 (crop 3:2) ให้การ์ดคอร์ส/บทความ/บทความที่เกี่ยวข้องใช้ขนาดนี้ · รูปในบทความใช้ large + srcset/sizes ถูกต้อง
4. ทดสอบกับรูปตัวอย่าง + รูปที่มีตัวหนังสือ (เอาจาก playground/media) เทียบ quality 70 กับ 78 ให้ผมดู 2–3 คู่
เกณฑ์ผ่าน: อัปโหลด JPG แล้วใน uploads ไม่มี .jpg เหลือ · ขนาดไฟล์ก่อน/หลังจดใน HANDOFF · การ์ดโหลดรูป ≤ ~80KB · โหมด download ของ Tools → นำเข้าบทความ ยังทำงาน · measure.py 0 ปัญหา

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```

**J. PageSpeed (เป้า Lighthouse mobile ≥90 ทุกหน้า)** — ✅ ทำแล้วรอบที่ 12 (prompt เก็บไว้เป็นประวัติ ถ้าจะทำซ้ำบนโฮสต์จริงให้ใช้ `scripts/perf.py` และข้อ 5 ของ HOSTING.md)

```
ทำเว็บ hrideone ต่อ repo imeeba/hrideone-web (ขอสิทธิ์ push ด้วย add_repo access:"push")
อ่าน docs/HANDOFF.md และ docs/PITFALLS.md ให้ครบก่อน แล้วเปิดเฉพาะหัวข้อที่เกี่ยวกับงานนี้ใน docs/ARCHITECTURE.md (ข้อ 4) และ docs/OPEN-QUESTIONS.md (ข้อ 6) แล้วทำงานนี้:
1. ทำ scripts/perf.py: Lighthouse (npm lighthouse + Chromium ที่ /opt/pw-browsers) วัด 6 แบบหน้า (/, /courses/, คอร์สเดี่ยว, /blog/, บทความ, /contact-us/) × mobile/desktop เก็บ JSON + ตารางสรุป · วัด baseline ก่อนแก้ จดใน HANDOFF
2. LCP: รูป hero fetchpriority=high ไม่ lazy · รูปแรกของบทความไม่ lazy · วิดีโอ hero มี poster และโหลดวิดีโอหลัง load / เฉพาะจอ ≥782px / ไม่โหลดเมื่อ Save-Data หรือ prefers-reduced-motion
3. ฟอนต์: preload ≤2 ไฟล์ (Kanit Thai 400 + Latin 400) · ตัดน้ำหนักที่ไม่ใช้ (ตรวจว่าใช้จริงกี่น้ำหนัก) · fallback font ใช้ size-adjust ลด CLS
4. CSS/JS: เช็กว่าโหลด CSS เฉพาะ block ที่ใช้ · minify base.css ใน tokens/build.mjs (ไฟล์ .min แยก ไม่แก้ต้นฉบับ) · JS โหลดเฉพาะหน้าที่ใช้ · ไม่มี jQuery ฝั่งหน้าเว็บ
5. รูปทุกรูปมี width/height · loading=lazy เฉพาะใต้จอแรก · sizes ถูกต้อง
6. Speculative Loading ของ core: ตั้ง prerender eagerness moderate (กดลิงก์แล้วเปิดแทบทันที) ยกเว้น wp-admin/ฟอร์ม
7. สคริปต์ภายนอก: GA4/GTM (ถ้าจะใส่) โหลดหลัง load · Turnstile เฉพาะหน้าที่มีฟอร์ม · ไม่มี YouTube iframe ตอนโหลด (ใช้ภาพก่อนกดเล่น)
8. จดสิ่งที่ต้องทำฝั่งโฮสต์ไว้ใน docs/HOSTING.md (cache header 1 ปี, Brotli, HTTP/3, Rcache, Cloudflare) — ห้ามแตะโฮสต์
เกณฑ์ผ่าน: local ไม่ใช่ตัวแทนความเร็วโฮสต์ จึงวัดคะแนนส่วน LCP/CLS/TBT/น้ำหนักหน้า: mobile ≥90 ทุกหน้า (เป้า 95) · CLS < 0.1 · หน้าแรกไม่รวมวิดีโอ < 500KB · measure.py 0 ปัญหา · ตารางก่อน/หลังใน HANDOFF

กฎ: ทำเฉพาะงานนี้ ปัญหานอกขอบเขตให้จดใน docs/PITFALLS.md หัวข้อ 'ปัญหาที่เจอนอกขอบเขต' แล้วแจ้งผม · ข้อความทุกจุดแปลได้ TH/EN/JA และลิงก์ภายในไปหน้าภาษานั้น · สี/ขนาดใช้ token เท่านั้น ห้ามแก้ theme.json ตรงๆ (แก้ tokens แล้ว node tokens/build.mjs) · ไม่เพิ่ม plugin · ทีมการตลาดต้องแก้เนื้อหาจากหลังบ้านได้โดยไม่เขียนโค้ด · ไอคอนใช้ชุดเดิมเท่านั้น (`hri_icon()` / `--hri-icon-*`, เพิ่มผ่าน scripts/build-icons.py ไม่ใช้ตัวอักษร → ✓ ×) · ห้ามแตะ Production · ห้าม secret ใน repo
ทดสอบ: local ด้วย scripts/local-setup.sh + Polylang จาก GitHub · เช็ค 1280/768/375 × TH/EN/JA (ไม่ล้นจอ, contrast AA, tap target ≥44px, ไม่มี PHP notice) วัดด้วย JS ก่อน screenshot เท่าที่จำเป็น
ปิดงาน: อัปเดตเอกสารตาม HANDOFF ข้อ 9 (+README ถ้าโครงสร้างเปลี่ยน) · bump Version theme/plugin · merge เข้า main แล้ว push · สรุปไทยสั้นๆ (ทำอะไร, อะไรให้ผมตรวจ/ตัดสินใจ)
```
