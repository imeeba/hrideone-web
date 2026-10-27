<?php
/**
 * Sample course content for previews, from the current hrideone.com Public Training pages.
 *
 * The texts are re-written summaries (the original wording could not be copied verbatim), in Thai, English
 * and Japanese (machine-made, JA needs a native check). Facts (dates, venues, prices, form links) come from
 * the live site as of 19 Oct 2026 and must be checked by the team before launch. See docs/HANDOFF.md.
 *
 * Used by seed-courses.php (Thai) and seed-languages.php (EN / JA).
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Trainers: key => array( 'th' => array( name, position, bio html ), 'en' => …, 'ja' => … ).
 */
function hri_seed_trainers(): array {
	$certs_th = '<!-- wp:list --><ul><li>Leadership GRID (USA)</li><li>Leadership Coaching, LMI (USA)</li><li>NLP Practitioner &amp; NLP Coach, American Board of NLP (USA)</li><li>Profitable Negotiation, VFM Associates (UK)</li><li>IOD: Director Certification Program, Director Mentorship Program, Advanced Audit Certification Program</li></ul><!-- /wp:list -->';
	return array(
		'chaipat' => array(
			'th' => array( 'ดร.ชัยพัชร์ เลิศรักษ์ทวีกุล', 'CEO, HRI DeOne', '<!-- wp:paragraph --><p>ผู้เชี่ยวชาญด้านการพัฒนาภาวะผู้นำและการขาย อดีตผู้บริหารระดับสูงของ SCG และ Tesco Lotus ผู้อำนวยการหลักสูตร TEN X</p><!-- /wp:paragraph -->' . $certs_th ),
			'en' => array( 'Dr. Chaipat Lertlucktaweekul', 'CEO, HRI DeOne', '<!-- wp:paragraph --><p>Leadership and sales development expert, formerly a senior executive at SCG and Tesco Lotus. Program Director of TEN X.</p><!-- /wp:paragraph -->' . $certs_th ),
			'ja' => array( 'Dr. Chaipat Lertlucktaweekul（チャイパット博士）', 'CEO, HRI DeOne', '<!-- wp:paragraph --><p>リーダーシップ開発と営業の専門家。SCG、Tesco Lotusで要職を歴任。TEN Xのプログラムディレクター。</p><!-- /wp:paragraph -->' . $certs_th ),
		),
		'martin'  => array(
			'th' => array( 'คุณชลภพ (Martin) กรองทอง', 'Lead Facilitator · Advisory Board, HRI DeOne', '<!-- wp:paragraph --><p>ประสบการณ์กว่า 20 ปี ในบทบาทผู้บริหารด้านการตลาดที่ Henkel, SCG และ Plantoys ทั้งกลยุทธ์ B2B และ B2C เป็นที่ปรึกษาและวิทยากรให้หน่วยงานระดับชาติ เช่น ธนาคารแห่งประเทศไทยและ TMA</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>MBA การตลาดและธุรกิจระหว่างประเทศ มหาวิทยาลัยธรรมศาสตร์ · วิศวกรรมอุตสาหการ จุฬาลงกรณ์มหาวิทยาลัย</p><!-- /wp:paragraph -->' ),
			'en' => array( 'Martin (Cholapob) Krongthong', 'Lead Facilitator · Advisory Board, HRI DeOne', '<!-- wp:paragraph --><p>Over 20 years as a senior marketing executive at Henkel, SCG and Plantoys, covering B2B and B2C strategy. Consultant and speaker for national organizations such as the Bank of Thailand and TMA.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>MBA in Marketing and International Business, Thammasat University · B.Eng. in Industrial Engineering, Chulalongkorn University</p><!-- /wp:paragraph -->' ),
			'ja' => array( 'Martin (Cholapob) Krongthong（マーティン）', 'リードファシリテーター・アドバイザリーボード、HRI DeOne', '<!-- wp:paragraph --><p>Henkel、SCG、Plantoysでマーケティング責任者を務めた20年超の経験を持ち、B2BとB2C双方の戦略に精通。タイ中央銀行やTMAなど国内の主要機関でコンサルタント・講師を務める。</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>タマサート大学MBA（マーケティング・国際ビジネス）／チュラロンコン大学工学部（産業工学）</p><!-- /wp:paragraph -->' ),
		),
		'kittipat' => array(
			'th' => array( 'คุณกิตติพัฒน์ ชุณหวิริยกุล', 'วิทยากรและผู้เชี่ยวชาญด้านการพัฒนาตนเอง', '<!-- wp:paragraph --><p>ผ่านการรับรอง LMI และ Passion Test อดีตผู้บริหาร Index Living Mall</p><!-- /wp:paragraph -->' ),
			'en' => array( 'Kittipat Chunhawiriyakul', 'Speaker and self-development specialist', '<!-- wp:paragraph --><p>LMI-certified and Passion Test practitioner; former executive at Index Living Mall.</p><!-- /wp:paragraph -->' ),
			'ja' => array( 'Kittipat Chunhawiriyakul（キティパット）', '講師・自己成長スペシャリスト', '<!-- wp:paragraph --><p>LMI認定、パッション・テスト実践者。元Index Living Mall幹部。</p><!-- /wp:paragraph -->' ),
		),
		'puttipong' => array(
			'th' => array( 'คุณพุทธิพงษ์ ศิริโชคดี', 'CEO, Innovate AI', '<!-- wp:paragraph --><p>ผู้บริหาร Innovate AI ถ่ายทอดการใช้ AI เพื่อเพิ่มประสิทธิภาพการทำงานของผู้จัดการในโมดูล Efficiency &amp; AI Mastery</p><!-- /wp:paragraph -->' ),
			'en' => array( 'Puttipong Sirichokdee', 'CEO, Innovate AI', '<!-- wp:paragraph --><p>Head of Innovate AI; leads the Efficiency &amp; AI Mastery module on using AI to raise managers’ productivity.</p><!-- /wp:paragraph -->' ),
			'ja' => array( 'Puttipong Sirichokdee（プッティポン）', 'CEO、Innovate AI', '<!-- wp:paragraph --><p>Innovate AI代表。Efficiency &amp; AI Masteryモジュールで、マネージャーの生産性を高めるAI活用を担当。</p><!-- /wp:paragraph -->' ),
		),
	);
}

/**
 * Public Training courses: slug => array(
 *   'meta'     => language-neutral fields,
 *   'trainers' => trainer keys,
 *   'th' | 'en' | 'ja' => array( 'excerpt', 'text' => translated meta fields, 'page' => hri_course_page_markup() data ),
 * ).
 */
