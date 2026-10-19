<?php
/**
 * Title: About — Message from the executives (full)
 * Slug: hri/about-message
 * Categories: hri
 * Keywords: about, เกี่ยวกับ
 * Description: Full message from the CEO and the Chairman with their photos (placeholders). The home page keeps a short version.
 *
 * @package hri-theme
 */

$hri_paragraphs = array(
	__( 'HR Institute ได้รับความไว้วางใจจากองค์กรญี่ปุ่นมาตั้งแต่ปี 1993 และ DeOne Academy เชี่ยวชาญด้านการพัฒนาภาวะผู้นำและการเปลี่ยนแปลงองค์กร การรวมกันเป็น HRI DeOne คือการยกระดับสู่มาตรฐานสากลที่ผสานจุดแข็งของทั้งสองฝ่าย', 'hri-theme' ),
	__( 'ในยุคที่ AI เปลี่ยนวิธีทำงาน องค์กรไม่ได้ต้องการแค่ความรู้ แต่ต้องการพันธมิตรที่ช่วยสร้างผลลัพธ์จริง เราจึงออกแบบทุกหลักสูตรและโครงการให้เชื่อมกับเป้าหมายทางธุรกิจ', 'hri-theme' ),
	__( 'HRI DeOne มุ่งหวังที่จะเป็นสะพานเชื่อมระหว่างญี่ปุ่น ไทย และภูมิภาคเอเชียตะวันออกเฉียงใต้ ด้วยประสบการณ์กว่า 30 ปีกับบริษัทญี่ปุ่น พร้อมร่วมสร้างการเติบโตในอนาคตไปพร้อมกับทุกท่าน', 'hri-theme' ),
);
$hri_signers = array(
	array( 'Dr. Chaipat Lertlucktaweekul', 'Board of Director, CEO' ),
	array( 'Mr. Ken Misaka', 'Board of Director, Chairman' ),
);
$hri_out = hri_bm_section( 'hri-message-section', 'surface-brand', 'message', '', '70', '70' )
	. hri_bm_head( __( 'สาส์นจากผู้บริหาร', 'hri-theme' ), 'Message from CEO & Chairman', '', '', true )
	. hri_bm_group( array( 'className' => 'hri-prose', 'layout' => array( 'type' => 'constrained', 'contentSize' => '760px' ) ) );
foreach ( $hri_paragraphs as $hri_paragraph ) {
	$hri_out .= hri_bm_p( esc_html( $hri_paragraph ), array( 'fontSize' => 'large' ) );
}
$hri_out .= hri_bm_group_end()
	. hri_bm_group( array( 'className' => 'hri-messages', 'layout' => array( 'type' => 'grid', 'columnCount' => 2, 'minimumColumnWidth' => '20rem' ) ) );
foreach ( $hri_signers as $hri_signer ) {
	$hri_out .= hri_bm_group( array( 'className' => 'is-style-card hri-message', 'layout' => array( 'type' => 'default' ) ) )
		. hri_bm_image( 'assets/images/placeholder/portrait.svg', $hri_signer[0], 'hri-message__photo' )
		. hri_bm_p( '<strong>' . esc_html( $hri_signer[0] ) . '</strong><br>' . esc_html( $hri_signer[1] ) )
		. hri_bm_group_end();
}
echo $hri_out . hri_bm_group_end() . hri_bm_section_end(); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup, escaped inside.
