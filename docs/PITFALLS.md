# PITFALLS — ข้อควรระวัง / สิ่งที่เคยพลาด (HANDOFF ข้อ 8)

> แยกออกมาจาก `docs/HANDOFF.md` เมื่อ 2026-10-26 (เนื้อหาเดิมทุกตัวอักษร) · **เลขข้อคงเดิม**: ข้อ 3–4 = [`ARCHITECTURE.md`](ARCHITECTURE.md) · ข้อ 5 = [`history/DONE-LOG.md`](history/DONE-LOG.md) · ข้อ 6 = [`OPEN-QUESTIONS.md`](OPEN-QUESTIONS.md) · ข้อ 7 = [`BACKLOG.md`](BACKLOG.md) · ข้อ 8 = [`PITFALLS.md`](PITFALLS.md) · ข้อ 10 = [`PROMPTS.md`](PROMPTS.md) · ข้อ 1, 2, 9 = [`HANDOFF.md`](HANDOFF.md)

## 8. ข้อควรระวัง / สิ่งที่เคยพลาด

- `paginate_links` ใส่ class `page-numbers` ให้ `<ul>` ด้วย → CSS ต้องเจาะ `a.page-numbers, span.page-numbers`
- รูปใน `aspect-ratio` box ต้องมี `overflow:hidden` + `img{display:block}` ไม่งั้นรูปดันกล่องสูงเกิน
- Navigation block ของ core ใส่ `items-justified-right` → ใน overlay ต้องใช้ selector ที่เจาะจงกว่าเพื่อจัดชิดซ้าย
- ถ้าเมนูเปิดค้างแล้วขยายจอเป็น desktop เมนูเต็มจอจะค้าง (พฤติกรรม core)
- `curl` กับ URL ที่มี `[]` ต้องใช้ `-g`
- blueprint ใช้ `post_name__in` (ไม่ใช่ `name__in`)
- ไม่มีข้อมูลลับใน repo (API key, Sheet URL)
- Polylang: หลังลิงก์คำแปลของหน้าแรกต้อง `PLL()->model->clean_languages_cache()` ไม่งั้นภาษาจำ page_on_front เดิม; `redirect_lang` ต้องเปิดไม่งั้นหน้าแรก EN เป็น `/en/style-guide-en/`
- Polylang สร้างภาษาใน runPHP ได้เฉพาะเมื่อ `PLL_ADMIN` true (ไม่มีภาษา = ไม่มี `PLL()` ฝั่ง front)
- Theme pattern list ถูก cache ตาม version theme → เพิ่ม pattern ใหม่ให้ bump `Version` ใน style.css (local: `wp eval 'wp_get_theme()->delete_pattern_cache();'`)
- ทั้ง theme ไม่มี `box-sizing: border-box` global → element ที่ตั้ง `min-height`/padding ให้ใส่ `box-sizing: border-box` เอง
- Playground ที่เปิดด้วย `#<blueprint>` เปลี่ยนแค่ hash จะไม่โหลดใหม่ ให้เปลี่ยน query (`?v=2#...`)
- Gallery ของ core ตั้งความกว้าง `figure` ด้วย `:not(#individual-image)` → CSS ที่จะ override ต้องใส่ selector เดียวกัน · ในกล่อง scroll ที่มี padding `%` ของ flex-basis คิดจากความกว้างหลังหัก padding → slider ใช้ `vw`
- `wp i18n make-pot` ของ theme ต้องใส่ `--skip-theme-json` (ไม่งั้นจะโหลด theme-i18n.json จาก svn.wordpress.org ซึ่ง workspace เข้าไม่ได้)
- Screenshot แบบ full page ไม่โหลดรูป `loading="lazy"` → ตั้ง `loading="eager"` ก่อนถ่าย หรือถ่ายทีละ section
- Editor ใน local ใช้ `wait_until: domcontentloaded` (admin ยิงไป wordpress.org ไม่จบ networkidle)
- วันที่บทความใน local เป็นภาษาอังกฤษเพราะไม่มี language pack ไทยของ core (บนโฮสต์จริงติดตั้ง ภาษาไทย แล้วจะเป็นเดือนไทย)
- ลบ/ย้าย component ใน pattern แล้วต้อง seed หน้าใหม่ (page content เก็บใน DB): local ใช้ `wp eval-file playground/seed.php` แล้ว `seed-languages.php` · ระวังตอนลบ CSS ทั้งบล็อก — กฎ selector ที่ใช้ร่วมกัน (เช่น `.hri-post`) อยู่ปนกับของที่ลบ
- เพิ่ม/แก้ข้อความใน pattern ของ theme: `wp i18n make-pot . languages/hri-theme.pot --skip-js --skip-theme-json --skip-block-json` → `wp i18n update-po` → ใส่ msgstr (อย่าให้ polib เขียนไฟล์ใหม่ทั้งไฟล์ diff จะบวม) → `make-mo` + `make-php`
- อย่าตั้ง className ของ section ซ้ำกับ class ของ block ใน hri-core (เช่น `hri-courses` = layout ของ course filter ทำให้หน้าเพี้ยน) ใช้ `hri-courses-section`
- **ตรวจหลังแก้ทุกครั้ง: `python3 -I scripts/measure.py`** (อยู่ใน repo แล้ว) · วัด overflow แนวนอน, tap target <44px, contrast AA, PHP notice, JS error ที่ 1280/768/375 × TH/EN/JA × `/`, `/courses/`, `/in-house/`, `/public-training/`, คอร์ส In-house 1 + Public 1, `/contact-us/` = 63 รอบ (~5 นาที) · exit code 1 ถ้ามีปัญหา
  - ตัวเลือก: `--base http://127.0.0.1:8899/wp` (ค่าเริ่มต้น) · `--pages /about/,/blog/` (ใส่ path ภาษาไทย สคริปต์ต่อ `-en`/`-ja` ให้เอง ยกเว้น `/` และ `/courses/`) · path ที่ EN/JA ไม่ใช้ `-en`/`-ja` เขียนแบบ `th|en|ja` เช่น `/category/sales/|/category/sales-en/|/category/sales-ja/` (`/blog/` ตั้งไว้ใน `PATH_OVERRIDE` แล้ว) · `--widths 1280,375` · `--langs th` · `--json out.json` · `--verbose`
  - ต้องมี Playwright (Chromium อยู่ที่ /opt/pw-browsers) · รันด้วย `python3 -I`
  - ตรวจ tap target ใน 3 สถานะ: ปกติ, เปิด `<details>` ทั้งหมด (ตัวกรองมือถือ, dropdown ภาษา), เปิดเมนูแฮมเบอร์เกอร์ · checkbox ใน label วัดที่ label · ลิงก์ inline กลางประโยคและ honeypot ข้าม · ลิงก์การ์ดที่ใช้ `::after` คลุมทั้งการ์ด (stretched link) วัดทั้งการ์ด · ข้อความบน Cover block (hero รูป/วิดีโอ) ข้ามการวัด contrast เพราะขึ้นกับ overlay (ตรวจด้วยมือ 4.9:1)
  - สคริปต์ **ไม่จับ** ปัญหาด้านหน้าตา (เช่น องค์ประกอบที่ควรซ่อนแต่โผล่) → ยังต้องถ่าย screenshot ดูเฉพาะจุดที่แก้ · เจอมาแล้วในรอบที่ 5: กฎ `display:flex` ที่เพิ่มให้ `summary` ตัวกรองทับ `display:none` ของ desktop