function hri_seed_public_courses(): array {
	$bangkok_club = 'The Bangkok Club';
	return array(
		/* ------------------------------------------------------------------ */
		'negotiation-mastery' => array(
			'meta'     => array( 'hri_batch_no' => 3, 'hri_reg_status' => 'open', 'hri_start_date' => '2026-10-29', 'hri_end_date' => '2026-10-30', 'hri_duration_days' => 2, 'hri_early_deadline' => '2026-09-29', 'hri_certificate' => '1', 'hri_register_url' => 'https://forms.gle/SSY8ceTTzuXhTQtD8' ),
			'trainers' => array( 'chaipat' ),
			'th'       => array(
				'excerpt' => 'พลิกเกมธุรกิจด้วยสุดยอดหลักสูตรการเจรจาต่อรอง',
				'text'    => array( 'hri_schedule_text' => '29–30 ต.ค. 2569', 'hri_location' => 'โรงแรม Chatrium Residence Sathorn', 'hri_price' => '25,000 บาท', 'hri_price_early' => '22,000 บาท', 'hri_audience' => 'ฝ่ายขายและพัฒนาธุรกิจ ฝ่ายจัดซื้อ เจ้าของ SME และผู้จัดการที่ต้องเจรจา' ),
				'page'    => array(
					'intro'    => array( 'การเจรจาที่ดีคือการสร้างมูลค่า ไม่ใช่การเอาชนะ', 'การเจรจาต่อรองที่เก่งจริงไม่ได้อยู่ที่การแบ่งพายก้อนเดิม หรือการเอาชนะอีกฝ่าย แต่คือการทำให้ “พาย” ก้อนนั้นใหญ่ขึ้นสำหรับทุกคน หลักสูตร 2 วันเข้มข้นนี้พาคุณเปลี่ยนมุมมองการเจรจา ฝึกกระบวนการ 8 ขั้นตอนที่ใช้ได้จริง และซ้อมจริงผ่านเวิร์กชอปพร้อมฟีดแบ็กรายบุคคลจากวิทยากร' ),
					'problems' => array(
						array( 'สงครามราคาที่กัดกินกำไร', 'ลดราคาเพื่อไม่ให้เสียดีลให้คู่แข่ง จนกำไรบางลงและลูกค้าเคยชินว่าต่อรองได้เสมอ', 'เรียนรู้การสื่อสารคุณค่าที่เหนือกว่าราคา เพื่อไม่ต้องแข่งกันที่ส่วนลดอย่างเดียว' ),
						array( 'ข้อโต้แย้งซ้ำซากที่ทำให้ดีลไม่ขยับ', '“งบไม่พอ” “ขอเทียบเจ้าอื่นก่อน” “ยังไม่เร่งด่วน” ข้อโต้แย้งเดิม ๆ ที่ทำให้การปิดการขายสะดุด', 'ฝึกรับมือข้อโต้แย้งและโน้มน้าวอย่างเป็นระบบ เปลี่ยนข้อโต้แย้งให้เป็นเงื่อนไขของข้อตกลง' ),
						array( 'ยอมเกินไป หรือแข็งเกินไป', 'บางคนยอมทุกอย่างเพื่อรักษาความสัมพันธ์ บางคนเอาชนะจนคู่ค้าไม่อยากคุยด้วยอีก', 'เรียนรู้ Principled Negotiation เพื่อให้ได้ทั้งผลลัพธ์และความสัมพันธ์ระยะยาว' ),
						array( 'ไม่มั่นใจเมื่อเจอคู่เจรจาที่ใหญ่กว่า', 'รู้สึกตัวเล็กเมื่อต้องเจอห้างค้าปลีกรายใหญ่ บริษัทข้ามชาติ หรือทีมจัดซื้อที่เก๋าเกม', 'สร้างอำนาจต่อรองและใช้ BATNA เป็นทางเลือกที่ดีที่สุดนอกโต๊ะเจรจา' ),
					),
					'outcomes' => array(
						array( 'Mindset Transformation', 'เปลี่ยนจากการแข่งขันแบบแพ้–ชนะ สู่การเจรจาเชิงร่วมมือที่สร้างมูลค่าร่วมกัน' ),
						array( 'Systematic Approach', 'กระบวนการเจรจา 8 ขั้นตอนที่พิสูจน์แล้ว ตั้งแต่การวิเคราะห์ ราคาสงวน ไปจนถึงการให้สัมปทานอย่างมีกลยุทธ์' ),
						array( 'Enhanced Confidence & Authority', 'ใช้ความฉลาดทางอารมณ์ อ่านภาษากาย และบริหารสถานะ เพื่อเจรจากับผู้บริหารระดับสูงหรือผู้ซื้อที่เก๋าเกมได้อย่างมั่นใจ' ),
						array( 'Adaptive Negotiation Tactics', 'รับมือแรงกดดัน เช่น การนิ่งเงียบหรือการกำหนดเวลา และควบคุมกรอบการเจรจา (Frame Control)' ),
					),
					'quote'    => array( 'You don’t get what you deserve, you get what you negotiate.', 'คุณไม่ได้สิ่งที่คุณควรได้ แต่ได้สิ่งที่คุณเจรจา' ),
					'program'  => array(
						'title'  => '2 Days Intensive',
						'lead'   => 'ภาคทฤษฎีประมาณ 40% ภาคปฏิบัติ 60% พร้อมฟีดแบ็กแบบเรียลไทม์จากวิทยากร',
						'groups' => array(
							array( 'Day 1', 'รากฐานการเจรจาอย่างมืออาชีพ', array( 'The New Paradigm of Negotiation', 'Negotiation vs. Persuasion', 'Decoding Interests: Position vs. Interest', 'Building Trust & Credibility', 'Creative Tactics for Shared Value', 'The Negotiator’s Golden Rules', 'Personal Profiling', 'Workshop I: Situation Analysis' ) ),
							array( 'Day 2', 'กระบวนการเจรจา 8 ขั้นตอนและการลงมือจริง', array( 'Step 1: Strategic Preparation & BATNA', 'Step 2: Professional Opening', 'Step 3: Signaling & Decoding', 'Step 4: Compelling Proposals', 'Step 5: Refining & Package Deals', 'Step 6: Bargaining & Overcoming Objections', 'Step 7: The Closing Masterclass', 'Step 8: Finalizing the Agreement', 'Advanced Tactics in Complex Situations', 'Role Play II: Full-Scale Simulation' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'reviews'  => array(
						array( 'คุณวิทวัส', 'ผู้จัดการฝ่ายขายและพัฒนาธุรกิจ', 'ใช้ระบบ 8 ขั้นตอนและ BATNA ปิดดีลกับพันธมิตรรายใหญ่ได้ โดยยังรักษามาร์จิ้นตามเป้า' ),
						array( 'คุณศิริพร', 'เจ้าหน้าที่จัดซื้ออาวุโส', 'เวิร์กชอปและ Role Play ทำให้เห็นจุดอ่อนของตัวเองเวลารับมือข้อโต้แย้ง และเรียนรู้ที่จะมองผลประโยชน์ที่อยู่เบื้องหลังจุดยืน' ),
						array( 'คุณนพพล', 'ผู้บริหารงานขายลูกค้ารายสำคัญ', 'ได้เทคนิค Signaling และกล้าเผชิญการปฏิเสธมากขึ้น' ),
					),
					'cta'      => array( 'พร้อมเปลี่ยนวิธีเจรจาของคุณแล้วหรือยัง', 'ลงทะเบียนวันนี้ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง' ),
					'faq'      => array(
						array( 'ใครควรเรียนหลักสูตรนี้?', 'เหมาะกับฝ่ายขายและพัฒนาธุรกิจ ฝ่ายจัดซื้อ เจ้าของ SME รวมถึงหัวหน้างานและผู้จัดการระดับกลางที่ต้องเจรจาภายในองค์กร' ),
						array( 'คนที่ไม่ช่างพูดหรือขี้อายเรียนได้ไหม?', 'ได้ หลักสูตรเน้นการฟัง การวิเคราะห์ และเทคนิคการตั้งคำถาม ซึ่งไม่ต้องอาศัยความช่างพูด' ),
						array( 'ภาคทฤษฎีและภาคปฏิบัติสัดส่วนเท่าไร?', 'ประมาณ 40 : 60 มีการวิเคราะห์กรณีศึกษาและ Role Play พร้อมฟีดแบ็กแบบเรียลไทม์' ),
						array( 'มีใบประกาศนียบัตรไหม?', 'มี ผู้เข้าอบรมทุกท่านจะได้รับใบประกาศนียบัตร (Certificate of Completion)' ),
						array( 'สมัครได้ช่องทางไหนบ้าง?', 'กดปุ่ม “สมัครเลย” โทรหาทีมงาน หรือติดต่อทาง LINE ทีมงานตอบกลับภายใน 24 ชั่วโมง' ),
					),
				),
			),
			'en'       => array(
				'excerpt' => 'Change the game in business with a top-tier negotiation course.',
				'text'    => array( 'hri_schedule_text' => '29–30 Oct 2026', 'hri_location' => 'Chatrium Residence Sathorn Hotel', 'hri_price' => 'THB 25,000', 'hri_price_early' => 'THB 22,000', 'hri_audience' => 'Sales and business development, procurement, SME owners and managers who negotiate' ),
				'page'    => array(
					'intro'    => array( 'Good negotiation creates value; it does not just win', 'Great negotiators do not split a fixed pie or beat the other side. They make the pie bigger for everyone. This intensive 2-day course changes how you see negotiation, teaches a practical 8-step process, and lets you practise in workshops with individual feedback from the trainer.' ),
					'problems' => array(
						array( 'Price wars that eat your profit', 'Discounting so as not to lose deals to competitors leaves thin margins and customers who expect to haggle every time.', 'Learn to communicate value beyond price, so you do not compete on discounts alone.' ),
						array( 'The same objections, deal after deal', '“No budget”, “let me compare with others”, “not urgent”: familiar objections that stall the close.', 'Practise handling objections and persuading systematically, turning objections into terms of the agreement.' ),
						array( 'Giving in too much, or pushing too hard', 'Some concede everything to keep the relationship; others win so hard that partners avoid them.', 'Learn Principled Negotiation to protect both the result and the long-term relationship.' ),
						array( 'Low confidence against bigger counterparts', 'Feeling small in front of large retailers, multinationals or seasoned procurement teams.', 'Build bargaining power and use your BATNA, the best alternative away from the table.' ),
					),
					'outcomes' => array(
						array( 'Mindset Transformation', 'Move from win–lose competition to collaborative negotiation that creates shared value.' ),
						array( 'Systematic Approach', 'A proven 8-step process, from analysis and reservation price to strategic concessions.' ),
						array( 'Enhanced Confidence & Authority', 'Use emotional intelligence, body-language reading and status management with senior executives and tough buyers.' ),
						array( 'Adaptive Negotiation Tactics', 'Handle pressure such as silence or deadlines, and control the frame of the negotiation.' ),
					),
					'quote'    => array( 'You don’t get what you deserve, you get what you negotiate.', '' ),
					'program'  => array(
						'title'  => '2 Days Intensive',
						'lead'   => 'About 40% theory and 60% practice, with real-time feedback from the trainer.',
						'groups' => array(
							array( 'Day 1', 'Foundations of professional negotiation', array( 'The New Paradigm of Negotiation', 'Negotiation vs. Persuasion', 'Decoding Interests: Position vs. Interest', 'Building Trust & Credibility', 'Creative Tactics for Shared Value', 'The Negotiator’s Golden Rules', 'Personal Profiling', 'Workshop I: Situation Analysis' ) ),
							array( 'Day 2', 'The 8-step process and hands-on practice', array( 'Step 1: Strategic Preparation & BATNA', 'Step 2: Professional Opening', 'Step 3: Signaling & Decoding', 'Step 4: Compelling Proposals', 'Step 5: Refining & Package Deals', 'Step 6: Bargaining & Overcoming Objections', 'Step 7: The Closing Masterclass', 'Step 8: Finalizing the Agreement', 'Advanced Tactics in Complex Situations', 'Role Play II: Full-Scale Simulation' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'reviews'  => array(
						array( 'Khun Witawat', 'Manager, Sales & Business Development', 'Used the 8-step system and BATNA to close a deal with a major partner while keeping margins on target.' ),
						array( 'Khun Siriporn', 'Senior Procurement Officer', 'The workshops and role plays showed my weak points in handling objections, and taught me to look at the interests behind positions.' ),
						array( 'Khun Noppol', 'Key Account Executive', 'Learned signaling techniques and became less afraid of rejection.' ),
					),
					'cta'      => array( 'Ready to change the way you negotiate?', 'Register today and our team will get back to you within 24 hours.' ),
					'faq'      => array(
						array( 'Who is this course for?', 'Sales and business development staff, procurement teams, SME owners, and supervisors or mid-level managers who negotiate internally.' ),
						array( 'Can quiet or shy people join?', 'Yes. The course focuses on listening, analysis and questioning techniques that do not depend on being talkative.' ),
						array( 'How much is theory and how much practice?', 'About 40 : 60, with case analysis and role plays and real-time feedback.' ),
						array( 'Is there a certificate?', 'Yes, every participant receives a Certificate of Completion.' ),
						array( 'How can I register?', 'Press “Register now”, call the team, or contact us on LINE. We reply within 24 hours.' ),
					),
				),
			),
			'ja'       => array(
				'excerpt' => '交渉力を磨き、ビジネスの流れを変える実践講座。',
				'text'    => array( 'hri_schedule_text' => '2026年10月29日〜30日', 'hri_location' => 'Chatrium Residence Sathorn ホテル', 'hri_price' => '25,000バーツ', 'hri_price_early' => '22,000バーツ', 'hri_audience' => '営業・事業開発、購買、中小企業経営者、交渉を担うマネージャー' ),
				'page'    => array(
					'intro'    => array( '優れた交渉は、勝つことではなく価値をつくること', '交渉の達人は、決まったパイを分け合ったり相手を負かしたりしません。全員にとってパイそのものを大きくします。2日間の集中講座で、交渉の見方を変え、実践的な8ステップを学び、講師の個別フィードバックのもとワークショップで練習します。' ),
					'problems' => array(
						array( '利益を削る価格競争', '競合に案件を取られないよう値引きを重ね、利益率が下がり、顧客も「いつでも値引きできる」と思い込んでしまう。', '価格以外の価値を伝える方法を学び、値引きだけで競わない交渉へ。' ),
						array( '同じ反論で止まる商談', '「予算がない」「他社と比較したい」「急ぎではない」。決まり文句の反論でクロージングが止まる。', '反論対応と説得を体系的に練習し、反論を合意の条件へと変える。' ),
						array( '譲りすぎ、または押しすぎ', '関係維持のためにすべて譲る人もいれば、勝ちすぎて取引先に避けられる人もいる。', '原則立脚型交渉（Principled Negotiation）で、成果と長期的な関係の両方を守る。' ),
						array( '格上の相手に自信が持てない', '大手小売、多国籍企業、百戦錬磨の購買チームを前にすると萎縮してしまう。', '交渉力を高め、交渉の席の外にある最善の代替案（BATNA）を活用する。' ),
					),
					'outcomes' => array(
						array( 'Mindset Transformation', '勝ち負けの競争から、共に価値を生む協調的な交渉へ。' ),
						array( 'Systematic Approach', '分析、留保価格、戦略的な譲歩まで、実証済みの8ステップ。' ),
						array( 'Enhanced Confidence & Authority', '感情知能、ボディランゲージの読み取り、ステータス管理で、役員や手強いバイヤーとも対等に。' ),
						array( 'Adaptive Negotiation Tactics', '沈黙や期限などのプレッシャーに対応し、交渉のフレームをコントロールする。' ),
					),
					'quote'    => array( 'You don’t get what you deserve, you get what you negotiate.', '得られるのは「値するもの」ではなく、「交渉して勝ち取ったもの」。' ),
					'program'  => array(
						'title'  => '2 Days Intensive',
						'lead'   => '理論約40%、実践約60%。講師からリアルタイムでフィードバックを受けます。',
						'groups' => array(
							array( 'Day 1', 'プロフェッショナルな交渉の基礎', array( 'The New Paradigm of Negotiation', 'Negotiation vs. Persuasion', 'Decoding Interests: Position vs. Interest', 'Building Trust & Credibility', 'Creative Tactics for Shared Value', 'The Negotiator’s Golden Rules', 'Personal Profiling', 'Workshop I: Situation Analysis' ) ),
							array( 'Day 2', '8ステップの交渉プロセスと実践', array( 'Step 1: Strategic Preparation & BATNA', 'Step 2: Professional Opening', 'Step 3: Signaling & Decoding', 'Step 4: Compelling Proposals', 'Step 5: Refining & Package Deals', 'Step 6: Bargaining & Overcoming Objections', 'Step 7: The Closing Masterclass', 'Step 8: Finalizing the Agreement', 'Advanced Tactics in Complex Situations', 'Role Play II: Full-Scale Simulation' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'reviews'  => array(
						array( 'ウィタワット様', '営業・事業開発マネージャー', '8ステップとBATNAを使い、利益率の目標を守ったまま大手パートナーとの契約をまとめられました。' ),
						array( 'シリポーン様', '購買シニアオフィサー', 'ワークショップとロールプレイで反論対応の弱点に気づき、立場の背後にある利害を見るようになりました。' ),
						array( 'ノッパン様', '大口顧客担当営業', 'シグナリングの技術を学び、断られることへの不安が小さくなりました。' ),
					),
					'cta'      => array( '交渉のやり方を変える準備はできましたか', '本日お申し込みいただければ、24時間以内に担当者からご連絡します。' ),
					'faq'      => array(
						array( 'どんな人に向いていますか。', '営業・事業開発、購買、中小企業経営者、社内で交渉を行う管理職に向いています。' ),
						array( '話すのが苦手な人でも受講できますか。', 'はい。傾聴、分析、質問の技術が中心で、話し上手である必要はありません。' ),
						array( '理論と実践の割合は。', '約40：60です。ケース分析とロールプレイを行い、リアルタイムでフィードバックします。' ),
						array( '修了証はありますか。', 'はい。受講者全員に修了証（Certificate of Completion）を発行します。' ),
						array( '申し込みはどこからできますか。', '「今すぐ申し込む」ボタン、お電話、またはLINEからお申し込みいただけます。24時間以内に返信します。' ),
					),
				),
			),
		),
		/* ------------------------------------------------------------------ */
		'the-next-director' => array(
			'meta'     => array( 'hri_batch_no' => 3, 'hri_reg_status' => 'open', 'hri_start_date' => '2026-11-11', 'hri_end_date' => '2026-11-26', 'hri_duration_days' => 6, 'hri_early_deadline' => '2026-10-11', 'hri_certificate' => '1', 'hri_register_url' => 'https://forms.gle/p6fsgEHb2sQE5Knz7' ),
			'trainers' => array( 'chaipat' ),
			'th'       => array(
				'excerpt' => 'พร้อมก้าวข้ามขีดจำกัด สู่บทบาท Director ที่องค์กรต้องการ',
				'text'    => array( 'hri_schedule_text' => '11–12, 18–19, 25–26 พ.ย. 2569', 'hri_time_text' => '09:00–16:00', 'hri_location' => $bangkok_club, 'hri_price' => '66,500 บาท', 'hri_price_early' => '59,500 บาท', 'hri_audience' => 'ผู้สืบทอดตำแหน่ง (High Potential) และ Director ใหม่ที่อยู่ในตำแหน่ง 0–2 ปี' ),
				'page'    => array(
					'intro'    => array( 'จาก “Controller” สู่ “Strategic Orchestrator”', 'องค์กรมักเลื่อนผู้ที่เก่งที่สุดขึ้นเป็น Director แต่หลายคนยังบริหารแบบ Manager คือคุมรายละเอียด แก้ปัญหาแทนทีม และวัดความสำเร็จจากงานที่เสร็จ ทั้งที่บทบาท Director คือผู้ประสานกลยุทธ์ นำวิสัยทัศน์ขององค์กรและบริหารระบบนิเวศของคนและงาน ช่องว่างนี้ปิดได้ด้วยการพัฒนาอย่างเป็นระบบ ไม่ใช่แค่ประสบการณ์หรือเวลา' ),
					'problems' => array(
						array( 'ขาดผู้สืบทอดที่พร้อมรับตำแหน่ง', 'เมื่อตำแหน่ง Director ว่างลง องค์กรมักหาคนที่พร้อมไม่ได้ เพราะไม่มีระบบพัฒนาและเตรียมผู้สืบทอดล่วงหน้า', 'เรียนรู้ Strategic Talent Management ด้วย 9-Box Grid, Competency Mapping และ Succession Planning' ),
						array( 'เก่งปฏิบัติ แต่อ่อนกลยุทธ์', 'ลงมือเก่ง แต่ตัดสินใจไม่ทำบางเรื่องได้ยาก ทรัพยากรจึงกระจายจนผลลัพธ์ไม่ชัด', 'ฝึกมอง Helicopter View คิดเชิงระบบนิเวศ และแปลงวิสัยทัศน์เป็นแผนที่ทำได้จริง' ),
						array( 'อ่านตัวเลขไม่ขาด ข้อเสนอจึงไม่ผ่าน', 'โครงการดี ๆ ถูก CFO หรือบอร์ดปฏิเสธ เพราะนำเสนอเป็นภาษางาน ไม่ใช่ภาษามูลค่าและตัวเลข', 'เสริม Financial Mindset และการสื่อสารคุณค่าสำหรับผู้บริหาร' ),
						array( 'กำแพง Silo และความขัดแย้งที่ไม่ถูกแก้', 'แต่ละหน่วยงานทำงานได้ดีในกรอบของตน แต่เป้าหมายร่วมกลับติดขัด', 'เข้าใจสไตล์ภาวะผู้นำของตน และเปลี่ยนความเห็นต่างของทีมผู้บริหารให้เป็นพลังสร้างสรรค์' ),
					),
					'outcomes' => array(
						array( 'Strategic Talent & Self-Mastery', 'เข้าใจสไตล์ภาวะผู้นำและจุดบอดของตน แล้วใช้การบริหารคนเก่ง Competency Mapping และ Succession Planning' ),
						array( 'Balanced Leadership Mindset', 'ขับเคลื่อนผลลัพธ์ (Task) พร้อมสร้างความไว้วางใจ (People) รู้ว่าเมื่อไรควรผลักดัน เมื่อไรควรรับฟัง' ),
						array( 'Comprehensive Executive Skillset', 'กลยุทธ์ ความเข้าใจธุรกิจและการเงิน การนำการเปลี่ยนแปลงและ Generative AI และการสื่อสารกับ CFO บอร์ด และทีมหน้างาน' ),
						array( 'Stronger Cohort Collaboration', 'เรียนร่วมกัน 6 วันเต็ม สร้างเครือข่ายข้ามองค์กรที่อยู่ต่อไปหลังจบหลักสูตร' ),
					),
					'quote'    => array( 'Effective leadership is not about making speeches or being liked; leadership is defined by results, not attributes.', 'ภาวะผู้นำที่แท้จริงไม่ได้วัดจากสุนทรพจน์หรือความนิยม แต่วัดจากผลลัพธ์ — Peter F. Drucker' ),
					'program'  => array(
						'title'  => '6 Days Executive Transformation Journey',
						'lead'   => 'เรียน 3 สัปดาห์ สัปดาห์ละ 2 วัน มีช่วงนำไปปฏิบัติจริงในงานระหว่างสัปดาห์ และ Executive Dialogue กับผู้บริหารรับเชิญทุกวัน',
						'groups' => array(
							array( 'Day 1', 'Self-Awareness & Talent Management Strategy', array( 'Executive self-awareness และ Mindset Shift', 'Director ในฐานะ Talent Magnet', 'Strategic Talent Management Framework: 9-Box Grid, Competency Mapping, Succession Planning' ) ),
							array( 'Day 2', 'Strategic Thinking & Visionary Leadership', array( 'The Helicopter View', 'Vision Casting', 'Strategic Prioritization: การเลือกและการแลกเปลี่ยน (Trade-offs)' ) ),
							array( 'Day 3', 'Business Acumen & Organizational Value', array( 'Financial Mindset for Directors', 'Value Chain Optimization', 'Outcome-Driven Leadership: OKR / KPI' ) ),
							array( 'Day 4', 'Executive Communication & Personal Branding', array( 'Executive Presence (Gravitas)', 'Stakeholder Management: Power–Interest Grid', 'The Leader as a Communicator: Storytelling' ) ),
							array( 'Day 5', 'Driving Change & Digital Transformation', array( 'Change Leadership Models', 'AI-Augmented Leadership', 'Agile Governance' ) ),
							array( 'Day 6', 'Creating Team Synergy', array( 'The Managerial Grid for Directors: 9,9 Team Management', 'Cross-Functional Synergy', 'Conflict Resolution in the Grid', 'Graduation' ) ),
						),
					),
					'trainers' => true,
					'cta'      => array( 'พร้อมก้าวสู่บทบาท Director ที่องค์กรต้องการแล้วหรือยัง', 'ลงทะเบียนวันนี้ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง องค์กรส่งเป็นกลุ่มได้' ),
					'faq'      => array(
						array( 'หลักสูตรนี้เหมาะกับใคร?', 'ผู้สืบทอดตำแหน่ง (High Potential ในสายงาน) และ Director ใหม่ที่อยู่ในตำแหน่ง 0–2 ปี องค์กรสามารถส่งเป็นกลุ่มได้' ),
						array( 'ทำไมต้อง 6 วัน?', 'การก้าวจาก Manager เป็น Director คือการเปลี่ยนบทบาท หลักสูตรจึงครอบคลุมการรู้จักตนเอง กลยุทธ์ การเงิน การสื่อสาร การนำการเปลี่ยนแปลง และการสร้างทีม โดยเรียน 3 สัปดาห์ สัปดาห์ละ 2 วัน' ),
						array( 'รูปแบบการเรียนเป็นอย่างไร?', 'ใช้ D.E.E.P. Leadership Impact Model ช่วงเช้าถึง 14:30 น. เรียนกรอบแนวคิดและเครื่องมือ ต่อด้วยเวิร์กชอปและแผนปฏิบัติ จากนั้น 14:45–16:00 น. เป็น Executive Dialogue กับผู้บริหารรับเชิญ' ),
						array( 'Guest Speaker ต่างจากวิทยากรหลักอย่างไร?', 'วิทยากรหลักสอนตั้งแต่ 09:00–14:30 น. ให้กรอบแนวคิด เครื่องมือ และแบบฝึกปฏิบัติ ส่วน Guest Speaker ช่วง 14:45–16:00 น. เป็นผู้บริหารระดับสูงจากองค์กรชั้นนำ มาแบ่งปันประสบการณ์จริงตามหัวข้อของแต่ละวัน' ),
						array( 'มีใบประกาศนียบัตรไหม?', 'ผู้ที่เข้าเรียนครบตามเกณฑ์ได้รับใบประกาศนียบัตรในพิธีจบหลักสูตร (Graduation) ช่วงบ่ายของวันสุดท้าย' ),
						array( 'สมัครอย่างไร?', 'กดปุ่ม “สมัครเลย” หรือติดต่อทีมงานทางโทรศัพท์และ LINE ทีมงานตอบกลับภายใน 24 ชั่วโมง' ),
					),
				),
			),
			'en'       => array(
				'excerpt' => 'Go beyond your limits and step into the Director role your organization needs.',
				'text'    => array( 'hri_schedule_text' => '11–12, 18–19, 25–26 Nov 2026', 'hri_time_text' => '09:00–16:00', 'hri_location' => $bangkok_club, 'hri_price' => 'THB 66,500', 'hri_price_early' => 'THB 59,500', 'hri_audience' => 'Successors (high potentials) and new Directors with 0–2 years in the role' ),
				'page'    => array(
					'intro'    => array( 'From “Controller” to “Strategic Orchestrator”', 'Organizations often promote their best performers to Director, but many keep managing like Managers: controlling details, solving problems for the team and measuring success by finished tasks. A Director is a strategic orchestrator who carries the organization’s vision and manages an ecosystem of people and work. That gap is closed by systematic development, not by experience or time alone.' ),
					'problems' => array(
						array( 'No successor ready to step up', 'When a Director seat opens, organizations struggle to find someone ready because successors were never developed in advance.', 'Learn Strategic Talent Management with the 9-Box Grid, Competency Mapping and Succession Planning.' ),
						array( 'Strong at execution, weak at strategy', 'Great at doing, but hard to decide what not to do, so resources are spread thin and results blur.', 'Practise the Helicopter View, ecosystem thinking and turning vision into an actionable plan.' ),
						array( 'Can’t speak in numbers, so proposals fail', 'Good projects are rejected by the CFO or board because they are pitched in the language of tasks, not value and figures.', 'Build a financial mindset and value-based communication for executives.' ),
						array( 'Silos and unresolved conflict', 'Each unit performs well inside its boundary, but shared goals stall.', 'Understand your leadership style and turn disagreement in the senior team into constructive energy.' ),
					),
					'outcomes' => array(
						array( 'Strategic Talent & Self-Mastery', 'Understand your leadership style and blind spots, then apply talent management, competency mapping and succession planning.' ),
						array( 'Balanced Leadership Mindset', 'Drive results (Task) while building trust (People), and know when to push and when to listen.' ),
						array( 'Comprehensive Executive Skillset', 'Strategy, business and financial acumen, leading change and Generative AI, and communicating with CFOs, boards and frontline teams.' ),
						array( 'Stronger Cohort Collaboration', 'Six full days together build a lasting cross-organization network.' ),
					),
					'quote'    => array( 'Effective leadership is not about making speeches or being liked; leadership is defined by results, not attributes.', '— Peter F. Drucker' ),
					'program'  => array(
						'title'  => '6 Days Executive Transformation Journey',
						'lead'   => 'Three weeks, two days a week, with time to apply lessons at work in between and an Executive Dialogue with a guest speaker each day.',
						'groups' => array(
							array( 'Day 1', 'Self-Awareness & Talent Management Strategy', array( 'Executive self-awareness and mindset shift', 'The Director as a Talent Magnet', 'Strategic Talent Management Framework: 9-Box Grid, Competency Mapping, Succession Planning' ) ),
							array( 'Day 2', 'Strategic Thinking & Visionary Leadership', array( 'The Helicopter View', 'Vision Casting', 'Strategic Prioritization: choices and trade-offs' ) ),
							array( 'Day 3', 'Business Acumen & Organizational Value', array( 'Financial Mindset for Directors', 'Value Chain Optimization', 'Outcome-Driven Leadership: OKR / KPI' ) ),
							array( 'Day 4', 'Executive Communication & Personal Branding', array( 'Executive Presence (Gravitas)', 'Stakeholder Management: Power–Interest Grid', 'The Leader as a Communicator: Storytelling' ) ),
							array( 'Day 5', 'Driving Change & Digital Transformation', array( 'Change Leadership Models', 'AI-Augmented Leadership', 'Agile Governance' ) ),
							array( 'Day 6', 'Creating Team Synergy', array( 'The Managerial Grid for Directors: 9,9 Team Management', 'Cross-Functional Synergy', 'Conflict Resolution in the Grid', 'Graduation' ) ),
						),
					),
					'trainers' => true,
					'cta'      => array( 'Ready to step into the Director role your organization needs?', 'Register today and our team will get back to you within 24 hours. Organizations can send groups.' ),
					'faq'      => array(
						array( 'Who is this course for?', 'Successors (high potentials in the pipeline) and new Directors with 0–2 years in the role. Organizations can send groups.' ),
						array( 'Why six days?', 'Moving from Manager to Director is a change of role, so the course covers self-awareness, strategy, finance, communication, change and team building, over three weeks at two days a week.' ),
						array( 'How is each day run?', 'It follows the D.E.E.P. Leadership Impact Model: frameworks and tools until 14:30, then workshops and action planning, followed by an Executive Dialogue with a guest speaker from 14:45 to 16:00.' ),
						array( 'How is the guest speaker different from the main trainer?', 'The main trainer teaches from 09:00 to 14:30 with frameworks, tools and exercises. The guest speaker, from 14:45 to 16:00, is a senior executive from a leading organization who shares real experience on that day’s topic.' ),
						array( 'Is there a certificate?', 'Participants who meet the attendance criteria receive a certificate at the Graduation ceremony on the afternoon of the last day.' ),
						array( 'How do I register?', 'Press “Register now”, or contact the team by phone or LINE. We reply within 24 hours.' ),
					),
				),
			),
			'ja'       => array(
				'excerpt' => '限界を越え、組織が求めるディレクターへ。',
				'text'    => array( 'hri_schedule_text' => '2026年11月11〜12日、18〜19日、25〜26日', 'hri_time_text' => '09:00〜16:00', 'hri_location' => $bangkok_club, 'hri_price' => '66,500バーツ', 'hri_price_early' => '59,500バーツ', 'hri_audience' => '後継者候補（ハイポテンシャル）および就任0〜2年の新任ディレクター' ),
				'page'    => array(
					'intro'    => array( '「コントローラー」から「戦略オーケストレーター」へ', '組織は最も優秀な人材をディレクターに昇進させますが、多くはマネージャーのまま、細部を管理し、チームの代わりに問題を解き、完了した作業で成功を測ります。ディレクターの役割は、組織のビジョンを担い、人と仕事のエコシステムを動かす戦略オーケストレーターであること。このギャップは、経験や時間ではなく、体系的な育成で埋まります。' ),
					'problems' => array(
						array( '後継者が育っていない', 'ディレクターの席が空いたとき、事前に後継者を育てていないため、準備の整った人材が見つからない。', '9ボックス、コンピテンシーマッピング、サクセッションプランによる戦略的タレントマネジメントを学ぶ。' ),
						array( '実行は得意だが戦略が弱い', '実行力はあるが「やらないこと」を決められず、資源が分散して成果がぼやける。', 'ヘリコプタービュー、エコシステム思考、ビジョンを実行計画へ落とし込む力を磨く。' ),
						array( '数字で語れず、提案が通らない', '良い企画がCFOや取締役会で却下される。作業の言葉で話し、価値と数字の言葉で話していないため。', '経営者のための財務マインドと、価値を伝えるコミュニケーションを身につける。' ),
						array( 'サイロと放置された対立', '各部門は自分の枠内では機能するが、共通目標が進まない。', '自分のリーダーシップスタイルを理解し、経営チームの意見の違いを建設的なエネルギーに変える。' ),
					),
					'outcomes' => array(
						array( 'Strategic Talent & Self-Mastery', '自分のリーダーシップスタイルと盲点を理解し、タレントマネジメント、コンピテンシーマッピング、後継者計画に活かす。' ),
						array( 'Balanced Leadership Mindset', '成果（Task）を追いながら信頼（People）を築き、押すべき時と聞くべき時を見極める。' ),
						array( 'Comprehensive Executive Skillset', '戦略、事業・財務の理解、変革とGenerative AIの活用、CFO・取締役会・現場へのコミュニケーション。' ),
						array( 'Stronger Cohort Collaboration', '丸6日間を共に過ごし、修了後も続く組織横断のネットワークを築く。' ),
					),
					'quote'    => array( 'Effective leadership is not about making speeches or being liked; leadership is defined by results, not attributes.', '— Peter F. Drucker' ),
					'program'  => array(
						'title'  => '6 Days Executive Transformation Journey',
						'lead'   => '週2日×3週間。合間に職場で実践し、毎日ゲストスピーカーとのエグゼクティブ・ダイアログがあります。',
						'groups' => array(
							array( 'Day 1', 'Self-Awareness & Talent Management Strategy', array( 'エグゼクティブとしての自己認識とマインドシフト', 'タレント・マグネットとしてのディレクター', '戦略的タレントマネジメント：9-Box Grid、Competency Mapping、Succession Planning' ) ),
							array( 'Day 2', 'Strategic Thinking & Visionary Leadership', array( 'The Helicopter View', 'Vision Casting', '戦略的優先順位づけ：選択とトレードオフ' ) ),
							array( 'Day 3', 'Business Acumen & Organizational Value', array( 'Financial Mindset for Directors', 'Value Chain Optimization', 'Outcome-Driven Leadership: OKR / KPI' ) ),
							array( 'Day 4', 'Executive Communication & Personal Branding', array( 'Executive Presence (Gravitas)', 'Stakeholder Management: Power–Interest Grid', 'The Leader as a Communicator: Storytelling' ) ),
							array( 'Day 5', 'Driving Change & Digital Transformation', array( 'Change Leadership Models', 'AI-Augmented Leadership', 'Agile Governance' ) ),
							array( 'Day 6', 'Creating Team Synergy', array( 'The Managerial Grid for Directors: 9,9 Team Management', 'Cross-Functional Synergy', 'Conflict Resolution in the Grid', 'Graduation' ) ),
						),
					),
					'trainers' => true,
					'cta'      => array( '組織が求めるディレクターへ、踏み出しませんか', '本日お申し込みいただければ、24時間以内に担当者からご連絡します。団体でのご参加も可能です。' ),
					'faq'      => array(
						array( 'どんな人に向いていますか。', '後継者候補（ハイポテンシャル人材）と、就任0〜2年の新任ディレクター。団体での受講も可能です。' ),
						array( 'なぜ6日間なのですか。', 'マネージャーからディレクターへの移行は役割の転換です。自己認識、戦略、財務、コミュニケーション、変革、チームづくりを、週2日×3週間で学びます。' ),
						array( '1日の進め方は。', 'D.E.E.P. Leadership Impact Modelに沿い、14:30までフレームワークとツール、続いてワークショップとアクションプラン、14:45〜16:00にゲストとのエグゼクティブ・ダイアログを行います。' ),
						array( 'ゲストスピーカーはメイン講師とどう違いますか。', 'メイン講師は9:00〜14:30にフレームワーク、ツール、演習を担当します。ゲストスピーカーは14:45〜16:00に、一流企業の経営層がその日のテーマに沿った実体験を共有します。' ),
						array( '修了証はありますか。', '出席基準を満たした方に、最終日午後のGraduationで修了証を授与します。' ),
						array( '申し込み方法は。', '「今すぐ申し込む」ボタン、またはお電話・LINEでご連絡ください。24時間以内に返信します。' ),
					),
				),
			),
		),
		/* ------------------------------------------------------------------ */
		'oxygen-leadership' => array(
			'meta'     => array( 'hri_batch_no' => 2, 'hri_reg_status' => 'open', 'hri_start_date' => '2027-01-12', 'hri_end_date' => '2027-02-16', 'hri_early_deadline' => '2026-12-12', 'hri_register_url' => 'https://forms.gle/pcJP8cWSxvMd5VMe6' ),
			'trainers' => array( 'martin' ),
			'th'       => array(
				'excerpt' => 'พลิกบทบาทผู้นำ สร้างบรรยากาศให้ทีมเติบโตอย่างยั่งยืน',
				'text'    => array( 'hri_schedule_text' => '12, 19, 26 ม.ค. และ 2, 9, 16 ก.พ. 2570 (ติดตามผลหลังจบประมาณ 1 เดือน)', 'hri_location' => $bangkok_club, 'hri_price' => '66,500 บาท', 'hri_price_early' => '59,500 บาท', 'hri_audience' => 'ผู้จัดการและผู้นำที่ต้องการสร้างบรรยากาศและพลังให้ทีม' ),
				'page'    => array(
					'intro'    => array( 'ทีมที่ “หายใจไม่ออก” อาจขาดแค่ออกซิเจนของผู้นำ', 'ทีมที่อึดอัดไม่ได้เกิดจากขาดทักษะหรือทรัพยากรเสมอไป แต่มักขาด “ออกซิเจน” ที่ผู้นำเป็นคนสร้าง ผู้นำคือผู้กำหนดบรรยากาศของทีม Toxic Leader ทำลายทีม Oxygen Leader สร้างองค์กร Learning Lab 7 สัปดาห์นี้พาผู้นำปรับมุมมอง สร้างบรรยากาศ และเสริมพลังให้ทีมเติบโตอย่างยั่งยืน' ),
					'problems' => array(
						array( 'องค์กรที่เคลื่อนช้า', 'งานค้างรอการตัดสินใจจากคนเดียว และทีมไม่ชัดเจนในเป้าหมาย', 'ตัดสิ่งรบกวนและสร้างเงื่อนไขที่โปร่งใส เพื่อให้ทีมตัดสินใจเองได้' ),
						array( 'ทีมเก่งแต่หมดแรง', 'คนเก่งหมดไฟและเริ่มคิดจะลาออก', 'ออกแบบงานให้ยั่งยืนและปกป้องพลังงานของทีม' ),
						array( 'บรรยากาศตึงเครียด ไม่กล้าพูด', 'ความไว้วางใจและความปลอดภัยทางจิตใจต่ำ ความคิดเห็นถูกกลืน ความขัดแย้งไม่ถูกพูดถึง', 'ฝึกเอาใจเขามาใส่ใจเรา การฟังอย่างลึก และการวางขอบเขตที่ชัดเจน' ),
						array( 'ฟื้นตัวช้าหลังเจอความผิดพลาด', 'ทีมโทษคนแทนที่จะแก้ปัญหา และผู้นำที่คุมอารมณ์ไม่ได้ทำให้เรื่องแย่ลง', 'ซ่อมกระบวนการ ไม่ใช่โทษคน และนำอย่างนิ่งภายใต้แรงกดดัน' ),
					),
					'outcomes' => array(
						array( 'The Atmosphere Architect', 'เปลี่ยนจากผู้บังคับใช้กฎเป็นผู้ออกแบบบรรยากาศของทีม และสร้างความไว้วางใจ' ),
						array( 'Tactical Clarity & Filter', 'กรอบ “5 Leads” สื่อสารเป้าหมายให้ชัด ลดประชุมที่เสียเปล่าและงานซ้ำซ้อน' ),
						array( 'Emotional Anchor', 'พัฒนาความยืดหยุ่นและการบริหารพลังงาน เพื่อนิ่งได้ในช่วงเปลี่ยนแปลง' ),
						array( 'The Empowerment Catalyst', 'มอบหมายงานโดยให้อิสระและความเป็นเจ้าของ จนทีมเดินงานได้แม้ไม่มีผู้นำ สู่วัฒนธรรม Leader–Leader' ),
					),
					'quote'    => array( 'One leader can change the air in the whole room.', 'ผู้นำเพียงคนเดียว เปลี่ยนอากาศในห้องได้ทั้งองค์กร' ),
					'program'  => array(
						'title'  => '7-Week Learning Lab · 3 Phases',
						'lead'   => 'ปิดท้ายด้วยเซสชันติดตามผลหลังจบประมาณ 1 เดือน เพื่อปรึกษากรณีจริงและเข้าร่วม “Oxygen Community”',
						'groups' => array(
							array( 'Phase 1', 'Resetting the Leader’s Mindset', array( 'Week 1 · The First Breath: Orientation และการจัดการความเชื่อที่จำกัดตัวเอง', 'Week 2 · Lead with Clarity (The Brain): ตัดสิ่งรบกวนและเข้าใจ “Why”' ) ),
							array( 'Phase 2', 'Building the Team’s Atmosphere', array( 'Week 3 · Lead with Energy (The Body): งานที่ยั่งยืน การคุมจังหวะ และการไม่หมดไฟ', 'Week 4 · Lead with Empathy (The Breath): การฟังอย่างลึกและความปลอดภัยทางจิตใจ', 'Week 5 · Lead with Resilience (The Balance): ซ่อมกระบวนการและการนำในภาวะวิกฤต' ) ),
							array( 'Phase 3', 'Empowering for Excellence', array( 'Week 6 · Lead with Trust (The Soul): อิสระและความเป็นเจ้าของ', 'Week 7 · Oxygen Leadership Blueprint: ประเมินตนเอง 5 ด้านหลัก Peer Coaching และแผนส่วนตัว' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 6,
					'cta'      => array( 'พร้อมเป็นผู้นำที่เปลี่ยนอากาศในทีมแล้วหรือยัง', 'ลงทะเบียนวันนี้ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง' ),
				),
			),
			'en'       => array(
				'excerpt' => 'Rethink leadership and create a climate where your team grows sustainably.',
				'text'    => array( 'hri_schedule_text' => '12, 19, 26 Jan and 2, 9, 16 Feb 2027 (follow-up about a month later)', 'hri_location' => $bangkok_club, 'hri_price' => 'THB 66,500', 'hri_price_early' => 'THB 59,500', 'hri_audience' => 'Managers and leaders who want to build a climate and energy for their teams' ),
				'page'    => array(
					'intro'    => array( 'A team that “can’t breathe” may only lack its leader’s oxygen', 'A suffocating team does not always lack skills or resources. It often lacks the “oxygen” only its leader can create, because the leader sets the team’s atmosphere. Toxic leaders destroy teams; Oxygen leaders build organizations. This 7-week Learning Lab helps leaders reset their mindset, build the atmosphere and empower teams to grow sustainably.' ),
					'problems' => array(
						array( 'A slow organization', 'Work waits on one person’s decisions and the team is unclear about goals.', 'Cut the noise and create transparent conditions so the team can decide on its own.' ),
						array( 'Capable but exhausted teams', 'High performers burn out and start thinking of leaving.', 'Design sustainable work and protect the team’s energy.' ),
						array( 'A tense atmosphere where nobody speaks up', 'Low trust and psychological safety silence ideas, and conflict goes unaddressed.', 'Practise empathy, deep listening and clear boundaries.' ),
						array( 'Slow recovery after setbacks', 'Teams blame people instead of fixing problems, and leaders who cannot manage their own emotions make it worse.', 'Repair the process, not the people, and lead steadily under pressure.' ),
					),
					'outcomes' => array(
						array( 'The Atmosphere Architect', 'Shift from rule enforcer to designer of the team’s climate, and build trust.' ),
						array( 'Tactical Clarity & Filter', 'The “5 Leads” framework to communicate clear goals and cut wasted meetings and duplicated work.' ),
						array( 'Emotional Anchor', 'Build resilience and energy management to stay steady through change.' ),
						array( 'The Empowerment Catalyst', 'Delegate with autonomy and ownership so the team can run without you, towards a leader–leader culture.' ),
					),
					'quote'    => array( 'One leader can change the air in the whole room.', '' ),
					'program'  => array(
						'title'  => '7-Week Learning Lab · 3 Phases',
						'lead'   => 'A follow-up session about one month after the course for case consultation and joining the “Oxygen Community”.',
						'groups' => array(
							array( 'Phase 1', 'Resetting the Leader’s Mindset', array( 'Week 1 · The First Breath: orientation and self-limiting beliefs', 'Week 2 · Lead with Clarity (The Brain): cut the noise and understand “Why”' ) ),
							array( 'Phase 2', 'Building the Team’s Atmosphere', array( 'Week 3 · Lead with Energy (The Body): sustainable work, pacing, avoiding burnout', 'Week 4 · Lead with Empathy (The Breath): deep listening and psychological safety', 'Week 5 · Lead with Resilience (The Balance): repairing processes and crisis leadership' ) ),
							array( 'Phase 3', 'Empowering for Excellence', array( 'Week 6 · Lead with Trust (The Soul): autonomy and ownership', 'Week 7 · Oxygen Leadership Blueprint: self-assessment across 5 core areas, peer coaching, personal blueprint' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 6,
					'cta'      => array( 'Ready to be the leader who changes the air in your team?', 'Register today and our team will get back to you within 24 hours.' ),
				),
			),
			'ja'       => array(
				'excerpt' => 'リーダーの役割を見直し、チームが持続的に成長する環境をつくる。',
				'text'    => array( 'hri_schedule_text' => '2027年1月12日・19日・26日、2月2日・9日・16日（修了後約1か月でフォローアップ）', 'hri_location' => $bangkok_club, 'hri_price' => '66,500バーツ', 'hri_price_early' => '59,500バーツ', 'hri_audience' => 'チームの雰囲気とエネルギーをつくりたいマネージャー・リーダー' ),
				'page'    => array(
					'intro'    => array( '「息苦しい」チームに足りないのは、リーダーの酸素かもしれない', '息苦しいチームは、スキルや資源が足りないとは限りません。リーダーだけがつくれる「酸素」が足りないのです。チームの空気を決めるのはリーダー。Toxicなリーダーはチームを壊し、Oxygenなリーダーは組織を育てます。7週間のLearning Labで、マインドセットを整え、空気をつくり、チームの持続的な成長を後押しします。' ),
					'problems' => array(
						array( '動きの遅い組織', '一人の判断待ちで仕事が滞り、チームの目標も曖昧。', 'ノイズを減らし、チームが自分で判断できる透明な条件をつくる。' ),
						array( '優秀なのに疲れ切ったチーム', '高い成果を出す人が燃え尽き、退職を考え始める。', '持続可能な仕事の設計と、チームのエネルギーを守る方法を学ぶ。' ),
						array( '緊張した空気で、誰も発言しない', '信頼と心理的安全性が低く、意見が封じられ、対立も語られない。', '共感、深い傾聴、明確な境界線を練習する。' ),
						array( '失敗からの回復が遅い', '問題解決より犯人探しになり、感情をコントロールできないリーダーが事態を悪化させる。', '人ではなくプロセスを直し、プレッシャー下でも安定して導く。' ),
					),
					'outcomes' => array(
						array( 'The Atmosphere Architect', 'ルールを守らせる人から、チームの空気を設計する人へ。信頼を築く。' ),
						array( 'Tactical Clarity & Filter', '「5 Leads」フレームワークで目標を明確に伝え、無駄な会議や重複作業を減らす。' ),
						array( 'Emotional Anchor', 'レジリエンスとエネルギー管理を磨き、変化の中でも安定する。' ),
						array( 'The Empowerment Catalyst', '自律と当事者意識を与えて任せ、リーダー不在でも回るリーダー・リーダー文化へ。' ),
					),
					'quote'    => array( 'One leader can change the air in the whole room.', 'たった一人のリーダーが、組織全体の空気を変えられる。' ),
					'program'  => array(
						'title'  => '7-Week Learning Lab · 3 Phases',
						'lead'   => '修了後約1か月で、事例相談と「Oxygen Community」参加のためのフォローアップセッションを行います。',
						'groups' => array(
							array( 'Phase 1', 'Resetting the Leader’s Mindset', array( 'Week 1 · The First Breath：オリエンテーションと自己制限的な思い込み', 'Week 2 · Lead with Clarity (The Brain)：ノイズを減らし「Why」を理解する' ) ),
							array( 'Phase 2', 'Building the Team’s Atmosphere', array( 'Week 3 · Lead with Energy (The Body)：持続可能な働き方、ペース配分、燃え尽き防止', 'Week 4 · Lead with Empathy (The Breath)：深い傾聴と心理的安全性', 'Week 5 · Lead with Resilience (The Balance)：プロセスの修復と危機時のリーダーシップ' ) ),
							array( 'Phase 3', 'Empowering for Excellence', array( 'Week 6 · Lead with Trust (The Soul)：自律と当事者意識', 'Week 7 · Oxygen Leadership Blueprint：5つの領域での自己評価、ピアコーチング、個人ブループリント' ) ),
						),
					),
					'trainers' => true,
					'gallery'  => 6,
					'cta'      => array( 'チームの空気を変えるリーダーになりませんか', '本日お申し込みいただければ、24時間以内に担当者からご連絡します。' ),
				),
			),
		),
		/* ------------------------------------------------------------------ */
		'the-new-manager' => array(
			'meta'     => array( 'hri_batch_no' => 22, 'hri_reg_status' => 'open', 'hri_start_date' => '2027-02-05', 'hri_end_date' => '2027-03-05', 'hri_early_deadline' => '2026-08-03', 'hri_certificate' => '1', 'hri_register_url' => 'https://forms.gle/GRcUzALgDKTC29n8' ),
			'trainers' => array( 'chaipat', 'martin', 'kittipat', 'puttipong' ),
			'th'       => array(
				'excerpt' => 'LEADER Journey สำหรับผู้จัดการใหม่ จากผู้เล่นดาวเด่น สู่ผู้นำที่ขับเคลื่อนทีมด้วย Coaching และ AI',
				'text'    => array( 'hri_schedule_text' => '5, 12, 19, 26 ก.พ. และ 5 มี.ค. 2570', 'hri_price' => '38,500 บาท', 'hri_price_early' => '33,500 บาท', 'hri_audience' => 'ผู้จัดการใหม่และพนักงานเก่งที่กำลังขึ้นเป็นหัวหน้า' ),
				'page'    => array(
					'intro'    => array( 'จากผู้เล่นดาวเด่น สู่ผู้นำที่ขับเคลื่อนทีม', 'ผู้จัดการระดับกลางคือ “Middle Power” ที่เชื่อมกลยุทธ์สู่การปฏิบัติ ยุค AI ช่วยลดคอขวดของงานประจำ แต่ผู้จัดการใหม่หลายคนยังติดกับดักการทำเองและแก้ปัญหาแทนทีม LEADER Journey นี้ช่วยเปลี่ยนบทบาทจากผู้ลงมือเป็นผู้นำที่เสริมพลังคนอื่น พร้อมใช้ AI เพิ่มประสิทธิภาพ' ),
					'problems' => array(
						array( 'ผู้เล่นดาวเด่น แต่นำทีมไม่เป็น', 'เก่งงานจนได้เป็นหัวหน้า แต่ยังทำเองทุกอย่าง ทีมจึงโตไม่ทัน', 'เรียนรู้การเปลี่ยนบทบาทจากผู้ลงมือเป็นผู้นำทีม' ),
						array( 'ช่องว่างระหว่างวัยและการให้ฟีดแบ็ก', 'สื่อสารกับคนต่างรุ่นไม่ตรงกัน ฟีดแบ็กถูกเข้าใจผิดหรือไม่ถูกใช้', 'ใช้กรอบ GROW และการสื่อสารที่ปรับตามคน เพื่อให้ฟีดแบ็กเกิดผลจริง' ),
						array( 'ผู้จัดการกลายเป็นคอขวด', 'ทุกเรื่องต้องผ่านผู้จัดการ งานจึงช้าและทีมไม่กล้าตัดสินใจ', 'กระจายอำนาจและวางระบบทำงานให้ทีมเดินได้เอง' ),
						array( 'กังวลเรื่อง AI', 'ไม่แน่ใจว่า AI จะมาแทนหรือช่วยงานผู้จัดการอย่างไร', 'ใช้ AI เป็นผู้ช่วยลดงานประจำ เพื่อใช้เวลากับการนำคน' ),
					),
					'outcomes' => array(
						array( 'Mindset', 'เปลี่ยนมุมมองจาก “ผู้ทำงานเก่ง” เป็น “ผู้สร้างผลงานผ่านทีม”' ),
						array( 'AI-Enhanced Productivity', 'ใช้ AI เพิ่มประสิทธิภาพงานผู้จัดการอย่างปลอดภัยและเกิดผลจริง' ),
						array( 'Feedback & Coaching', 'ให้ฟีดแบ็กและโค้ชทีมด้วยกรอบ GROW จนเกิดการเปลี่ยนแปลง' ),
						array( 'Systematic Execution', 'วางระบบติดตามและขับเคลื่อนงานอย่างต่อเนื่อง' ),
					),
					'audience' => array( 'ผู้จัดการที่เพิ่งได้รับการแต่งตั้ง', 'พนักงานเก่งที่กำลังจะก้าวสู่ตำแหน่งหัวหน้า', 'ผู้จัดการที่ต้องนำทีมหลายรุ่นวัย', 'องค์กรที่ต้องการเตรียมผู้จัดการรุ่นใหม่' ),
					'quote'    => array( 'Managers fix problems. Leaders empower people to fix them.', 'ผู้จัดการแก้ปัญหา ผู้นำเสริมพลังให้คนแก้ปัญหาได้เอง' ),
					'program'  => array(
						'title'  => 'LEADER Journey · 7 Modules',
						'lead'   => 'เรียน 7 ครั้งต่อเนื่อง จบด้วยการนำเสนอผลงาน (ตรวจรูปแบบสัปดาห์/ครั้งกับทีมงานก่อนยืนยัน)',
						'groups' => array(
							array( 'L', 'Lead Transition', array(), 'ก้าวข้ามจากผู้ลงมือสู่ผู้นำทีม' ),
							array( 'E', 'Empower Change', array(), 'เสริมพลังและนำการเปลี่ยนแปลง' ),
							array( 'A', 'Adapt Communication', array(), 'ปรับการสื่อสารให้เข้ากับคนแต่ละแบบและแต่ละรุ่น' ),
							array( 'D', 'Developing Team', array(), 'พัฒนาทีมด้วยการโค้ชและฟีดแบ็ก' ),
							array( 'E', 'Efficiency & AI Mastery', array(), 'ใช้ AI เพิ่มประสิทธิภาพงานผู้จัดการ' ),
							array( 'R', 'Reinforce Teamwork', array(), 'สร้างการทำงานเป็นทีมที่แข็งแรงและต่อเนื่อง' ),
							array( 'Final', 'Final Presentation', array(), 'นำเสนอผลงานและแผนนำไปใช้จริง' ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'cta'      => array( 'พร้อมก้าวสู่การเป็นผู้นำที่ทีมไว้ใจแล้วหรือยัง', 'ลงทะเบียนวันนี้ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง' ),
					'faq'      => array(
						array( 'หลักสูตรนี้เหมาะกับใคร?', 'ผู้จัดการที่เพิ่งได้รับการแต่งตั้ง พนักงานเก่งที่กำลังจะเป็นหัวหน้า และผู้จัดการที่ต้องนำทีมหลายรุ่นวัย' ),
						array( 'เรียนแค่ 3 ชั่วโมงต่อครั้ง จะเพียงพอหรือ?', 'หลักสูตรแบ่งเป็น 7 ครั้ง ครั้งละ 3 ชั่วโมง แทนการอัดเนื้อหาให้จบในไม่กี่วัน ระหว่างครั้งผู้เรียนนำสิ่งที่เรียนไปลองใช้กับทีมจริง แล้วนำผลกลับมาแลกเปลี่ยนหรือปรึกษาวิทยากรในครั้งถัดไป เพื่อให้ทักษะกลายเป็นความเคยชิน' ),
						array( 'เน้นเรื่อง AI แค่ไหน?', 'มีโมดูล Efficiency & AI Mastery โดยผู้เชี่ยวชาญจาก Innovate AI เน้นการนำ AI มาลดงานประจำและเพิ่มประสิทธิภาพของผู้จัดการ' ),
						array( 'มีใบประกาศนียบัตรไหม?', 'มี ผู้เรียนทุกท่านจะได้รับใบประกาศนียบัตรการจบหลักสูตร' ),
						array( 'สมัครอย่างไร?', 'กดปุ่ม “สมัครเลย” หรือติดต่อทีมงานทางโทรศัพท์และ LINE ทีมงานตอบกลับภายใน 24 ชั่วโมง' ),
					),
				),
			),
			'en'       => array(
				'excerpt' => 'The LEADER Journey for new managers: from star player to a leader who drives the team with coaching and AI.',
				'text'    => array( 'hri_schedule_text' => '5, 12, 19, 26 Feb and 5 Mar 2027', 'hri_price' => 'THB 38,500', 'hri_price_early' => 'THB 33,500', 'hri_audience' => 'New managers and high performers about to become team leaders' ),
				'page'    => array(
					'intro'    => array( 'From star player to a leader who drives the team', 'Mid-level managers are the “Middle Power” that turns strategy into action. AI reduces routine bottlenecks, yet many new managers still fall into the trap of doing everything and solving problems for the team. The LEADER Journey moves you from doer to a leader who empowers others, using AI to raise productivity.' ),
					'problems' => array(
						array( 'A star player who can’t lead a team', 'Promoted for being good at the work, but still doing everything alone, so the team never grows.', 'Learn to move from doer to team leader.' ),
						array( 'Generation gaps and feedback', 'Communication across generations misses, and feedback is misunderstood or goes unused.', 'Use the GROW framework and people-adapted communication so feedback actually works.' ),
						array( 'The manager becomes the bottleneck', 'Everything needs the manager’s sign-off, so work slows and the team hesitates to decide.', 'Delegate authority and set up systems that let the team run on its own.' ),
						array( 'Worry about AI', 'Unsure whether AI will replace or help the manager’s job.', 'Use AI as an assistant that cuts routine work, so you can spend time leading people.' ),
					),
					'outcomes' => array(
						array( 'Mindset', 'Shift from “great individual contributor” to “someone who delivers through the team”.' ),
						array( 'AI-Enhanced Productivity', 'Use AI safely to raise managers’ productivity in real work.' ),
						array( 'Feedback & Coaching', 'Give feedback and coach the team with GROW until behaviour changes.' ),
						array( 'Systematic Execution', 'Set up systems to follow up and drive work consistently.' ),
					),
					'audience' => array( 'Newly appointed managers', 'High performers about to become team leaders', 'Managers leading multi-generational teams', 'Organizations preparing the next generation of managers' ),
					'quote'    => array( 'Managers fix problems. Leaders empower people to fix them.', '' ),
					'program'  => array(
						'title'  => 'LEADER Journey · 7 Modules',
						'lead'   => 'Seven sessions ending with a final presentation (confirm the weekly format with the team before publishing).',
						'groups' => array(
							array( 'L', 'Lead Transition', array(), 'Step from doer to team leader.' ),
							array( 'E', 'Empower Change', array(), 'Empower people and lead change.' ),
							array( 'A', 'Adapt Communication', array(), 'Adapt communication to each person and generation.' ),
							array( 'D', 'Developing Team', array(), 'Develop the team through coaching and feedback.' ),
							array( 'E', 'Efficiency & AI Mastery', array(), 'Use AI to raise a manager’s productivity.' ),
							array( 'R', 'Reinforce Teamwork', array(), 'Build strong, lasting teamwork.' ),
							array( 'Final', 'Final Presentation', array(), 'Present results and a plan to apply them.' ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'cta'      => array( 'Ready to become a leader your team trusts?', 'Register today and our team will get back to you within 24 hours.' ),
					'faq'      => array(
						array( 'Who is this course for?', 'Newly appointed managers, high performers about to lead a team, and managers leading multi-generational teams.' ),
						array( 'Is 3 hours per session enough?', 'The course runs as 7 sessions of 3 hours each, instead of cramming everything into a few days. Between sessions you try what you learned with your real team, then bring the results back to share or discuss with the trainer so the skills become habits.' ),
						array( 'How much does it focus on AI?', 'There is a dedicated Efficiency & AI Mastery module with an expert from Innovate AI, focused on using AI to cut routine work and raise managers’ productivity.' ),
						array( 'Is there a certificate?', 'Yes, every participant receives a certificate of completion.' ),
						array( 'How do I register?', 'Press “Register now”, or contact the team by phone or LINE. We reply within 24 hours.' ),
					),
				),
			),
			'ja'       => array(
				'excerpt' => '新任マネージャーのためのLEADER Journey。エース選手から、コーチングとAIでチームを動かすリーダーへ。',
				'text'    => array( 'hri_schedule_text' => '2027年2月5日・12日・19日・26日、3月5日', 'hri_price' => '38,500バーツ', 'hri_price_early' => '33,500バーツ', 'hri_audience' => '新任マネージャー、チームリーダー昇格予定の優秀な社員' ),
				'page'    => array(
					'intro'    => array( 'エース選手から、チームを動かすリーダーへ', '中間管理職は、戦略を実行につなぐ「ミドルパワー」です。AIが定型業務のボトルネックを減らす一方、新任マネージャーの多くは自分で全部やり、チームの代わりに問題を解いてしまいます。LEADER Journeyは、実務者から人を力づけるリーダーへの転換を支援し、AIで生産性も高めます。' ),
					'problems' => array(
						array( 'エースだがチームを率いられない', '仕事ができて昇進したが、今も一人で抱え込み、チームが育たない。', '実務者からチームリーダーへ役割を転換する方法を学ぶ。' ),
						array( '世代間ギャップとフィードバック', '世代が違う相手とのコミュニケーションがかみ合わず、フィードバックが誤解されたり活かされなかったりする。', 'GROWフレームワークと相手に合わせたコミュニケーションで、フィードバックを成果につなげる。' ),
						array( 'マネージャーがボトルネックに', 'すべてがマネージャーの承認待ちで、仕事が遅れ、チームも決断をためらう。', '権限委譲と仕組みづくりで、チームが自走できるようにする。' ),
						array( 'AIへの不安', 'AIがマネージャーの仕事を奪うのか助けるのか分からない。', 'AIを定型業務を減らすアシスタントとして使い、人を導く時間をつくる。' ),
					),
					'outcomes' => array(
						array( 'Mindset', '「優秀な個人」から「チームを通じて成果を出す人」へ視点を転換する。' ),
						array( 'AI-Enhanced Productivity', 'AIを安全に活用し、マネージャーの業務生産性を実務で高める。' ),
						array( 'Feedback & Coaching', 'GROWでフィードバックとコーチングを行い、行動変容につなげる。' ),
						array( 'Systematic Execution', '進捗を追い、仕事を継続的に前へ進める仕組みをつくる。' ),
					),
					'audience' => array( '新たに任命されたマネージャー', 'チームリーダー昇格予定の優秀な社員', '多世代のチームを率いるマネージャー', '次世代のマネージャーを育成したい組織' ),
					'quote'    => array( 'Managers fix problems. Leaders empower people to fix them.', 'マネージャーは問題を解決する。リーダーは、人が問題を解決できるように力づける。' ),
					'program'  => array(
						'title'  => 'LEADER Journey · 7 Modules',
						'lead'   => '全7回、最終発表で締めくくります（週ごとの形式は公開前に事務局へ要確認）。',
						'groups' => array(
							array( 'L', 'Lead Transition', array(), '実務者からチームリーダーへ。' ),
							array( 'E', 'Empower Change', array(), '人を力づけ、変革を導く。' ),
							array( 'A', 'Adapt Communication', array(), '相手や世代に合わせてコミュニケーションを調整する。' ),
							array( 'D', 'Developing Team', array(), 'コーチングとフィードバックでチームを育てる。' ),
							array( 'E', 'Efficiency & AI Mastery', array(), 'AIでマネージャーの生産性を高める。' ),
							array( 'R', 'Reinforce Teamwork', array(), '強く持続するチームワークをつくる。' ),
							array( 'Final', 'Final Presentation', array(), '成果と実践計画を発表する。' ),
						),
					),
					'trainers' => true,
					'gallery'  => 5,
					'cta'      => array( 'チームから信頼されるリーダーへ、踏み出しませんか', '本日お申し込みいただければ、24時間以内に担当者からご連絡します。' ),
					'faq'      => array(
						array( 'どんな人に向いていますか。', '新任マネージャー、チームリーダー昇格予定の優秀な社員、多世代のチームを率いるマネージャーです。' ),
						array( '1回3時間で十分ですか。', '数日に詰め込むのではなく、1回3時間×7回に分けて学びます。各回の間に学んだことを実際のチームで試し、結果を次回に持ち寄って共有・相談することで、スキルを習慣にします。' ),
						array( 'AIはどのくらい扱いますか。', 'Innovate AIの専門家による「Efficiency & AI Mastery」モジュールがあり、AIで定型業務を減らし、マネージャーの生産性を高める方法を扱います。' ),
						array( '修了証はありますか。', 'はい。受講者全員に修了証を発行します。' ),
						array( '申し込み方法は。', '「今すぐ申し込む」ボタン、またはお電話・LINEでご連絡ください。24時間以内に返信します。' ),
					),
				),
			),
		),
		/* ------------------------------------------------------------------ */
		'lean-transformation-in-7-weeks' => array(
			'meta'     => array( 'hri_batch_no' => 3, 'hri_reg_status' => 'open', 'hri_start_date' => '2027-02-24', 'hri_end_date' => '2027-04-08', 'hri_register_url' => 'https://forms.gle/LfSc51i8i6uGy2JG6' ),
			'trainers' => array(),
			'th'       => array(
				'excerpt' => 'ปลดล็อกศักยภาพธุรกิจ ด้วยแก่นแท้ระบบการผลิตระดับโลกจาก TOYOTA',
				'text'    => array( 'hri_schedule_text' => '24 ก.พ. – 8 เม.ย. 2570 (7 สัปดาห์)', 'hri_price' => '150,000 บาท ต่อองค์กร (2 ท่าน)', 'hri_price_note' => 'ท่านที่ 3 เป็นต้นไป ท่านละ 30,000 บาท', 'hri_audience' => 'โรงงานอุตสาหกรรม ผู้ผลิตชิ้นส่วน SME และองค์กรบริการ' ),
				'page'    => array(
					'intro'    => array( 'ต้นทุนขึ้น กำไรไม่ขึ้น อาจมีความสูญเสียซ่อนอยู่', 'เมื่อต้นทุนเพิ่มขึ้นแต่กำไรไม่เพิ่ม องค์กรอาจกำลังแบกความสูญเปล่าที่มองไม่เห็น ปัญหามักไม่ใช่ความไม่พยายาม แต่คือกระบวนการที่ยุ่งยาก หลักสูตรนี้เป็นรากฐานในการเปลี่ยนองค์กรจากเชื่องช้าสู่คล่องตัว ด้วยแก่นแท้ของระบบการผลิตแบบ Toyota' ),
					'problems' => array(
						array( 'งานช้าเพราะรอ', 'การรอคอย คอขวด คนและเครื่องจักรที่ว่าง ทำให้ Lead Time ยาว', 'เรียนรู้ Continuous Flow และ Pull System เพื่อเร่งงานตั้งแต่รับออเดอร์จนส่งมอบ' ),
						array( 'ของเสียและงานซ้ำ', 'ของเสียและการทำซ้ำกินเวลา ทรัพยากร และความเชื่อมั่นของลูกค้า', 'ใช้ Jidoka และ Standardized Work เพื่อลดอัตราของเสีย' ),
						array( 'สต็อกเกินและเงินจม', 'สินค้าคงคลังส่วนเกินตรึงเงินสดและบังคับให้ความสูญเปล่าซ่อนอยู่', 'ใช้การผลิตแบบ Just-in-Time เพื่อปลดปล่อยเงินทุน' ),
						array( 'ทำเยอะ แต่ไม่สร้างคุณค่า', 'ขั้นตอนที่ทำตามความเคยชินโดยไม่เพิ่มคุณค่าให้ลูกค้า', 'เรียนรู้ระบุและขจัด “7 Wastes”' ),
					),
					'outcomes' => array(
						array( 'Maximizing Quality', 'ทำให้คุณภาพเป็นผลลัพธ์ของระบบที่ทำซ้ำได้ ด้วย Jidoka และ Standardized Work ลดของเสียและงานแก้' ),
						array( 'Minimizing Cost', 'ค้นหากำไรที่ซ่อนอยู่ในกระบวนการเดิมด้วยการขจัดความสูญเปล่า โดยไม่ต้องซื้อเครื่องจักรหรือเพิ่มคน' ),
						array( 'Optimizing Speed', 'สร้าง Lead Time ที่สั้นที่สุดด้วย Continuous Flow และ Pull System เพื่อตอบสนองลูกค้าได้เร็วขึ้น' ),
					),
					'audience' => array( 'โรงงานอุตสาหกรรมที่ต้องการลดต้นทุนและเร่งการส่งมอบ', 'ผู้ผลิตชิ้นส่วนและซัพพลายเออร์', 'SME ที่ต้องการวางรากฐานวัฒนธรรม Lean', 'องค์กรบริการที่ต้องการลดขั้นตอนและความสูญเปล่า (หลักการใช้ได้นอกอุตสาหกรรมยานยนต์)' ),
					'quote'    => array( 'The most dangerous kind of waste is the waste we do not recognize.', 'ความสูญเปล่าที่อันตรายที่สุด คือความสูญเปล่าที่เรามองไม่เห็น' ),
					'program'  => array(
						'title'  => '7 Weeks · Learn, Apply, Present',
						'lead'   => 'สลับการเรียนกับโปรเจกต์จริงในองค์กรของคุณ พร้อมที่ปรึกษาจากผู้เชี่ยวชาญ Toyota หน้างานจริง และ Plant Tour โรงงานประกอบ Toyota (บ้านโพธิ์ สำโรง และ Gateway)',
						'groups' => array(
							array( 'Week 1', 'The DNA of Toyota', array(), 'แนะนำ TPS และปรัชญาเบื้องหลังระบบการผลิตของ Toyota' ),
							array( 'Week 2', 'Efficiency Architecture', array(), 'Pull System และการบริหารการไหลของงานให้ราบรื่น' ),
							array( 'Week 3', 'Standardized Excellence', array(), 'สร้างและใช้ Standard Work และการบริหารกำลังคน รากฐานของคุณภาพและ Kaizen' ),
							array( 'Week 4', 'The Art of Kaizen & Planning', array(), 'คิดแบบ Kaizen อย่างเป็นระบบ และวางแผนโครงการ Lean ในองค์กรของตน' ),
							array( 'Week 5–6', 'On-site Transformation', array(), 'นำสิ่งที่เรียนไปใช้กับกระบวนการจริง พร้อมคำแนะนำจากผู้เชี่ยวชาญ Toyota หน้างาน' ),
							array( 'Week 7', 'Final Presentation', array(), 'นำเสนอผลลัพธ์ และสร้างแรงสนับสนุนให้ Lean เกิดต่อเนื่อง' ),
						),
					),
					'cta'      => array( 'พร้อมเปลี่ยนองค์กรด้วยแก่นแท้ Lean แล้วหรือยัง', 'ลงทะเบียนวันนี้ ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง' ),
					'faq'      => array(
						array( 'องค์กรแบบไหนควรเข้าร่วม?', 'องค์กรที่มีการผลิตหรือกระบวนการทำงานที่ต้องการเพิ่มประสิทธิภาพ ทั้งโรงงาน ผู้ผลิตชิ้นส่วน SME และธุรกิจบริการ' ),
						array( 'ทำไมต้อง 7 สัปดาห์ ไม่เรียนรวดเดียว?', 'หลักสูตรสลับการเรียนกับโปรเจกต์จริงและการเยี่ยมชมโดยผู้เชี่ยวชาญ Toyota เพื่อให้ Lean เป็นการเปลี่ยนแปลงจริง ไม่ใช่แค่บันทึกความรู้' ),
						array( 'ได้เข้าชมโรงงาน Toyota จริงไหม?', 'ได้ มี Plant Tour โรงงานประกอบ Toyota แบบพิเศษสำหรับผู้เข้าร่วม' ),
						array( 'ส่งผู้เข้าอบรมได้กี่คน?', 'ค่าลงทะเบียนครอบคลุม 2 ท่านต่อองค์กร ท่านที่ 3 เป็นต้นไป ท่านละ 30,000 บาท แนะนำให้ส่งทั้งผู้บริหารและหน้างาน' ),
						array( 'สมัครอย่างไร?', 'กดปุ่ม “สมัครเลย” หรือติดต่อทีมงานทางโทรศัพท์และ LINE ทีมงานตอบกลับภายใน 24 ชั่วโมง' ),
					),
				),
			),
			'en'       => array(
				'excerpt' => 'Unlock your business potential with the essence of the world-class TOYOTA production system.',
				'text'    => array( 'hri_schedule_text' => '24 Feb – 8 Apr 2027 (7 weeks)', 'hri_price' => 'THB 150,000 per organization (2 people)', 'hri_price_note' => 'THB 30,000 for each additional person', 'hri_audience' => 'Factories, parts manufacturers, SMEs and service organizations' ),
				'page'    => array(
					'intro'    => array( 'Costs rising, profit not: hidden waste may be the cause', 'When costs rise but profit does not, an organization may be carrying waste it cannot see. The problem is rarely lack of effort; it is cumbersome processes. This course is the foundation for moving from slow to agile, using the essence of the Toyota production system.' ),
					'problems' => array(
						array( 'Work is slow because of waiting', 'Waiting, bottlenecks, idle people and machines lengthen lead time.', 'Learn Continuous Flow and Pull Systems to speed up delivery from order to handover.' ),
						array( 'Defects and rework', 'Scrap and redoing work drain time, resources and customer trust.', 'Use Jidoka and Standardized Work to cut defect rates.' ),
						array( 'Excess inventory and trapped cash', 'Surplus stock ties up capital and hides waste.', 'Use Just-in-Time production to free up capital.' ),
						array( 'Lots of activity, little value', 'Steps done out of habit that add no value for the customer.', 'Learn to identify and eliminate the “7 Wastes”.' ),
					),
					'outcomes' => array(
						array( 'Maximizing Quality', 'Make quality a repeatable result of the system through Jidoka and Standardized Work, reducing defects and rework.' ),
						array( 'Minimizing Cost', 'Find hidden profit in existing processes by eliminating waste, without new machines or extra staff.' ),
						array( 'Optimizing Speed', 'Build the shortest lead time with Continuous Flow and Pull Systems, so you respond to customers faster.' ),
					),
					'audience' => array( 'Factories that want lower cost and faster delivery', 'Parts manufacturers and suppliers', 'SMEs laying the foundation of a Lean culture', 'Service organizations cutting steps and waste (the principles apply beyond automotive)' ),
					'quote'    => array( 'The most dangerous kind of waste is the waste we do not recognize.', '' ),
					'program'  => array(
						'title'  => '7 Weeks · Learn, Apply, Present',
						'lead'   => 'Learning alternates with real projects in your own organization, with on-site advice from Toyota experts and a plant tour of Toyota assembly facilities (Banpho, Samrong and Gateway).',
						'groups' => array(
							array( 'Week 1', 'The DNA of Toyota', array(), 'An introduction to TPS and the philosophy behind Toyota’s production system.' ),
							array( 'Week 2', 'Efficiency Architecture', array(), 'Pull Systems and flow management for smooth production.' ),
							array( 'Week 3', 'Standardized Excellence', array(), 'Creating and using standard work and workforce management, the basis of quality and Kaizen.' ),
							array( 'Week 4', 'The Art of Kaizen & Planning', array(), 'Systematic Kaizen thinking and planning a Lean project for your own organization.' ),
							array( 'Week 5–6', 'On-site Transformation', array(), 'Apply what you learned to real processes with Toyota experts advising on the shop floor.' ),
							array( 'Week 7', 'Final Presentation', array(), 'Present results and build buy-in for sustained Lean practice.' ),
						),
					),
					'cta'      => array( 'Ready to transform your organization with the essence of Lean?', 'Register today and our team will get back to you within 24 hours.' ),
					'faq'      => array(
						array( 'Which organizations should join?', 'Organizations with production or work processes that need more efficiency: factories, parts makers, SMEs and service businesses.' ),
						array( 'Why 7 weeks instead of one intensive session?', 'Learning alternates with real projects and visits by Toyota experts, so Lean becomes real change rather than notes.' ),
						array( 'Will we visit a real Toyota plant?', 'Yes, there is a plant tour of Toyota assembly facilities for participants.' ),
						array( 'How many people can we send?', 'The fee covers two people per organization; each additional person is THB 30,000. We recommend sending both management and frontline staff.' ),
						array( 'How do I register?', 'Press “Register now”, or contact the team by phone or LINE. We reply within 24 hours.' ),
					),
				),
			),
			'ja'       => array(
				'excerpt' => 'TOYOTAの世界水準の生産方式の本質で、ビジネスの可能性を引き出す。',
				'text'    => array( 'hri_schedule_text' => '2027年2月24日〜4月8日（7週間）', 'hri_price' => '1組織あたり150,000バーツ（2名）', 'hri_price_note' => '3名様以降は1名あたり30,000バーツ', 'hri_audience' => '工場、部品メーカー、中小企業、サービス業' ),
				'page'    => array(
					'intro'    => array( 'コストは上がるのに利益は増えない。原因は隠れたムダかもしれません', 'コストが上がっても利益が増えないとき、組織は見えないムダを抱えているかもしれません。問題は努力不足ではなく、煩雑なプロセスにあることがほとんどです。このコースは、トヨタ生産方式の本質で、組織を「遅い」から「俊敏」へ変える土台をつくります。' ),
					'problems' => array(
						array( '待ちによる遅い仕事', '待ち時間、ボトルネック、遊んでいる人や設備がリードタイムを長くする。', '連続フローとプルシステムを学び、受注から納品までを速める。' ),
						array( '不良とやり直し', '不良と手戻りが時間、資源、顧客の信頼を奪う。', '自働化（Jidoka）と標準作業で不良率を下げる。' ),
						array( '過剰在庫と固定された資金', '余剰在庫が資金を拘束し、ムダを隠す。', 'ジャストインタイムで資金を解放する。' ),
						array( '動いているのに価値を生まない', '習慣でやっているだけで、顧客にとっての価値を増やさない工程。', '「7つのムダ」を見つけ、なくす方法を学ぶ。' ),
					),
					'outcomes' => array(
						array( 'Maximizing Quality', '自働化と標準作業で、品質を再現可能なシステムの成果にし、不良と手戻りを減らす。' ),
						array( 'Minimizing Cost', '新しい設備も人員増もなしに、既存プロセスのムダを排除して隠れた利益を見つける。' ),
						array( 'Optimizing Speed', '連続フローとプルシステムで最短のリードタイムを実現し、顧客対応を速くする。' ),
					),
					'audience' => array( 'コスト削減と納期短縮を目指す工場', '部品メーカー・サプライヤー', 'リーン文化の土台をつくりたい中小企業', '工程とムダを減らしたいサービス業（原則は自動車産業以外にも適用可能）' ),
					'quote'    => array( 'The most dangerous kind of waste is the waste we do not recognize.', '最も危険なムダとは、私たちが気づいていないムダである。' ),
					'program'  => array(
						'title'  => '7 Weeks · Learn, Apply, Present',
						'lead'   => '学びと自社での実プロジェクトを交互に進め、トヨタの専門家による現場アドバイスと、トヨタ組立工場（バンポー、サムロン、ゲートウェイ）の見学があります。',
						'groups' => array(
							array( 'Week 1', 'The DNA of Toyota', array(), 'TPSとトヨタの生産方式の背景にある哲学の紹介。' ),
							array( 'Week 2', 'Efficiency Architecture', array(), 'プルシステムと、円滑な生産のための流れの管理。' ),
							array( 'Week 3', 'Standardized Excellence', array(), '標準作業の作成・活用と要員管理。品質と改善の基盤。' ),
							array( 'Week 4', 'The Art of Kaizen & Planning', array(), '体系的な改善の考え方と、自組織のリーンプロジェクト計画。' ),
							array( 'Week 5–6', 'On-site Transformation', array(), '学んだことを実際のプロセスに適用。トヨタの専門家が現場で助言。' ),
							array( 'Week 7', 'Final Presentation', array(), '成果を発表し、リーンを継続するための賛同を得る。' ),
						),
					),
					'cta'      => array( 'リーンの本質で、組織を変えませんか', '本日お申し込みいただければ、24時間以内に担当者からご連絡します。' ),
					'faq'      => array(
						array( 'どんな組織に向いていますか。', '生産や業務プロセスの効率化を目指す工場、部品メーカー、中小企業、サービス業です。' ),
						array( 'なぜ一度に集中ではなく7週間なのですか。', '学びと実プロジェクト、トヨタ専門家の訪問を交互に行い、リーンを知識ではなく本当の変化にするためです。' ),
						array( '実際のトヨタの工場を見学できますか。', 'はい。参加者向けにトヨタ組立工場の見学があります。' ),
						array( '何名まで参加できますか。', '参加費は1組織2名分を含み、3名様以降は1名30,000バーツです。経営層と現場の両方の参加をおすすめします。' ),
						array( '申し込み方法は。', '「今すぐ申し込む」ボタン、またはお電話・LINEでご連絡ください。24時間以内に返信します。' ),
					),
				),
			),
		),
		/* ------------------------------------------------------------------ */
		'ten-x' => array(
			'meta'     => array( 'hri_batch_no' => 6, 'hri_reg_status' => 'open', 'hri_start_date' => '2027-06-15', 'hri_duration_days' => 0, 'hri_external_url' => 'https://tenx.utcc.ac.th/' ),
			'trainers' => array(),
			'th'       => array( 'excerpt' => 'หลักสูตรผู้บริหารระดับสูงเพื่อการเติบโตแบบก้าวกระโดด จัดร่วมกับ UTCC', 'text' => array( 'hri_schedule_text' => 'ทุกวันอังคาร เริ่ม 15 มิ.ย. 2570', 'hri_price' => 'ดูรายละเอียดที่เว็บไซต์ TEN X' ) ),
			'en'       => array( 'excerpt' => 'An executive program for exponential growth, run with UTCC.', 'text' => array( 'hri_schedule_text' => 'Every Tuesday from 15 Jun 2027', 'hri_price' => 'See the TEN X website' ) ),
			'ja'       => array( 'excerpt' => '飛躍的成長のためのエグゼクティブ向けプログラム（UTCCとの共同開催）。', 'text' => array( 'hri_schedule_text' => '2027年6月15日開始、毎週火曜日', 'hri_price' => 'TEN Xのウェブサイトをご覧ください' ) ),
		),
	);
}

/**
 * In-house courses with real (re-written) content: slug => array( 'th' | 'en' | 'ja' => array( excerpt, page ) ).
 * All 15 In-house courses are filled; a course missing here would get the placeholder sections from
 * hri_course_page_sample( 'inhouse' ).
 * Source: hrideone.com/courses/<slug>/ (checked 19 Oct 2026): intro, 4 pain points and 5 outcomes re-written,
 * not copied. The third item of each pain point (how the course helps) is NOT on the original site: written
 * here. Texts need marketing review (see docs/HANDOFF.md section 6).
 */
function hri_seed_inhouse_courses(): array {
	return array(
		'coaching-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรยกระดับทักษะการโค้ชสู่ความเป็นมืออาชีพ เปลี่ยนผู้จัดการจากคนสั่งงานเป็นผู้พัฒนาคน',
				'page'    => array(
					'intro'    => array( 'จากผู้สั่งงาน สู่โค้ชที่ปลดล็อกศักยภาพทีม', 'ผู้จัดการจำนวนมากถูกพัฒนาให้เก่งงาน แต่ไม่ได้ถูกสอนให้พัฒนาคน ผลคือทีมพึ่งพาหัวหน้า ศักยภาพถูกใช้ไม่เต็มที่ และการสื่อสารสะดุด หลักสูตรนี้พาผู้จัดการฝึกทักษะการโค้ชอย่างเป็นระบบ ด้วยกรอบ G.R.O.W. เพื่อให้ทีมคิดและแก้ปัญหาเองได้ และสร้างผลลัพธ์ทางธุรกิจที่ยั่งยืน' ),
					'problems' => array(
						array( 'The Directive Trap', 'ผู้จัดการสั่งและแก้ปัญหาแทนทีมจนกลายเป็นคอขวดของงาน', 'ฝึกถามเพื่อให้ทีมคิดเอง แทนการให้คำตอบ' ),
						array( 'Underutilized Talent', 'คนเก่งในทีมไม่ได้ใช้ศักยภาพเต็มที่ เพราะไม่มีใครช่วยดึงออกมา', 'เรียนรู้การค้นหาและปลดล็อกศักยภาพรายบุคคล' ),
						array( 'Ineffective Development', 'การพัฒนาคนทำเป็นครั้งคราว ไม่ผูกกับเป้าหมายงานจริง', 'ใช้ G.R.O.W. เชื่อมการพัฒนากับผลลัพธ์ของงาน' ),
						array( 'Communication Gap', 'ฟีดแบ็กไม่ตรงจุด ทำให้ความเข้าใจคลาดเคลื่อนและความสัมพันธ์ในทีมตึงเครียด', 'ฝึกการฟังอย่างลึกและการให้ฟีดแบ็กเชิงสร้างสรรค์' ),
					),
					'outcomes' => array(
						array( 'Strategic Productivity Enhancement', 'ทีมโฟกัสงานที่สำคัญและทำงานได้เองมากขึ้น ผู้จัดการมีเวลาคิดเชิงกลยุทธ์' ),
						array( 'Agile Problem-Solving Culture', 'ใช้ G.R.O.W. สร้างวัฒนธรรมที่ทีมแก้ปัญหาเองได้อย่างคล่องตัว' ),
						array( 'High-Performance Communication', 'สื่อสารและให้ฟีดแบ็กที่ทำให้เกิดการเปลี่ยนแปลงจริง' ),
						array( 'Sustainable Talent Pipeline', 'พัฒนาผู้สืบทอดและคนเก่งอย่างต่อเนื่องภายในองค์กร' ),
						array( 'Measurable Business Growth', 'เชื่อมการโค้ชกับตัวชี้วัดของธุรกิจ เพื่อเห็นผลที่วัดได้' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Take your coaching skills to a professional level and turn managers from task-givers into people developers.',
				'page'    => array(
					'intro'    => array( 'From giving orders to coaching your team’s potential', 'Many managers are developed to be good at the work, not at developing people. The result: teams depend on the boss, potential goes unused and communication stalls. This course trains managers in systematic coaching with the G.R.O.W. model, so teams think and solve problems themselves and deliver lasting business results.' ),
					'problems' => array(
						array( 'The Directive Trap', 'Managers instruct and solve problems for the team until they become the bottleneck.', 'Practise asking questions that make the team think, instead of giving answers.' ),
						array( 'Underutilized Talent', 'Capable people do not reach their full potential because nobody helps draw it out.', 'Learn to find and unlock each person’s potential.' ),
						array( 'Ineffective Development', 'People development happens now and then and is not tied to real work goals.', 'Use G.R.O.W. to link development to work outcomes.' ),
						array( 'Communication Gap', 'Feedback misses the point, so understanding slips and team relationships strain.', 'Practise deep listening and constructive feedback.' ),
					),
					'outcomes' => array(
						array( 'Strategic Productivity Enhancement', 'Teams focus on what matters and work more independently, leaving managers time for strategy.' ),
						array( 'Agile Problem-Solving Culture', 'Use G.R.O.W. to build a culture where the team solves problems on its own.' ),
						array( 'High-Performance Communication', 'Communicate and give feedback that creates real change.' ),
						array( 'Sustainable Talent Pipeline', 'Keep developing successors and high performers inside the organization.' ),
						array( 'Measurable Business Growth', 'Tie coaching to business metrics so results can be measured.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'コーチングスキルをプロレベルへ。マネージャーを「指示する人」から「人を育てる人」へ。',
				'page'    => array(
					'intro'    => array( '指示する人から、チームの可能性を引き出すコーチへ', '多くのマネージャーは仕事ができるように育てられますが、人を育てる方法は教わっていません。その結果、チームは上司に依存し、可能性が活かされず、コミュニケーションも滞ります。このコースでは、G.R.O.W.モデルによる体系的なコーチングを実践し、チームが自ら考え解決し、持続的な成果を生む状態をつくります。' ),
					'problems' => array(
						array( 'The Directive Trap', 'マネージャーが指示と問題解決を抱え込み、ボトルネックになる。', '答えを与えるのではなく、チームが考える問いかけを練習する。' ),
						array( 'Underutilized Talent', '力のある人材が、引き出す人がいないために能力を発揮できない。', '一人ひとりの可能性を見つけ、引き出す方法を学ぶ。' ),
						array( 'Ineffective Development', '人材育成が単発で、実際の仕事の目標と結びついていない。', 'G.R.O.W.で育成を仕事の成果に結びつける。' ),
						array( 'Communication Gap', 'フィードバックが的外れで、認識のずれやチームの緊張を生む。', '深い傾聴と建設的なフィードバックを練習する。' ),
					),
					'outcomes' => array(
						array( 'Strategic Productivity Enhancement', 'チームが重要な仕事に集中し自走することで、マネージャーが戦略に時間を使える。' ),
						array( 'Agile Problem-Solving Culture', 'G.R.O.W.で、チームが自ら課題を解決する俊敏な文化をつくる。' ),
						array( 'High-Performance Communication', '本当の変化を生むコミュニケーションとフィードバック。' ),
						array( 'Sustainable Talent Pipeline', '後継者とハイパフォーマーを組織内で継続的に育成する。' ),
						array( 'Measurable Business Growth', 'コーチングを事業指標と結びつけ、成果を測定できるようにする。' ),
					),
				),
			),
		),
		'high-performance-leadership-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรยกระดับภาวะผู้นำเพื่อสร้างผลลัพธ์ที่เหนือกว่า เปลี่ยนผู้นำให้เป็นผู้สร้างผลกำไรให้องค์กร',
				'page'    => array(
					'intro'    => array( 'จากผู้ตามงาน สู่ผู้นำที่สร้างผลกำไรให้องค์กร', 'องค์กรลงทุนกับกลยุทธ์มหาศาล แต่ผลลัพธ์กลับไม่ขยับ เพราะผู้นำยังบริหารแบบตั้งรับ และมองทีมเป็นกลุ่มก้อนโดยไม่วัดผลรายบุคคล หลักสูตรนี้เปลี่ยนผู้นำให้เป็นผู้สร้างผลกำไร ด้วยกลไกที่ดึงผลิตภาพจากทีมได้จริง ไม่ใช่แค่เพิ่มชั่วโมงทำงาน' ),
					'problems' => array(
						array( 'The Productivity Gap', 'ทีมทำงานหนักแต่ไม่ถึง KPI เพราะขาดทิศทางที่ชัดเจน ต้นทุนแรงงานจำนวนมากจึงสูญเปล่า', 'ตั้งเป้าหมายและวัดผลรายบุคคลให้สอดคล้องกับทิศทางองค์กร' ),
						array( 'Busy but Not Effective', 'ทุกคนยุ่งทั้งวันแต่เป้าหมายสำคัญไม่คืบหน้า เงินเดือนจึงจ่ายให้งานที่ไม่สร้างคุณค่า', 'แยกงานที่สร้างผลลัพธ์ออกจากงานที่แค่ยุ่ง และจัดลำดับให้ทีม' ),
						array( 'Talent Bleed', 'คนเก่งที่สร้างผลงานหลักกำลังจะลาออก เพราะผู้นำขาดทักษะจูงใจและบริหารผลงานรายบุคคล', 'ฝึกจูงใจและบริหารผลงานคนเก่ง ให้อยู่และเติบโตไปกับองค์กร' ),
						array( 'Invisible Losses', 'ข้อผิดพลาดเล็ก ๆ ที่เกิดซ้ำกัดกินกำไรสุทธิอย่างเงียบ ๆ เพราะไม่มีระบบป้องกันตั้งแต่ต้น', 'สร้างระบบที่มองเห็นคอขวดและป้องกันความสูญเสียตั้งแต่ต้นทาง' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Profitability Multiplier', 'ผู้นำเลิกคุมงานรายวันแล้วบริหารเชิงกลยุทธ์ เชื่อมเป้าหมายทีมกับทิศทางบริษัท ให้ทรัพยากรสร้างผลตอบแทนสูงสุด' ),
						array( 'Data-Driven Performance Ecosystem', 'ใช้ข้อมูลแทนสัญชาตญาณ เห็นคอขวดและความคืบหน้าของทีมชัดเจน ตัดสินใจได้แม่นยำขึ้น' ),
						array( 'High-ROI Talent Optimization & Succession', 'ยกระดับคนระดับกลางให้เติบโตเร็วขึ้น ลดต้นทุนการสรรหา และรักษาคนเก่งด้วยเส้นทางอาชีพที่ชัดเจน' ),
						array( 'Decision-Making Resilience in Volatile Markets', 'ตัดสินใจเชิงกลยุทธ์ได้คมแม้ภายใต้แรงกดดัน และปรับทิศทางได้เร็วโดยไม่เสียต้นทุนจากการตัดสินใจล่าช้า' ),
						array( 'Sustainable Competitive Edge through New Record Culture', 'สร้างวัฒนธรรมปรับปรุงต่อเนื่อง ที่ทีมท้าทายสถิติเดิมของตัวเองอยู่เสมอ' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Raise leadership to deliver superior results, turning leaders into profit builders for the organization.',
				'page'    => array(
					'intro'    => array( 'From following the work to building profit', 'Organizations invest heavily in strategy, yet results stay flat because leaders manage reactively and treat the team as a group without measuring individuals. This course turns leaders into profit builders with mechanisms that draw real productivity from the team, not just more hours.' ),
					'problems' => array(
						array( 'The Productivity Gap', 'Teams work hard but miss their KPIs for lack of clear direction, and a large share of labor cost is wasted.', 'Set and measure individual goals aligned with the organization’s direction.' ),
						array( 'Busy but Not Effective', 'Everyone is busy all day, yet key targets do not move, so salaries pay for work that adds no value.', 'Separate work that creates results from work that only keeps people busy, and prioritize it for the team.' ),
						array( 'Talent Bleed', 'The people who deliver most of the results are getting ready to leave because leaders lack the skills to motivate and manage individual performance.', 'Practise motivating and managing top performers so they stay and grow with the organization.' ),
						array( 'Invisible Losses', 'Small recurring errors quietly erode net profit because no preventive system was built early.', 'Build systems that reveal bottlenecks and prevent losses at the source.' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Profitability Multiplier', 'Leaders move from daily task control to strategic management, aligning team goals with company direction so resources earn the highest return.' ),
						array( 'Data-Driven Performance Ecosystem', 'Replace intuition with data, see bottlenecks and team progress clearly, and decide more accurately.' ),
						array( 'High-ROI Talent Optimization & Succession', 'Mid-level people rise faster, hiring costs fall, and top talent stays thanks to clear career paths.' ),
						array( 'Decision-Making Resilience in Volatile Markets', 'Make sharp strategic decisions under pressure and pivot quickly, without paying for delayed decisions.' ),
						array( 'Sustainable Competitive Edge through New Record Culture', 'Build a culture of continuous improvement in which teams keep challenging their own past results.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'リーダーシップを高め、より大きな成果を生み出す。リーダーを組織の「利益を生み出す人」へ。',
				'page'    => array(
					'intro'    => array( '仕事を追う人から、利益を生み出すリーダーへ', '組織は戦略に多額の投資をしても、成果が動かないことがあります。リーダーが受け身のマネジメントを続け、チームを個人の成果を測らずに一括りで見ているからです。このコースは、労働時間を増やすのではなく、チームから本当の生産性を引き出す仕組みによって、リーダーを利益を生み出す存在へ変えます。' ),
					'problems' => array(
						array( 'The Productivity Gap', 'チームは懸命に働いても方向性が不明確でKPIに届かず、多くの人件費が無駄になる。', '個人の目標を組織の方向性に合わせて設定し、成果を測定する。' ),
						array( 'Busy but Not Effective', '全員が一日中忙しいのに重要な目標が進まず、価値を生まない仕事に給与を払っている。', '成果を生む仕事と単に忙しいだけの仕事を分け、チームの優先順位を整える。' ),
						array( 'Talent Bleed', '成果の中心を担う人材が、モチベーションや個人の成果管理のスキルに欠けるリーダーのもとで離職しようとしている。', 'ハイパフォーマーを動機づけ、組織で成長し続けてもらう管理を練習する。' ),
						array( 'Invisible Losses', '小さなミスの繰り返しが、最初に予防の仕組みを作らなかったために、静かに純利益を削っている。', 'ボトルネックを可視化し、損失を源流から防ぐ仕組みをつくる。' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Profitability Multiplier', '日々の作業管理から戦略的マネジメントへ。チームの目標を会社の方向性に合わせ、資源から最大のリターンを得る。' ),
						array( 'Data-Driven Performance Ecosystem', '勘ではなくデータで判断し、ボトルネックとチームの進捗を明確に把握して、より正確に意思決定する。' ),
						array( 'High-ROI Talent Optimization & Succession', '中堅層の成長を加速し、採用コストを抑え、明確なキャリアパスで優秀な人材を引き留める。' ),
						array( 'Decision-Making Resilience in Volatile Markets', 'プレッシャー下でも鋭い戦略判断を行い、判断の遅れによるコストを出さずに素早く方向転換する。' ),
						array( 'Sustainable Competitive Edge through New Record Culture', 'チームが自らの過去の成果に挑み続ける、継続的改善の文化をつくる。' ),
					),
				),
			),
		),
		'proactive-leadership-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรยกระดับ DNA ผู้นำเชิงรุก เปลี่ยนผู้นำจากคนแก้ปัญหาเฉพาะหน้าเป็นผู้ออกแบบอนาคต',
				'page'    => array(
					'intro'    => array( 'ผู้นำที่วางหมากล่วงหน้า ไม่ใช่แค่ดับไฟตรงหน้า', 'ในวันที่คู่แข่งกำลังวางแผนปีหน้า ผู้นำยังต้องใช้ทั้งวันแก้ปัญหาเฉพาะหน้าอยู่หรือไม่ การทำงานเชิงรับทำให้มองไม่เห็นความเสี่ยงที่ซ่อนอยู่ และองค์กรที่รอวิกฤตเกิดก่อนค่อยแก้ ต้องจ่ายต้นทุนแฝงทั้งโอกาสที่หลุดมือและทีมที่หมดไฟ หลักสูตรนี้เปลี่ยนผู้นำให้เป็นผู้ออกแบบอนาคตด้วยตัวเอง' ),
					'problems' => array(
						array( 'The Firefighting Cycle', 'วนอยู่กับการแก้ปัญหาเฉพาะหน้าซ้ำ ๆ จนไม่มีเวลาคิดเชิงกลยุทธ์หรือสร้างนวัตกรรม', 'ฝึกแยกงานเร่งด่วนออกจากงานที่ต้องวางระบบ แล้วจัดเวลาให้การคิดเชิงรุก' ),
						array( 'Blind-Spot Vulnerability', 'ไม่ได้คาดการณ์ความเสี่ยงล่วงหน้า วิกฤตที่ป้องกันได้จึงเกิดขึ้นจริง', 'เรียนรู้วิเคราะห์ต้นตอและมองหาความเสี่ยงก่อนบานปลาย' ),
						array( 'Bottleneck Leadership', 'ทีมไม่กล้าตัดสินใจและรอคำสั่ง เพราะไม่มีระบบรองรับการทำงานด้วยตัวเอง', 'วางระบบและกรอบการตัดสินใจให้ทีมทำงานเชิงรุกได้เอง' ),
						array( 'Stagnant KPI Performance', 'ตัวชี้วัดส่งสัญญาณเมื่อสายเกินแก้ เพราะไม่ได้ติดตามสัญญาณเตือนล่วงหน้า', 'ใช้ Lead Indicators ติดตามสัญญาณเตือนก่อนปัญหากระทบผลลัพธ์' ),
					),
					'outcomes' => array(
						array( 'Strategic Foresight & Risk Immunity', 'สร้างภูมิคุ้มกันให้องค์กรด้วยการวิเคราะห์ต้นตอและมาตรการป้องกันเชิงรุก ลดความสูญเสียจากวิกฤตที่ไม่คาดคิด' ),
						array( 'High-Performance Agility', 'ทีมทำงานเชิงรุกและพร้อมออกจาก Comfort Zone เพื่อคว้าโอกาสในตลาดก่อนคู่แข่ง' ),
						array( 'Predictive Operational Control', 'บริหารด้วยข้อมูลจาก Lead Indicators แทนการคาดเดา ปรับทิศทางก่อนปัญหากระทบกำไร' ),
						array( 'Maximizing Resource ROI', 'ลดงานซ้ำซ้อนและเวลาที่สูญเปล่า แล้วเปลี่ยนเป็นมูลค่าเพิ่มให้องค์กร' ),
						array( 'Resilient Leadership Legacy', 'สร้างวัฒนธรรมพัฒนาต่อเนื่อง องค์กรเติบโตได้เองแม้ในช่วงวิกฤต และผู้บริหารระดับสูงแบกภาระการตัดสินใจน้อยลง' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Upgrade the DNA of proactive leaders, turning firefighters into designers of the future.',
				'page'    => array(
					'intro'    => array( 'Leaders who plan ahead, not just fight today’s fire', 'While competitors plan for next year, do your leaders still spend the whole day on urgent problems? Reactive work hides risks, and organizations that wait for a crisis pay a hidden price in lost opportunities and burned-out teams. This course turns leaders into people who design the future themselves.' ),
					'problems' => array(
						array( 'The Firefighting Cycle', 'Stuck in a loop of repeated urgent fixes, with no time for strategic thinking or innovation.', 'Practise separating urgent tasks from work that needs a system, and protect time for proactive thinking.' ),
						array( 'Blind-Spot Vulnerability', 'Risks are not anticipated, so crises that could have been prevented actually happen.', 'Learn to analyze root causes and spot risks before they grow.' ),
						array( 'Bottleneck Leadership', 'The team hesitates to decide and waits for orders because no system supports working independently.', 'Set up systems and decision frameworks so the team can act proactively on its own.' ),
						array( 'Stagnant KPI Performance', 'Indicators only signal trouble when it is too late, because early-warning signals are not tracked.', 'Use lead indicators to watch early warnings before problems hit results.' ),
					),
					'outcomes' => array(
						array( 'Strategic Foresight & Risk Immunity', 'Build organizational immunity through root-cause analysis and proactive prevention, reducing losses from unexpected crises.' ),
						array( 'High-Performance Agility', 'Teams work proactively and step out of their comfort zone to seize market opportunities before competitors.' ),
						array( 'Predictive Operational Control', 'Manage with lead-indicator data instead of guesswork, and adjust direction before problems reach profit.' ),
						array( 'Maximizing Resource ROI', 'Cut duplicated work and wasted time, and turn them into added value for the organization.' ),
						array( 'Resilient Leadership Legacy', 'Build a culture of continuous improvement so the organization keeps growing even in a crisis, with a lighter decision load on senior executives.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'プロアクティブなリーダーのDNAを磨く。目の前の問題対応から、未来を設計する人へ。',
				'page'    => array(
					'intro'    => array( '目の前の火消しではなく、先を読んで手を打つリーダーへ', '競合が来年の計画を立てているとき、リーダーは一日中目の前の問題に追われていないでしょうか。受け身の働き方は潜在的なリスクを見えなくし、危機が起きてから動く組織は、逃した機会と燃え尽きたチームという見えないコストを払うことになります。このコースは、リーダーを自ら未来を設計する人へと変えます。' ),
					'problems' => array(
						array( 'The Firefighting Cycle', '目の前の問題対応を繰り返し、戦略的に考える時間やイノベーションの余地がない。', '緊急の仕事と仕組み化すべき仕事を切り分け、先を見据えて考える時間を確保する練習をする。' ),
						array( 'Blind-Spot Vulnerability', 'リスクを事前に見通せず、防げたはずの危機が現実になる。', '根本原因を分析し、拡大する前にリスクを見つける方法を学ぶ。' ),
						array( 'Bottleneck Leadership', '自走を支える仕組みがないため、チームが判断をためらい指示を待つ。', 'チームが自ら先回りして動けるよう、仕組みと判断の枠組みを整える。' ),
						array( 'Stagnant KPI Performance', '先行する警告を追っていないため、指標が反応したときにはもう手遅れになっている。', '先行指標を使い、問題が成果に響く前に警告サインを追う。' ),
					),
					'outcomes' => array(
						array( 'Strategic Foresight & Risk Immunity', '根本原因の分析と先回りの予防策で組織に免疫をつくり、想定外の危機による損失を減らす。' ),
						array( 'High-Performance Agility', 'チームが先回りで動き、コンフォートゾーンを出て、競合より先に市場機会をつかむ。' ),
						array( 'Predictive Operational Control', '推測ではなく先行指標のデータで管理し、問題が利益に響く前に方向を修正する。' ),
						array( 'Maximizing Resource ROI', '重複作業と無駄な時間を減らし、組織の付加価値へと転換する。' ),
						array( 'Resilient Leadership Legacy', '継続的に改善する文化を築き、危機の中でも組織が自ら成長し、上級幹部の意思決定の負担も軽くなる。' ),
					),
				),
			),
		),
		'time-and-priority-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรบริหารเวลาและจัดลำดับความสำคัญ ช่วยทีมแยกงานสำคัญออกจากงานด่วน และสร้างผลลัพธ์สูงสุดภายใต้ทรัพยากรจำกัด',
				'page'    => array(
					'intro'    => array( 'ยุ่งไม่เท่ากับคืบหน้า', 'เมื่อทุกอย่างดูด่วนไปหมด พนักงานอาจกำลังหมดไฟหรือเสียโฟกัสจากงานที่ล้นมือ ความยุ่งที่ไม่สร้างความคืบหน้าทำให้องค์กรเสียต้นทุนแฝงจากการผัดวันประกันพรุ่งและการจัดลำดับความสำคัญที่สับสน หลักสูตรนี้ไม่ได้สอนแค่การใช้ปฏิทิน แต่ปรับ Growth Mindset ในการทำงาน ให้ทีมแยกงานสำคัญออกจากงานด่วน และสร้างผลลัพธ์สูงสุดภายใต้ทรัพยากรที่มี' ),
					'problems' => array(
						array( 'Multitasking Trap', 'ทำหลายอย่างพร้อมกันแต่ไม่เสร็จสักอย่าง', 'ฝึกโฟกัสทีละงานและจัดลำดับตามผลกระทบ' ),
						array( 'Meeting Overload', 'เวลาหมดไปกับการประชุมที่ไม่ได้ข้อสรุป', 'ออกแบบการประชุมให้มีเป้าหมายและจบด้วยสิ่งที่ต้องลงมือทำ' ),
						array( 'Poor Delegation', 'หัวหน้าไม่กล้ามอบหมายงาน หรือมอบหมายแบบไม่เป็นระบบ', 'เรียนรู้หลักการมอบหมายงานที่ชัดเจนและติดตามผลได้' ),
						array( 'Deadline Stress', 'ต้องปั่นงานนาทีสุดท้ายจนคุณภาพงานลดลง', 'วางแผนย้อนจากเดดไลน์และบริหารเวลาล่วงหน้า' ),
					),
					'outcomes' => array(
						array( 'Maximizing Resource Efficiency', 'ทีมคัดงานที่สร้างคุณค่าสูงสุดออกจากงานที่เปลืองเวลา ลดการสูญเสียทรัพยากรบุคคลไปกับงานที่ไม่ก่อให้เกิดรายได้' ),
						array( 'Reduced Operational Costs & Hidden Losses', 'การจัดการเวลาเป็นระบบลดการเร่งงานนาทีสุดท้าย ลดข้อผิดพลาดและค่าใช้จ่ายในการแก้ไข คุณภาพงานคงที่' ),
						array( 'Enhanced Team Synergies', 'สื่อสารลำดับความสำคัญชัดเจน ลดการรอคอยและการส่งต่องานที่ล่าช้า ลดความขัดแย้งในทีม' ),
						array( 'Strategic Proactivity vs. Reactive Firefighting', 'เปลี่ยนวัฒนธรรมจากไล่ดับไฟเป็นวางแผนเชิงรุก มีเวลาเหลือสำหรับกลยุทธ์และนวัตกรรมมากขึ้น' ),
						array( 'Employee Well-being & Retention', 'ลด Burnout ของคนเก่ง รักษาสมดุลชีวิตและงาน ช่วยลดการลาออกและเพิ่มความผูกพันต่อองค์กร' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Help teams separate what is important from what is urgent and get the most from limited resources.',
				'page'    => array(
					'intro'    => array( 'Busy is not the same as making progress', 'When everything looks urgent, people burn out or lose focus under an overloaded workload. Busyness that produces no progress costs the organization in procrastination and muddled priorities. This course goes beyond using a calendar: it shifts the work mindset so teams tell the important from the urgent and deliver maximum output with the resources they have.' ),
					'problems' => array(
						array( 'Multitasking Trap', 'People juggle many tasks at once and finish none of them.', 'Practise single-task focus and rank work by impact.' ),
						array( 'Meeting Overload', 'Time disappears into meetings that end without a conclusion.', 'Design meetings with a clear goal that end in action.' ),
						array( 'Poor Delegation', 'Supervisors hesitate to delegate, or delegate without a system.', 'Learn clear delegation principles you can follow up on.' ),
						array( 'Deadline Stress', 'Last-minute rushes lower the quality of the work.', 'Plan backwards from deadlines and manage time ahead of them.' ),
					),
					'outcomes' => array(
						array( 'Maximizing Resource Efficiency', 'Teams filter high-value work from time-wasting tasks, cutting the loss of people’s time on work that earns nothing.' ),
						array( 'Reduced Operational Costs & Hidden Losses', 'Systematic time management reduces last-minute rushes, errors and rework costs, and keeps quality steady.' ),
						array( 'Enhanced Team Synergies', 'Priorities are communicated clearly, so waiting and late handovers drop and conflict in the team falls.' ),
						array( 'Strategic Proactivity vs. Reactive Firefighting', 'Shift the culture from firefighting to proactive planning, leaving more time for strategy and innovation.' ),
						array( 'Employee Well-being & Retention', 'Less burnout among key talent and a better work-life balance help lower turnover and raise engagement.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '時間管理と優先順位づけで、重要な仕事と緊急の仕事を切り分け、限られた資源で最大の成果を出す。',
				'page'    => array(
					'intro'    => array( '忙しいことは、進んでいることではない', 'すべてが緊急に見えるとき、従業員は仕事の多さに燃え尽きたり集中力を失ったりしているかもしれません。進捗を生まない忙しさは、先延ばしと優先順位の混乱という見えないコストを組織に生みます。このコースはカレンダーの使い方を教えるだけでなく、仕事のグロースマインドセットを整え、チームが重要な仕事と緊急の仕事を見分け、限られた資源で最大の成果を出せるようにします。' ),
					'problems' => array(
						array( 'Multitasking Trap', '複数の仕事を同時に抱え、どれも終わらない。', '一度に一つの仕事に集中し、影響度で優先順位をつける練習をする。' ),
						array( 'Meeting Overload', '結論の出ない会議に時間を取られる。', '目的が明確で、行動で終わる会議を設計する。' ),
						array( 'Poor Delegation', '上司が仕事を任せられない、または仕組みなく任せてしまう。', '明確で、フォローアップできる権限委譲の原則を学ぶ。' ),
						array( 'Deadline Stress', '直前に慌てて仕上げ、仕事の質が下がる。', '締め切りから逆算して計画し、先回りで時間を管理する。' ),
					),
					'outcomes' => array(
						array( 'Maximizing Resource Efficiency', '組織にとって価値の高い仕事を時間を浪費する仕事から選り分け、収益につながらない仕事による人材の損失を減らす。' ),
						array( 'Reduced Operational Costs & Hidden Losses', '体系的な時間管理で直前の駆け込みが減り、ミスと修正コストが下がり、品質が安定する。' ),
						array( 'Enhanced Team Synergies', '優先順位を明確に伝えることで、待ち時間や引き継ぎの遅れが減り、チーム内の対立も減る。' ),
						array( 'Strategic Proactivity vs. Reactive Firefighting', '火消し中心の文化から先回りの計画へ。戦略やイノベーションに使える時間が増える。' ),
						array( 'Employee Well-being & Retention', '重要人材の燃え尽きを減らし、ワークライフバランスを保つことで、離職を抑え、エンゲージメントを高める。' ),
					),
				),
			),
		),
		'growth-mindset-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรปรับวิธีคิด พลิกวิกฤติเป็นโอกาส สร้าง Growth Mindset ระดับองค์กร',
				'page'    => array(
					'intro'    => array( 'เปลี่ยนกับดักความคิด ให้เป็นพลังของทั้งองค์กร', 'หลายองค์กรติดขัดเพราะกับดักความคิดแบบ Fixed Mindset ที่ยึดวิธีเดิมและเห็นข้อจำกัดมากกว่าโอกาส เมื่อทัศนคติเชิงลบและความกลัวล้มเหลวครอบงำวัฒนธรรม ศักยภาพของคนก็ลดลงอย่างรวดเร็ว Growth Mindset จึงเป็นยุทธศาสตร์ระดับองค์กร ไม่ใช่เรื่องทัศนคติส่วนตัว เพื่อให้คนปรับตัวได้ เปลี่ยนวิกฤตเป็นโอกาส และสร้างผลลัพธ์เหนือมาตรฐานเดิม' ),
					'problems' => array(
						array( 'Innovation Stagnation', 'พนักงานไม่กล้าคิดริเริ่มหรือลองสิ่งใหม่เพราะกลัวล้มเหลว องค์กรจึงขาดนวัตกรรม', 'สร้างพื้นที่ปลอดภัยให้ลองและเรียนรู้จากความล้มเหลว' ),
						array( 'Reactive Mindset', 'ทีมเห็นแต่ปัญหามากกว่าทางออก การรับมือวิกฤตจึงล่าช้าและไม่มีประสิทธิภาพ', 'ฝึกมองหาทางออกและโอกาสในสถานการณ์ที่ยาก' ),
						array( 'Fixed Performance', 'ยึดวิธีทำงานเดิมและไม่เรียนรู้ทักษะใหม่ ขีดความสามารถในการแข่งขันจึงหยุดนิ่ง', 'ปลูกนิสัยเรียนรู้ต่อเนื่องและกล้าปรับวิธีทำงาน' ),
						array( 'Internal Conflict', 'ความเชื่อที่จำกัดสร้างทัศนคติเชิงลบต่อการทำงานร่วมกัน กระทบบรรยากาศและประสิทธิภาพของทีม', 'ปรับความเชื่อที่จำกัดและสร้างทัศนคติเชิงบวกในการทำงานร่วมกัน' ),
					),
					'outcomes' => array(
						array( 'High-Impact Innovation Culture', 'เปลี่ยนความกลัวล้มเหลวเป็นการเรียนรู้เชิงกลยุทธ์ เป็นฐานของนวัตกรรมที่ตอบโจทย์ตลาด' ),
						array( 'Agile Adaptability to Market Changes', 'ทีมยืดหยุ่นทางความคิด ปรับตัวกับตลาดผันผวนได้เร็ว และไม่พลาดโอกาสแม้ในช่วงวิกฤต' ),
						array( 'Enhanced Employee Potential & Retention', 'พัฒนาคนให้เป็น “Better Me” ดึงศักยภาพออกมาใช้ และเพิ่มความผูกพันต่อองค์กรในระยะยาว' ),
						array( 'Strategic Problem-Solving Efficiency', 'มุ่งแก้ปัญหาอย่างเป็นบวกและสร้างสรรค์ ลดเวลาแก้ปัญหาหน้างานและเพิ่มประสิทธิภาพ' ),
						array( 'Measurable Cultural Transformation', 'สร้างวัฒนธรรมที่เชื่อในความเป็นไปได้และมุ่งเป้าสูง เติบโตผ่านการพัฒนาคนภายในอย่างเป็นระบบ' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Change how people think and turn crisis into opportunity by building a Growth Mindset across the organization.',
				'page'    => array(
					'intro'    => array( 'Turn a mindset trap into strength for the whole organization', 'Many organizations stall in a fixed-mindset trap: people cling to old methods and see limits before opportunities. When negative attitudes and fear of failure dominate the culture, people’s potential shrinks fast. A Growth Mindset is an organization-level strategy, not a personal attitude, so people can adapt, turn crisis into opportunity and deliver beyond the old standard.' ),
					'problems' => array(
						array( 'Innovation Stagnation', 'Employees do not dare to take initiative or try new things for fear of failing, so the organization lacks innovation.', 'Create a safe space to experiment and learn from failure.' ),
						array( 'Reactive Mindset', 'Teams see problems more than solutions, so crisis response is slow and ineffective.', 'Practise looking for ways forward and opportunities in tough situations.' ),
						array( 'Fixed Performance', 'People stick to old ways of working and do not learn new skills, so competitiveness stands still.', 'Build a habit of continuous learning and the courage to adjust how work is done.' ),
						array( 'Internal Conflict', 'Limiting beliefs create negative attitudes toward collaboration and hurt team atmosphere and performance.', 'Reframe limiting beliefs and build a positive attitude toward working together.' ),
					),
					'outcomes' => array(
						array( 'High-Impact Innovation Culture', 'Turn fear of failure into strategic learning, the base for innovation that fits the market.' ),
						array( 'Agile Adaptability to Market Changes', 'Mindset flexibility helps teams adapt quickly to a volatile market and not miss opportunities, even in a crisis.' ),
						array( 'Enhanced Employee Potential & Retention', 'Develop people into a “Better Me”, draw out their potential and raise long-term commitment to the organization.' ),
						array( 'Strategic Problem-Solving Efficiency', 'Solve problems positively and creatively, cutting time lost to on-the-job bottlenecks and improving efficiency.' ),
						array( 'Measurable Cultural Transformation', 'Build a culture that believes in possibility and aims high, growing through systematic in-house people development.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '考え方を変え、危機を好機に変える。組織全体でグロースマインドセットを育てる。',
				'page'    => array(
					'intro'    => array( '思考の罠を、組織全体の力に変える', '多くの組織は、従来のやり方に固執し、機会よりも制約を見てしまう「固定マインドセット」の罠で行き詰まります。否定的な態度と失敗への恐れが文化を支配すると、人の可能性は急速にしぼみます。グロースマインドセットは個人の心構えではなく組織レベルの戦略であり、人が適応し、危機を好機に変え、従来の水準を超える成果を出すためのものです。' ),
					'problems' => array(
						array( 'Innovation Stagnation', '失敗を恐れて、従業員が自ら動いたり新しいことを試したりせず、組織にイノベーションが生まれない。', '試して、失敗から学べる安全な場をつくる。' ),
						array( 'Reactive Mindset', 'チームが解決策より問題ばかりを見て、危機への対応が遅く非効率になる。', '困難な状況でも打開策と機会を探す練習をする。' ),
						array( 'Fixed Performance', '従来の働き方に固執し、新しいスキルを学ばず、競争力が停滞する。', '学び続ける習慣と、働き方を見直す勇気を育てる。' ),
						array( 'Internal Conflict', '制限的な思い込みが協働への否定的な態度を生み、チームの雰囲気と効率を損なう。', '制限的な思い込みを見直し、協働に対する前向きな態度を育てる。' ),
					),
					'outcomes' => array(
						array( 'High-Impact Innovation Culture', '失敗への恐れを戦略的な学びに変え、市場に合ったイノベーションの土台をつくる。' ),
						array( 'Agile Adaptability to Market Changes', '思考の柔軟性により、変動の大きい市場に素早く適応し、危機の中でも機会を逃さない。' ),
						array( 'Enhanced Employee Potential & Retention', '人を「Better Me」へと育て、潜在力を引き出し、組織への長期的なエンゲージメントを高める。' ),
						array( 'Strategic Problem-Solving Efficiency', '前向きで創造的に問題を解決し、現場のボトルネックにかかる時間を減らして効率を高める。' ),
						array( 'Measurable Cultural Transformation', '可能性を信じ高い目標を目指す文化を築き、組織内の人材育成を通じて体系的に成長する。' ),
					),
				),
			),
		),
		'communication-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรทักษะการสื่อสารเพื่อกลยุทธ์และผลลัพธ์ ใช้การสื่อสารผลักดันวิสัยทัศน์ให้เกิดขึ้นจริง',
				'page'    => array(
					'intro'    => array( 'สื่อสารให้วิสัยทัศน์ไปถึงหน้างาน', 'การสื่อสารในองค์กรไม่ใช่แค่การส่งข้อมูล แต่เป็นเครื่องมือผลักดันวิสัยทัศน์ให้เกิดขึ้นจริง ผู้นำและหัวหน้างานหลายคนพบว่าวิสัยทัศน์ไปไม่ถึงระดับปฏิบัติการ และไอเดียดีของพนักงานถูกปัดตกเพราะขาดทักษะการนำเสนอ การสื่อสารที่ล้มเหลวสร้างต้นทุนแฝงจากความเข้าใจคลาดเคลื่อนและงานที่ล่าช้า' ),
					'problems' => array(
						array( 'Strategic Disconnect', 'ผู้บริหารสื่อสารกลยุทธ์แล้ว แต่พนักงานยังไม่เข้าใจและนำไปปฏิบัติไม่ได้', 'ฝึกถ่ายทอดวิสัยทัศน์ให้ชัดและเชื่อมถึงงานของแต่ละคน' ),
						array( 'Idea Rejection', 'โครงการหรือนวัตกรรมใหม่ไม่ได้รับการสนับสนุน เพราะผู้นำเสนอขาดเทคนิคการโน้มน้าวและขายแนวคิด', 'เรียนรู้เทคนิคโน้มน้าวใจและขายไอเดียให้ผู้บริหาร' ),
						array( 'Communication Barriers', 'ความขัดแย้งและความไม่เข้าใจเกิดบ่อย เพราะไม่รู้ว่าคนในทีมสื่อสารต่างกันอย่างไร', 'เข้าใจรูปแบบการสื่อสารของแต่ละคนและปรับวิธีพูดให้เข้ากัน' ),
						array( 'Motivation Gap', 'หัวหน้าสร้างแรงจูงใจและขับเคลื่อนทีมไปในทิศทางเดียวกันไม่ได้', 'ใช้การตั้งคำถามและภาษาจูงใจเพื่อสร้างแรงขับเคลื่อนในทีม' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Execution', 'ผู้บริหารสื่อวิสัยทัศน์และทิศทางธุรกิจได้หนักแน่น กลยุทธ์ถูกนำไปปฏิบัติสอดคล้องกันทุกระดับ' ),
						array( 'Increased Buy-in & Internal Support', 'เพิ่มโอกาสผลักดันโครงการและนวัตกรรมด้วยเทคนิคขายแนวคิด ไอเดียที่มีศักยภาพได้รับการสนับสนุนจากผู้บริหาร' ),
						array( 'Conflict Optimization & Team Synergy', 'ลดความขัดแย้งและเพิ่มความร่วมมือ ด้วยความเข้าใจรูปแบบการสื่อสารและมุมมองที่ต่างกันของคนในทีม' ),
						array( 'Enhanced Stakeholder Relationships', 'สร้างความสัมพันธ์และการสื่อสารที่ตรงใจกลุ่มเป้าหมาย ลูกค้าและพันธมิตรแน่นแฟ้นขึ้น' ),
						array( 'Talent Motivation & Engagement', 'ตั้งคำถามเพื่อค้นหาความต้องการและเลือกภาษาจูงใจ ดึงศักยภาพของทีมและสร้างความผูกพันกับองค์กร' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Communication skills for strategy and results: use communication to turn the vision into reality.',
				'page'    => array(
					'intro'    => array( 'Make the vision reach the front line', 'Communication in an organization is more than passing on information; it is a tool for turning the vision into reality. Many leaders and supervisors find that the vision never reaches operations and that good ideas from staff get turned down for lack of presentation skills. Failed communication carries a hidden cost in misunderstandings and delays.' ),
					'problems' => array(
						array( 'Strategic Disconnect', 'Executives communicate the strategy, yet staff still do not understand it or cannot put it into practice.', 'Practise conveying the vision clearly and linking it to each person’s work.' ),
						array( 'Idea Rejection', 'New projects or innovations get no support because the presenter lacks persuasion and idea-selling techniques.', 'Learn persuasion techniques and how to sell an idea to executives.' ),
						array( 'Communication Barriers', 'Conflict and misunderstanding happen often because people do not know how their teammates communicate differently.', 'Understand each person’s communication style and adapt how you speak.' ),
						array( 'Motivation Gap', 'Supervisors cannot motivate the team or get everyone moving in the same direction.', 'Use questioning and motivating language to build momentum in the team.' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Execution', 'Executives convey vision and business direction with conviction, so strategy is carried out consistently at every level.' ),
						array( 'Increased Buy-in & Internal Support', 'Better odds of pushing projects and innovations through, as promising ideas win executive support.' ),
						array( 'Conflict Optimization & Team Synergy', 'Less conflict and more collaboration from understanding the different communication styles and views in the team.' ),
						array( 'Enhanced Stakeholder Relationships', 'Build rapport and communicate in a way that resonates with the audience, strengthening ties with customers and partners.' ),
						array( 'Talent Motivation & Engagement', 'Ask questions to uncover needs and choose motivating language, drawing out the team’s potential and engagement.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '戦略と成果のためのコミュニケーション力。ビジョンを現実にするためにコミュニケーションを使う。',
				'page'    => array(
					'intro'    => array( 'ビジョンを現場まで届けるコミュニケーション', '組織のコミュニケーションは単なる情報伝達ではなく、ビジョンを現実にするための手段です。多くのリーダーや上司が、ビジョンが現場に届かないこと、プレゼンテーション力不足で従業員の良いアイデアが却下されることを経験しています。コミュニケーションの失敗は、誤解や業務の遅れという見えないコストを生みます。' ),
					'problems' => array(
						array( 'Strategic Disconnect', '経営層が戦略を伝えても、従業員が理解できず、実行に移せない。', 'ビジョンを明確に伝え、一人ひとりの仕事に結びつける練習をする。' ),
						array( 'Idea Rejection', '提案者に説得とアイデアを売り込む技術が不足し、新しいプロジェクトやイノベーションが支持されない。', '経営層に向けた説得とアイデアの売り込み方を学ぶ。' ),
						array( 'Communication Barriers', 'チームメンバーのコミュニケーションの違いを知らないため、対立や誤解が頻繁に起きる。', '一人ひとりのコミュニケーションスタイルを理解し、話し方を合わせる。' ),
						array( 'Motivation Gap', '上司がチームを動機づけ、同じ方向へ動かすことができない。', '問いかけと動機づけの言葉でチームの推進力をつくる。' ),
					),
					'outcomes' => array(
						array( 'Strategic Alignment & Execution', '経営層がビジョンと事業の方向性を力強く伝え、戦略があらゆる階層で一貫して実行される。' ),
						array( 'Increased Buy-in & Internal Support', 'アイデアの売り込み技術により、可能性のある案が経営層の支持を得て、プロジェクトを推進しやすくなる。' ),
						array( 'Conflict Optimization & Team Synergy', 'チーム内のコミュニケーションスタイルや視点の違いを理解することで、対立が減り、協働が進む。' ),
						array( 'Enhanced Stakeholder Relationships', '相手の心に響く関係づくりとコミュニケーションで、顧客やパートナーとの絆を強める。' ),
						array( 'Talent Motivation & Engagement', '質問でニーズを探り、動機づけの言葉を選ぶことで、チームの力を引き出し、エンゲージメントを高める。' ),
					),
				),
			),
		),
		'conflict-management-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรจัดการความขัดแย้ง เปลี่ยนความเห็นต่างให้เป็นพลังสร้างสรรค์ของทีม',
				'page'    => array(
					'intro'    => array( 'ความขัดแย้งไม่ใช่ปัญหา วิธีจัดการที่ล้มเหลวต่างหาก', 'ความขัดแย้งในที่ทำงานเป็นเรื่องปกติ ความเสียหายที่แท้จริงมาจากวิธีจัดการที่ล้มเหลว เมื่อความเห็นต่างกลายเป็นความบาดหมาง วัฒนธรรมองค์กรก็ตึงเครียด ทีมขาดความร่วมมือ ผลงานหยุดชะงัก และคนเก่งลาออกมากขึ้น หลักสูตรนี้ฝึกทักษะจัดการความขัดแย้ง เพื่อเปลี่ยนสถานการณ์ตึงเครียดให้เป็นการแลกเปลี่ยนเชิงสร้างสรรค์ และรักษาโฟกัสของทีมไว้ที่เป้าหมายธุรกิจ' ),
					'problems' => array(
						array( 'The Collaboration Breakdown', 'ความเห็นไม่ลงรอยกลายเป็นกำแพง ต่างฝ่ายต่างทำงานแยกส่วนจนขาดความร่วมมือ', 'เรียนรู้ทลายกำแพงและฟื้นความร่วมมือระหว่างฝ่าย' ),
						array( 'Chronic Conflict Avoidance', 'ปัญหาเรื้อรังที่ไม่มีใครกล้าพูดตรง ๆ ถูกซุกไว้จนบานปลายยากจะควบคุม', 'ฝึกเปิดประเด็นอย่างตรงไปตรงมาแต่ยังรักษาความสัมพันธ์' ),
						array( 'Ineffective Confrontation', 'หัวหน้าขาดทักษะการเผชิญหน้า บรรยากาศแย่ลงหรือพนักงานตัดสินใจลาออก', 'ใช้เครื่องมือเผชิญหน้าอย่างมืออาชีพเพื่อคลายความตึงเครียด' ),
						array( 'Resource Misalignment', 'พลังงานของทีมหมดไปกับการเอาชนะว่าใครถูก แทนการหาแนวทางที่สร้างคุณค่าให้ธุรกิจ', 'เปลี่ยนจากการเอาชนะเป็นการหาข้อสรุปที่ดีที่สุดร่วมกัน' ),
					),
					'outcomes' => array(
						array( 'Strategic Synergy Enhancement', 'เปลี่ยนความเห็นต่างเป็นนวัตกรรมและความคิดสร้างสรรค์ ทีมสร้างผลงานคุณภาพสูงขึ้น' ),
						array( 'Optimized Decision-Making Speed', 'ลดเวลาที่เสียไปกับการโต้เถียงไร้ข้อสรุป ตัดสินใจเรื่องสำคัญได้เร็วและแม่นยำขึ้น' ),
						array( 'Cultivated Psychological Safety', 'สร้างสภาพแวดล้อมที่ปลอดภัยต่อการพูดคุยตรง ๆ พนักงานมีความผูกพันและกำลังใจทุ่มเทมากขึ้น' ),
						array( 'Sustainable Talent Retention', 'รักษาคนเก่งด้วยการบริหารความสัมพันธ์ในทีมอย่างมืออาชีพ ลดการลาออกจากความอึดอัดและความไม่เข้าใจ' ),
						array( 'Measurable Operational Efficiency', 'ยกระดับมาตรฐานการบริหารที่วัดผลได้ ด้วยเครื่องมือสากลอย่าง TKI Model ให้ความขัดแย้งเป็นตัวถ่วงน้อยที่สุด' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Manage conflict and turn differences of opinion into constructive energy for the team.',
				'page'    => array(
					'intro'    => array( 'Conflict is not the problem; handling it badly is', 'Conflict at work is normal. The real damage comes from handling it badly. When disagreement turns into resentment, the culture becomes tense, teams stop collaborating, work stalls and good people leave. This course builds conflict-management skills that turn tense situations into constructive exchange and keep the team focused on business goals.' ),
					'problems' => array(
						array( 'The Collaboration Breakdown', 'Disagreements become walls, and each side works in a silo with little real collaboration.', 'Learn to break down the walls and restore cooperation across teams.' ),
						array( 'Chronic Conflict Avoidance', 'Long-running problems nobody dares to raise directly are swept aside until they grow out of control.', 'Practise raising issues openly while protecting the relationship.' ),
						array( 'Ineffective Confrontation', 'Supervisors lack confrontation skills, so the atmosphere worsens or people decide to leave.', 'Use professional confrontation tools to ease the tension.' ),
						array( 'Resource Misalignment', 'Team energy goes into winning arguments instead of finding the approach that creates the most value for the business.', 'Shift from winning to finding the best conclusion together.' ),
					),
					'outcomes' => array(
						array( 'Strategic Synergy Enhancement', 'Turn differing opinions into innovation and creativity, so the team produces higher-quality work.' ),
						array( 'Optimized Decision-Making Speed', 'Cut the time lost in arguments without a conclusion and decide important matters faster and more accurately.' ),
						array( 'Cultivated Psychological Safety', 'Create a safe environment for frank conversation, raising engagement and motivation.' ),
						array( 'Sustainable Talent Retention', 'Keep high performers through professional management of team relationships, reducing exits caused by discomfort and misunderstanding.' ),
						array( 'Measurable Operational Efficiency', 'Raise management standards in a measurable way with an international tool, the TKI Model, so conflict is a minimal drag on goals.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '対立を管理し、意見の違いをチームの建設的な力に変える。',
				'page'    => array(
					'intro'    => array( '問題は対立ではなく、対処の失敗', '職場での対立は普通のことです。本当の損失は、対処に失敗することから生まれます。意見の違いが反目に変わると、組織文化は張りつめ、チームの協力が失われ、仕事が止まり、優秀な人材が辞めていきます。このコースは対立管理のスキルを磨き、緊張した場面を建設的な対話に変え、チームの焦点を事業目標に保ちます。' ),
					'problems' => array(
						array( 'The Collaboration Breakdown', '意見の不一致が壁となり、各部門が個別に動いて協力が失われる。', '壁を取り払い、部門を越えた協力関係を取り戻す方法を学ぶ。' ),
						array( 'Chronic Conflict Avoidance', '誰も正面から言い出せない慢性的な問題が放置され、手に負えなくなるまで拡大する。', '関係を保ちながら率直に問題を切り出す練習をする。' ),
						array( 'Ineffective Confrontation', '上司に向き合うスキルがなく、職場の雰囲気が悪化したり、離職を決める人が出る。', 'プロとして向き合う手法で、緊張を和らげる。' ),
						array( 'Resource Misalignment', 'チームのエネルギーが、事業に最大の価値を生む方法の追求ではなく、誰が正しいかの勝負に使われる。', '勝つことから、共に最善の結論を見つけることへ切り替える。' ),
					),
					'outcomes' => array(
						array( 'Strategic Synergy Enhancement', '意見の違いをイノベーションと創造性に変え、チームがより質の高い成果を出す。' ),
						array( 'Optimized Decision-Making Speed', '結論の出ない議論に費やす時間を減らし、重要な判断をより速く正確に行う。' ),
						array( 'Cultivated Psychological Safety', '率直に話せる安全な環境をつくり、従業員のエンゲージメントと意欲を高める。' ),
						array( 'Sustainable Talent Retention', 'チーム内の関係をプロとして管理し、居心地の悪さや誤解による離職を減らして優秀な人材を守る。' ),
						array( 'Measurable Operational Efficiency', '国際的なツールであるTKIモデルを使い、測定可能な水準でマネジメントを高め、対立が目標達成の足かせにならないようにする。' ),
					),
				),
			),
		),
		'change-transformation-mastery' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรผู้นำการเปลี่ยนแปลงและทรานส์ฟอร์มอย่างมืออาชีพ นำองค์กรผ่านความเปลี่ยนแปลงอย่างรวดเร็ว แม่นยำ และยั่งยืน',
				'page'    => array(
					'intro'    => array( 'กลยุทธ์ดีแค่ไหนก็ไม่เกิด ถ้าคนไม่ไปด้วย', 'ในโลกผันผวนแบบ VUCA ผู้นำจำนวนมากเจอปัญหาเดียวกัน คือกลยุทธ์ดีแต่เดินต่อไม่ได้ เพราะการเปลี่ยนแปลงสะดุดจากแรงต้านของพนักงานและวัฒนธรรมการทำงานเดิม องค์กรที่ขาดทักษะบริหารการเปลี่ยนแปลงจะพลาดโอกาสเติบโต และแบกต้นทุนแฝงจากความร่วมมือที่ต่ำและการสูญเสียคนศักยภาพสูง หลักสูตรนี้เป็นกุญแจนำองค์กรผ่านการเปลี่ยนแปลงอย่างรวดเร็ว แม่นยำ และยั่งยืน' ),
					'problems' => array(
						array( 'The Resistance Barrier', 'พนักงานต่อต้านการเปลี่ยนแปลง โครงการสำคัญจึงล่าช้าหรือเริ่มไม่ได้', 'เข้าใจที่มาของแรงต้านและรับมืออย่างเป็นระบบ' ),
						array( 'Cultural Lag', 'องค์กรประกาศทิศทางใหม่ แต่คนยังยึดวัฒนธรรมและขั้นตอนเดิม เกิดช่องว่างระหว่างกลยุทธ์กับการปฏิบัติ', 'ปรับวัฒนธรรมและพฤติกรรมให้เดินตามทิศทางใหม่' ),
						array( 'Leadership Disconnect', 'ผู้นำสื่อสารเหตุผลของการเปลี่ยนแปลงไม่ได้และสร้างแนวร่วมไม่สำเร็จ ทีมจึงไม่เข้าใจเป้าหมายร่วมกัน', 'ฝึกสื่อสารเหตุผลและสร้าง Buy-in ให้ทีมเห็นเป้าหมายเดียวกัน' ),
						array( 'Transformation Stall', 'แรงส่งหายไป โครงการทรานส์ฟอร์มค้างครึ่งทางโดยไม่เกิดผลทางธุรกิจที่จับต้องได้', 'วางแผนรักษาแรงส่งและติดตามผลจนเห็นผลลัพธ์จริง' ),
					),
					'outcomes' => array(
						array( 'Accelerated Strategic Execution & Alignment', 'นำกลยุทธ์ไปปฏิบัติได้เร็วทุกระดับ นโยบายใหม่ถูกยอมรับลื่นไหล ช่องว่างระหว่างวิสัยทัศน์ผู้บริหารกับงานประจำวันแคบลง' ),
						array( 'Cultural Alignment & High-Impact Buy-in', 'ใช้การบริหารการรับรู้และการสื่อสารเชิงกลยุทธ์ เปลี่ยนผู้ต่อต้านเป็นผู้ขับเคลื่อนการเปลี่ยนแปลง' ),
						array( 'Reduced Transition Costs & Operational Efficiency', 'ลดความสูญเสียจากความสับสน งานซ้ำซ้อน และความขัดแย้งช่วงเปลี่ยนผ่าน ควบคุมงบและกระบวนการได้มั่นคงขึ้น' ),
						array( 'Enhanced Leadership Impact & Persuasion', 'ผู้นำทุกระดับมีทักษะนำการเปลี่ยนแปลง และสื่อสารทิศทางที่ซับซ้อนได้ชัดเจนและโน้มน้าวใจ' ),
						array( 'Resilient Transformation Culture for Growth', 'สร้างวัฒนธรรมยืดหยุ่นที่รับมือสภาวะ VUCA ได้ และรักษาความได้เปรียบในการแข่งขันระยะยาว' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Lead change and transformation professionally, taking the organization through change quickly, accurately and sustainably.',
				'page'    => array(
					'intro'    => array( 'A strong strategy goes nowhere if people do not come along', 'In a VUCA world, many leaders face the same problem: a good strategy that cannot be carried out, because change stalls on employee resistance and the old work culture. Organizations without change-management skills miss growth opportunities and carry hidden costs from weak collaboration and the loss of high-potential people. This course is the key to leading the organization through change quickly, accurately and sustainably.' ),
					'problems' => array(
						array( 'The Resistance Barrier', 'Employees resist change, so important initiatives are delayed or cannot start.', 'Understand where resistance comes from and handle it systematically.' ),
						array( 'Cultural Lag', 'A new direction is announced, but people stay attached to the old culture and processes, leaving a gap between strategy and practice.', 'Shift culture and behavior to follow the new direction.' ),
						array( 'Leadership Disconnect', 'Leaders fail to explain why change is needed or to build buy-in, so teams do not share the goal.', 'Practise explaining the reasons for change and building buy-in so the team sees the same goal.' ),
						array( 'Transformation Stall', 'Momentum is lost and transformation projects stop halfway without tangible business results.', 'Plan to sustain momentum and track progress until real results show.' ),
					),
					'outcomes' => array(
						array( 'Accelerated Strategic Execution & Alignment', 'Strategy is executed faster at every level, new policies are adopted smoothly, and the gap between executive vision and daily work narrows.' ),
						array( 'Cultural Alignment & High-Impact Buy-in', 'Use perception management and strategic communication to turn resisters into change agents.' ),
						array( 'Reduced Transition Costs & Operational Efficiency', 'Less loss from confusion, duplicated work and conflict during transitions, with steadier budget and process control.' ),
						array( 'Enhanced Leadership Impact & Persuasion', 'Leaders at every level build change-leadership skills and can explain complex direction clearly and persuasively.' ),
						array( 'Resilient Transformation Culture for Growth', 'Build a flexible culture that copes with VUCA conditions and sustains a long-term competitive advantage.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '変革をプロとして導く。組織を、素早く、正確に、持続的に変化の先へ導くコース。',
				'page'    => array(
					'intro'    => array( '良い戦略も、人がついてこなければ動かない', 'VUCAの時代、多くのリーダーが同じ壁にぶつかります。優れた戦略があっても、従業員の抵抗や従来の企業文化によって変革が止まり、実行できないのです。変革マネジメントのスキルを欠く組織は、成長の機会を逃し、協力の低下や高ポテンシャル人材の流出という見えないコストを負います。このコースは、組織を素早く、正確に、持続的に変化へ導くための鍵です。' ),
					'problems' => array(
						array( 'The Resistance Barrier', '従業員が変化に抵抗し、重要な取り組みが遅れたり始められなかったりする。', '抵抗の背景を理解し、体系的に対処する。' ),
						array( 'Cultural Lag', '新しい方向性を示しても、従来の文化と手順に固執し、戦略と実行の間にギャップが生まれる。', '新しい方向性に沿うよう、文化と行動を変える。' ),
						array( 'Leadership Disconnect', 'リーダーが変革の理由を伝えられず、賛同者を得られないため、チームが目標を共有できない。', '変革の理由を伝え、賛同を得る練習をして、チームが同じ目標を見られるようにする。' ),
						array( 'Transformation Stall', '勢いが失われ、変革プロジェクトが途中で止まり、具体的な事業成果が出ない。', '勢いを保つ計画を立て、実際の成果が出るまで進捗を追う。' ),
					),
					'outcomes' => array(
						array( 'Accelerated Strategic Execution & Alignment', 'あらゆる階層で戦略の実行が早まり、新しい方針がスムーズに受け入れられ、経営層のビジョンと日々の業務の差が縮まる。' ),
						array( 'Cultural Alignment & High-Impact Buy-in', '認識のマネジメントと戦略的コミュニケーションにより、抵抗する人を変革の推進役に変える。' ),
						array( 'Reduced Transition Costs & Operational Efficiency', '移行期の混乱、重複作業、対立による損失が減り、予算とプロセスをより安定して管理できる。' ),
						array( 'Enhanced Leadership Impact & Persuasion', 'あらゆる階層のリーダーが変革をリードするスキルを身につけ、複雑な方向性を明確で説得力を持って伝えられる。' ),
						array( 'Resilient Transformation Culture for Growth', 'VUCAの状況に対応できる柔軟な文化を築き、長期的な競争優位を保つ。' ),
					),
				),
			),
		),
		'b2b-selling-masterclass' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรพลิกเกมขายองค์กรด้วยกลยุทธ์ปิดดีลระดับสูง เปลี่ยนทีมขายเป็นนักวางหมาก B2B',
				'page'    => array(
					'intro'    => array( 'ดีลใหญ่ไม่ได้หลุดเพราะสินค้า แต่เพราะทีมขายเล่นเกมไม่เป็น', 'ดีล B2B ขนาดใหญ่มักไม่ได้หลุดมือเพราะสินค้าไม่ดี แต่เพราะทีมขายยังใช้วิธีนำเสนอแบบเดิม เข้าไม่ถึงผู้มีอำนาจอนุมัติงบ และเสียส่วนแบ่งให้คู่แข่ง หลักสูตรนี้เปลี่ยนทีมขายให้เป็นนักวางกลยุทธ์ที่เข้าถึงผู้ตัดสินใจ และปิดดีลซับซ้อนได้อย่างมีกำไร' ),
					'problems' => array(
						array( 'The Gatekeeper Wall', 'ทีมเข้าถึงได้แค่พนักงานระดับปฏิบัติการ ไม่ถึง Economic Buyer ที่อนุมัติงบ เสียเวลากับการประชุมไม่รู้จบ', 'วางกลยุทธ์ข้ามด่านผู้คุมประตูไปถึงผู้ถืองบ' ),
						array( 'Stagnant Value Perception', 'สินค้าถูกมองเป็นของที่เหมือนกันหมด เพราะทีมขายขายคุณสมบัติ ไม่ใช่ผลลัพธ์ทางธุรกิจ', 'สื่อสารคุณค่าเชิงธุรกิจแทนการเล่าฟีเจอร์' ),
						array( 'Complexity Paralysis', 'จัดการผู้มีส่วนได้ส่วนเสียหลายฝ่ายในองค์กรลูกค้าไม่ได้ เกิดแรงต้านและความล่าช้า เปิดช่องให้คู่แข่งแทรก', 'เรียนรู้ทำแผนที่ผู้มีส่วนได้ส่วนเสียและสร้างฉันทามติ' ),
						array( 'Pipeline Mirage', 'ไปป์ไลน์ดูใหญ่แต่ปิดไม่ลง เพราะคัดกรองโอกาสไม่เข้มพอ พยากรณ์ยอดขายจึงเชื่อถือไม่ได้', 'ใช้เกณฑ์คัดกรองโอกาสอย่างเข้มเพื่อโฟกัสดีลที่ปิดได้จริง' ),
					),
					'outcomes' => array(
						array( 'Economic Buyer Access Strategy', 'เข้าถึงผู้ถืองบได้ตรงจุด ลดการประชุมที่ไม่จำเป็น และเพิ่มโอกาสในดีลใหญ่' ),
						array( 'Multi-Stakeholder Consensus Management', 'จัดการมุมมองที่ต่างกันของผู้มีส่วนได้ส่วนเสีย เปลี่ยนฝ่ายที่ต่อต้านเป็นพันธมิตรผลักดันดีลให้ผ่านการอนุมัติ' ),
						array( 'Complex Deal Engineering', 'ประเมินความเสี่ยงและออกแบบกลยุทธ์ปิดดีลเป็นรายกรณีสำหรับดีลมูลค่าสูง แทนการรอจังหวะ' ),
						array( 'Value-Added Pricing Defense', 'เปลี่ยนบทสนทนาจากเปรียบเทียบราคาเป็นคุณค่าทางธุรกิจที่คู่แข่งลอกยาก รักษาราคาได้แม้ถูกกดดัน' ),
						array( 'Predictable Revenue Forecasting', 'คัดกรองโอกาสอย่างเข้มงวด ผู้นำได้พยากรณ์ที่อิงรายได้ที่มีโอกาสเกิดจริง วางแผนกระแสเงินสดและจัดสรรทรัพยากรได้ดีขึ้น' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Change the B2B sales game with high-level deal-closing strategy, turning sales teams into strategic players.',
				'page'    => array(
					'intro'    => array( 'Big deals are lost to how the team plays, not the product', 'Large B2B deals are rarely lost because the product is weak. They are lost because the sales team still uses the same old pitch, cannot reach the person who approves the budget, and gives share to competitors. This course turns sales teams into strategists who reach decision-makers and close complex deals profitably.' ),
					'problems' => array(
						array( 'The Gatekeeper Wall', 'The team reaches operational staff but not the Economic Buyer who approves budgets, and burns time in endless meetings.', 'Plan a strategy to get past the gatekeepers to the budget holder.' ),
						array( 'Stagnant Value Perception', 'The product is seen as a commodity because the team sells features, not business outcomes.', 'Communicate business value instead of reciting features.' ),
						array( 'Complexity Paralysis', 'The team cannot manage the many stakeholders inside the client, causing resistance and delay that lets competitors step in.', 'Learn to map stakeholders and build consensus.' ),
						array( 'Pipeline Mirage', 'The pipeline looks big but deals do not close because opportunities are not qualified rigorously, so forecasts cannot be trusted.', 'Use strict qualification criteria to focus on deals that can really close.' ),
					),
					'outcomes' => array(
						array( 'Economic Buyer Access Strategy', 'Reach the budget holder directly, cut unnecessary meetings and improve the odds on large deals.' ),
						array( 'Multi-Stakeholder Consensus Management', 'Manage the differing views of stakeholders and turn resistant groups into allies who help the deal win approval.' ),
						array( 'Complex Deal Engineering', 'Assess risk and design a closing strategy case by case for high-value deals, instead of waiting for the right moment.' ),
						array( 'Value-Added Pricing Defense', 'Move the conversation from price comparison to business value competitors cannot easily copy, and hold price under pressure.' ),
						array( 'Predictable Revenue Forecasting', 'Rigorous qualification gives leaders forecasts based on revenue likely to materialize, for better cash-flow planning and resource allocation.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '法人営業の勝ち方を変える、高度なクロージング戦略。営業チームを戦略的なプレイヤーへ。',
				'page'    => array(
					'intro'    => array( '大型案件を失うのは、商品ではなく営業チームの戦い方のせい', '大型のB2B案件は、商品が悪いから失うわけではありません。営業チームが旧来のプレゼンのままで、予算の決裁者に届かず、競合にシェアを奪われるからです。このコースは、営業チームを意思決定者にたどり着き、複雑な案件を利益を出しながら成約できる戦略家へと変えます。' ),
					'problems' => array(
						array( 'The Gatekeeper Wall', '現場担当者までは届くが、予算を承認するEconomic Buyerには届かず、終わりのない打ち合わせで時間を失う。', '門番を越えて予算決裁者に至る戦略を立てる。' ),
						array( 'Stagnant Value Perception', '営業が事業成果ではなく機能を売るため、商品がコモディティとして見られる。', '機能の説明ではなく、事業上の価値を伝える。' ),
						array( 'Complexity Paralysis', '顧客企業内の多くの関係者を調整できず、抵抗と遅れが生じ、競合が入り込む余地ができる。', '関係者マップの作り方と合意形成を学ぶ。' ),
						array( 'Pipeline Mirage', 'パイプラインは大きく見えるが、案件の見極めが甘く成約せず、売上予測も信頼できない。', '厳格な見極め基準で、本当に成約できる案件に集中する。' ),
					),
					'outcomes' => array(
						array( 'Economic Buyer Access Strategy', '予算決裁者に的確にアクセスし、不要な打ち合わせを減らして、大型案件の成約率を高める。' ),
						array( 'Multi-Stakeholder Consensus Management', '関係者の異なる見方を調整し、抵抗する部門を、案件の承認を後押しする味方に変える。' ),
						array( 'Complex Deal Engineering', 'タイミングを待つのではなく、高額案件ごとにリスクを評価し、個別のクロージング戦略を設計する。' ),
						array( 'Value-Added Pricing Defense', '価格比較から、競合が真似しにくい事業価値へと対話を移し、圧力のもとでも価格を守る。' ),
						array( 'Predictable Revenue Forecasting', '厳格な見極めにより、実現可能性の高い売上に基づく予測を得て、キャッシュフロー計画と資源配分を改善する。' ),
					),
				),
			),
		),
		'negotiation-masterclass' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรเจรจาอย่างเหนือชั้น ปิดดีลแบบ Win-Win โดยปกป้องทั้งกำไรและความสัมพันธ์',
				'page'    => array(
					'intro'    => array( 'การเจรจาที่ไม่เป็นระบบ คือหลุมดำของกำไร', 'การเจรจาต่อรองที่ไม่เป็นระบบทำให้องค์กรสูญเสียกำไรไปทุกวัน ทีมมักยอมถอยเพียงเพื่อรักษาความสัมพันธ์ หรือรับข้อเสนอที่ไม่เป็นธรรมเพราะไม่มีแนวทาง เมื่อพนักงานแยกไม่ออกระหว่างการต่อรองกับการยอมให้ ผลลัพธ์ก็อยู่ในมือคู่ค้า หลักสูตรนี้ปรับมุมคิดและสร้างทักษะเจรจาระดับสากล ให้ทีมยืนหยัดจุดยืนได้โดยยังรักษาความร่วมมือ' ),
					'problems' => array(
						array( 'Fast Discounting', 'ทีมยอมลดข้อเสนอเร็วเกินไปเพียงเพื่อให้การเจรจาจบ องค์กรจึงเสียประโยชน์ที่ควรได้', 'ฝึกยืนหยัดข้อเสนอและแลกเปลี่ยนเงื่อนไขอย่างมีกลยุทธ์' ),
						array( 'Negotiation Deadlock', 'เมื่อการเจรจาติดขัดมักหาทางออกไม่ได้ ดีลสำคัญจึงหลุดมือ', 'ใช้กลยุทธ์ปลดล็อกทางตัน ให้ดีลที่ค้างกลับมามีโอกาส' ),
						array( 'Fixed Style Weakness', 'ปรับสไตล์การเจรจาให้เข้ากับคู่เจรจาแต่ละแบบไม่ได้ จึงถูกอ่านทางและถูกกดดันง่าย', 'เรียนรู้ปรับสไตล์ตามคู่เจรจาแต่ละประเภท' ),
						array( 'No Preparation Plan', 'เข้าห้องเจรจาโดยไม่มีแผนเตรียมตัว ไม่ชัดว่าเป้าหมาย ขอบเขตที่ยอมได้ และทางเลือกสำรองคืออะไร', 'ใช้กระบวนการ 8 ขั้นตอน ตั้งแต่เตรียมตัวจนถึงปิดดีล' ),
					),
					'outcomes' => array(
						array( 'Strategic Margin Protection', 'วิเคราะห์ความต่างระหว่าง Position กับ Interest เพื่อปกป้องกำไรและผลประโยชน์ขององค์กร' ),
						array( 'Advanced Impasse Resolution', 'ใช้กลยุทธ์ทลายทางตัน เปลี่ยนดีลที่ค้างให้เป็นโอกาสที่มีคุณค่า' ),
						array( 'High-Impact Process Engineering', 'วางการเจรจาเป็นระบบด้วยกระบวนการ 8 ขั้นตอนตั้งแต่เตรียมตัวถึงปิดดีล ลดความเสี่ยงและวัดผลได้' ),
						array( 'Versatile Negotiation Adaptability', 'ปรับสไตล์การเจรจาให้เข้ากับคู่เจรจา 4 รูปแบบ' ),
						array( 'Sustainable Win-Win Partnerships', 'สมดุลระหว่างเป้าหมายตัวเลขกับความสัมพันธ์ที่ดีกับคู่ค้า เพื่อการเติบโตระยะยาว' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Negotiate at a higher level and close win-win deals while protecting both margin and the relationship.',
				'page'    => array(
					'intro'    => array( 'Unstructured negotiation is a black hole for profit', 'Negotiation without a system costs the organization profit every day. Teams give way just to keep the relationship, or accept unfair offers because they have no method. When people cannot tell bargaining from conceding, the counterpart controls the outcome. This course shifts the mindset and builds international-level negotiation skills so teams hold their position while keeping cooperation.' ),
					'problems' => array(
						array( 'Fast Discounting', 'The team lowers its offer too quickly just to end the negotiation, so the organization loses benefits it should have gained.', 'Practise holding the offer and trading terms strategically.' ),
						array( 'Negotiation Deadlock', 'When talks stall, teams often cannot find a way out, and important deals slip away.', 'Use strategies to break the deadlock and bring stalled deals back to life.' ),
						array( 'Fixed Style Weakness', 'The team cannot adapt its negotiating style to different counterparts, so it is easy to read and pressure.', 'Learn to adapt your style to each type of counterpart.' ),
						array( 'No Preparation Plan', 'People walk into the room without a plan, unclear on their goal, their limits and their fallback options.', 'Follow an 8-step process from preparation to closing.' ),
					),
					'outcomes' => array(
						array( 'Strategic Margin Protection', 'Analyze the difference between position and interest to protect profit and the organization’s interests.' ),
						array( 'Advanced Impasse Resolution', 'Use strategies to break deadlocks and turn stalled deals into valuable opportunities.' ),
						array( 'High-Impact Process Engineering', 'Systematize negotiation with an 8-step process from preparation to closing, reducing risk and making results measurable.' ),
						array( 'Versatile Negotiation Adaptability', 'Adjust your negotiating style to four types of counterpart.' ),
						array( 'Sustainable Win-Win Partnerships', 'Balance numerical targets with good relationships with partners for long-term growth.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '一段上の交渉で、利益と関係の両方を守りながらWin-Winの合意へ。',
				'page'    => array(
					'intro'    => array( '仕組みのない交渉は、利益のブラックホール', '仕組みのない交渉は、毎日のように組織の利益を失わせます。チームは関係を保つためだけに譲歩したり、方針がないために不利な条件を受け入れたりします。交渉と譲歩の区別がつかなければ、結果は相手の手に握られます。このコースは考え方を整え、国際水準の交渉スキルを養い、協力関係を保ちながら自分たちの立場を守れるようにします。' ),
					'problems' => array(
						array( 'Fast Discounting', '交渉を終わらせたいだけで、すぐに条件を下げてしまい、本来得られるはずの利益を失う。', '提案を維持し、戦略的に条件を交換する練習をする。' ),
						array( 'Negotiation Deadlock', '交渉が行き詰まると出口を見つけられず、重要な案件を逃してしまう。', '行き詰まりを打開する戦略で、止まった案件を再び動かす。' ),
						array( 'Fixed Style Weakness', '相手に合わせて交渉スタイルを変えられず、読まれやすく、圧力をかけられやすい。', '相手のタイプに合わせてスタイルを調整する方法を学ぶ。' ),
						array( 'No Preparation Plan', '準備の計画なしに交渉に臨み、目標、譲れる範囲、代替案が曖昧なままになる。', '準備からクロージングまでの8ステップのプロセスに沿う。' ),
					),
					'outcomes' => array(
						array( 'Strategic Margin Protection', 'ポジションとインタレストの違いを分析し、利益と組織の利益を守る。' ),
						array( 'Advanced Impasse Resolution', '行き詰まりを打開する戦略で、止まった案件を価値ある機会に変える。' ),
						array( 'High-Impact Process Engineering', '準備からクロージングまでの8ステップで交渉を体系化し、リスクを減らし、成果を測定できるようにする。' ),
						array( 'Versatile Negotiation Adaptability', '4つのタイプの相手に合わせて交渉スタイルを調整する。' ),
						array( 'Sustainable Win-Win Partnerships', '数値目標とパートナーとの良好な関係を両立させ、長期的な成長につなげる。' ),
					),
				),
			),
		),
		'strategic-selling-masterclass' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรยกระดับงานขายสู่ระดับกลยุทธ์ เปลี่ยนทีมขายจากผู้รับออเดอร์เป็นพันธมิตรที่ลูกค้ายอมจ่าย',
				'page'    => array(
					'intro'    => array( 'ยุคนี้ไม่ได้วัดกันที่ใครขายเก่งกว่า', 'คู่แข่งไม่ได้มีแค่รายเดิม แต่รวมถึงผู้เปลี่ยนเกมที่อาจเปลี่ยนพฤติกรรมการซื้อของลูกค้า หลายองค์กรติดภาวะยอดขายตัน เพราะทีมขายทำหน้าที่แค่รับออเดอร์ ยังไม่ใช่พันธมิตรที่ลูกค้ายอมจ่ายให้ หลักสูตรนี้ไม่ได้สอนการพูดโน้มน้าว แต่ปรับกระบวนการคิดและวางกลยุทธ์การขายทั้งระบบ ให้วัดกันที่ความสามารถในการแก้ปัญหาให้ลูกค้า' ),
					'problems' => array(
						array( 'The Value Gap', 'นำเสนอได้แค่คุณสมบัติสินค้า แสดงมูลค่าทางธุรกิจไม่ได้ จึงลงเอยที่สงครามราคา', 'เรียนรู้แปลงคุณสมบัติเป็นมูลค่าทางธุรกิจที่ลูกค้าเห็นได้' ),
						array( 'Transactional Stagnation', 'เน้นปิดการขายทีละครั้ง จนละเลยมูลค่าตลอดช่วงชีวิตลูกค้าและกำไรที่เกิดซ้ำ', 'วางกลยุทธ์ดูแลลูกค้าให้ซื้อซ้ำและเติบโตระยะยาว' ),
						array( 'Strategy Disconnect', 'ทีมขายทำงานไร้ทิศทาง ไปเสนอขายกลุ่มที่ไม่ใช่เป้าหมาย เสียทั้งเวลาและงบประมาณ', 'กำหนดกลุ่มลูกค้าเป้าหมายและแผนการขายให้ชัดเจน' ),
						array( 'Pipeline Blindness', 'วิเคราะห์โอกาสการขายไม่แม่น พยากรณ์ยอดขายจึงเชื่อถือไม่ได้ และจัดสรรทรัพยากรผิดพลาด', 'วิเคราะห์โอกาสอย่างเป็นระบบเพื่อพยากรณ์และจัดสรรทรัพยากรให้แม่นยำ' ),
					),
					'outcomes' => array(
						array( 'Value-Based Profitability Maximization', 'เปลี่ยนทีมขายจากนักปิดยอดเป็น Value Architect ที่สื่อสารคุณค่าเหนือราคา รักษาอัตรากำไรแม้แข่งขันด้านราคาสูง' ),
						array( 'High-Precision Sales Ecosystem', 'ใช้ระบบที่อิงข้อมูลคัดกรองและโฟกัสลูกค้ามูลค่าสูง ลดเวลากับโอกาสที่ให้กำไรต่ำ' ),
						array( 'Sustainable Recurring Revenue Growth', 'พัฒนาการขายแบบที่ปรึกษา เปลี่ยนผู้ซื้อชั่วคราวเป็นพันธมิตรที่ซื้อซ้ำ รายได้คาดการณ์ได้' ),
						array( 'Strategic Sales-Marketing Alignment', 'เชื่อมแผนขายกับเป้าหมายการตลาด ให้กิจกรรมการขายช่วยสร้างแบรนด์และผลักดันเป้าหมายบริษัท' ),
						array( 'Agile Decision-Making & Risk Mitigation', 'ติดตามผลการขายแบบเรียลไทม์ ผู้นำเห็นคอขวดและความเสี่ยงล่วงหน้า ปรับกลยุทธ์ได้ทันเวลา' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Raise selling to a strategic level, turning order-takers into partners customers are willing to pay.',
				'page'    => array(
					'intro'    => array( 'Today it is not about who sells better', 'Competition is no longer just the familiar rivals but also disruptors who may change how customers buy. Many organizations are stuck in flat sales because the sales team only takes orders and is not yet a partner customers will pay for. This course is not about persuasive speaking; it reshapes how the team thinks and plans selling across the whole system, so that competing is measured by how well you solve the customer’s problem.' ),
					'problems' => array(
						array( 'The Value Gap', 'The team can only present product features, not business value, and ends up in a price war.', 'Learn to turn features into business value the customer can see.' ),
						array( 'Transactional Stagnation', 'The focus is closing one sale at a time, ignoring customer lifetime value and repeat profit.', 'Plan how to keep customers buying and growing over the long term.' ),
						array( 'Strategy Disconnect', 'The team works without direction and pitches to the wrong segments, wasting time and budget.', 'Define target customer groups and a clear sales plan.' ),
						array( 'Pipeline Blindness', 'Opportunities are analyzed inaccurately, so forecasts are unreliable and resources are misallocated.', 'Analyze opportunities systematically for accurate forecasts and resource allocation.' ),
					),
					'outcomes' => array(
						array( 'Value-Based Profitability Maximization', 'Turn closers into value architects who communicate value above price and keep margins even under heavy price competition.' ),
						array( 'High-Precision Sales Ecosystem', 'Use a data-based system to qualify and focus on high-value customers, spending less time on low-profit opportunities.' ),
						array( 'Sustainable Recurring Revenue Growth', 'Build consultative selling skills that turn one-time buyers into repeat partners and make revenue predictable.' ),
						array( 'Strategic Sales-Marketing Alignment', 'Link the sales plan to marketing goals so selling activity builds the brand and supports company objectives.' ),
						array( 'Agile Decision-Making & Risk Mitigation', 'Real-time sales tracking lets leaders see bottlenecks and risks early and adjust strategy in time.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '営業を戦略レベルへ。注文を受けるだけの営業チームを、顧客が対価を払うパートナーへ。',
				'page'    => array(
					'intro'    => array( '今は、誰が売るのがうまいかで決まる時代ではない', '競争相手は従来のライバルだけではなく、顧客の購買行動を変えうる破壊的な存在にも広がっています。営業チームが注文を受けるだけで、顧客が対価を払うパートナーになれていないため、多くの組織が売上の頭打ちに陥っています。このコースは説得話術ではなく、チームの考え方と営業戦略をシステム全体で整え、顧客の課題をどれだけ解決できるかで勝負する力を育てます。' ),
					'problems' => array(
						array( 'The Value Gap', '商品の機能しか説明できず、事業価値を示せないため、価格競争に陥る。', '機能を、顧客に見える事業価値へ変換する方法を学ぶ。' ),
						array( 'Transactional Stagnation', '一件ずつの成約に集中するあまり、顧客生涯価値と継続的な利益を見落とす。', '顧客が繰り返し購入し、長期的に成長する関係の戦略を立てる。' ),
						array( 'Strategy Disconnect', '営業が方向性なく動き、ターゲット外の層に提案して、時間と予算を無駄にする。', 'ターゲット顧客層と、明確な営業計画を定める。' ),
						array( 'Pipeline Blindness', '商談の分析が不正確なため、売上予測が信頼できず、資源配分も誤る。', '商談を体系的に分析し、正確な予測と資源配分につなげる。' ),
					),
					'outcomes' => array(
						array( 'Value-Based Profitability Maximization', '営業を「成約者」から、価格より価値を伝えるValue Architectへ。激しい価格競争でも利益率を守る。' ),
						array( 'High-Precision Sales Ecosystem', 'データに基づく仕組みで高価値顧客を選別して集中し、低利益の案件に使う時間を減らす。' ),
						array( 'Sustainable Recurring Revenue Growth', 'コンサルティブ営業のスキルで、一度きりの購入者を継続購入するパートナーに変え、売上を予測可能にする。' ),
						array( 'Strategic Sales-Marketing Alignment', '営業計画をマーケティング目標と結びつけ、営業活動がブランドを育て、会社の目標を後押しする。' ),
						array( 'Agile Decision-Making & Risk Mitigation', 'リアルタイムの営業追跡で、ボトルネックとリスクを早期に把握し、間に合うタイミングで戦略を修正する。' ),
					),
				),
			),
		),
		'consultative-selling-masterclass' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรอัปเกรดทักษะการขายเชิงที่ปรึกษา เปลี่ยนทีมขายจากผู้ขายสินค้าเป็นที่ปรึกษาที่ลูกค้าไว้ใจ',
				'page'    => array(
					'intro'    => array( 'จากผู้ขายสินค้า สู่ที่ปรึกษาที่ลูกค้ายอมจ่าย', 'เมื่อสินค้าและบริการถูกเทียบกันที่ราคา ทีมขายที่เอาแต่นำเสนอสิ่งที่บริษัทมี แทนที่จะฟังสิ่งที่ลูกค้าต้องการ จะชนะด้วยการโน้มน้าวแบบเดิมไม่ได้อีก และการลดราคาเพื่อปิดดีลทำให้กำไรรั่วไหลไปทุกดีล หลักสูตรนี้เปลี่ยนทีมขายจาก Supplier เป็น Expert Consultant ที่ลูกค้าไว้ใจและยอมจ่ายเพื่อผลลัพธ์ทางธุรกิจที่คุ้มค่า' ),
					'problems' => array(
						array( 'The Product-Push Trap', 'ท่องแต่คุณสมบัติสินค้าจนไม่ฟังปัญหาจริงของลูกค้า ข้อเสนอถูกมองข้ามและปิดดีลไม่ได้', 'ฝึกฟังและตั้งคำถาม ให้ข้อเสนอตรงกับปัญหาจริง' ),
						array( 'Profit Margin Erosion', 'ขาดทักษะสร้างความแตกต่างด้วยคุณค่า จึงพึ่งส่วนลดเป็นเครื่องมือโน้มน้าวเพียงอย่างเดียว', 'สร้างความแตกต่างด้วยคุณค่าเพื่อรักษาอัตรากำไร' ),
						array( 'The Hidden Needs Blind Spot', 'ขาดเทคนิคตั้งคำถามเชิงลึก จึงพลาดโอกาสใหม่และความต้องการที่ลูกค้ายังไม่ได้รับการตอบสนอง', 'ใช้เทคนิคตั้งคำถามเชิงลึกเพื่อค้นหาความต้องการที่ซ่อนอยู่' ),
						array( 'Sales Cycle Stagnation', 'รอบการขายยืดเยื้อไร้ทิศทาง เพราะไม่ได้วางแผนก่อนพบลูกค้าและไม่ได้วิเคราะห์สไตล์ของลูกค้า', 'วางแผนก่อนพบและวิเคราะห์สไตล์ลูกค้าเพื่อเร่งรอบการขาย' ),
					),
					'outcomes' => array(
						array( 'High-Margin Solution Selling', 'เปลี่ยนจากการเสนอสินค้าเป็นการออกแบบโซลูชันตามความต้องการเชิงลึก เลี่ยงสงครามราคาและรักษากำไร' ),
						array( 'Unmet Needs Discovery & Expansion', 'ตั้งคำถามเชิงกลยุทธ์เพื่อค้นพบความต้องการแฝง เกิดดีลที่ใหญ่ขึ้นและการเติบโตในลูกค้าเดิม' ),
						array( 'Competitive Strategic Positioning', 'วิเคราะห์สไตล์ลูกค้าได้แม่นยำ ปรับการนำเสนอให้ตรงพฤติกรรมและความต้องการของตลาด' ),
						array( 'Accelerated Sales Velocity', 'รับมือข้อโต้แย้งอย่างมืออาชีพและนำเสนอโดยโฟกัสที่ปัญหา รอบการขายสั้นลง ตัดสินใจเร็วขึ้น' ),
						array( 'Long-term Strategic Partnership', 'เปลี่ยนความสัมพันธ์จากซื้อครั้งเดียวเป็นพันธมิตรระยะยาว ลดต้นทุนการรักษาลูกค้าและหนุนการเติบโตของกำไร' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Upgrade consultative selling skills, turning product sellers into advisors customers trust.',
				'page'    => array(
					'intro'    => array( 'From selling products to advising customers who will pay for it', 'When products and services are compared on price alone, a sales team that keeps presenting what the company has, instead of listening to what the customer needs, can no longer win with the old way of persuading. Closing with discounts also lets profit leak out of every deal. This course moves the team from supplier to expert consultant, trusted by customers and paid for worthwhile business results.' ),
					'problems' => array(
						array( 'The Product-Push Trap', 'The team memorizes product features and stops listening to the customer’s real problem, so offers are overlooked and deals do not close.', 'Practise listening and questioning so offers match the real problem.' ),
						array( 'Profit Margin Erosion', 'Without the skill to differentiate on value, discounts become the only way to persuade.', 'Differentiate on value to protect margins.' ),
						array( 'The Hidden Needs Blind Spot', 'Without deep questioning techniques, the team misses new opportunities and the customer’s unmet needs.', 'Use deep questioning to uncover hidden needs.' ),
						array( 'Sales Cycle Stagnation', 'Sales cycles drag on without direction because there is no planning before meetings and no analysis of the customer’s style.', 'Plan before meetings and analyze the customer’s style to speed up the cycle.' ),
					),
					'outcomes' => array(
						array( 'High-Margin Solution Selling', 'Move from pitching products to designing solutions for deeper needs, avoiding price wars and protecting margin.' ),
						array( 'Unmet Needs Discovery & Expansion', 'Strategic questioning uncovers hidden needs, creating bigger deals and growth within existing customers.' ),
						array( 'Competitive Strategic Positioning', 'Accurate analysis of customer style lets teams tailor presentations to market behavior and needs.' ),
						array( 'Accelerated Sales Velocity', 'Professional objection handling and problem-focused presentations shorten the sales cycle and speed up decisions.' ),
						array( 'Long-term Strategic Partnership', 'Shift relationships from one-off purchases to long-term partnerships, lowering retention costs and supporting profit growth.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'コンサルティブ営業のスキルを高め、商品を売るチームを顧客に信頼されるアドバイザーへ。',
				'page'    => array(
					'intro'    => array( '商品を売る人から、顧客が対価を払うアドバイザーへ', '商品やサービスが価格だけで比べられる中、顧客のニーズを聞かず自社の持つものばかりを提案する営業チームは、従来の説得ではもう勝てません。値引きでの成約は、案件のたびに利益を流出させます。このコースは、チームをサプライヤーから、顧客に信頼され、価値ある事業成果に対価を払ってもらえるエキスパート・コンサルタントへと導きます。' ),
					'problems' => array(
						array( 'The Product-Push Trap', '商品の機能を暗記するだけで顧客の本当の課題を聞かず、提案が見過ごされ、成約できない。', '傾聴と質問を練習し、提案を本当の課題に合わせる。' ),
						array( 'Profit Margin Erosion', '価値で差別化するスキルがなく、値引きが唯一の説得手段になる。', '価値で差別化し、利益率を守る。' ),
						array( 'The Hidden Needs Blind Spot', '深掘りする質問の技術がなく、新たな機会や顧客の満たされていないニーズを見逃す。', '深掘りの質問で、隠れたニーズを探る。' ),
						array( 'Sales Cycle Stagnation', '商談前の計画や顧客スタイルの分析がないため、営業サイクルが方向性なく長引く。', '商談前に計画を立て、顧客スタイルを分析して、サイクルを短縮する。' ),
					),
					'outcomes' => array(
						array( 'High-Margin Solution Selling', '商品の提案から、より深いニーズに対するソリューション設計へ。価格競争を避けて利益を守る。' ),
						array( 'Unmet Needs Discovery & Expansion', '戦略的な質問で潜在ニーズを見つけ、より大きな案件と既存顧客内での成長を生む。' ),
						array( 'Competitive Strategic Positioning', '顧客スタイルを正確に分析し、市場の行動とニーズに合わせて提案を調整する。' ),
						array( 'Accelerated Sales Velocity', '反論への適切な対応と課題に焦点を当てた提案で、営業サイクルを短縮し、意思決定を早める。' ),
						array( 'Long-term Strategic Partnership', '一度きりの取引から長期的なパートナーシップへ。顧客維持のコストを下げ、利益の成長を支える。' ),
					),
				),
			),
		),
		'essential-sales-masterclass' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรติดอาวุธขั้นตอนการขาย วางแผนกลยุทธ์อย่างมือโปร เปลี่ยนทีมขายจากเสี่ยงดวงเป็นทำงานอย่างเป็นระบบ',
				'page'    => array(
					'intro'    => array( 'เลิกขายแบบเสี่ยงดวง', 'ทีมขายของคุณทำงานแบบเสี่ยงดวงอยู่หรือไม่ หลายองค์กรมีทีมที่มีทักษะแต่ไม่มีแผน หรือมีแผนแต่พลาดเมื่อเจอลูกค้าจริง การปล่อยพนักงานขายออกไปโดยไม่มีกระบวนการมาตรฐานคือการทิ้งเงินเดือนและค่าคอมมิชชันไปกับความพยายามที่สูญเปล่า หลักสูตรนี้เปลี่ยนทีมขายให้วิเคราะห์ตลาด วางแผนเชิงรุก และปิดการขายด้วยเทคนิคที่อิงมูลค่า ไม่ใช่ราคา' ),
					'problems' => array(
						array( 'The Directionless Hustle', 'ทีมขายทำงานหนักแต่ยอดไม่เดิน เพราะขาดการวิเคราะห์คู่แข่งและแผนปฏิบัติการที่สอดคล้องกับเป้าหมายองค์กร', 'เรียนรู้วิเคราะห์ตลาดและคู่แข่ง แล้วแปลงเป็นแผนปฏิบัติการ' ),
						array( 'Superficial Selling Trap', 'เน้นนำเสนอคุณสมบัติสินค้า แต่ไม่เข้าใจพฤติกรรมและความต้องการจริงของลูกค้า ปิดการขายยากและใช้เวลานาน', 'ใช้การถามเชิงลึกเพื่อเข้าใจลูกค้าก่อนนำเสนอ' ),
						array( 'Handling Rejection Failure', 'เสียความมั่นใจและไปไม่เป็นเมื่อลูกค้าโต้แย้ง พลาดดีลที่ควรได้', 'ฝึกรับมือข้อโต้แย้งอย่างมั่นใจเป็นขั้นตอน' ),
						array( 'Unpredictable Sales Performance', 'ยอดขายผันผวนเพราะไม่มีกระบวนการติดตามและประเมินผล คาดการณ์ผลลัพธ์ธุรกิจไม่ได้', 'วางระบบติดตามและประเมินผลการขายที่ชัดเจน' ),
					),
					'outcomes' => array(
						array( 'Systematized Sales Excellence', 'ปรับทักษะทีมขายด้วยกระบวนการ 7 ขั้นตอนระดับมืออาชีพที่วัดผลได้ ลดความผิดพลาดและสร้างมาตรฐานรายได้ที่มั่นคง' ),
						array( 'Strategic Market Advantage', 'วิเคราะห์ตลาดและคู่แข่งแบบรู้เขารู้เรา ดึงจุดขายเด่น (FAB) มาสร้างความได้เปรียบและรักษากำไร' ),
						array( 'High-Impact Value Proposition', 'จากพนักงานขายทั่วไปสู่ที่ปรึกษาด้านโซลูชัน วิเคราะห์ลูกค้าและนำเสนอคุณค่าตรงใจ ลดการสูญเสียโอกาสและเพิ่มการซื้อซ้ำ' ),
						array( 'Optimized Operational Efficiency', 'แผนปฏิบัติการขายเชิงรุกประสานกับแผนการตลาด ทรัพยากรที่ลงทุนถูกใช้คุ้มค่าและมุ่งสู่เป้าเดียวกัน' ),
						array( 'Predictable Growth & Risk Management', 'ระบบติดตามผลที่ชัดเจนพร้อมแผนสำรอง ผู้นำบริหารความเสี่ยงและพยากรณ์การเติบโตได้แม่นยำ' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Arm your team with a selling process and professional strategic planning, moving from gambling on luck to working systematically.',
				'page'    => array(
					'intro'    => array( 'Stop selling on luck', 'Is your sales team working on luck? Many organizations have skilled people with no plan, or a plan that falls apart in front of a real customer. Sending salespeople out without a standard process throws salary and commission away on wasted effort. This course turns the team into people who analyze the market, plan proactively and close with techniques based on value, not price.' ),
					'problems' => array(
						array( 'The Directionless Hustle', 'The team works hard but sales do not move, for lack of competitor analysis and an action plan aligned with company goals.', 'Learn to analyze the market and competitors and turn that into an action plan.' ),
						array( 'Superficial Selling Trap', 'The focus is on product features, without reaching the customer’s real behavior and needs, so closing is hard and slow.', 'Use deep questioning to understand the customer before presenting.' ),
						array( 'Handling Rejection Failure', 'The team loses confidence and is lost when customers push back, missing deals it should have won.', 'Practise handling objections with confidence, step by step.' ),
						array( 'Unpredictable Sales Performance', 'Sales swing widely because there is no process for tracking and evaluating, so business results cannot be forecast.', 'Set up a clear system for tracking and evaluating sales.' ),
					),
					'outcomes' => array(
						array( 'Systematized Sales Excellence', 'Rebuild the team’s skills with a measurable 7-step professional process, cutting errors and setting a new standard for steady revenue.' ),
						array( 'Strategic Market Advantage', 'Know the market and competitors well, and use standout selling points (FAB) to build advantage and protect profit.' ),
						array( 'High-Impact Value Proposition', 'Move from ordinary salespeople to solution advisors who read the customer and present value that fits, losing fewer chances and earning more repeat purchases.' ),
						array( 'Optimized Operational Efficiency', 'A proactive sales action plan in step with marketing means every resource is used well and aimed at the same goal.' ),
						array( 'Predictable Growth & Risk Management', 'Clear tracking with contingency plans helps leaders manage risk and forecast growth accurately.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => '営業プロセスとプロの戦略立案を身につけ、運任せの営業から、体系的な営業チームへ。',
				'page'    => array(
					'intro'    => array( '運任せの営業をやめる', 'あなたの営業チームは、運任せで動いていませんか。スキルはあっても計画がない、計画はあっても実際の顧客の前で崩れる、という組織は少なくありません。標準的なプロセスなしに営業担当を送り出すことは、給与とコミッションを無駄な努力に捨てるのと同じです。このコースは、チームが市場を分析し、先を読んで計画し、価格ではなく価値に基づく手法で成約できるようにします。' ),
					'problems' => array(
						array( 'The Directionless Hustle', '競合分析と、組織の目標に沿った行動計画がないため、懸命に働いても売上が伸びない。', '市場と競合を分析し、行動計画に落とし込む方法を学ぶ。' ),
						array( 'Superficial Selling Trap', '商品の機能の説明に偏り、顧客の本当の行動やニーズに届かず、成約が難しく時間もかかる。', '提案の前に、深掘りの質問で顧客を理解する。' ),
						array( 'Handling Rejection Failure', '顧客に反論されると自信を失い、対応できず、取れるはずの案件を逃す。', '反論に自信を持って対応する練習を、手順に沿って行う。' ),
						array( 'Unpredictable Sales Performance', '追跡と評価のプロセスがなく売上の変動が大きいため、事業成果を予測できない。', '営業の追跡と評価の明確な仕組みをつくる。' ),
					),
					'outcomes' => array(
						array( 'Systematized Sales Excellence', '測定可能な7ステップのプロのプロセスでスキルを再構築し、ミスを減らして、安定した収益の新しい基準をつくる。' ),
						array( 'Strategic Market Advantage', '市場と競合を深く理解し、際立つセールスポイント（FAB）で優位性を築き、利益を守る。' ),
						array( 'High-Impact Value Proposition', '一般的な営業から、顧客を読み取り響く価値を提案するソリューション・アドバイザーへ。機会損失を減らし、リピートを増やす。' ),
						array( 'Optimized Operational Efficiency', 'マーケティングと連動した先回りの営業行動計画で、投じたすべての資源が同じ目標に向けて有効に使われる。' ),
						array( 'Predictable Growth & Risk Management', '明確な追跡の仕組みと代替策により、リーダーがリスクを管理し、成長を正確に予測できる。' ),
					),
				),
			),
		),
		'service-excellence' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรยกระดับงานบริการสู่กลยุทธ์มัดใจลูกค้า เปลี่ยนพนักงานเป็นทูตวัฒนธรรมองค์กร',
				'page'    => array(
					'intro'    => array( 'คุณภาพบริการคือสมรภูมิสุดท้าย', 'เมื่อสินค้าและราคาใกล้เคียงกัน คุณภาพการบริการคือสมรภูมิสุดท้ายที่ตัดสินว่าใครอยู่หรือไป หลายองค์กรเสียลูกค้าอย่างเงียบ ๆ เพราะพนักงานขาดทัศนคติเชิงบวกและทักษะสื่อสาร จนบริการกลายเป็นงานประจำ และเมื่อเกิดปัญหาก็กระทบความเชื่อมั่นต่อแบรนด์ หลักสูตรนี้เปลี่ยนพนักงานจากผู้ให้บริการตามหน้าที่ ให้เป็นทูตวัฒนธรรมองค์กรที่ส่งมอบความประทับใจระดับพรีเมียมในทุกจุดสัมผัส' ),
					'problems' => array(
						array( 'Negative Service Mindset', 'พนักงานมองงานบริการเป็นภาระ ไม่มีความสุขกับงาน บรรยากาศจึงตึงเครียดและไม่เป็นมิตร', 'ปรับมุมมองต่อการบริการให้เห็นคุณค่าและภูมิใจในงาน' ),
						array( 'Poor Communication Skills', 'ขาดวาจา น้ำเสียง และภาษากายที่เหมาะสม เกิดความเข้าใจผิดและประสบการณ์ที่น่าผิดหวัง', 'ฝึกวาจา น้ำเสียง และภาษากายที่สร้างความประทับใจ' ),
						array( 'Complaint Mismanagement', 'เมื่อถูกตำหนิ พนักงานตอบโต้ด้วยอารมณ์หรือเลี่ยงปัญหา แทนที่จะพลิกให้เป็นโอกาสสร้างความประทับใจ', 'ใช้การฟังและการจัดการอารมณ์เปลี่ยนข้อร้องเรียนเป็นความไว้วางใจ' ),
						array( 'Disconnected Brand Image', 'พนักงานไม่เข้าใจบทบาทตัวแทนแบรนด์ มาตรฐานบริการจึงไม่สม่ำเสมอและไม่สะท้อนค่านิยมองค์กร', 'เข้าใจบทบาทตัวแทนแบรนด์และส่งมอบมาตรฐานเดียวกันทุกจุดสัมผัส' ),
					),
					'outcomes' => array(
						array( 'Elevated Brand Equity & Reputation', 'พนักงานเป็น Brand Ambassador ส่งมอบบริการระดับสากล สร้างภาพลักษณ์ที่น่าเชื่อถือและคู่แข่งลอกยาก' ),
						array( 'Customer Loyalty & Lifetime Value (LTV)', 'ประสบการณ์เหนือความคาดหมายเปลี่ยนผู้ซื้อครั้งเดียวเป็นลูกค้าประจำที่บอกต่อ ลดต้นทุนการหาลูกค้าใหม่' ),
						array( 'Effective Conflict Transformation', 'การฟังเชิงรุกและการจัดการอารมณ์ขั้นสูงเปลี่ยนข้อร้องเรียนรุนแรงให้เป็นความเข้าใจและความภักดี' ),
						array( 'Internal Synergy & Workplace Productivity', 'ทัศนคติเชิงบวกและการสื่อสารที่ดีในทีมลดความขัดแย้งภายใน และทำให้งานส่งมอบลื่นไหลขึ้น' ),
						array( 'Strategic Growth & Profitability', 'บริการเป็นกลยุทธ์หลักที่ดึงดูดลูกค้าพรีเมียมซึ่งยอมจ่ายมากขึ้น รายได้และกำไรสุทธิเติบโตต่อเนื่อง' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Raise service to a strategy that wins customers, turning employees into ambassadors of the organization’s culture.',
				'page'    => array(
					'intro'    => array( 'Service quality is the last battleground', 'When products and prices are nearly the same, service quality is the last battleground that decides who stays and who goes. Many organizations lose customers quietly because staff lack a positive attitude and communication skills, so service becomes routine, and when problems arise it damages trust in the brand. This course turns employees from people who serve out of duty into ambassadors of the organization’s culture, delivering a premium impression at every touchpoint.' ),
					'problems' => array(
						array( 'Negative Service Mindset', 'Staff see service as a burden and take no joy in the work, so the atmosphere feels tense and unfriendly.', 'Shift how people see service so they find value and pride in the work.' ),
						array( 'Poor Communication Skills', 'Words, tone and body language are not right, leading to misunderstandings and disappointing experiences.', 'Practise the words, tone and body language that leave a good impression.' ),
						array( 'Complaint Mismanagement', 'When criticized, staff react emotionally or avoid the issue instead of turning it into a chance to impress.', 'Use listening and emotion management to turn complaints into trust.' ),
						array( 'Disconnected Brand Image', 'Staff do not understand their role as brand representatives, so service standards are uneven and do not reflect company values.', 'Understand the role of brand representative and deliver the same standard at every touchpoint.' ),
					),
					'outcomes' => array(
						array( 'Elevated Brand Equity & Reputation', 'Staff become brand ambassadors delivering international-standard service, building a credible image competitors find hard to copy.' ),
						array( 'Customer Loyalty & Lifetime Value (LTV)', 'Experiences beyond expectation turn one-time buyers into repeat customers who recommend you, lowering acquisition costs.' ),
						array( 'Effective Conflict Transformation', 'Active listening and advanced emotion management turn serious complaints into understanding and loyalty.' ),
						array( 'Internal Synergy & Workplace Productivity', 'Positive attitudes and good communication inside the team reduce internal conflict and keep work flowing.' ),
						array( 'Strategic Growth & Profitability', 'Service becomes a core strategy that attracts premium customers willing to pay more, with steady growth in revenue and net profit.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'サービスを、顧客の心をつかむ戦略へ。従業員を組織文化のアンバサダーに変える。',
				'page'    => array(
					'intro'    => array( 'サービス品質は、最後の戦場', '商品も価格もほぼ同じになったとき、サービス品質は誰が残り、誰が去るかを決める最後の戦場です。従業員に前向きな姿勢とコミュニケーションスキルが欠けているために、多くの組織が静かに顧客を失っています。サービスが日常業務と化し、問題が起きればブランドへの信頼にも響きます。このコースは、義務として対応する従業員を、あらゆる接点でプレミアムな印象を届ける組織文化のアンバサダーへと変えます。' ),
					'problems' => array(
						array( 'Negative Service Mindset', '従業員がサービスを負担と捉え、仕事に喜びがなく、雰囲気が張りつめて親しみにくくなる。', 'サービスの見方を変え、仕事に価値と誇りを見出す。' ),
						array( 'Poor Communication Skills', '言葉遣い、声のトーン、ボディランゲージが適切でなく、誤解や残念な体験につながる。', '好印象を与える言葉、トーン、ボディランゲージを練習する。' ),
						array( 'Complaint Mismanagement', '指摘されると、感情的に反応したり問題を避けたりして、好印象を与える機会に変えられない。', '傾聴と感情のコントロールで、クレームを信頼に変える。' ),
						array( 'Disconnected Brand Image', 'ブランドの代表としての役割を理解しておらず、サービス基準にばらつきがあり、会社の価値観が反映されない。', 'ブランド代表としての役割を理解し、あらゆる接点で同じ基準を届ける。' ),
					),
					'outcomes' => array(
						array( 'Elevated Brand Equity & Reputation', '従業員がブランドアンバサダーとして国際水準のサービスを届け、競合が真似しにくい信頼できるイメージを築く。' ),
						array( 'Customer Loyalty & Lifetime Value (LTV)', '期待を超える体験で、一度きりの購入者を、推奨してくれるリピーターに変え、新規獲得コストを下げる。' ),
						array( 'Effective Conflict Transformation', '積極的な傾聴と高度な感情コントロールで、深刻なクレームを理解と忠誠心へ変える。' ),
						array( 'Internal Synergy & Workplace Productivity', '前向きな姿勢とチーム内の良いコミュニケーションで内部の対立が減り、仕事の流れがスムーズになる。' ),
						array( 'Strategic Growth & Profitability', 'サービスを中核戦略とし、より多く払うプレミアム顧客を引きつけて、売上と純利益を着実に伸ばす。' ),
					),
				),
			),
		),
		'complaint-handling-excellence' => array(
			'th' => array(
				'excerpt' => 'หลักสูตรรับมือข้อติชม กู้คืนความเชื่อมั่นอย่างมืออาชีพ เปลี่ยนลูกค้าที่โกรธให้เป็นผู้ภักดีต่อแบรนด์',
				'page'    => array(
					'intro'    => array( 'ข้อร้องเรียนเพียงรายเดียว อาจกลายเป็นวิกฤตของแบรนด์', 'ในยุคที่ Social Media ขยายความผิดพลาดเล็กน้อยให้กลายเป็นวิกฤตได้ ข้อร้องเรียนของลูกค้าเพียงรายเดียวก็อาจเป็นความเสี่ยงต่อชื่อเสียงองค์กร หลายองค์กรพลาดเพราะพนักงานมองการถูกตำหนิเป็นเรื่องส่วนตัว และตอบโต้ด้วยอารมณ์แทนกลยุทธ์ จนเสียลูกค้าและส่วนแบ่งตลาด หลักสูตรนี้พัฒนาพนักงานให้เจรจาและแก้สถานการณ์วิกฤต จนลูกค้าที่โกรธกลับมาเป็นผู้ภักดีต่อแบรนด์' ),
					'problems' => array(
						array( 'Emotional Defense', 'ควบคุมอารมณ์ไม่ได้เมื่อถูกกดดัน จึงตอบโต้ด้วยอารมณ์ จนบทสนทนาบานปลายเป็นความขัดแย้ง', 'ฝึกควบคุมอารมณ์และตอบสนองอย่างมีกลยุทธ์ภายใต้แรงกดดัน' ),
						array( 'Lacking Empathy', 'ไม่เข้าใจอารมณ์ของลูกค้าและฟังแบบเข้าข้างตัวเอง คำชี้แจงจึงถูกมองเป็นการแก้ตัว', 'ฝึกฟังด้วยความเข้าอกเข้าใจก่อนชี้แจง' ),
						array( 'Ineffective Problem Solving', 'แก้ปัญหาไม่ตรงจุด ให้ข้อมูลผิด หรือรับปากส่ง ๆ เพื่อให้จบ ลูกค้าจึงร้องเรียนซ้ำและเสียความเชื่อมั่น', 'ใช้ขั้นตอนแก้ปัญหาที่จบได้ในครั้งเดียวและทำตามสัญญา' ),
						array( 'Burnout & Stress', 'ความเครียดสะสมและขาดเทคนิคจัดการอารมณ์ตนเอง จนท้อแท้และประสิทธิภาพลดลง', 'เรียนรู้เทคนิคดูแลอารมณ์ตนเองและฟื้นพลังหลังเจอสถานการณ์ตึงเครียด' ),
					),
					'outcomes' => array(
						array( 'Strategic Recovery & Customer Retention', 'เปลี่ยนลูกค้าที่ผิดหวังเป็นผู้สนับสนุนด้วยการเยียวยาความรู้สึกและการชดเชยที่เหมาะสม ลดการสูญเสียลูกค้า' ),
						array( 'Brand Reputation Immunity', 'สร้างเกราะป้องกันชื่อเสียงออนไลน์ ลดการบอกต่อเชิงลบ และเปลี่ยนความไม่พอใจให้เป็นความประทับใจ' ),
						array( 'Actionable Consumer Insights', 'ใช้ข้อมูลจากข้อร้องเรียนชี้จุดอ่อนของกระบวนการ เพื่อพัฒนาสินค้าและบริการอย่างยั่งยืน' ),
						array( 'Operational Risk Mitigation', 'ลดความเสียหายทางการเงินและทรัพยากรจากปัญหาที่บานปลาย ด้วยระบบแก้ปัญหาเชิงรุกที่จบในครั้งเดียว' ),
						array( 'Resilient Workforce Performance', 'ทีมมีความฉลาดทางอารมณ์ ควบคุมสถานการณ์กดดันได้ ลดภาวะ Burnout และรักษามาตรฐานบริการในทุกสถานการณ์' ),
					),
				),
			),
			'en' => array(
				'excerpt' => 'Handle feedback and win back trust professionally, turning angry customers into loyal advocates of the brand.',
				'page'    => array(
					'intro'    => array( 'One complaint can become a brand crisis', 'In an age when social media can blow a small mistake up into a crisis, a single customer complaint can put the organization’s reputation at risk. Many organizations fail because staff take criticism personally and react with emotion instead of strategy, losing customers and market share. This course trains staff to negotiate and resolve critical situations until an angry customer becomes loyal to the brand.' ),
					'problems' => array(
						array( 'Emotional Defense', 'Staff cannot control their emotions under pressure and react emotionally, so the conversation escalates into conflict.', 'Practise emotional control and strategic responses under pressure.' ),
						array( 'Lacking Empathy', 'Staff do not grasp the customer’s feelings and listen defensively, so explanations sound like excuses.', 'Practise listening with empathy before explaining.' ),
						array( 'Ineffective Problem Solving', 'Fixes miss the point, wrong information is given, or promises are made carelessly just to end the call, so customers complain again and lose confidence.', 'Use a problem-solving routine that resolves the issue in one go, and keep your promises.' ),
						array( 'Burnout & Stress', 'Accumulated stress and no techniques for managing their own emotions leave staff discouraged and less effective.', 'Learn techniques to manage your own emotions and recover after tense situations.' ),
					),
					'outcomes' => array(
						array( 'Strategic Recovery & Customer Retention', 'Turn disappointed customers into supporters through emotional care and fitting compensation, reducing customer loss.' ),
						array( 'Brand Reputation Immunity', 'Shield the online reputation, reduce negative word of mouth and turn dissatisfaction into a good impression.' ),
						array( 'Actionable Consumer Insights', 'Use complaint data to pinpoint process weaknesses and improve products and services sustainably.' ),
						array( 'Operational Risk Mitigation', 'Cut the financial and resource damage from problems that spiral, with a proactive system that resolves issues in one go.' ),
						array( 'Resilient Workforce Performance', 'A team with emotional intelligence that keeps control under pressure, with less burnout and steady service standards in every situation.' ),
					),
				),
			),
			'ja' => array(
				'excerpt' => 'クレーム対応で、信頼をプロとして取り戻す。怒れる顧客を、ブランドの忠実なファンへ。',
				'page'    => array(
					'intro'    => array( 'たった一件のクレームが、ブランドの危機になる', 'ソーシャルメディアが小さなミスを危機にまで拡大させる時代、顧客一人のクレームでも組織の評判を脅かしかねません。多くの組織が失敗するのは、従業員が指摘を個人的に受け止め、戦略ではなく感情で反応して、顧客と市場シェアを失うからです。このコースは、従業員が危機的な状況を交渉し解決できるよう育て、怒っていた顧客がブランドの忠実なファンへ変わるまでを目指します。' ),
					'problems' => array(
						array( 'Emotional Defense', 'プレッシャーの中で感情を抑えられず、感情で反応して、会話が対立にまで拡大する。', 'プレッシャー下でも感情を制御し、戦略的に対応する練習をする。' ),
						array( 'Lacking Empathy', '顧客の気持ちを理解せず、自己弁護しながら聞くため、説明が言い訳に聞こえる。', '説明の前に、共感して聴く練習をする。' ),
						array( 'Ineffective Problem Solving', '的外れな対応、誤った情報、場を終わらせるためだけの安易な約束により、顧客が再びクレームを入れ、信頼を失う。', '一度で解決できる対応手順を使い、約束を守る。' ),
						array( 'Burnout & Stress', 'ストレスが蓄積し、自分の感情を整える技術もなく、意欲と効率が下がる。', '自分の感情を整え、緊張した場面のあとに立て直す技術を学ぶ。' ),
					),
					'outcomes' => array(
						array( 'Strategic Recovery & Customer Retention', '気持ちへのケアと適切な補償により、失望した顧客を支持者に変え、顧客離れを減らす。' ),
						array( 'Brand Reputation Immunity', 'オンライン上の評判を守り、ネガティブな口コミを減らして、不満を好印象に変える。' ),
						array( 'Actionable Consumer Insights', 'クレームのデータからプロセスの弱点を特定し、商品とサービスを持続的に改善する。' ),
						array( 'Operational Risk Mitigation', '一度で解決する先回りの仕組みで、問題の拡大による金銭的・資源的な損害を減らす。' ),
						array( 'Resilient Workforce Performance', '感情の知性を備え、プレッシャー下でも状況を制御できるチーム。燃え尽きを減らし、どんな状況でもサービス水準を保つ。' ),
					),
				),
			),
		),
	);
}
