<?php

add_filter('if_menu_conditions', 'ifMenuAdvancedConditions');

function ifMenuAdvancedConditions(array $conditions) {
	$activePlugins = apply_filters('active_plugins', get_option('active_plugins'));


	// User location
	$conditions[] = array(
		'id'		=>	'user-location',
		'type'		=>	'multiple',
		'name'		=>	__('From country', 'if-menu'),
		'options'	=>	array(
			'AF'	=>	'Afghanistan',
			'AX'	=>	'&#197;land Islands',
			'AL'	=>	'Albania',
			'DZ'	=>	'Algeria',
			'AS'	=>	'American Samoa',
			'AD'	=>	'Andorra',
			'AO'	=>	'Angola',
			'AI'	=>	'Anguilla',
			'AQ'	=>	'Antarctica',
			'AG'	=>	'Antigua and Barbuda',
			'AR'	=>	'Argentina',
			'AM'	=>	'Armenia',
			'AW'	=>	'Aruba',
			'AU'	=>	'Australia',
			'AT'	=>	'Austria',
			'AZ'	=>	'Azerbaijan',
			'BS'	=>	'Bahamas',
			'BH'	=>	'Bahrain',
			'BD'	=>	'Bangladesh',
			'BB'	=>	'Barbados',
			'BY'	=>	'Belarus',
			'BE'	=>	'Belgium',
			'PW'	=>	'Belau',
			'BZ'	=>	'Belize',
			'BJ'	=>	'Benin',
			'BM'	=>	'Bermuda',
			'BT'	=>	'Bhutan',
			'BO'	=>	'Bolivia',
			'BQ'	=>	'Bonaire, Saint Eustatius and Saba',
			'BA'	=>	'Bosnia and Herzegovina',
			'BW'	=>	'Botswana',
			'BV'	=>	'Bouvet Island',
			'BR'	=>	'Brazil',
			'IO'	=>	'British Indian Ocean Territory',
			'VG'	=>	'British Virgin Islands',
			'BN'	=>	'Brunei',
			'BG'	=>	'Bulgaria',
			'BF'	=>	'Burkina Faso',
			'BI'	=>	'Burundi',
			'KH'	=>	'Cambodia',
			'CM'	=>	'Cameroon',
			'CA'	=>	'Canada',
			'CV'	=>	'Cape Verde',
			'KY'	=>	'Cayman Islands',
			'CF'	=>	'Central African Republic',
			'TD'	=>	'Chad',
			'CL'	=>	'Chile',
			'CN'	=>	'China',
			'CX'	=>	'Christmas Island',
			'CC'	=>	'Cocos (Keeling) Islands',
			'CO'	=>	'Colombia',
			'KM'	=>	'Comoros',
			'CG'	=>	'Congo (Brazzaville)',
			'CD'	=>	'Congo (Kinshasa)',
			'CK'	=>	'Cook Islands',
			'CR'	=>	'Costa Rica',
			'HR'	=>	'Croatia',
			'CU'	=>	'Cuba',
			'CW'	=>	'Cura&ccedil;ao',
			'CY'	=>	'Cyprus',
			'CZ'	=>	'Czech Republic',
			'DK'	=>	'Denmark',
			'DJ'	=>	'Djibouti',
			'DM'	=>	'Dominica',
			'DO'	=>	'Dominican Republic',
			'EC'	=>	'Ecuador',
			'EG'	=>	'Egypt',
			'SV'	=>	'El Salvador',
			'GQ'	=>	'Equatorial Guinea',
			'ER'	=>	'Eritrea',
			'EE'	=>	'Estonia',
			'ET'	=>	'Ethiopia',
			'FK'	=>	'Falkland Islands',
			'FO'	=>	'Faroe Islands',
			'FJ'	=>	'Fiji',
			'FI'	=>	'Finland',
			'FR'	=>	'France',
			'GF'	=>	'French Guiana',
			'PF'	=>	'French Polynesia',
			'TF'	=>	'French Southern Territories',
			'GA'	=>	'Gabon',
			'GM'	=>	'Gambia',
			'GE'	=>	'Georgia',
			'DE'	=>	'Germany',
			'GH'	=>	'Ghana',
			'GI'	=>	'Gibraltar',
			'GR'	=>	'Greece',
			'GL'	=>	'Greenland',
			'GD'	=>	'Grenada',
			'GP'	=>	'Guadeloupe',
			'GU'	=>	'Guam',
			'GT'	=>	'Guatemala',
			'GG'	=>	'Guernsey',
			'GN'	=>	'Guinea',
			'GW'	=>	'Guinea-Bissau',
			'GY'	=>	'Guyana',
			'HT'	=>	'Haiti',
			'HM'	=>	'Heard Island and McDonald Islands',
			'HN'	=>	'Honduras',
			'HK'	=>	'Hong Kong',
			'HU'	=>	'Hungary',
			'IS'	=>	'Iceland',
			'IN'	=>	'India',
			'ID'	=>	'Indonesia',
			'IR'	=>	'Iran',
			'IQ'	=>	'Iraq',
			'IE'	=>	'Ireland',
			'IM'	=>	'Isle of Man',
			'IL'	=>	'Israel',
			'IT'	=>	'Italy',
			'CI'	=>	'Ivory Coast',
			'JM'	=>	'Jamaica',
			'JP'	=>	'Japan',
			'JE'	=>	'Jersey',
			'JO'	=>	'Jordan',
			'KZ'	=>	'Kazakhstan',
			'KE'	=>	'Kenya',
			'KI'	=>	'Kiribati',
			'KW'	=>	'Kuwait',
			'KG'	=>	'Kyrgyzstan',
			'LA'	=>	'Laos',
			'LV'	=>	'Latvia',
			'LB'	=>	'Lebanon',
			'LS'	=>	'Lesotho',
			'LR'	=>	'Liberia',
			'LY'	=>	'Libya',
			'LI'	=>	'Liechtenstein',
			'LT'	=>	'Lithuania',
			'LU'	=>	'Luxembourg',
			'MO'	=>	'Macao S.A.R., China',
			'MK'	=>	'Macedonia',
			'MG'	=>	'Madagascar',
			'MW'	=>	'Malawi',
			'MY'	=>	'Malaysia',
			'MV'	=>	'Maldives',
			'ML'	=>	'Mali',
			'MT'	=>	'Malta',
			'MH'	=>	'Marshall Islands',
			'MQ'	=>	'Martinique',
			'MR'	=>	'Mauritania',
			'MU'	=>	'Mauritius',
			'YT'	=>	'Mayotte',
			'MX'	=>	'Mexico',
			'FM'	=>	'Micronesia',
			'MD'	=>	'Moldova',
			'MC'	=>	'Monaco',
			'MN'	=>	'Mongolia',
			'ME'	=>	'Montenegro',
			'MS'	=>	'Montserrat',
			'MA'	=>	'Morocco',
			'MZ'	=>	'Mozambique',
			'MM'	=>	'Myanmar',
			'NA'	=>	'Namibia',
			'NR'	=>	'Nauru',
			'NP'	=>	'Nepal',
			'NL'	=>	'Netherlands',
			'NC'	=>	'New Caledonia',
			'NZ'	=>	'New Zealand',
			'NI'	=>	'Nicaragua',
			'NE'	=>	'Niger',
			'NG'	=>	'Nigeria',
			'NU'	=>	'Niue',
			'NF'	=>	'Norfolk Island',
			'MP'	=>	'Northern Mariana Islands',
			'KP'	=>	'North Korea',
			'NO'	=>	'Norway',
			'OM'	=>	'Oman',
			'PK'	=>	'Pakistan',
			'PS'	=>	'Palestinian Territory',
			'PA'	=>	'Panama',
			'PG'	=>	'Papua New Guinea',
			'PY'	=>	'Paraguay',
			'PE'	=>	'Peru',
			'PH'	=>	'Philippines',
			'PN'	=>	'Pitcairn',
			'PL'	=>	'Poland',
			'PT'	=>	'Portugal',
			'PR'	=>	'Puerto Rico',
			'QA'	=>	'Qatar',
			'RE'	=>	'Reunion',
			'RO'	=>	'Romania',
			'RU'	=>	'Russia',
			'RW'	=>	'Rwanda',
			'BL'	=>	'Saint Barth&eacute;lemy',
			'SH'	=>	'Saint Helena',
			'KN'	=>	'Saint Kitts and Nevis',
			'LC'	=>	'Saint Lucia',
			'MF'	=>	'Saint Martin (French part)',
			'SX'	=>	'Saint Martin (Dutch part)',
			'PM'	=>	'Saint Pierre and Miquelon',
			'VC'	=>	'Saint Vincent and the Grenadines',
			'SM'	=>	'San Marino',
			'ST'	=>	'S&atilde;o Tom&eacute; and Pr&iacute;ncipe',
			'SA'	=>	'Saudi Arabia',
			'SN'	=>	'Senegal',
			'RS'	=>	'Serbia',
			'SC'	=>	'Seychelles',
			'SL'	=>	'Sierra Leone',
			'SG'	=>	'Singapore',
			'SK'	=>	'Slovakia',
			'SI'	=>	'Slovenia',
			'SB'	=>	'Solomon Islands',
			'SO'	=>	'Somalia',
			'ZA'	=>	'South Africa',
			'GS'	=>	'South Georgia/Sandwich Islands',
			'KR'	=>	'South Korea',
			'SS'	=>	'South Sudan',
			'ES'	=>	'Spain',
			'LK'	=>	'Sri Lanka',
			'SD'	=>	'Sudan',
			'SR'	=>	'Suriname',
			'SJ'	=>	'Svalbard and Jan Mayen',
			'SZ'	=>	'Swaziland',
			'SE'	=>	'Sweden',
			'CH'	=>	'Switzerland',
			'SY'	=>	'Syria',
			'TW'	=>	'Taiwan',
			'TJ'	=>	'Tajikistan',
			'TZ'	=>	'Tanzania',
			'TH'	=>	'Thailand',
			'TL'	=>	'Timor-Leste',
			'TG'	=>	'Togo',
			'TK'	=>	'Tokelau',
			'TO'	=>	'Tonga',
			'TT'	=>	'Trinidad and Tobago',
			'TN'	=>	'Tunisia',
			'TR'	=>	'Turkey',
			'TM'	=>	'Turkmenistan',
			'TC'	=>	'Turks and Caicos Islands',
			'TV'	=>	'Tuvalu',
			'UG'	=>	'Uganda',
			'UA'	=>	'Ukraine',
			'AE'	=>	'United Arab Emirates',
			'GB'	=>	'United Kingdom (UK)',
			'US'	=>	'United States (US)',
			'UM'	=>	'United States (US) Minor Outlying Islands',
			'VI'	=>	'United States (US) Virgin Islands',
			'UY'	=>	'Uruguay',
			'UZ'	=>	'Uzbekistan',
			'VU'	=>	'Vanuatu',
			'VA'	=>	'Vatican',
			'VE'	=>	'Venezuela',
			'VN'	=>	'Vietnam',
			'WF'	=>	'Wallis and Futuna',
			'EH'	=>	'Western Sahara',
			'WS'	=>	'Samoa',
			'YE'	=>	'Yemen',
			'ZM'	=>	'Zambia',
			'ZW'	=>	'Zimbabwe'
		),
		'condition'	=>	function($item, $selectedOptions = array()) {
			return in_array(get_user_country_code(), $selectedOptions);
		},
		'group'		=>	__('User', 'if-menu')
	);


	// Third-party plugin integration - Groups
	if (in_array('groups/groups.php', $activePlugins) && class_exists('Groups_Group')) {
		$groupOptions = array();
		foreach (Groups_Group::get_groups() as $group) {
			$groupOptions[$group->group_id] = $group->name;
		}

		$conditions[] = array(
			'id'		=>	'user-in-group',
			'type'		=>	'multiple',
			'name'		=>	__('Is in group', 'if-menu'),
			'condition'	=>	function($item, $selectedGroups = array()) {
				$isInGroup = false;
				$groupsUser = new Groups_User(get_current_user_id());
				foreach ($selectedGroups as $groupId) {
					if ($groupsUser->is_member($groupId)) {
						$isInGroup = true;
					}
				}
				return $isInGroup;
			},
			'options'	=>	$groupOptions,
			'group'		=>	__('User', 'if-menu')
		);
	}


	// Third-party plugin integration - WooCommerce Subscriptions
	// Third-party plugin integration - Listing Payments (for WP Job Manager)
	if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', $activePlugins)) {
		$subscriptionsOptions = array();

		$subscriptions = get_posts(array(
			'numberposts'	=>	-1,
			'post_type'		=>	array('product', 'product-variation'),
			'post_status'	=>	'publish',
			'tax_query'		=>	array(array(
				'taxonomy'		=>	'product_type',
				'field'			=>	'slug',
				'terms'			=>	array('subscription', 'variable-subscription', 'job_package_subscription', 'resume_package_subscription')
			))
		));

		foreach ($subscriptions as $subscription) {
			$subscriptionsOptions[$subscription->ID] = $subscription->post_title;
		}

		$conditions[] = array(
			'id'		=>	'woocommerce-subscriptions',
			'type'		=>	'multiple',
			'name'		=>	__('Has active subscription', 'if-menu'),
			'condition'	=>	function($item, $selectedSubscriptions = array()) {
				$hasSubscription = false;

				if (is_array($selectedSubscriptions) && count($selectedSubscriptions)) {
					foreach ($selectedSubscriptions as $subscriptionId) {
						if (wcs_user_has_subscription(0, $subscriptionId, 'active')) {
							$hasSubscription = true;
						}
					}
				} elseif (wcs_user_has_subscription(0, '', 'active')) {
					$hasSubscription = true;
				}

				return $hasSubscription;
			},
			'options'	=>	$subscriptionsOptions,
			'group'		=>	__('User', 'if-menu')
		);
	}


	// Third-party plugin integration - WishList Member
	if (function_exists('wlmapi_the_levels')) {
		$membershipLevelOptions = array();
		$wishlistMembershipLevels = wlmapi_the_levels();

		foreach ($wishlistMembershipLevels['levels']['level'] as $level) {
			$membershipLevelOptions[$level['id']] = $level['name'];
		}

		$conditions[] = array(
			'id'		=>	'wishlist-member',
			'type'		=>	'multiple',
			'name'		=>	__('WishList Membership Level', 'if-menu'),
			'condition'	=>	function($item, $membershipLevels = array()) {
				$hasAccess = false;
				$userId = get_current_user_id();

				foreach ($membershipLevels as $level) {
					if (wlmapi_is_user_a_member($level, $userId)) {
						$hasAccess = true;
					}
				}

				return $hasAccess;
			},
			'group'		=>	__('User', 'if-menu'),
			'options'	=>	$membershipLevelOptions
		);
	}


	// Third-party plugin integration - WooCommerce Memberships
	if (in_array('woocommerce-memberships/woocommerce-memberships.php', $activePlugins)) {
		$membershipsOptions = array();
		$plans = wc_memberships_get_membership_plans();

		foreach ($plans as $plan) {
			$membershipsOptions[$plan->id] = $plan->name;
		}

		$conditions[] = array(
			'id'		=>	'woocommerce-memberships',
			'type'		=>	'multiple',
			'name'		=>	__('Has active membership plan', 'if-menu'),
			'condition'	=>	function($item, $selectedPlans = array()) {
				$hasPlan = false;

				if (!is_user_logged_in()) {
					return false;
				}

				if (is_array($selectedPlans) && count($selectedPlans)) {
					// check if current user has one of selected memberships
					foreach ($selectedPlans as $planId) {
						if (wc_memberships_is_user_active_member(null, $planId)) {
							$hasPlan = true;
						}
					}
				} elseif (wc_memberships_get_user_memberships(null, ['status' => 'active'])) {
					// check if current user has any active memberships
					$hasPlan = true;
				}

				return $hasPlan;
			},
			'options'	=>	$membershipsOptions,
			'group'		=>	__('User', 'if-menu')
		);
	}


	// Third-party plugin integration - Restrict Content Pro
	if (in_array('restrict-content-pro/restrict-content-pro.php', $activePlugins)) {
		$conditions[] = array(
			'id'		=>	'restrict-content-pro-active',
			'name'		=>	__('Any RCP membership active', 'if-menu'),
			'condition'	=>	'rcp_user_has_active_membership',
			'group'		=>	__('User', 'if-menu')
		);

		$levelsOptions = array();
		$levels = rcp_get_membership_levels();

		if ($levels) {
			foreach ($levels as $level) {
				$levelsOptions[$level->get_id()] = $level->get_name() . ' - Level ' . $level->get_access_level();
			}
		}

		$conditions[] = array(
			'id'		=>	'restrict-content-pro',
			'type'		=>	'multiple',
			'name'		=>	__('Has Restrict Membership', 'if-menu'),
			'condition'	=>	function($item, $selectedLevels = array()) {
				$userId = get_current_user_id();

				if (!$userId) {
					return false;
				}

				return in_array(rcp_get_customer_membership_level_ids($userId), $selectedLevels);
			},
			'options'	=>	$levelsOptions,
			'group'		=>	__('User', 'if-menu')
		);

		$conditions[] = array(
			'id'		=>	'restrict-content-pro-expired',
			'name'		=>	__('Expired Restrict Membership', 'if-menu'),
			'condition'	=>	'rcp_user_has_expired_membership',
			'group'		=>	__('User', 'if-menu')
		);
	}


	// Available languages
	$availableLanguages = [
		'en_US'		=>	'English (US)'
	];
	$langCodes = array(
		'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'bm' => 'Bambara', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali',
		'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese', 'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree',
		'cs' => 'Czech', 'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe', 'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Western Frisian', 'ff' => 'Fulah', 'ka' => 'Georgian', 'de' => 'German', 'gd' => 'Gaelic; Scottish Gaelic',
		'ga' => 'Irish', 'gl' => 'Galician', 'gv' => 'Manx', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian', 'ig' => 'Igbo', 'is' => 'Icelandic', 'io' => 'Ido', 'ii' => 'Sichuan Yi', 'iu' => 'Inuktitut', 'ie' => 'Interlingue',
		'ia' => 'Interlingua (International Auxiliary Language Association)', 'id' => 'Indonesian', 'ik' => 'Inupiaq', 'it' => 'Italian', 'jv' => 'Javanese', 'ja' => 'Japanese', 'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'ks' => 'Kashmiri', 'kr' => 'Kanuri', 'kk' => 'Kazakh', 'km' => 'Central Khmer', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz',
		'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish; Letzeburgesch', 'lu' => 'Luba-Katanga', 'lg' => 'Ganda', 'mk' => 'Macedonian', 'mh' => 'Marshallese', 'ml' => 'Malayalam',
		'mi' => 'Maori', 'mr' => 'Marathi', 'ms' => 'Malay', 'mg' => 'Malagasy', 'mt' => 'Maltese', 'mo' => 'Moldavian', 'mn' => 'Mongolian', 'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
		'no' => 'Norwegian', 'ny' => 'Chichewa; Chewa; Nyanja', 'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pi' => 'Pali', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto', 'qu' => 'Quechua', 'rm' => 'Romansh', 'ro' => 'Romanian', 'rn' => 'Rundi', 'ru' => 'Russian',
		'sg' => 'Sango', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'hr' => 'Croatian', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'se' => 'Northern Sami', 'sm' => 'Samoan', 'sn' => 'Shona', 'sd' => 'Sindhi', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'sc' => 'Sardinian', 'ss' => 'Swati', 'su' => 'Sundanese', 'sw' => 'Swahili',
		'sv' => 'Swedish', 'ty' => 'Tahitian', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'tg' => 'Tajik', 'tl' => 'Tagalog', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'tn' => 'Tswana', 'ts' => 'Tsonga', 'tk' => 'Turkmen', 'tr' => 'Turkish', 'tw' => 'Twi', 'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
		've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük', 'cy' => 'Welsh','wa' => 'Walloon','wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'za' => 'Zhuang; Chuang', 'zu' => 'Zulu' );

	foreach (get_available_languages() as $languageCode) {
		$codes = explode('_', $languageCode);
		$languageName = strtr($codes[0], $langCodes);

		if (isset($codes[1])) {
			$languageName .= ' (' . $codes[1] . ')';
		}

		$availableLanguages[$languageCode] = $languageName;
	}

	$conditions[] = array(
		'id'		=>	'languages',
		'type'		=>	'multiple',
		'name'		=>	__('Language', 'if-menu'),
		'options'	=>	$availableLanguages,
		'condition'	=>	function($item, $selectedLanguages = array()) {
			return in_array(get_locale(), $selectedLanguages);
		},
		'group'		=>	__('Language', 'if-menu')
	);


	return $conditions;
}