- **class `.hri-card` / `.hri-card__title` ซ้ำกันระหว่าง theme (การ์ดหน้าบริการ, `base.css`) กับ plugin (การ์ดคอร์ส, `courses.css`)** · theme โหลดทีหลัง จึงทับขนาดหัวข้อของ plugin (การ์ดคอร์สเคยเป็น x-large เพราะเหตุนี้) → ถ้าจะแก้เฉพาะการ์ดคอร์สให้ใช้ `.hri-course-card` (เพิ่ม class นี้ใน `course-card.php` แล้ว) ถ้าแก้ `.hri-card` ตรงๆ จะกระทบการ์ดหน้าบริการด้วย
- `container-type` ทำให้ element นั้นเป็น containing block ของลูกที่ `position:absolute` → อย่าใส่ที่ `.hri-card__body` (ลิงก์ `::after` คลุมทั้งการ์ดจะถูกตัดแค่ในส่วน body) ใส่ที่ตัวการ์ด `.hri-course-card` เท่านั้น
- Tap target: ใช้ `var(--wp--custom--tap)` (token `tap` = 44px) เป็น `min-block-size`/`min-inline-size` + `inline-flex` + `align-items:center` + `box-sizing:border-box` · ตัวอักษร/ไอคอนขนาดเดิม แค่พื้นที่กดขยาย
- Production/Ruk-com: พิจารณา `define( 'PLL_COOKIE', false );` ใน wp-config เพื่อไม่ให้ cookie ภาษาไปรบกวน Rcache (ภาษาอยู่ใน URL แล้ว)
- `wp eval-file` ต้องใช้ path แบบ absolute · seed ใหม่ต้อง `wp eval 'wp_get_theme()->delete_pattern_cache();'` (หรือ bump Version) ก่อน แล้ว `seed.php` → `seed-languages.php`
- Pattern PHP ที่ใช้ helper `hri_bm_*` ต้องส่ง array ตามรูปแบบที่ helper คาด (`hri_bm_cards` รับ associative: title/text/help/kicker/num) ส่งแบบ indexed จะ fatal
- Block ที่ render ด้วย PHP แล้วออก HTML เอง ต้องใส่ class `is-layout-flex wp-block-buttons-is-layout-flex` ให้ `wp-block-buttons` ไม่งั้นปุ่มซ้อนกัน (core ใส่ให้เฉพาะ block ที่ผ่าน parser)
- Constrained layout ของ core ตั้ง `margin-inline:auto` ทับ CSS ของเรา → ถ้าอยากชิดซ้ายต้อง `!important` หรือ selector เจาะจงกว่า (`.hri-banner > .hri-banner__text`)
- `wp_set_post_categories` ในรอบ seed ซ้ำอาจคง Uncategorized ไว้ → ใช้ `wp_set_object_terms( $id, [term_id], 'category' )`
- `wp i18n make-pot` ของ plugin ต้องใส่ `--skip-block-json` (ไม่งั้นยิงไป svn.wordpress.org) และ `update-po` ต้องใช้ path absolute
- Screenshot ใน local: php -S รับทีละ request รูปบางรูปโหลดไม่ทัน (เห็นเป็นกล่องว่าง) ไม่ใช่บั๊ก — ตรวจด้วย `naturalWidth`
- `?course=` ชนกับ query var ของ CPT `course` ทำให้หน้าเพี้ยน → ใช้ `?hri_course=`
- **ปุ่มที่ PHP พิมพ์ HTML เอง** (`wp-block-button__link` นอก parser) ได้แค่สไตล์ element button ของ theme.json: ไม่มี CSS ของ core Button (ลิงก์ inline → padding ล้นกล่อง ทับของข้างล่าง) และไม่มี variation `outline` → `wp_enqueue_style( 'wp-block-button' )` + กฎ outline ใน `base.css` (หน้าอื่นไม่เห็นปัญหาเพราะมี Button block จริงในหน้า)
- ปิดงานแล้วต้องหยุดเซิร์ฟเวอร์ local (`php -S` ที่รันเป็น background task) ด้วย ไม่งั้นแอปของ Jimmi แสดงว่ายังทำงานอยู่
- ไอคอนใน CSS ใช้ `mask` → สีคือ `background-color` ไม่ใช่ `color` · ไอคอนใหม่ต้องเพิ่มใน `scripts/build-icons.py` (ตัวแปร CSS มีเฉพาะที่ตั้ง `True`)
- วัด overflow ด้วย JS ต้องข้ามช่อง honeypot `.hri-lead__hp` (ซ่อนนอกจอโดยตั้งใจ) ไม่งั้นทุกหน้าที่มีฟอร์มโดน flag ผิด
- `scripts/local-setup.sh` เมื่อรันผ่าน pipe (`| tail`) ในเครื่องมือที่มี timeout อาจค้างหลังติดตั้ง+seed เสร็จแล้ว (เซิร์ฟเวอร์ `php -S` ที่สคริปต์เปิดไว้ยังเป็น child) → รันแบบ background หรือเปลี่ยนเป็น `> log 2>&1` แล้วเช็กว่าเสร็จด้วย `curl -sI http://127.0.0.1:8899/wp/` และ `wp post list --post_type=course --format=count` (ได้ 21 หลัง seed)
- **Polylang ไม่สนใจ `'lang'` ใน query ที่ใช้ `name` (slug)** → `get_posts( name + lang )` คืนโพสต์ภาษาอื่นได้ ต้องเช็ก `pll_get_post_language()` เอง (ใช้ใน `blog.php`, `blog-import.php`)
- Polylang redirect `/en/<slug>/` ไปโพสต์ภาษาไทยที่มี slug นั้น (canonical) ก่อน `template_redirect` ของเรา → redirect ของ EN/JA ต้องทำใน `parse_request`
- ปุ่มใน form ห้ามชื่อ `submit` (`submit_button()` ค่าเริ่มต้น) ถ้าจะเรียก `form.submit()` ด้วย JS — ชื่อนี้บังเมธอด
- `upload_max_filesize` ค่าเริ่มต้น 2MB (php -S ใน local) → ไฟล์นำเข้าใช้ `.json.gz` (0.7MB)
- REST API ใน local (php -S) ใช้ `/?rest_route=/wp/v2/...` (`/wp-json/` ไม่ route)
- ตรวจ block valid ทั้งเว็บ: เปิด `post-new.php` แล้วใช้ `wp.blocks.parse()` กับ `content.raw` ของทุกโพสต์ (ดู `isValid` ทุก block รวม innerBlocks)
- regex หา URL ใน**ข้อความ JSON** ต้องหยุดที่ `\"` ด้วย (`[^"\\\s)]+`) ไม่งั้นได้ URL ที่มี `\` ติดท้าย (รอบที่ 7 รูปในเนื้อหา 425 รูปพังเพราะเหตุนี้)
- GitHub API ผ่าน proxy ของ Claude: สั่ง/ดูสถานะ workflow ได้ (`gh api .../actions/workflows/<file>/dispatches`, `.../runs/<id>`) แต่ **อ่าน log ไม่ได้** และ **เปิด GitHub Pages ไม่ได้** · workflow ที่รันนานให้พิมพ์ความคืบหน้า + มีเพดานเวลา + commit ผลแม้ล้ม (`if: always()`)
- gzip ของ Python คนละเวอร์ชันได้ไบต์ต่างกันแม้ข้อความเดียวกัน → อย่าเขียน `.gz` ใหม่ถ้าข้อมูลไม่เปลี่ยน (ไม่งั้น commit วน)
- jsDelivr cache `@main` (ไฟล์ที่แก้ทับชื่อเดิมอาจเห็นของเก่าได้หลายชั่วโมง) → ไฟล์ใหม่ตั้งชื่อใหม่ หรือ purge ที่ `https://purge.jsdelivr.net/gh/imeeba/hrideone-web@main/<path>`
- Playground: เปิดหน้าอื่นใช้ช่อง URL ด้านล่างของ Playground หรือพารามิเตอร์ `&url=/path/` (ตั้ง `location` ของ iframe ตรงๆ ได้หน้าว่าง เพราะ URL มี scope) · iframe ซ้อน 2 ชั้น
- `download_url()` ปฏิเสธพอร์ตที่ไม่ใช่ 80/443/8080 → ทดสอบ download ใน local ต้องเพิ่ม `http_allowed_safe_ports` และใช้ชื่อโฮสต์คนละชื่อกับเว็บ (`localhost` vs `127.0.0.1`) เพราะ importer ข้าม URL ของเว็บตัวเอง
- Polylang จาก GitHub ใช้ tag `3.9` (ตัว `3.9.0-rc1`/beta ก็มี → เลือก tag ที่ไม่มี rc/beta) · seed ภาษา: `wp hri languages` → `wp eval-file <absolute>/playground/seed.php` → `seed-languages.php` ตามลำดับ (ตอนรัน `seed.php` ครั้งแรกใน local-setup ยังไม่มี Polylang เป็นปกติ)

