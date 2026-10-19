# CONTENT GAP — เทียบเว็บใหม่กับ hrideone.com (19 ต.ค. 2569)

> เอกสารนี้ใช้วางแผนงานถัดไป ยังไม่ได้แก้โค้ด · อ่านคู่กับ `docs/HANDOFF.md`
> ข้อมูลเว็บเดิมได้จากการเปิดหน้าเว็บจริง (สรุป ไม่ใช่ข้อความเต็ม) — workspace ดึง HTML/รูปตรงๆ ไม่ได้ ตอนย้ายเนื้อหาจริงให้คัดลอกข้อความจากหน้าเว็บเดิม หรือขอไฟล์จาก Jimmi

## 0. ภาพรวม

| หน้า (เว็บเดิม) | เว็บใหม่ตอนนี้ | ช่องว่าง | ลำดับความสำคัญ |
|---|---|---|---|
| หน้าคอร์ส Public 5 หน้า `/public-training/<slug>/` | หน้าคอร์สเดี่ยว = ชื่อ + excerpt + ตารางข้อมูล + ข้อความตัวอย่าง | **ขาดเกือบทั้งหมด** (ปัญหา, ผลลัพธ์, หลักสูตรรายวัน, วิทยากร, รีวิว, ราคา Early Bird, FAQ, ปุ่มสมัคร Google Form) | 🔴 สูงสุด (หน้าขายเงินจริง) |
| หน้าคอร์ส In-house ~15 หน้า `/courses/<slug>/` | เหมือนข้างบน | ขาด ปัญหา/ผลลัพธ์/วิดีโอ/FAQ/ฟอร์ม | 🔴 |
| Contact `/contact-us/` | **ไม่มี** (ทุก CTA ชี้มาที่นี่ → 404) | ทั้งหน้า + ฟอร์ม Lead | 🔴 |
| About `/about/` | **ไม่มี** (เมนูชี้ไป 404) | ทั้งหน้า | 🟠 |
| Blog `/blog/` + บทความ ~150 เรื่อง | มีแค่บทความตัวอย่าง 3 เรื่อง ไม่มีหน้า `/blog/` | listing + หมวด + ย้ายบทความ | 🟠 |
| In-house: Leadership Development / Business Development / Team Simulation | ไม่มี (เนื้อหาบางส่วนอยู่ในการ์ด Leadership/Business ของ `/in-house/`) | คำอธิบายรายหัวข้อ | 🟡 |
| Transformation 4 หน้าย่อย | ย่อรวมไว้ใน `/transformation-solutions/` | รายละเอียดบางจุด (ดูข้อ 6) | 🟡 |
| Japanese Concept / DO·WA·KEI | ย่อรวมไว้ใน `/japanese-concept/` | วิดีโอ, ปุ่มสมัคร, ราคา/วันที่ (เว็บเดิมก็ไม่มี) | 🟡 |
| หน้าแรก | ครบทุก section | แบนเนอร์คอร์ส 7 ใบ (รวม TEN X), รูปจริง | 🟢 |
| `/in-house/`, `/public-training/` | ทำแล้วรอบนี้ | TEN X, วันที่บนการ์ด, รูปจริง | 🟢 |

---

## 1. หน้าคอร์ส Public (สำคัญที่สุด)

เว็บเดิมทุกหน้าคอร์ส Public ใช้โครงเดียวกัน ทำเป็น **pattern หน้าคอร์ส Public** (block ในเนื้อหา แก้ได้ใน editor) + เพิ่ม meta ที่ต้องใช้กรอง/แสดงในการ์ด

### 1.1 โครงหน้า (ตามลำดับเว็บเดิม)

1. **Hero** — ชื่อคอร์ส, รุ่นที่, คำโปรย 1 บรรทัด, ปุ่ม "สมัครเลย" (→ Google Form ของคอร์ส), รูป
2. **Intro / Hook** — 1 ย่อหน้า ตั้งคำถามหรือมุมมองใหม่
3. **"คุณกำลังเจอปัญหาเหล่านี้อยู่ไหม?"** — 4 ข้อ แต่ละข้อมี ไอคอน/รูป + หัวข้อ + คำอธิบาย + "คอร์สนี้ช่วยอย่างไร"
4. **Business Outcomes "สิ่งที่จะเปลี่ยนไปหลังเรียนหลักสูตรนี้"** — 4 มิติ (หัวข้ออังกฤษ + คำอธิบายไทย) + **วิดีโอ MP4 แนวตั้ง** (มีเกือบทุกคอร์ส)
5. **Quote** — ประโยคอังกฤษ + คำแปลไทย
6. **Program / Curriculum** — แบ่งตาม Day / Week / Phase แต่ละช่วงมีรายการ module (บางคอร์สมีรูปประกอบ)
7. **Keynote Speaker / วิทยากร** — รูป, ชื่อ (ไทย/อังกฤษ), ตำแหน่ง, ประวัติย่อ, รายการ certification (บางคอร์สมีวิทยากร 4 คน)
8. **Course Highlights** — แกลเลอรีรูปบรรยากาศรุ่นก่อน (5–12 รูป)
9. **Real Voices, Real Results** — รีวิว: ชื่อ, ตำแหน่ง, ข้อความ (รูป avatar เว็บเดิมเป็นรูปกลาง)
10. **Closing CTA** — ข้อความปิด + ปุ่ม "สมัครเลย"
11. **ราคา / วันที่ / สถานที่** — ราคา Early Bird + วันหมดเขต, ราคาปกติ, สถานที่, วันอบรม (หลายวันไม่ต่อเนื่อง), บางคอร์สคิดราคาต่อองค์กร
12. **FAQ** — 4–6 ข้อ (กลุ่มเป้าหมาย, ทำไมระยะเวลานี้, สัดส่วน workshop, ใบประกาศ, วิธีสมัคร)
13. **ช่องทางสมัคร** — ฟอร์ม (ชื่อ-นามสกุล, บริษัท, ตำแหน่ง, เบอร์, หลักสูตรที่สนใจ) + โทร 065-0979444 + LINE + "ตอบกลับภายใน 24 ชม."

