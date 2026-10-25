<?php
/**
 * Structured data (JSON-LD): one schema.org @graph per page, built from the site's own data so it
 * never needs separate editing:
 *
 * - every page: EducationalOrganization (Settings → ข้อมูลบริษัท), WebSite (+ site search),
 *   WebPage (or CollectionPage / AboutPage / ContactPage / SearchResultsPage) and BreadcrumbList
 * - article: BlogPosting · course: Course + hasCourseInstance + offers (price, Early Bird until its
 *   deadline) + instructor (Person, from the course's trainers)
 * - any page with FAQ items (Details blocks with class hri-faq__item): FAQPage
 *
 * Node ids are "<url>#<name>", so nodes link to each other across pages. Texts are in the page's
 * language. TSF's own JSON-LD is switched off (seo.php), so there is no second graph.
 * Filter `hri_schema_graph` changes or removes nodes; return an empty array to print nothing.
 *
 * @package hri-core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_head',
	static function () {
		if ( is_feed() || is_robots() || is_embed() ) {
			return;
		}
		$graph = (array) apply_filters( 'hri_schema_graph', hri_schema_graph() );
		$graph = array_values( array_filter( $graph ) );
		if ( ! $graph ) {
			return;
		}
		$json = wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP
		);
		if ( $json ) {
			echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- JSON encoded with HEX_TAG.
		}
	},
	3
);

/**
 * Home URL of the current (or given) language.
 */
function hri_schema_home( string $lang = '' ): string {
	if ( function_exists( 'pll_home_url' ) ) {
		return (string) pll_home_url( $lang ? $lang : (string) pll_current_language() );
	}
	return home_url( '/' );
}

/**
 * BCP 47 language of the page (th, en, ja).
 */
function hri_schema_lang(): string {
	return hri_current_language();
}

/**
 * Plain text: no tags, entities decoded, spaces collapsed, cut at $max characters.
 */
function hri_schema_text( string $html, int $max = 0 ): string {
	$text = trim( (string) preg_replace( '/\s+/u', ' ', wp_strip_all_tags( html_entity_decode( $html, ENT_QUOTES, 'UTF-8' ) ) ) );
	if ( $max && mb_strlen( $text ) > $max ) {
		$text = rtrim( mb_substr( $text, 0, $max - 1 ) ) . '…';
	}
	return $text;
}

/**
 * "065-097-9444" → "+66-65-097-9444" (Thai numbers; others unchanged).
 */
function hri_schema_phone( string $phone ): string {
	$digits = preg_replace( '/\D/', '', $phone );
	if ( preg_match( '/^0(\d{8,9})$/', (string) $digits, $m ) ) {
		$rest = $m[1];
		return '+66-' . substr( $rest, 0, 2 ) . '-' . substr( $rest, 2, 3 ) . '-' . substr( $rest, 5 );
	}
	return trim( $phone );
}

/**
 * ImageObject for a URL (width / height when known).
 *
 * @return array<string,mixed>|null
 */
function hri_schema_image( string $url, int $width = 0, int $height = 0 ): ?array {
	if ( '' === $url ) {
		return null;
	}
	$out = array(
		'@type' => 'ImageObject',
		'url'   => $url,
	);
	if ( $width && $height ) {
		$out['width']  = $width;
		$out['height'] = $height;
	}
	return $out;
}

/**
 * Cover ImageObject of a post (featured image or remote cover).
 *
 * @return array<string,mixed>|null
 */
function hri_schema_post_image( int $post_id ): ?array {
	$thumb = get_post_thumbnail_id( $post_id );
	if ( $thumb ) {
		$src = wp_get_attachment_image_src( $thumb, 'large' );
		return $src ? hri_schema_image( (string) $src[0], (int) $src[1], (int) $src[2] ) : null;
	}
	$url = hri_remote_thumbnail_url( $post_id );
	return hri_schema_image( $url, (int) get_post_meta( $post_id, '_hri_thumbnail_w', true ), (int) get_post_meta( $post_id, '_hri_thumbnail_h', true ) );
}

/**
 * Organization id (one per site, the Thai home URL).
 */
function hri_schema_org_id(): string {
	return hri_schema_home( function_exists( 'pll_default_language' ) ? (string) pll_default_language() : '' ) . '#organization';
}

/**
 * The company: EducationalOrganization.
 *
 * @return array<string,mixed>
 */