- **รูป (รอบที่ 11)**: `set_quality()` ของ WordPress ไม่มีผลเมื่อบันทึกเป็นนามสกุลใหม่ (PNG→WebP) เพราะ `save()` ตั้ง quality ใหม่จาก filter `wp_editor_set_quality` → ถ้าจะ override ต้องใช้ filter ที่ priority สูงกว่าและเปิดค้างไว้จน `save()` จบ (`hri_image_convert_file()` ทำให้แล้ว) · `wp_unique_filename()` มองว่า `name.jpg` ชน `name.webp` → ต้องลบต้นฉบับก่อนตั้งชื่อ ไม่งั้นได้ `name-1.webp` · ประเภทไฟล์ของ attachment (`post_mime_type`) ไม่เท่ากับไฟล์จริงเสมอ: WordPress ย่อรูปใหญ่เป็น `-scaled.webp` แต่ attachment ยังเป็น `image/jpeg` และเก็บ `original_image` (JPG ใหญ่) ไว้ → ตัดสินจาก `wp_check_filetype( $file )` · `wp_insert_post()` ต้อง `wp_slash()` ถ้า content มี `\/` (ใน test fixture) · บล็อก Featured Image ถ้าไม่ใส่ `sizeSlug` จะใช้ขนาดเต็ม · `wp-cli eval-file` ที่ไม่มี `mysql` ใช้ `wp db query` ไม่ได้ (SQLite) ให้ใช้ `wp eval 'echo get_post_field(...)'`

