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
			'meta'     => array( 'hri_batch_no' => 22, 'hri_reg_status' => 'open', 'hri_start_date' => '2027-02-05', 'hri_end_date' => '2027-03-05', 'hri_early_deadline' => '2026-08-03', 'hri_register_url' => 'https://forms.gle/GRcUzALgDKTC29n8' ),
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
						array( 'เน้นเรื่อง AI แค่ไหน?', 'มีโมดูล Efficiency & AI Mastery โดยผู้เชี่ยวชาญจาก Innovate AI เน้นการนำ AI มาลดงานประจำและเพิ่มประสิทธิภาพของผู้จัดการ' ),
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
						array( 'How much does it focus on AI?', 'There is a dedicated Efficiency & AI Mastery module with an expert from Innovate AI, focused on using AI to cut routine work and raise managers’ productivity.' ),
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
						array( 'AIはどのくらい扱いますか。', 'Innovate AIの専門家による「Efficiency & AI Mastery」モジュールがあり、AIで定型業務を減らし、マネージャーの生産性を高める方法を扱います。' ),
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