### 1.2 ข้อมูลที่ต้องเพิ่มใน content model (meta ของ `course`)

ตอนนี้มี: `hri_duration_days`, `hri_audience`, `hri_batch_no`, `hri_reg_status`, `hri_start_date`, `hri_end_date`, `hri_location`, `hri_price`

ต้องเพิ่ม (เสนอ):
| meta | ใช้ทำอะไร | หมายเหตุ |
|---|---|---|
| `hri_register_url` | ปุ่ม "สมัครเลย" ทุกจุดในหน้า + การ์ด | ตอนนี้เว็บเดิมใช้ Google Form แยกต่อคอร์ส (URL ข้อ 1.3) · ภายหลังเปลี่ยนเป็นฟอร์ม Lead ของเราเอง |
| `hri_price_early` + `hri_early_deadline` | แสดง Early Bird + วันหมดเขต (ซ่อนเองเมื่อเลยวัน) | |
| `hri_schedule_text` | วันที่แบบหลายวันไม่ต่อเนื่อง เช่น "12, 19, 26 ม.ค. 2, 9, 16 ก.พ. 2570" | start/end เดิมยังใช้เรียงลำดับ/สถานะ |
| `hri_time_text` | เวลาเรียน เช่น 09:00–16:00 | เว็บเดิมแทบไม่ระบุ |
| `hri_price_note` | เงื่อนไขราคา เช่น "ต่อองค์กร 2 ท่าน เพิ่มท่านละ 30,000" | Lean |
| `hri_certificate` (bool) | แสดง "มีใบประกาศ" ใน course facts | |
| `hri_video` (attachment) | วิดีโอใน Business Outcomes | หรือใส่เป็น Video block ในเนื้อหาก็ได้ |

ทั้งหมด sync ระหว่างคำแปล ยกเว้นข้อความ (`schedule_text`, `price_note` copy ครั้งแรกแล้วแปลเอง) · การ์ดคอร์ส Public ควรแสดง **วันที่อบรม** (เว็บเดิมแสดงในหน้า Public listing ทุกคอร์ส — ของเราแสดงแค่รุ่น+สถานะ)

### 1.3 ข้อมูลรายคอร์ส (จากเว็บเดิม)

| คอร์ส | รุ่น | วันที่ | สถานที่ | ราคา | ปุ่มสมัคร |
|---|---|---|---|---|---|
| Negotiation Mastery | 3 | 29–30 ต.ค. 2569 (2 วัน) | Chatrium Residence Sathorn | Early 22,000 (ก่อน 29 ก.ย. 69) / ปกติ 25,000 | forms.gle/SSY8ceTTzuXhTQtD8 |
| The Next Director | 3 | 11–12, 18–19, 25–26 พ.ย. 2569 (6 วัน) | The Bangkok Club | Early 59,500 (ก่อน 11 ต.ค. 69) / ปกติ 66,500 | forms.gle/p6fsgEHb2sQE5Knz7 |
| Oxygen Leadership | 2 | 12, 19, 26 ม.ค. 2, 9, 16 ก.พ. 2570 (+follow-up 1 เดือน) | The Bangkok Club | Early 59,500 (ก่อน 12 ธ.ค. 69) / ปกติ 66,500 | forms.gle/pcJP8cWSxvMd5VMe6 |
| The New Manager | 22 (ในหน้าเขียน 21) | 5, 12, 19, 26 ก.พ. 5 มี.ค. 2570 (จากหน้า listing; ตารางในหน้าคอร์สว่าง) | ไม่ระบุ | Early 33,500 (ก่อน 3 ส.ค. 69 — เลยแล้ว) / ปกติ 38,500 | forms.gle/GRHcUzALgDKTC29n8 |
| Lean Transformation in 7 weeks | 3 (ในหน้าเขียน 2) | 24 ก.พ.–8 เม.ย. 2570 (จาก listing; ในหน้าคอร์สยังเป็นรุ่นเก่า ส.ค.–ต.ค. 69) | ไม่ระบุ (มี plant tour Toyota บ้านโพธิ์ / สำโรง / Gateway) | 150,000 ต่อองค์กร (2 ท่าน) เพิ่มท่านละ 30,000 · ไม่มี Early | forms.gle/LfSc51i8i6uGy2JG6 |
| TEN X | 6 | ทุกวันอังคาร เริ่ม 15 มิ.ย. 2570 | — | — | ลิงก์ออก tenx.utcc.ac.th |

