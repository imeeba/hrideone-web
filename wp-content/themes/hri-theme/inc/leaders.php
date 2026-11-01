<?php
/**
 * Texts and markup shared by the pages that show the executives' message (Home, About).
 *
 * The texts are the ones of the old hrideone.com/about page, word for word (Thai is the source language;
 * the English and Japanese versions come from the old site's EN / JA pages, in languages/*.po).
 *
 * @package hri-theme
 */

/**
 * The two executives: name, role, company, headline (lines separated by a line break) and message paragraphs.
 *
 * @return array[] Each: name, role, org, headline (string), paragraphs (string[]).
 */
function hri_leaders(): array {
	return array(
		array(
			'name'       => 'Dr. Chaipat Lertlucktaweekul',
			'role'       => 'Board of Director, CEO',
			'org'        => 'HRI DeOne Co., Ltd.',
			'headline'   => __( "การก่อตั้ง HRI DeOne\nไม่ใช่เพียงการรวมกันของสองบริษัท\nแต่เป็นการรวมกันของสองจุดแข็ง\nสองวัฒนธรรม และวิสัยทัศน์เดียวกัน", 'hri-theme' ),
			'paragraphs' => array(
				__( "HR Institute เป็นองค์กรชั้นนำด้านการพัฒนาผู้นำและทรัพยากรมนุษย์ที่ได้รับความไว้วางใจจากองค์กรชั้นนำของญี่ปุ่นมาอย่างต่อเนื่องตั้งแต่ปี 1993 ขณะที่ DeOne Academy มีความเชี่ยวชาญด้าน Leadership และ Transformation พร้อมความเข้าใจบริบทขององค์กรไทยอย่างลึกซึ้ง", 'hri-theme' ),
				__( "การผสานจุดแข็งของทั้งสององค์กร ทำให้เราเชื่อมั่นว่า เราจะสามารถสร้างคุณค่าที่มากกว่าการทำงานของแต่ละองค์กรเพียงลำพัง นี่คือก้าวสำคัญในการยกระดับการพัฒนาผู้นำและองค์กรของประเทศไทยให้เชื่อมโยงกับมาตรฐานระดับสากล", 'hri-theme' ),
				__( "โลกธุรกิจกำลังเปลี่ยนแปลงอย่างรวดเร็วจาก AI เทคโนโลยี และความท้าทายใหม่ ๆ องค์กรจึงไม่ได้ต้องการเพียงหลักสูตรฝึกอบรม แต่ต้องการพันธมิตรที่สามารถพัฒนาผู้นำ สร้างการเปลี่ยนแปลง และยกระดับศักยภาพของทั้งองค์กรได้อย่างเป็นรูปธรรม นี่คือบทบาทที่ HRI DeOne ตั้งใจจะทำ", 'hri-theme' ),
			),
		),
		array(
			'name'       => 'Mr. Ken Misaka',
			'role'       => 'Board of Director, Chairman',
			'org'        => 'HRI DeOne Co., Ltd.',
			'headline'   => __( "HRI DeOne มุ่งหวังที่จะเป็น\nสะพานเชื่อมระหว่างญี่ปุ่น ไทย\nและภูมิภาคเอเชียตะวันออกเฉียงใต้\nพร้อมเป็นพันธมิตรที่ร่วมสร้าง\nการเติบโตในอนาคตไปพร้อมกับทุกท่าน", 'hri-theme' ),
			'paragraphs' => array(
				__( "ตลอดระยะเวลากว่า 30 ปีที่ผ่านมา เราได้พัฒนาและสั่งสมองค์ความรู้ด้านการบริหารจัดการ การพัฒนาทรัพยากรมนุษย์ และการพัฒนาองค์กรร่วมกับบริษัทญี่ปุ่นมาอย่างต่อเนื่อง เราเชื่อมั่นเป็นอย่างยิ่งว่า องค์ความรู้เหล่านี้สามารถก้าวข้ามพรมแดน และสร้างคุณค่าให้แก่องค์กรในประเทศไทย รวมถึงองค์กรในภูมิภาคเอเชียตะวันออกเฉียงใต้ได้อย่างแท้จริง", 'hri-theme' ),
				__( "เรามุ่งมั่นที่จะพัฒนาบริการและแนวทางการพัฒนาที่ได้รับการยกระดับจากพลังร่วมของทั้งสององค์กร และส่งมอบคุณค่าเหล่านั้นให้แก่ลูกค้าของเราอย่างดีที่สุด", 'hri-theme' ),
				__( "การก้าวเข้าสู่บทใหม่ร่วมกับเพื่อนร่วมงานและพันธมิตรทุกฝ่ายในครั้งนี้ เป็นสิ่งที่เราภาคภูมิใจเป็นอย่างยิ่ง และเต็มไปด้วยความคาดหวังต่ออนาคตที่กำลังจะมาถึง ผมหวังเป็นอย่างยิ่งว่าทุกท่านจะให้การสนับสนุน และร่วมเดินไปกับ HRI DeOne ในเส้นทางแห่งการเติบโตครั้งใหม่นี้ครับ", 'hri-theme' ),
			),
		),
	);
}