- **ความเร็ว (รอบที่ 12)**: Lighthouse ผ่าน `php -S` ตรงๆ ให้ตัวเลขผิด (ไม่บีบอัด) ใช้ `scripts/perf.py` ที่มีพร็อกซี · `pyftsubset` ของ glob `kanit-latin-*` จับ `latin-ext` ด้วย → ตัดไฟล์ ext เหลือ ~700 ไบต์ (เคยพลาด, แก้แล้วในสคริปต์) · core ไม่ใส่ `loading="lazy"` ให้ `<img>` ที่ไม่มี width+height · `git rm` ไฟล์ที่ถูกแก้ค้างอยู่ต้องใส่ `-f` · ทดสอบ before/after ด้วย `git worktree add /tmp/x origin/main` แล้วชี้ symlink ของ `hri-local` ไปที่นั่น (เสร็จแล้วชี้กลับ; `php -S` มี realpath cache สลับ symlink แล้วอาจเห็นของเก่า 1–2 request) · `<details open>` ที่ JS ปิดทีหลังบนมือถือ = layout shift ใหญ่ (ปิดด้วย inline script หลังองค์ประกอบ) · ตัวกรอง `wp_speculation_rules_href_exclude_paths` ใช้ pattern ของ URLPattern: `?` ใน pattern เป็นตัวขยาย ห้ามใส่ query string (core ข้ามทุก URL ที่มี query อยู่แล้ว) · `styles_inline_size_limit` เรียงไฟล์เล็กก่อนแล้ว inline จนเต็มงบ (ค่าเริ่มต้น 20 KB, ตอนนี้ 40 KB) · ฟอนต์ตัวเดียวกันที่ถูก subset ใหม่ไม่ต้องเปลี่ยน `unicode-range` (ตัวอักษรที่หายไปทั้งหมดนอก range เดิมอยู่แล้ว: ตัวผสม Vietnamese)