**เนื้อหาเฉพาะแต่ละคอร์ส (ต้องย้ายมาใส่):**

- **Negotiation Mastery** — Intro (สร้างมูลค่า ขยาย "พาย"); ปัญหา 4: สงครามราคา, ข้อโต้แย้งซ้ำ, ยอมเกินไป/แข็งเกินไป (Principled Negotiation), ไม่มั่นใจต่อคู่ที่ใหญ่กว่า (BATNA); Outcomes 4: Mindset Transformation, Systematic Approach (8 ขั้น), Enhanced Confidence & Authority, Adaptive Negotiation Tactics + วิดีโอ; Quote "You don't get what you deserve, you get what you negotiate"; **2 Days Intensive** — Day 1 (8 module: The New Paradigm, Negotiation vs. Persuasion, Decoding Interests, Building Trust & Credibility, Creative Tactics for Shared Value, Golden Rules, Personal Profiling, Workshop I) · Day 2 (8 Steps: Strategic Preparation & BATNA → Professional Opening → Signaling & Decoding → Compelling Proposals → Refining & Package Deals → Bargaining & Overcoming Objections → Closing Masterclass → Finalizing Agreement, + Advanced Tactics, Role Play II); วิทยากร ดร.ชัยพัชร์ (ประวัติ SCG, Tesco Lotus + certification 7 รายการ); Highlights 5 รูป; รีวิว 3 (คุณวิทวัส, คุณศิริพร, คุณนพพล); FAQ 4 (ใครควรเรียน, คนขี้อายเรียนได้ไหม, ทฤษฎี 40 / ปฏิบัติ 60, ใบประกาศ)
- **The Next Director** — Hook (จาก Controller สู่ Strategic Orchestrator); ปัญหา 4 (ขาดผู้สืบทอด, เก่งปฏิบัติแต่อ่อนกลยุทธ์, อ่านตัวเลขไม่เป็น*, กำแพง Silo); Outcomes 4 + วิดีโอ; Quote Peter Drucker; **6-Day Journey 18 module** (Day 1 Self-Awareness & Talent Management · Day 2 Strategic Thinking & Visionary · Day 3 Business Acumen · Day 4 Executive Communication & Personal Branding · Day 5 Driving Change & Digital/AI · Day 6 Team Synergy + Graduation); วิทยากร ดร.ชัยพัชร์ + Guest Speakers; FAQ 6 (HiPo/New Director 0–2 ปี, ทำไม 6 วัน = 3 สัปดาห์ สัปดาห์ละ 2 วัน, D.E.E.P. Model, เวลา main trainer 09:00–14:30 / guest 14:45–16:00, ใบประกาศในพิธีจบ, วิธีสมัคร) — *ข้อ 3 ในเว็บเดิมใส่เนื้อหาผิด (เป็นเรื่องเจรจา) ให้ถาม Jimmi
- **Oxygen Leadership** — Hook (ทีม "หายใจไม่ออก" ผู้นำกำหนดบรรยากาศ); ปัญหา 4 (ตัดสินใจช้า, คนเก่งหมดไฟ, กลัวพูด/ไม่มี psychological safety, ฟื้นตัวช้า/วัฒนธรรมโทษกัน); Outcomes 4 (Atmosphere Architect, Tactical Clarity & Filter "5 Leads", Emotional Anchor, Empowerment Catalyst) + วิดีโอ; Quote "One leader can change the air in the whole room"; **7-Week Learning Lab 3 Phase** (W1 Orientation, W2 Clarity · W3 Energy, W4 Empathy, W5 Resilience · W6 Trust, W7 Blueprint + follow-up 1 เดือน "Oxygen Community"); วิทยากร คุณชลภพ (Martin) กรองทอง (Henkel, SCG, Plantoys; ที่ปรึกษา ธปท., TMA; MBA ธรรมศาสตร์, วิศวะ จุฬาฯ); Highlights 12 รูป; รีวิว 3 — *ในเว็บเดิมเป็นรีวิวของ Negotiation (คัดลอกมาผิด) ห้ามใช้*; ไม่มี FAQ
- **The New Manager** — Hook (Middle Power, AI ลดคอขวด); ปัญหา 4 (Star Player นำทีมไม่เป็น, ช่องว่างวัย/GROW feedback, ผู้จัดการเป็นคอขวด, กังวลเรื่อง AI); Outcomes 4 (Mindset, AI-Enhanced Productivity, Feedback & Coaching, Systematic Execution) + วิดีโอ; Quote "Managers fix problems. Leaders empower people to fix them."; **LEADER Journey 7 module** (L Lead Transition, E Empower Change, A Adapt Communication, D Developing Team, E Efficiency & AI Mastery, R Reinforce Teamwork, Final Presentation); วิทยากร 4 คน (ดร.ชัยพัชร์, คุณชลภพ, คุณกิตติพัฒน์ ชุณหวิริยกุล, คุณพุทธิพงษ์ ศิริโชคดี/Innovate AI); Highlights 5 รูป; กลุ่มเป้าหมาย 4 ข้อ; FAQ 5 (ใคร, 3 ชม./ครั้งพอไหม, เน้น AI แค่ไหน, ใบประกาศ, วิธีสมัคร) — *เว็บเดิมเขียน 7 สัปดาห์ แต่ FAQ บอก 7 ครั้งใน 8 สัปดาห์ ให้ถาม*
- **Lean Transformation in 7 weeks** — Hook (ต้นทุนขึ้นกำไรไม่ขึ้น = ความสูญเสียที่ซ่อนอยู่); อาการ 4 (งานช้าเพราะรอ → Continuous Flow/Pull, ของเสีย/งานซ้ำ → Jidoka/Standardized Work, สต็อกเกิน → JIT, ทำแต่ไม่สร้างคุณค่า → 7 Wastes); Outcomes 3 (Maximizing Quality, Minimizing Cost, Optimizing Speed) + วิดีโอ; Quote; **หลักสูตร 7 สัปดาห์ 6 module** (W1 DNA of Toyota, W2 Efficiency Architecture, W3 Standardized Excellence, W4 Kaizen & Planning, W5–6 On-site Transformation กับผู้เชี่ยวชาญ Toyota, W7 Final Presentation); กลุ่มเป้าหมาย (โรงงาน, ผู้ผลิตชิ้นส่วน, SME, บริการ); FAQ 4 (องค์กรแบบไหน, ทำไม 7 สัปดาห์, ได้ดูโรงงาน Toyota จริงไหม, ส่งได้กี่คน); ไม่มีวิทยากรระบุชื่อ, ไม่มีรีวิว
- **TEN X รุ่น 6** — การ์ดในหน้า Public/หน้าแรกเท่านั้น ลิงก์ออก tenx.utcc.ac.th (หลักสูตรร่วม หอการค้าไทย / สภาหอการค้าฯ / ม.หอการค้าไทย / DeOne Academy) → ต้องรองรับ **คอร์สที่ลิงก์ออกนอกเว็บ** (meta `hri_external_url` หรือ register_url + การ์ดลิงก์ออก) ตอนนี้ seed ยังไม่มี TEN X