function hri_schema_organization(): array {
	$name    = (string) get_bloginfo( 'name' );
	$address = hri_company( 'address' );
	$lines   = array_values( array_filter( array_map( 'trim', preg_split( '/\R/u', $address ) ) ) );
	$legal   = hri_company( 'legal_name' );
	// The first line of the address is the company name (footer layout).
	if ( $lines && ( 0 === stripos( $lines[0], $legal ) || 0 === stripos( $lines[0], $name ) || false !== stripos( $lines[0], 'CO., LTD' ) || false !== mb_strpos( $lines[0], 'จำกัด' ) ) ) {
		array_shift( $lines );
	}
	$street = trim( implode( ' ', $lines ), " ,\t" );
	$postal = preg_match( '/\b(\d{5})\b/', $street, $m ) ? $m[1] : '';

	$logo_url = get_site_icon_url( 512 );
	$logo     = $logo_url ? hri_schema_image( $logo_url, 512, 512 ) : hri_schema_image( get_theme_file_uri( 'assets/images/logo/logo.svg' ) );

	$same = array();
	foreach ( array( 'facebook_url', 'youtube_url', 'linkedin_url', 'line_url' ) as $key ) {
		$url = hri_company( $key );
		if ( $url ) {
			$same[] = $url;
		}
	}

	$phone = hri_company( 'phone' );
	$email = hri_company( 'email' );
	$org   = array(
		'@type'       => 'EducationalOrganization',
		'@id'         => hri_schema_org_id(),
		'name'        => $name,
		'legalName'   => $legal ? $legal : null,
		'url'         => hri_schema_home(),
		'logo'        => $logo,
		'image'       => $logo,
		'description' => hri_company( 'tagline' ),
		'telephone'   => $phone ? hri_schema_phone( $phone ) : null,
		'email'       => $email ? $email : null,
		'address'     => $street ? array_filter(
			array(
				'@type'          => 'PostalAddress',
				'streetAddress'  => $street,
				'postalCode'     => $postal,
				'addressCountry' => 'TH',
			)
		) : null,
		'sameAs'      => $same ? $same : null,
	);
	if ( $phone || $email ) {
		$org['contactPoint'] = array_filter(
			array(
				'@type'             => 'ContactPoint',
				'contactType'       => 'customer service',
				'telephone'         => $phone ? hri_schema_phone( $phone ) : null,
				'email'             => $email ? $email : null,
				'url'               => hri_contact_url(),
				'availableLanguage' => array( 'th', 'en', 'ja' ),
			)
		);
	}
	return array_filter( $org, static fn( $v ) => null !== $v && '' !== $v );
}

/**
 * WebSite of the current language, with the site search.
 *
 * @return array<string,mixed>
 */