### ปัญหาที่เจอนอกขอบเขต (ยังไม่แก้)

> tap target ของ header/footer/ตัวกรอง/หน้าคอร์ส/ฟอร์ม และชื่อคอร์สตัดกลางคำ แก้แล้วในรอบที่ 5 (ข้อ 5)

- **ตัวกรองคอร์สบน desktop สูงกว่าหน้าจอ** (`/courses/` ที่ 1280: ~1390px, หลังเพิ่ม tap target 44px; ก่อนหน้า ~1150px ก็สูงกว่า viewport 900px อยู่แล้ว) แต่ `.hri-filter` เป็น `position: sticky` → ส่วนล่างของตัวกรอง (ระยะเวลา, รูปแบบ) เข้าถึงไม่ได้จนกว่าจะเลื่อนไปท้ายรายการคอร์ส · ข้อเสนอ: `max-height: calc(100vh - 2 * <top>)` + `overflow-y: auto` ที่ `.hri-filter` (ระวัง box-shadow ring ที่ `.hri-filter` ถูกตัด) หรือเลิก sticky
- `/style-guide/` ป้าย `#767C82` 4.47:1 (ต้อง 4.5) (Blog UI แก้แล้วในรอบที่ 6)
- ลิงก์ที่อยู่เดี่ยวๆ ในย่อหน้าของเนื้อหาบทความ (เช่น "ดูรายละเอียดหลักสูตร TEN X" ใน `why-success-ompanies-fail-in-15-years` ทั้ง 3 ภาษา) สูง 24px: ผ่าน WCAG 2.5.8 AA แต่ไม่ถึง 44px · ไม่ได้แก้ CSS เพราะ selector แยกลิงก์เดี่ยวกับลิงก์กลางประโยคไม่ได้แม่น → ทางเลือก: ให้การตลาดเปลี่ยนเป็น Button block ในบทความที่เป็น CTA
- ลิงก์ inline ในย่อหน้า PDPA ของฟอร์ม (`hri/lead-form`) สูง ~24px **คงไว้ตั้งใจ**: WCAG 2.5.8 ยกเว้นลิงก์ที่อยู่กลางประโยค และถ้าเพิ่ม padding ระยะบรรทัดจะห่างผิดปกติ (ผ่านเกณฑ์ AA 24px ที่ตั้งไว้ ไม่ถึง 44px) · `measure.py` ข้ามลิงก์ประเภทนี้
- การ์ดบริการ/หัวข้อ (`.hri-card__title` ของ theme) ยังไม่ได้ตรวจเรื่องคำยาวตัดกลางคำ (รอบที่ 5 แก้เฉพาะการ์ดคอร์ส)