### 1.4 วิทยากร (ใช้ซ้ำหลายคอร์ส)

ควรเป็นข้อมูลชุดเดียวใช้ซ้ำ (CPT `trainer` หรือ synced pattern ต่อคน) แทนการพิมพ์ซ้ำ (เว็บเดิม The Next Director แสดงบล็อกวิทยากรซ้ำ 6 ครั้ง)
- ดร.ชัยพัชร์ เลิศรักษ์ทวีกุล (Dr. Chaipat Lertlucktaweekul) — CEO; ประวัติ SCG, Tesco Lotus; Leadership GRID (USA), Leadership Coaching LMI (USA), NLP Practitioner & Coach ABNLP (USA), Profitable Negotiation VFM (UK), IOD DCP / Director Mentorship / Advanced Audit; ผู้อำนวยการหลักสูตร TEN X
- คุณชลภพ (Martin) กรองทอง — Lead Facilitator, Advisory Board
- คุณกิตติพัฒน์ ชุณหวิริยกุล — LMI certification, Passion Test, อดีต Index Living Mall
- คุณพุทธิพงษ์ ศิริโชคดี — CEO Innovate AI
- (ตรวจสะกดชื่อกับ Jimmi ทุกคน — เว็บเดิมสะกดอังกฤษไม่ตรงกัน)

---

## 2. หน้าคอร์ส In-house (`/courses/<slug>/`)

ตรวจตัวอย่าง Coaching Mastery โครงคือ:
1. H1 ชื่อไทยยาว + ชื่ออังกฤษ ("หลักสูตรยกระดับทักษะการโค้ชสู่ความเป็นมืออาชีพ (Coaching Mastery)")
2. Intro 1 ย่อหน้า
3. "คุณกำลังเจอปัญหาเหล่านี้อยู่ไหม?" 4 ข้อ + ไอคอน (เช่น The Directive Trap, Underutilized Talent, Ineffective Development, Communication Gap)
4. Business Outcomes 5 ข้อ + วิดีโอแนวตั้ง (เช่น Strategic Productivity Enhancement, Agile Problem-Solving Culture/G.R.O.W., High-Performance Communication, Sustainable Talent Pipeline, Measurable Business Growth)
5. ทำไมต้องจัดอบรมกับ HRI DeOne (4 ข้อเดียวกับ `/in-house/` → ใช้ synced pattern)
6. FAQ 5 ข้อ (ชุดเดียวกับ `/in-house/` → synced pattern)
7. ฟอร์มสอบถาม (ชื่อ, บริษัท, ตำแหน่ง, เบอร์, หลักสูตรที่สนใจ = เติมชื่อคอร์สให้อัตโนมัติ)