function hri_schema_website(): array {
	$home = hri_schema_home();
	return array(
		'@type'           => 'WebSite',
		'@id'             => $home . '#website',
		'url'             => $home,
		'name'            => (string) get_bloginfo( 'name' ),
		'description'     => hri_schema_text( (string) get_bloginfo( 'description' ) ),
		'inLanguage'      => hri_schema_lang(),
		'publisher'       => array( '@id' => hri_schema_org_id() ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => add_query_arg( 's', '{search_term_string}', $home ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * URL of the current page (without paging query strings of our filters).
 */
function hri_schema_current_url(): string {
	if ( is_singular() ) {
		return (string) get_permalink( get_queried_object_id() );
	}
	if ( is_front_page() ) {
		return hri_schema_home();
	}
	if ( is_home() ) {
		return (string) get_permalink( (int) get_option( 'page_for_posts' ) );
	}
	if ( is_post_type_archive() ) {
		return (string) get_post_type_archive_link( (string) get_query_var( 'post_type' ) ?: 'course' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? '' : (string) $link;
	}
	if ( is_search() ) {
		return add_query_arg( 's', rawurlencode( (string) get_search_query( false ) ), hri_schema_home() );
	}
	return '';
}

/**
 * Title of the current page as shown in the breadcrumb / WebPage name.
 */
function hri_schema_current_title(): string {
	if ( is_singular() ) {
		return hri_schema_text( get_the_title( get_queried_object_id() ) );
	}
	if ( is_home() ) {
		return hri_schema_text( get_the_title( (int) get_option( 'page_for_posts' ) ) );
	}
	if ( is_post_type_archive( 'course' ) ) {
		return __( 'หลักสูตรทั้งหมด', 'hri-core' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		return $term instanceof WP_Term ? hri_schema_text( $term->name ) : '';
	}
	if ( is_search() ) {
		/* translators: %s: search words */
		return sprintf( __( 'ผลการค้นหา “%s”', 'hri-core' ), get_search_query( false ) );
	}
	return hri_schema_text( wp_get_document_title() );
}

/**
 * Blog page (posts page) of the current language: array( url, title ) or null.
 *
 * @return array{0:string,1:string}|null
 */
function hri_schema_blog_crumb(): ?array {
	$id = (int) get_option( 'page_for_posts' );
	if ( ! $id ) {
		return null;
	}
	if ( function_exists( 'pll_get_post' ) ) {
		$id = (int) ( pll_get_post( $id ) ?: $id );
	}
	return array( (string) get_permalink( $id ), hri_schema_text( get_the_title( $id ) ) );
}

/**
 * Breadcrumb items (url, name) from the home page to the current page.
 *
 * @return array<int,array{0:string,1:string}>
 */
function hri_schema_breadcrumb_items(): array {
	$items = array( array( hri_schema_home(), __( 'หน้าแรก', 'hri-core' ) ) );

	if ( is_singular( 'post' ) || is_category() || is_tag() ) {
		$blog = hri_schema_blog_crumb();
		if ( $blog ) {
			$items[] = $blog;
		}
		if ( is_singular( 'post' ) ) {
			$cats = get_the_category( get_queried_object_id() );
			if ( $cats ) {
				$items[] = array( (string) get_category_link( $cats[0] ), hri_schema_text( $cats[0]->name ) );
			}
		}
	} elseif ( is_singular( 'course' ) ) {
		$items[] = array( (string) get_post_type_archive_link( 'course' ), __( 'หลักสูตรทั้งหมด', 'hri-core' ) );
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $parent ) {
			$items[] = array( (string) get_permalink( $parent ), hri_schema_text( get_the_title( $parent ) ) );
		}
	}

	$items[] = array( hri_schema_current_url(), hri_schema_current_title() );
	return $items;
}

/**
 * BreadcrumbList node (not on the home page).
 *
 * @return array<string,mixed>|null
 */
function hri_schema_breadcrumb( string $url ): ?array {
	if ( is_front_page() || is_404() ) {
		return null;
	}
	$list  = array();
	$items = hri_schema_breadcrumb_items();
	$last  = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		if ( '' === $item[1] ) {
			continue;
		}
		$entry = array(
			'@type'    => 'ListItem',
			'position' => count( $list ) + 1,
			'name'     => $item[1],
		);
		// The last item is the page itself: Google takes its URL from the page.
		if ( $item[0] && $i < $last ) {
			$entry['item'] = $item[0];
		}
		$list[] = $entry;
	}
	return count( $list ) > 1 ? array(
		'@type'           => 'BreadcrumbList',
		'@id'             => $url . '#breadcrumb',
		'itemListElement' => $list,
	) : null;
}

/**
 * The page node.
 *
 * @return array<string,mixed>
 */
function hri_schema_webpage( string $url ): array {
	$type = 'WebPage';
	if ( is_home() || is_post_type_archive() || is_category() || is_tag() || is_tax() ) {
		$type = 'CollectionPage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} elseif ( is_page() ) {
		$slug = hri_schema_source_slug( get_queried_object_id() );
		$type = 'about' === $slug ? 'AboutPage' : ( 'contact-us' === $slug ? 'ContactPage' : 'WebPage' );
	}

	$page = array(
		'@type'      => $type,
		'@id'        => $url . '#webpage',
		'url'        => $url,
		'name'       => hri_schema_current_title(),
		'inLanguage' => hri_schema_lang(),
		'isPartOf'   => array( '@id' => hri_schema_home() . '#website' ),
	);
	if ( is_front_page() ) {
		$page['about'] = array( '@id' => hri_schema_org_id() );
	}
	if ( is_singular() ) {
		$id                    = get_queried_object_id();
		$page['description']   = hri_seo_description( $id );
		$page['datePublished'] = get_post_time( 'c', true, $id );
		$page['dateModified']  = get_post_modified_time( 'c', true, $id );
		$image                 = hri_schema_post_image( $id );
		if ( $image ) {
			$page['primaryImageOfPage'] = $image;
		}
	}
	if ( ! is_front_page() ) {
		$page['breadcrumb'] = array( '@id' => $url . '#breadcrumb' );
	}
	return array_filter( $page, static fn( $v ) => '' !== $v && null !== $v );
}

/**
 * Slug of the source-language (Thai) version of a page.
 */
function hri_schema_source_slug( int $post_id ): string {
	if ( function_exists( 'pll_get_post' ) && function_exists( 'pll_default_language' ) ) {
		$post_id = (int) ( pll_get_post( $post_id, (string) pll_default_language() ) ?: $post_id );
	}
	return (string) get_post_field( 'post_name', $post_id );
}

/**
 * BlogPosting for an article.
 *
 * @return array<string,mixed>
 */
function hri_schema_blogposting( int $post_id, string $url ): array {
	$cats = wp_list_pluck( get_the_category( $post_id ), 'name' );
	$post = array(
		'@type'            => 'BlogPosting',
		'@id'              => $url . '#article',
		'headline'         => hri_schema_text( get_the_title( $post_id ), 110 ),
		'description'      => hri_seo_description( $post_id ),
		'url'              => $url,
		'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
		'datePublished'    => get_post_time( 'c', true, $post_id ),
		'dateModified'     => get_post_modified_time( 'c', true, $post_id ),
		'inLanguage'       => hri_schema_lang(),
		'image'            => hri_schema_post_image( $post_id ),
		'author'           => array( '@id' => hri_schema_org_id() ),
		'publisher'        => array( '@id' => hri_schema_org_id() ),
		'articleSection'   => $cats ? array_map( 'hri_schema_text', $cats ) : null,
		'isPartOf'         => array( '@id' => hri_schema_home() . '#website' ),
	);
	return array_filter( $post, static fn( $v ) => '' !== $v && null !== $v );
}

/**
 * A price string of the course details ("25,000 บาท", "฿25,000 + VAT") as a number, or null.
 */
function hri_schema_price( string $price ): ?string {
	if ( ! preg_match( '/\d[\d,]*(?:\.\d+)?/', $price, $m ) ) {
		return null;
	}
	$number = str_replace( ',', '', $m[0] );
	return (float) $number > 0 ? $number : null;
}

/**
 * Person nodes of the course's trainers.
 *
 * @return array<int,array<string,mixed>>
 */
function hri_schema_trainers( int $course_id ): array {
	$out = array();
	foreach ( hri_course_trainer_ids( $course_id ) as $id ) {
		$person = array(
			'@type'       => 'Person',
			'name'        => hri_schema_text( get_the_title( $id ) ),
			'jobTitle'    => has_excerpt( $id ) ? hri_schema_text( get_the_excerpt( $id ) ) : null,
			'description' => hri_schema_text( (string) get_post_field( 'post_content', $id ), 300 ),
			'image'       => hri_seo_image_url( $id, 'medium' ),
			'worksFor'    => array( '@id' => hri_schema_org_id() ),
		);
		$out[] = array_filter( $person, static fn( $v ) => '' !== $v && null !== $v );
	}
	return $out;
}

/**
 * Course with its current instance (Public: batch dates and venue) and offers.
 *
 * @return array<string,mixed>
 */
function hri_schema_course( int $post_id, string $url ): array {
	$meta     = hri_course_meta( $post_id );
	$program  = hri_course_first_slug( $post_id, 'hri_program' );
	$formats  = wp_get_post_terms( $post_id, 'hri_format', array( 'fields' => 'slugs' ) );
	$formats  = is_array( $formats ) ? $formats : array();
	$days     = (int) ( $meta['hri_duration_days'] ?? 0 );
	$trainers = hri_schema_trainers( $post_id );
	$levels   = hri_course_term_names( $post_id, 'hri_level' );
	$topics   = array_merge( hri_course_term_names( $post_id, 'hri_pillar' ), hri_course_term_names( $post_id, 'hri_topic' ) );

	$modes = array();
	foreach ( $formats as $slug ) {
		$modes[] = array(
			'on-site' => 'Onsite',
			'online'  => 'Online',
			'hybrid'  => 'Blended',
		)[ $slug ] ?? null;
	}
	$modes = array_values( array_filter( array_unique( $modes ) ) );
	if ( ! $modes ) {
		$modes = array( 'Onsite' );
	}

	$course = array(
		'@type'            => 'Course',
		'@id'              => $url . '#course',
		'name'             => hri_schema_text( get_the_title( $post_id ) ),
		'description'      => hri_seo_description( $post_id ),
		'url'              => $url,
		'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
		'inLanguage'       => hri_schema_lang(),
		'image'            => hri_schema_post_image( $post_id ),
		'provider'         => array( '@id' => hri_schema_org_id() ),
		'educationalLevel' => $levels ? implode( ', ', array_map( 'hri_schema_text', $levels ) ) : null,
		'about'            => $topics ? array_map( 'hri_schema_text', $topics ) : null,
		'audience'         => ! empty( $meta['hri_audience'] ) ? array(
			'@type'        => 'Audience',
			'audienceType' => hri_schema_text( (string) $meta['hri_audience'] ),
		) : null,
		'timeRequired'     => $days ? 'P' . $days . 'D' : null,
		'instructor'       => $trainers ? $trainers : null,
		'educationalCredentialAwarded' => ! empty( $meta['hri_certificate'] ) ? __( 'ใบประกาศนียบัตร', 'hri-core' ) : null,
	);

	// The one current instance (the site keeps only the latest batch of a Public course).
	$instance = array(
		'@type'          => 'CourseInstance',
		'courseMode'     => 1 === count( $modes ) ? $modes[0] : $modes,
		'courseWorkload' => $days ? 'P' . $days . 'D' : null,
		'instructor'     => $trainers ? $trainers : null,
		'inLanguage'     => 'th', // Training is held in Thai; EN/JA pages describe the same class.
	);
	if ( 'public' === $program ) {
		if ( ! empty( $meta['hri_batch_no'] ) ) {
			/* translators: 1: course name, 2: batch number */
			$instance['name'] = sprintf( __( '%1$s รุ่นที่ %2$d', 'hri-core' ), $course['name'], (int) $meta['hri_batch_no'] );
		}
		if ( ! empty( $meta['hri_start_date'] ) ) {
			$instance['startDate'] = (string) $meta['hri_start_date'];
		}
		if ( ! empty( $meta['hri_end_date'] ) ) {
			$instance['endDate'] = (string) $meta['hri_end_date'];
		} elseif ( ! empty( $meta['hri_start_date'] ) && $days ) {
			$instance['endDate'] = wp_date( 'Y-m-d', strtotime( $meta['hri_start_date'] . ' +' . ( $days - 1 ) . ' days' ) );
		}
		if ( ! empty( $meta['hri_location'] ) ) {
			$instance['location'] = in_array( 'Online', $modes, true ) && 1 === count( $modes )
				? array( '@type' => 'VirtualLocation', 'name' => hri_schema_text( (string) $meta['hri_location'] ) )
				: array(
					'@type'   => 'Place',
					'name'    => hri_schema_text( (string) $meta['hri_location'] ),
					'address' => array(
						'@type'          => 'PostalAddress',
						'name'           => hri_schema_text( (string) $meta['hri_location'] ),
						'addressCountry' => 'TH',
					),
				);
		}
		if ( ! empty( $meta['hri_start_date'] ) ) {
			$instance['eventStatus'] = 'https://schema.org/EventScheduled';
		}
	}
	$course['hasCourseInstance'] = array( array_filter( $instance, static fn( $v ) => '' !== $v && null !== $v ) );
	$course['offers']            = hri_schema_course_offers( $post_id, $meta, $program, $url );

	return array_filter( $course, static fn( $v ) => '' !== $v && null !== $v && array() !== $v );
}

/**
 * Offers of a course: Public = regular price (+ Early Bird until its deadline); In-house = quotation.
 *
 * @param array<string,mixed> $meta Course meta.
 * @return array<int,array<string,mixed>>
 */
function hri_schema_course_offers( int $post_id, array $meta, string $program, string $url ): array {
	$status       = (string) ( $meta['hri_reg_status'] ?? '' );
	$availability = array(
		'open'   => 'https://schema.org/InStock',
		'full'   => 'https://schema.org/SoldOut',
		'closed' => 'https://schema.org/Discontinued',
	)[ $status ] ?? null;
	$register     = (string) ( $meta['hri_register_url'] ?? '' );
	$offer_url    = 'public' === $program && 'open' === $status && $register ? $register : $url;
	$base         = array(
		'@type'         => 'Offer',
		'category'      => 'Paid',
		'url'           => $offer_url,
		'priceCurrency' => 'THB',
		'availability'  => $availability,
		'offeredBy'     => array( '@id' => hri_schema_org_id() ),
	);

	$offers = array();
	$price  = hri_schema_price( (string) ( $meta['hri_price'] ?? '' ) );
	$early  = hri_schema_price( (string) ( $meta['hri_price_early'] ?? '' ) );
	$until  = (string) ( $meta['hri_early_deadline'] ?? '' );
	$today  = wp_date( 'Y-m-d' );

	if ( $early && ( '' === $until || $until >= $today ) ) {
		$offers[] = array_filter(
			$base + array(
				'name'            => 'Early Bird',
				'price'           => $early,
				'priceValidUntil' => $until ? $until : null,
			)
		);
	}
	if ( $price ) {
		$regular = array( 'price' => $price );
		if ( $offers && $until ) {
			$regular['validFrom'] = wp_date( 'Y-m-d', strtotime( $until . ' +1 day' ) );
		}
		if ( ! empty( $meta['hri_end_date'] ) || ! empty( $meta['hri_start_date'] ) ) {
			$regular['priceValidUntil'] = (string) ( $meta['hri_end_date'] ?? $meta['hri_start_date'] );
		}
		$offers[] = array_filter( $base + $regular );
	}
	if ( ! $offers ) {
		// No public price (In-house: quotation). category is what Google needs; price is optional.
		$offers[] = array_filter(
			$base + array(
				'description' => 'inhouse' === $program ? __( 'ราคาตามใบเสนอราคา (จัดอบรมภายในองค์กร)', 'hri-core' ) : null,
			)
		);
		unset( $offers[0]['priceCurrency'] );
	}
	return $offers;
}

/**
 * Questions and answers of the FAQ Details blocks (class hri-faq__item) in the post's content.
 *
 * @return array<int,array{0:string,1:string}>
 */
function hri_schema_faq_items( int $post_id ): array {
	$items = array();
	$walk  = static function ( array $blocks ) use ( &$walk, &$items ): void {
		foreach ( $blocks as $block ) {
			if ( 'core/details' === ( $block['blockName'] ?? '' ) && str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'hri-faq__item' ) ) {
				$question = hri_schema_text( (string) ( $block['attrs']['summary'] ?? '' ) );
				if ( '' === $question && preg_match( '#<summary[^>]*>(.*?)</summary>#s', (string) $block['innerHTML'], $m ) ) {
					$question = hri_schema_text( $m[1] );
				}
				$answer = '';
				foreach ( $block['innerBlocks'] as $inner ) {
					$answer .= ' ' . render_block( $inner );
				}
				$answer = hri_schema_text( $answer );
				if ( '' !== $question && '' !== $answer ) {
					$items[] = array( $question, $answer );
				}
				continue;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$walk( $block['innerBlocks'] );
			}
		}
	};
	$walk( parse_blocks( (string) get_post_field( 'post_content', $post_id ) ) );
	return $items;
}

/**
 * FAQPage node, or null.
 *
 * @return array<string,mixed>|null
 */
function hri_schema_faq( int $post_id, string $url ): ?array {
	$items = hri_schema_faq_items( $post_id );
	if ( ! $items ) {
		return null;
	}
	return array(
		'@type'      => 'FAQPage',
		'@id'        => $url . '#faq',
		'url'        => $url,
		'name'       => hri_schema_current_title(),
		'inLanguage' => hri_schema_lang(),
		'isPartOf'   => array( '@id' => $url . '#webpage' ),
		'mainEntity' => array_map(
			static fn( $item ) => array(
				'@type'          => 'Question',
				'name'           => $item[0],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $item[1],
				),
			),
			$items
		),
	);
}

/**
 * The page's graph.
 *
 * @return array<int,array<string,mixed>>
 */
function hri_schema_graph(): array {
	$graph = array( hri_schema_organization(), hri_schema_website() );
	if ( is_404() ) {
		return $graph;
	}
	$url = hri_schema_current_url();
	if ( '' === $url ) {
		return $graph;
	}
	if ( is_singular() && hri_seo_is_noindex( get_queried_object_id() ) ) {
		return $graph;
	}

	$graph[] = hri_schema_webpage( $url );
	$graph[] = hri_schema_breadcrumb( $url );

	if ( is_singular( 'post' ) ) {
		$graph[] = hri_schema_blogposting( get_queried_object_id(), $url );
	} elseif ( is_singular( 'course' ) ) {
		$graph[] = hri_schema_course( get_queried_object_id(), $url );
	}
	if ( is_singular() ) {
		$graph[] = hri_schema_faq( get_queried_object_id(), $url );
	}
	return $graph;
}