**จากรอบที่ 9 (รูปจริง)**
- workspace ของ Claude เข้า hrideone.com และ jsDelivr ไม่ได้ (curl/WebFetch ถูกปฏิเสธ) → งานที่ต้องเปิดเว็บเดิม/ดาวน์โหลดรูปให้รันบน GitHub Actions (`crawl-images.yml`, `site-media.yml`, `demo-media.yml`) · workflow ที่ trigger ด้วย `push: paths` รันบน branch ได้ (ไม่ต้องกด Run) แต่ `workflow_dispatch` ใช้ได้เฉพาะ workflow ที่อยู่บน `main` · `gh` ใช้ได้ (`gh run list --branch <b>`) แม้ `gh auth status` จะขึ้น fail
- workflow commit กลับเข้า branch เอง → `git pull --rebase` ก่อน push ทุกครั้ง
- clone แบบ `--depth 1` ได้ refspec เฉพาะ `main` → branch ใหม่ไม่มี tracking (hook เตือน "no remote branch" ทั้งที่ push แล้ว) แก้: `git config --add remote.origin.fetch '+refs/heads/<b>:refs/remotes/origin/<b>' && git fetch origin && git branch --set-upstream-to=origin/<b>`
- screenshot ของ crawl ใหญ่ (16 MB) → ไม่เก็บใน repo (`.gitignore`) ดูย้อนหลังได้จาก commit `ae36993` (`git show ae36993:playground/data/crawl/shots/<ไฟล์>`) · `pages.json` เก็บไว้ (รายการรูปทุกหน้าของเว็บเดิม + URL ต้นฉบับ)
- รูปพื้นหลัง hero ของหน้าคอร์สเดิมไม่มีตัวหนังสือ (ข้อความเป็น HTML) → ใช้เป็นปกได้ · แต่ thumbnail ในหน้ารวมคอร์สเดิม (`thumbnail-IH-*.webp`, `1920x1080-0*.webp`, `Website-Public-page-*`) **มีตัวหนังสือไทยฝัง** ห้ามใช้เป็นปก (หน้า EN/JA)
- `782.webp` ที่ HANDOFF เดิมเขียนว่าเป็นรูป hero In-house จริงๆ เป็นภาพเครื่องหมายคำถาม 3D (รูป CTA ท้ายหน้าคอร์ส) · `1920x1080-01.webp` เป็นแบนเนอร์คอร์ส Negotiation มีตัวหนังสือ ไม่ใช่รูป "ทำไมต้อง Public"
- core/image: อย่าใส่ width/height ใน `<img>` ของ markup ที่ seed สร้าง (core เก็บเป็น attribute แล้ว save เป็น style → editor validate ไม่ผ่าน) · ขนาดใส่ได้เฉพาะรูปที่ PHP พิมพ์เอง (featured image ผ่าน filter ใน `blog.php`)
- ทดสอบ local: `scripts/local-setup.sh` + Polylang 3.9.1 (`git clone --depth 1 --branch 3.9.1 https://github.com/polylang/polylang` ใน plugins แล้ว `composer dump-autoload --no-dev`) + `wp hri languages` + `wp eval-file playground/seed-languages.php` · รูป site-media ใน local: `ln -s <repo>/playground/site-media <local>/site-media` + `wp config set HRI_SITE_MEDIA_BASE http://127.0.0.1:8899/site-media --type=constant` แล้วรัน seed ซ้ำ · รูปบล็อก (jsDelivr) ใน local จะขึ้นเป็นรูปเสีย = ปกติ
- slider ที่มีสไลด์ >5 บนจอ 375: ปุ่มลูกศรเคยหดเหลือ 28px (flex shrink) แก้แล้ว ถ้าเพิ่ม control อื่นในแถวนี้ให้เช็ก tap target อีกครั้ง