เว็บเดิมหน้า In-house **ไม่มี** outline, กลุ่มเป้าหมาย, ระยะเวลา, วิทยากร, รีวิว — ถ้าการตลาดมีข้อมูลควรเพิ่ม (โดยเฉพาะ outline + ระยะเวลา ช่วย convert มาก)

**งาน:** pattern หน้าคอร์ส In-house + ย้ายเนื้อหาครบ 15 คอร์ส (ต้องเปิดดูทีละหน้า — ยังตรวจแค่ Coaching Mastery):
high-performance-leadership-mastery, proactive-leadership-mastery, time-and-priority-mastery, growth-mindset-mastery, communication-mastery, coaching-mastery, conflict-management-mastery, change-transformation-mastery, b2b-selling-masterclass, negotiation-masterclass, strategic-selling-masterclass, consultative-selling-masterclass, essential-sales-masterclass, service-excellence, complaint-handling-excellence (URL `https://hrideone.com/courses/<slug>/`) · thumbnail เว็บเดิม เช่น `thumbnail-IH-03.webp`

---

## 3. Contact `/contact-us/` (ไม่มี — ทุก CTA ชี้มาที่นี่)

เว็บเดิมมี: Google Map (สำนักงาน ซ.จุฬาฯ 5), หัวข้อ "ติดต่อเรา / CONTACT US", ชื่อบริษัทไทย "เอช อาร์ ไอ ดีวัน จำกัด" + ที่อยู่ไทย + เลขทะเบียน 0105548058192, **Our Group Company**: HR Institute Co., Ltd. (1-13-23 Jingumae, Shibuya-ku, Tokyo 150-0001) และ HRI Vietnam (Sunwah Innovation Center, Ho Chi Minh City) พร้อมลิงก์เว็บ/แผนที่, โซเชียล, เบอร์โทร · **เว็บเดิมไม่มีฟอร์ม**

ควรทำ (เพื่อ convert):
- ฟอร์ม Lead (HANDOFF ข้อ 7.3) ฟิลด์ตามฟอร์มในหน้าคอร์ส: ชื่อ-นามสกุล, บริษัท, ตำแหน่ง, เบอร์, อีเมล, หลักสูตร/บริการที่สนใจ (dropdown: In-house / Public รายคอร์ส / Transformation / Japanese Concept / อื่นๆ), ข้อความ, checkbox PDPA · รับ `?course=<slug>` เพื่อเติมค่าให้อัตโนมัติจากปุ่มในหน้าคอร์ส
- ช่องทางด่วน: โทร (tel:), LINE, อีเมล + "ตอบกลับภายใน 24 ชั่วโมง"
- ที่อยู่ + แผนที่ (Jimmi เคยตัดสินใจไม่ใส่แผนที่ใน footer — ถามว่าในหน้า Contact ใส่ได้ไหม; แผนที่ embed ใช้ iframe ไม่ต้องใช้ปลั๊กอิน)
- Group companies (HRI Japan / HRI Vietnam) — ช่วยเรื่องความน่าเชื่อถือ
- แถบ CTA ใน footer ซ่อนอยู่แล้วในหน้านี้ (`hri_is_contact_page()`)

---

## 4. About `/about/` (ไม่มี)

เว็บเดิม (ตามลำดับ): รูป hero → **พันธกิจ** "Develop Leaders. Transform the Future." → **วิสัยทัศน์** "A trusted partner in shaping organizations that thrive across generations." → **คุณค่าหลัก** "Where Differences Become Strength." (การรวมสององค์กร) + รูป → **สาส์นจากผู้บริหาร** ฉบับเต็ม (HR Institute ได้รับความไว้วางใจจากองค์กรญี่ปุ่นตั้งแต่ 1993, DeOne Academy เชี่ยวชาญ Leadership & Transformation, ยกระดับสู่มาตรฐานสากล, ยุค AI ต้องการพาร์ทเนอร์ที่สร้างผลลัพธ์จริง, สะพานญี่ปุ่น–ไทย–อาเซียน, 30+ ปีกับบริษัทญี่ปุ่น) ลงชื่อ ดร.ชัยพัชร์ (CEO) และ Mr. Ken Misaka (Chairman) + รูป 2 ท่าน → **โลโก้ลูกค้า 40** → **Social Impact** "มุ่งพัฒนาสังคมให้น่าอยู่" (กิจกรรมชุมชนร่วม HRI Group) → บทความ 3 เรื่อง + View All

ควรเพิ่มนอกจากของเดิม (ถาม Jimmi ก่อน): ตัวเลขสถิติ (ใช้ชุดเดียวกับหน้าแรก), ไทม์ไลน์ (1993 HR Institute ก่อตั้งงานกับองค์กรญี่ปุ่น → DeOne Academy → ควบรวมเป็น HRI DeOne → งานเปิดตัว "Develop Leaders, Transform the Future"), Group companies, ปุ่ม CTA ไปบริการ 3 ด้าน
- สาส์นในหน้าแรกของเราย่อมาจากที่นี่ (HANDOFF ข้อ 6 บอกว่ายังไม่ครบ) → หน้า About ใส่ฉบับเต็ม หน้าแรกคงฉบับย่อ + ลิงก์ "อ่านต่อ"