/**
 * One message: large portrait next to the headline, the paragraphs and the signature.
 *
 * @param array  $leader     Item of hri_leaders().
 * @param bool   $reverse    Portrait on the right (desktop).
 * @param int    $paragraphs How many paragraphs to show (0 = all).
 * @param string $more_url   Link "read the full message" under the text ('' = none).
 */
function hri_bm_leader( array $leader, bool $reverse = false, int $paragraphs = 0, string $more_url = '' ): string {
	$items = $paragraphs > 0 ? array_slice( $leader['paragraphs'], 0, $paragraphs ) : $leader['paragraphs'];
	$out   = hri_bm_group( array( 'className' => hri_bm_class( array( 'hri-split', 'hri-leader', $reverse ? 'hri-leader--reverse' : '' ) ) ) )
		. hri_bm_image( 'assets/images/placeholder/portrait.svg', $leader['name'], 'hri-leader__photo' )
		. hri_bm_group( array( 'className' => 'hri-split__text hri-leader__text' ) )
		. hri_bm_heading( $leader['headline'], array( 'level' => 3, 'className' => 'hri-leader__headline' ) );
	foreach ( $items as $item ) {
		$out .= hri_bm_p( esc_html( $item ) );
	}
	if ( '' !== $more_url ) {
		$out .= hri_bm_p( '<a href="' . esc_url( $more_url ) . '">' . esc_html__( 'อ่านสาส์นฉบับเต็ม', 'hri-theme' ) . ' →</a>', array( 'className' => 'hri-leader__more' ) );
	}
	$out .= hri_bm_p( '<strong>' . esc_html( $leader['name'] ) . '</strong><br>' . esc_html( $leader['role'] ) . '<br>' . esc_html( $leader['org'] ), array( 'className' => 'hri-leader__sign' ) );
	return $out . hri_bm_group_end() . hri_bm_group_end();
}

/**
 * One row: illustration and text (kicker, English statement, headline, paragraph).
 */
function hri_about_row( string $kicker, string $statement, string $headline, string $text, bool $reverse ): string {
	return hri_bm_group( array( 'className' => hri_bm_class( array( 'hri-split', 'hri-about-row', $reverse ? 'hri-about-row--reverse' : '' ) ) ) )
		. hri_bm_photo( $statement )
		. hri_bm_group( array( 'className' => 'hri-split__text' ) )
		. hri_bm_eyebrow( $kicker, false )
		. hri_bm_heading( $statement, array( 'level' => 2, 'className' => 'hri-about-row__title' ) )
		. hri_bm_p( '<strong>' . nl2br( esc_html( $headline ) ) . '</strong>', array( 'fontSize' => 'large' ) )
		. hri_bm_p( esc_html( $text ) )
		. hri_bm_group_end()
		. hri_bm_group_end();
}