**จากรอบที่ 10 (ค้นหา)**
- **ทดสอบกับ MySQL ไม่ได้ใน workspace** (มีแต่ SQLite) → SQL ในดัชนีเขียนแบบมาตรฐาน (`CREATE TABLE`, `IN`, `LIKE` + `esc_like`, ไม่มี FTS/คำสั่งเฉพาะ SQLite) แต่ **ต้องลองบน Ruk-com จริงก่อนเปิดใช้**: `wp hri search reindex` แล้ว `wp hri search query เจรจา` · ถ้าตารางไม่ถูกสร้าง ดู `$wpdb->last_error` · คอลัมน์ `token` เป็น `utf8mb4_bin` (ต้อง MySQL ≥5.5.3)
- **ถ้าโฮสต์ไม่มี ext-intl** ระบบสลับเป็น n-gram เองและสร้างดัชนีใหม่ (ผลไทยดีพอ ดัชนีใหญ่ขึ้น ~2.5 เท่า) · ถาม Ruk-com ว่าเปิด intl ได้ไหม (`php -m | grep intl`)
- ความต่างของ SQLite integration: เขียนดัชนีทีละโพสต์ช้า (~35–40 ms ไม่ว่าจะ batch ขนาดไหน) จึงสร้างดัชนีใน Playground ตอน boot ~20 วินาที (ขั้นสุดท้ายใน blueprint) · อย่าเรียก `hri_search()` ในโปรเซสเดียวกับที่เพิ่งบันทึกโพสต์แล้วหวังเห็นผล (ดัชนีอัปเดตตอน `shutdown`)
- ICU ตัดคำโดยไม่สนบริบทไม่ได้: คำเดียวกันพิมพ์เดี่ยวๆ กับอยู่ในประโยคอาจตัดไม่เหมือนกัน (ชื่อทับศัพท์) → มีตัวสำรอง substring · อย่าลบตัวสำรองนี้
- pattern ของ theme ถูก cache ตามเลขเวอร์ชัน theme → เพิ่ม pattern ใหม่แล้ว WordPress ไม่เห็นจนกว่าจะ bump `Version` ใน `style.css`
- `wp i18n update-po` + polib เขียนไฟล์ใหม่ทั้งไฟล์ (diff บวม) → เติม `msgstr` ด้วยสคริปต์แก้ข้อความตรงๆ (plural ของ JA มีฟอร์มเดียว `msgstr[0]`)
- `curl` กับ `?s[]=a` ต้องใส่ `-g` ไม่งั้น curl ตีความ `[]` เป็น glob