---

## 5. Blog

เว็บเดิม: `/blog/` แสดงชื่อบทความอย่างเดียว (ไม่มีรูป/วันที่/excerpt) 15 เรื่องต่อหน้า × **10 หน้า (~150 บทความ)**, ตัวกรองหมวด: All, ไม่มีหมวดหมู่, Business, Sales, Coaching · บทความอยู่ที่ root (`/<slug>/` ไม่มี `/blog/` นำหน้า)

ต้องทำ:
- หน้า `/blog/` (template `home.html`/`archive.html` + Query Loop การ์ดแบบเดียวกับ "คลังความรู้" หน้าแรก: รูป 3:2, หมวด, วันที่, excerpt) + แถบหมวด + pagination · ภาษาละ 1 listing (Polylang)
- template บทความเดี่ยว (`single.html` มีแล้ว ตรวจว่ามี: หมวด, วันที่, รูปปก, เนื้อหา, บทความที่เกี่ยวข้อง 3 เรื่อง, CTA ไปบริการที่เกี่ยวกับหมวด)
- หน้าหมวด `/category/<slug>/` (เว็บเดิมมี business, sales, coaching) — เปลี่ยนหมวด "ไม่มีหมวดหมู่" เป็นชื่อจริง
- **ย้ายบทความทั้งหมด** ด้วย WordPress Export/Import (Tools → Export ที่เว็บเดิม) คง slug เดิมเพื่อไม่ต้อง 301 (permalink `/%postname%/` ตรงกันแล้ว) · รวม slug พิมพ์ผิด `why-success-ompanies-fail-in-15-years`
- หน้า 1 มีบทความ: ข้อ 1–3 (อยู่ใน seed แล้ว) + top-reasons-digital-transformation-fails, unlock-data-power-for-superior-sales-strategy, pride-month-workplace-diversity-matters, why-esg-matters-impact-cases-on-organizations, why-do-managers-need-to-develop-leadership-skills, 5-ways-to-avoid-employees-feeling-alienated, 7-ways-to-change-yourself-to-share-your-opinion, 4-tips-to-solve-silo-problem, professional-manager-can-create-different-results, challenges-of-culture-in-the-new-era-organization, too-kind-boss-is-silent-danger-in-organization, understanding-co-worker-style-improve-culture
- บทความ EN/JA: ไม่มีในเว็บเดิม — ตัดสินใจว่าแปลเฉพาะบางเรื่อง หรือ listing EN/JA แสดงว่างพร้อมข้อความแนะนำ (ถาม Jimmi)

---

## 6. หน้าย่อยบริการ

### 6.1 In-house: Leadership Development / Business Development / Team Simulation
- **Leadership Development** — intro (hub หลักสูตรผู้นำทุกระดับ สำหรับ HR วาง L&D) + **Lead Self** (คำอธิบาย) › Self Improvement / Thinking / Personal Communication (คำอธิบายแต่ละหัวข้อ + ลิงก์คอร์ส) + **Lead Team** › Coaching / Teamwork (ยังไม่มีคอร์ส) / Team Communication
- **Business Development** — intro (Digital Transformation, Growth Mindset, ใช้ข้อมูลหาโอกาส) + Strategy / Sales / Customer Service / Operation (ยังไม่มีคอร์ส) พร้อมคำอธิบาย + ลิงก์คอร์ส
- **Team Simulation** — เว็บเดิมว่าง (มีแค่หัวข้อ) → ถาม Jimmi ว่ามีบริการนี้จริงไหม/เนื้อหาคืออะไร
- เว็บใหม่: `/in-house/` มีการ์ด Leadership/Business พร้อมรายชื่อหัวข้อ แต่ **ไม่มีคำอธิบายรายหัวข้อ** (Self Improvement = …) ข้อเสนอ: (ก) ใส่คำอธิบายสั้นใต้แต่ละหัวข้อใน `/in-house/` + 301 สองหน้าย่อยมาที่ `/in-house/?pillar[]=…#courses` หรือ (ข) สร้างหน้าย่อยตามเดิม · ใส่คำอธิบาย topic เป็น term description ของ `hri_topic` แล้วแสดงในหน้า filter ตอนเลือกหัวข้อนั้น ก็ได้

### 6.2 Transformation Solutions (เทียบกับที่ย่อไว้)
- Business Strategy: เว็บเดิมมีคำปิด "องค์กรที่เติบโตได้อย่างยั่งยืน เริ่มจาก กลยุทธ์ ที่ทุกคนเดินไปด้วยกัน", คำเตือน "เปลี่ยนเร็วเกินไปโดยไม่มีโครงสร้างทำให้สับสน", tagline "90 วัน เพื่อการเปลี่ยนแปลงที่จับต้องได้" · 7 ขั้นของ Strategic Execution เว็บเดิมไม่ได้แจกแจงครบ (ถ้ามีเอกสาร ให้ทำเป็น steps)
- Technology Strategy: คำปิด "ความช้าอาจกลายเป็นจุดอ่อนของธุรกิจ" · ONE7AI ควรมีลิงก์/โลโก้ (ถาม)
- People Strategy: เว็บเดิมมีรูป 3 รูปใน Key Focus (PI) — ยังไม่มี · ควรมีโลโก้/ลิงก์ The Predictive Index (ตรวจสิทธิ์ใช้โลโก้)
- Culture Strategy: ครบ
- Partner: โลโก้ 8 ไฟล์ `logo-partner-01…08.png` ยังเป็น placeholder · ไม่รู้ชื่อบริษัท → ต้องได้ alt จาก Jimmi
- ทุกหน้าย่อยเว็บเดิม **ไม่มีปุ่ม CTA เลย** (ของใหม่มีแล้ว)
- ถ้าจะคงหน้าย่อย: แยก section ปัจจุบันออกเป็นหน้า `/transformation-solutions/<pillar>/` ใช้ pattern เดิม ไม่ต้องเขียนเนื้อหาใหม่ · ถ้าไม่คง: 301 ไป `#business-strategy` ฯลฯ

### 6.3 Japanese Concept
- หน้าหลักเว็บเดิมแทบว่าง (มีแค่หัวข้อ + tagline) — ของใหม่ดีกว่าแล้ว
- DO·WA·KEI (`/japanese-concept/do-wa-kei/`): ขาดใน build ใหม่ → **วิดีโอ** ใน Business Outcomes, ปุ่ม "สมัครเลย" เว็บเดิมไม่ระบุปลายทาง, ไม่มีราคา/วันที่/จำนวนที่นั่ง/ระยะเวลา Part 2 → ถ้าเป็นคอร์สเปิดรับสมัคร ควรทำเป็น `course` Public (ใช้ template ข้อ 1) แล้วหน้า Japanese Concept ลิงก์ไป
- Japanese Working Philosophy / Cross-cultural Communication & Collaboration: เว็บเดิมมีแค่ชื่อในเมนู (ลิงก์ไปหน้าแรก) **ไม่มีเนื้อหา** คำอธิบายในเว็บใหม่ผมเขียนเอง → ต้องได้เนื้อหาจริง (เป้าหมาย, กลุ่มผู้เรียน, หัวข้อ, ระยะเวลา, รูปแบบ)

---

## 7. หน้าแรก (ทำแล้ว ขาดเล็กน้อย)

| เว็บเดิม | เว็บใหม่ | ต้องทำ |
|---|---|---|
| แบนเนอร์ด้านบน = การ์ดคอร์ส 7 ใบ (5 Public + In-house + TEN X) แต่ละใบมีปุ่ม EXPLORE | Hero วิดีโอ/รูป + "หลักสูตรยอดฮิต" (อัตโนมัติ 4 คอร์สที่เปิด) | เพิ่ม TEN X (คอร์สลิงก์ออก ข้อ 1.3) · ถาม Jimmi ว่าอยากได้ slider แบนเนอร์คอร์สแบบเดิมไหม |
| หลักสูตรยอดฮิต = รูปแบนเนอร์ 2 รูป (Oxygen Leadership) | อัตโนมัติจากคอร์ส | — |
| บริการ 3 ด้าน + รูป Asset-10.png | infographic หลอดไฟ | — |
| Gallery "บรรยากาศการจัดอบรมที่ผ่านมา" | slider รูปตัวอย่าง | รูปจริง |
| คลังความรู้ 3 เรื่อง + View All | Query Loop + ปุ่ม → `/blog/` (ยัง 404) | ทำ `/blog/` (ข้อ 5) |
| ประโยคปิด "A trusted partner in shaping organizations that thrive across generations." ทุกหน้า | อยู่ใน tagline footer (ภาษาไทย) | ถามว่าอยากได้ประโยคอังกฤษนี้เป็นแถบเหนือ footer ทุกหน้าเหมือนเดิมไหม |

---

## 8. `/in-house/` และ `/public-training/` (ทำรอบนี้)

- Public: เว็บเดิมแสดง **วันที่อบรม** + คำอธิบาย 2–3 บรรทัดของแต่ละคอร์สในหน้า listing → การ์ดคอร์ส Public ควรมีวันที่ (`hri_schedule_text` หรือ start–end) และ excerpt ยาวกว่านี้
- Public: TEN X ยังไม่อยู่ในรายการ
- Public: รูปแบนเนอร์ "ทำไมต้องอบรม Public" `/wp-content/uploads/2026/09/1920x1080-01-1024x576.webp`
- In-house: รูป hero `/wp-content/uploads/2026/09/782.webp`
- In-house: เว็บเดิมมี **ฟอร์มสอบถามท้ายหน้า** (ชื่อ, บริษัท, ตำแหน่ง, เบอร์, หลักสูตรที่สนใจ) → เมื่อมีฟอร์ม Lead ให้ใส่ท้าย `/in-house/` และท้ายหน้าคอร์สด้วย ไม่ใช่แค่หน้า Contact
- In-house FAQ ข้อ "เริ่มต้นอย่างไร" เว็บเดิมพูดถึง "กรอกแบบฟอร์มด้านล่าง" → ตอนนี้ของเราชี้ไปหน้าติดต่อ (ปรับเมื่อมีฟอร์ม)