**ปัญหานอกขอบเขตที่เจอในรอบที่ 10 (ยังไม่แก้)**
- **ข้อเสนอแนะ UX ที่ยังไม่ได้ทำ (จากการตรวจซ้ำรอบที่ 10)**: ไอคอนค้นหาข้างปุ่มเมนูบนมือถือ (ตอนนี้ต้องเปิดเมนูก่อน) · "คุณหมายถึง …" เมื่อพิมพ์ผิดเล็กน้อย (negotation → negotiation) · เก็บคำที่ค้นแล้วไม่เจอไว้ให้ทีมการตลาดเห็นใน Settings → การค้นหา (เพื่อเพิ่ม synonym) · "หลักสูตรแนะนำ" ตอนนี้ = คอร์สเปิดรับ 3 ตัวแรกตามลำดับ ไม่ใช่ตามความนิยม (ถ้าต้องการให้ทีมเลือกเอง ทำช่องเลือกใน Settings) · คำอังกฤษหลายคำ (เช่น "TEN X") ยังไม่บังคับให้อยู่ติดกัน และตัวอักษรเดี่ยว ("X") ถูกตัดทิ้ง · เลขหน้าในมุมมองประเภทเดียวยังแสดงทุกหน้า (ควรย่อเมื่อเกิน ~7)
- ตัวกรองคอร์ส (`hri/course-filter` ช่อง `q`) ยังค้นด้วย `LIKE` ของ WordPress → คำไทยยาวหรือ synonym ไม่ทำงานเหมือนค้นทั้งเว็บ · ถ้าอยากให้เหมือนกัน ให้เรียก `hri_search( $q, array( 'types' => array( 'course' ) ) )` แทน
- หน้า 404 ชื่อหน้า (`<title>`) เป็นภาษาอังกฤษ "Page not found" ทุกภาษา (ข้อความของ core)
- ผลค้นหาไม่แสดงตัวอย่างข้อความที่ตรงคำ (แสดง excerpt/ต้นเนื้อหา) และไม่ไฮไลต์คำ — ตั้งใจให้เบา
- **จำกัดความถี่ฟอร์ม Lead ใช้ IP จริงแล้ว** (`hri_lead_ip()`): เชื่อ header proxy เฉพาะเมื่อ peer เป็น IP ภายใน หรือเมื่อตั้ง `HRI_IP_HEADER` ใน wp-config.php (ดู `LEAD-SETUP.md`) · **ต้องตรวจหลังขึ้นโฮสต์จริง**: ถ้าอยู่หลัง Cloudflare/proxy สาธารณะ ต้องตั้ง `HRI_IP_HEADER` ให้ตรงกับโฮสต์ ไม่งั้นทุกคนถูกนับเป็นคนเดียวกัน (5 ครั้ง/10 นาทีรวมทั้งเว็บ) · Turnstile ยังปิดอยู่จนกว่าจะใส่ key