---

## 9. ทั้งเว็บ (global)

- **ฟอร์ม Lead** — เว็บเดิมใช้ Google Form แยกคอร์ส + ฟอร์มในหน้า; ของใหม่ยังไม่มีเลย (HANDOFF ข้อ 7.3) → เป็น blocker ของ Contact, หน้าคอร์ส, In-house
- **LINE OA ไม่ตรงกัน** — หน้าคอร์สเขียน @deoneacademy บางหน้า @hrideone; footer ลิงก์ page.line.me/hrideone → ถาม Jimmi ว่าใช้อันไหน
- **เบอร์โทร** — เขียน 065-0979444 และ 065-097-9444 → ใช้ `hri_company('phone')` ที่เดียว
- **เลขผู้เสียภาษี** — เว็บเดิมแสดงทุกหน้า, ของใหม่ตัดออก (Jimmi ตัดสินแล้ว) แต่ควรอยู่ในหน้า Contact
- **ชื่อบริษัทภาษาไทย** "เอช อาร์ ไอ ดีวัน จำกัด" (จากหน้า Contact เดิม) → ใช้ใน `hri_company_defaults()` แทนชื่ออังกฤษในที่อยู่ไทย (HANDOFF ข้อ 6 ค้างตรวจอยู่)
- **วิดีโอ MP4 แนวตั้ง** ใช้ซ้ำหลายหน้า (`0_Success_Cinematic_720x1280.mp4`, `0_Growth_Data_1080x1920.mp4`) → ต้องมีสไตล์ Video block แนวตั้ง (เล่นเงียบ วน มี poster, ไม่ autoplay ถ้า reduced motion — ใช้แนวทางเดียวกับ hero.js)
- **ไอคอนปัญหา** (webp) ในหน้าคอร์ส → ทำชุดไอคอน SVG ของเราเอง (สีตาม token) หรือใช้รูปเดิม
- **SEO** — หน้าคอร์สควรมี schema Course/CourseInstance (ราคา, วันที่, สถานที่) จาก meta ใหม่ข้อ 1.2 · FAQPage จาก FAQ ทุกหน้า (HANDOFF ข้อ 7.5)

---

## 10. เรื่องที่ต้องถาม Jimmi (รวม)

1. LINE OA ที่ถูกต้อง (@hrideone หรือ @deoneacademy)
2. รุ่นที่ถูกต้อง: The New Manager 21 หรือ 22, Lean 2 หรือ 3 · วันที่/สถานที่ The New Manager และ Lean รุ่นปัจจุบัน
3. The Next Director ปัญหาข้อ "อ่านตัวเลขไม่เป็น" เนื้อหาจริง
4. Oxygen Leadership: รีวิวจริง (ของเดิมคัดลอกจาก Negotiation), มี FAQ/ใบประกาศไหม
5. The New Manager: 7 สัปดาห์ หรือ 7 ครั้งใน 8 สัปดาห์ · เวลาเรียน
6. DO·WA·KEI เป็นคอร์สเปิดรับสมัครไหม (ราคา วันที่ ที่นั่ง ปุ่มสมัคร)
7. Team Simulation, Japanese Working Philosophy, Cross-cultural Communication — มีเนื้อหาไหม
8. ชื่อพาร์ทเนอร์ 8 ราย (Transformation) + ลิงก์ ONE7AI / The Predictive Index
9. หน้าย่อยบริการ: คงไว้ หรือรวมหน้าเดียว + 301
10. ประโยคปิดภาษาอังกฤษเหนือ footer ทุกหน้า: เอาไหม · แบนเนอร์คอร์สหน้าแรกแบบเดิม: เอาไหม
11. บทความ EN/JA: แปลไหม
12. ไฟล์: รูปจริงทั้งหมด (hero แต่ละหน้า, วิทยากร, Highlights, Gallery, โลโก้ลูกค้า 40, พาร์ทเนอร์ 8), วิดีโอ MP4, export บทความจากเว็บเดิม

---

## 11. ลำดับงานที่เสนอ (สำหรับแชทถัดไป)

1. **ฟอร์ม Lead + หน้า Contact** (ปลด 404 ของทุก CTA) — รวมฟอร์มท้าย `/in-house/` และ `?course=` prefill
2. **Content model คอร์ส** (meta ข้อ 1.2 + วิทยากร) + **pattern หน้าคอร์ส Public** + ย้ายเนื้อหา 5 คอร์ส + TEN X (ลิงก์ออก) + วันที่บนการ์ด
3. **pattern หน้าคอร์ส In-house** + ย้ายเนื้อหา 15 คอร์ส (synced pattern Why/FAQ)
4. **About**
5. **Blog listing/หมวด/single** + import บทความ
6. หน้าย่อยบริการ / 301 (ตามที่ Jimmi ตอบข้อ 10.9) + DO·WA·KEI เป็นคอร์ส
7. ใส่รูป/วิดีโอจริงทั้งเว็บ
