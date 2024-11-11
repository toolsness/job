<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {

        $countries = array(
			array('code' => 'US', 'country_name' => 'United States'),
			array('code' => 'CA', 'country_name' => 'Canada'),
			array('code' => 'AF', 'country_name' => 'Afghanistan'),
			array('code' => 'AL', 'country_name' => 'Albania'),
			array('code' => 'DZ', 'country_name' => 'Algeria'),
			array('code' => 'AS', 'country_name' => 'American Samoa'),
			array('code' => 'AD', 'country_name' => 'Andorra'),
			array('code' => 'AO', 'country_name' => 'Angola'),
			array('code' => 'AI', 'country_name' => 'Anguilla'),
			array('code' => 'AQ', 'country_name' => 'Antarctica'),
			array('code' => 'AG', 'country_name' => 'Antigua and/or Barbuda'),
			array('code' => 'AR', 'country_name' => 'Argentina'),
			array('code' => 'AM', 'country_name' => 'Armenia'),
			array('code' => 'AW', 'country_name' => 'Aruba'),
			array('code' => 'AU', 'country_name' => 'Australia'),
			array('code' => 'AT', 'country_name' => 'Austria'),
			array('code' => 'AZ', 'country_name' => 'Azerbaijan'),
			array('code' => 'BS', 'country_name' => 'Bahamas'),
			array('code' => 'BH', 'country_name' => 'Bahrain'),
			array('code' => 'BD', 'country_name' => 'Bangladesh'),
			array('code' => 'BB', 'country_name' => 'Barbados'),
			array('code' => 'BY', 'country_name' => 'Belarus'),
			array('code' => 'BE', 'country_name' => 'Belgium'),
			array('code' => 'BZ', 'country_name' => 'Belize'),
			array('code' => 'BJ', 'country_name' => 'Benin'),
			array('code' => 'BM', 'country_name' => 'Bermuda'),
			array('code' => 'BT', 'country_name' => 'Bhutan'),
			array('code' => 'BO', 'country_name' => 'Bolivia'),
			array('code' => 'BA', 'country_name' => 'Bosnia and Herzegovina'),
			array('code' => 'BW', 'country_name' => 'Botswana'),
			array('code' => 'BV', 'country_name' => 'Bouvet Island'),
			array('code' => 'BR', 'country_name' => 'Brazil'),
			array('code' => 'IO', 'country_name' => 'British lndian Ocean Territory'),
			array('code' => 'BN', 'country_name' => 'Brunei Darussalam'),
			array('code' => 'BG', 'country_name' => 'Bulgaria'),
			array('code' => 'BF', 'country_name' => 'Burkina Faso'),
			array('code' => 'BI', 'country_name' => 'Burundi'),
			array('code' => 'KH', 'country_name' => 'Cambodia'),
			array('code' => 'CM', 'country_name' => 'Cameroon'),
			array('code' => 'CV', 'country_name' => 'Cape Verde'),
			array('code' => 'KY', 'country_name' => 'Cayman Islands'),
			array('code' => 'CF', 'country_name' => 'Central African Republic'),
			array('code' => 'TD', 'country_name' => 'Chad'),
			array('code' => 'CL', 'country_name' => 'Chile'),
			array('code' => 'CN', 'country_name' => 'China'),
			array('code' => 'CX', 'country_name' => 'Christmas Island'),
			array('code' => 'CC', 'country_name' => 'Cocos (Keeling) Islands'),
			array('code' => 'CO', 'country_name' => 'Colombia'),
			array('code' => 'KM', 'country_name' => 'Comoros'),
			array('code' => 'CG', 'country_name' => 'Congo'),
			array('code' => 'CK', 'country_name' => 'Cook Islands'),
			array('code' => 'CR', 'country_name' => 'Costa Rica'),
			array('code' => 'HR', 'country_name' => 'Croatia (Hrvatska)'),
			array('code' => 'CU', 'country_name' => 'Cuba'),
			array('code' => 'CY', 'country_name' => 'Cyprus'),
			array('code' => 'CZ', 'country_name' => 'Czech Republic'),
			array('code' => 'CD', 'country_name' => 'Democratic Republic of Congo'),
			array('code' => 'DK', 'country_name' => 'Denmark'),
			array('code' => 'DJ', 'country_name' => 'Djibouti'),
			array('code' => 'DM', 'country_name' => 'Dominica'),
			array('code' => 'DO', 'country_name' => 'Dominican Republic'),
			array('code' => 'TP', 'country_name' => 'East Timor'),
			array('code' => 'EC', 'country_name' => 'Ecudaor'),
			array('code' => 'EG', 'country_name' => 'Egypt'),
			array('code' => 'SV', 'country_name' => 'El Salvador'),
			array('code' => 'GQ', 'country_name' => 'Equatorial Guinea'),
			array('code' => 'ER', 'country_name' => 'Eritrea'),
			array('code' => 'EE', 'country_name' => 'Estonia'),
			array('code' => 'ET', 'country_name' => 'Ethiopia'),
			array('code' => 'FK', 'country_name' => 'Falkland Islands (Malvinas)'),
			array('code' => 'FO', 'country_name' => 'Faroe Islands'),
			array('code' => 'FJ', 'country_name' => 'Fiji'),
			array('code' => 'FI', 'country_name' => 'Finland'),
			array('code' => 'FR', 'country_name' => 'France'),
			array('code' => 'FX', 'country_name' => 'France, Metropolitan'),
			array('code' => 'GF', 'country_name' => 'French Guiana'),
			array('code' => 'PF', 'country_name' => 'French Polynesia'),
			array('code' => 'TF', 'country_name' => 'French Southern Territories'),
			array('code' => 'GA', 'country_name' => 'Gabon'),
			array('code' => 'GM', 'country_name' => 'Gambia'),
			array('code' => 'GE', 'country_name' => 'Georgia'),
			array('code' => 'DE', 'country_name' => 'Germany'),
			array('code' => 'GH', 'country_name' => 'Ghana'),
			array('code' => 'GI', 'country_name' => 'Gibraltar'),
			array('code' => 'GR', 'country_name' => 'Greece'),
			array('code' => 'GL', 'country_name' => 'Greenland'),
			array('code' => 'GD', 'country_name' => 'Grenada'),
			array('code' => 'GP', 'country_name' => 'Guadeloupe'),
			array('code' => 'GU', 'country_name' => 'Guam'),
			array('code' => 'GT', 'country_name' => 'Guatemala'),
			array('code' => 'GN', 'country_name' => 'Guinea'),
			array('code' => 'GW', 'country_name' => 'Guinea-Bissau'),
			array('code' => 'GY', 'country_name' => 'Guyana'),
			array('code' => 'HT', 'country_name' => 'Haiti'),
			array('code' => 'HM', 'country_name' => 'Heard and Mc Donald Islands'),
			array('code' => 'HN', 'country_name' => 'Honduras'),
			array('code' => 'HK', 'country_name' => 'Hong Kong'),
			array('code' => 'HU', 'country_name' => 'Hungary'),
			array('code' => 'IS', 'country_name' => 'Iceland'),
			array('code' => 'IN', 'country_name' => 'India'),
			array('code' => 'ID', 'country_name' => 'Indonesia'),
			array('code' => 'IR', 'country_name' => 'Iran (Islamic Republic of)'),
			array('code' => 'IQ', 'country_name' => 'Iraq'),
			array('code' => 'IE', 'country_name' => 'Ireland'),
			array('code' => 'IL', 'country_name' => 'Israel'),
			array('code' => 'IT', 'country_name' => 'Italy'),
			array('code' => 'CI', 'country_name' => 'Ivory Coast'),
			array('code' => 'JM', 'country_name' => 'Jamaica'),
			array('code' => 'JP', 'country_name' => 'Japan'),
			array('code' => 'JO', 'country_name' => 'Jordan'),
			array('code' => 'KZ', 'country_name' => 'Kazakhstan'),
			array('code' => 'KE', 'country_name' => 'Kenya'),
			array('code' => 'KI', 'country_name' => 'Kiribati'),
			array('code' => 'KP', 'country_name' => 'Korea, Democratic People\'s Republic of'),
			array('code' => 'KR', 'country_name' => 'Korea, Republic of'),
			array('code' => 'KW', 'country_name' => 'Kuwait'),
			array('code' => 'KG', 'country_name' => 'Kyrgyzstan'),
			array('code' => 'LA', 'country_name' => 'Lao People\'s Democratic Republic'),
			array('code' => 'LV', 'country_name' => 'Latvia'),
			array('code' => 'LB', 'country_name' => 'Lebanon'),
			array('code' => 'LS', 'country_name' => 'Lesotho'),
			array('code' => 'LR', 'country_name' => 'Liberia'),
			array('code' => 'LY', 'country_name' => 'Libyan Arab Jamahiriya'),
			array('code' => 'LI', 'country_name' => 'Liechtenstein'),
			array('code' => 'LT', 'country_name' => 'Lithuania'),
			array('code' => 'LU', 'country_name' => 'Luxembourg'),
			array('code' => 'MO', 'country_name' => 'Macau'),
			array('code' => 'MK', 'country_name' => 'Macedonia'),
			array('code' => 'MG', 'country_name' => 'Madagascar'),
			array('code' => 'MW', 'country_name' => 'Malawi'),
			array('code' => 'MY', 'country_name' => 'Malaysia'),
			array('code' => 'MV', 'country_name' => 'Maldives'),
			array('code' => 'ML', 'country_name' => 'Mali'),
			array('code' => 'MT', 'country_name' => 'Malta'),
			array('code' => 'MH', 'country_name' => 'Marshall Islands'),
			array('code' => 'MQ', 'country_name' => 'Martinique'),
			array('code' => 'MR', 'country_name' => 'Mauritania'),
			array('code' => 'MU', 'country_name' => 'Mauritius'),
			array('code' => 'TY', 'country_name' => 'Mayotte'),
			array('code' => 'MX', 'country_name' => 'Mexico'),
			array('code' => 'FM', 'country_name' => 'Micronesia, Federated States of'),
			array('code' => 'MD', 'country_name' => 'Moldova, Republic of'),
			array('code' => 'MC', 'country_name' => 'Monaco'),
			array('code' => 'MN', 'country_name' => 'Mongolia'),
			array('code' => 'MS', 'country_name' => 'Montserrat'),
			array('code' => 'MA', 'country_name' => 'Morocco'),
			array('code' => 'MZ', 'country_name' => 'Mozambique'),
			array('code' => 'MM', 'country_name' => 'Myanmar'),
			array('code' => 'NA', 'country_name' => 'Namibia'),
			array('code' => 'NR', 'country_name' => 'Nauru'),
			array('code' => 'NP', 'country_name' => 'Nepal'),
			array('code' => 'NL', 'country_name' => 'Netherlands'),
			array('code' => 'AN', 'country_name' => 'Netherlands Antilles'),
			array('code' => 'NC', 'country_name' => 'New Caledonia'),
			array('code' => 'NZ', 'country_name' => 'New Zealand'),
			array('code' => 'NI', 'country_name' => 'Nicaragua'),
			array('code' => 'NE', 'country_name' => 'Niger'),
			array('code' => 'NG', 'country_name' => 'Nigeria'),
			array('code' => 'NU', 'country_name' => 'Niue'),
			array('code' => 'NF', 'country_name' => 'Norfork Island'),
			array('code' => 'MP', 'country_name' => 'Northern Mariana Islands'),
			array('code' => 'NO', 'country_name' => 'Norway'),
			array('code' => 'OM', 'country_name' => 'Oman'),
			array('code' => 'PK', 'country_name' => 'Pakistan'),
			array('code' => 'PW', 'country_name' => 'Palau'),
			array('code' => 'PA', 'country_name' => 'Panama'),
			array('code' => 'PG', 'country_name' => 'Papua New Guinea'),
			array('code' => 'PY', 'country_name' => 'Paraguay'),
			array('code' => 'PE', 'country_name' => 'Peru'),
			array('code' => 'PH', 'country_name' => 'Philippines'),
			array('code' => 'PN', 'country_name' => 'Pitcairn'),
			array('code' => 'PL', 'country_name' => 'Poland'),
			array('code' => 'PT', 'country_name' => 'Portugal'),
			array('code' => 'PR', 'country_name' => 'Puerto Rico'),
			array('code' => 'QA', 'country_name' => 'Qatar'),
			array('code' => 'SS', 'country_name' => 'Republic of South Sudan'),
			array('code' => 'RE', 'country_name' => 'Reunion'),
			array('code' => 'RO', 'country_name' => 'Romania'),
			array('code' => 'RU', 'country_name' => 'Russian Federation'),
			array('code' => 'RW', 'country_name' => 'Rwanda'),
			array('code' => 'KN', 'country_name' => 'Saint Kitts and Nevis'),
			array('code' => 'LC', 'country_name' => 'Saint Lucia'),
			array('code' => 'VC', 'country_name' => 'Saint Vincent and the Grenadines'),
			array('code' => 'WS', 'country_name' => 'Samoa'),
			array('code' => 'SM', 'country_name' => 'San Marino'),
			array('code' => 'ST', 'country_name' => 'Sao Tome and Principe'),
			array('code' => 'SA', 'country_name' => 'Saudi Arabia'),
			array('code' => 'SN', 'country_name' => 'Senegal'),
			array('code' => 'RS', 'country_name' => 'Serbia'),
			array('code' => 'SC', 'country_name' => 'Seychelles'),
			array('code' => 'SL', 'country_name' => 'Sierra Leone'),
			array('code' => 'SG', 'country_name' => 'Singapore'),
			array('code' => 'SK', 'country_name' => 'Slovakia'),
			array('code' => 'SI', 'country_name' => 'Slovenia'),
			array('code' => 'SB', 'country_name' => 'Solomon Islands'),
			array('code' => 'SO', 'country_name' => 'Somalia'),
			array('code' => 'ZA', 'country_name' => 'South Africa'),
			array('code' => 'GS', 'country_name' => 'South Georgia South Sandwich Islands'),
			array('code' => 'ES', 'country_name' => 'Spain'),
			array('code' => 'LK', 'country_name' => 'Sri Lanka'),
			array('code' => 'SH', 'country_name' => 'St. Helena'),
			array('code' => 'PM', 'country_name' => 'St. Pierre and Miquelon'),
			array('code' => 'SD', 'country_name' => 'Sudan'),
			array('code' => 'SR', 'country_name' => 'Suriname'),
			array('code' => 'SJ', 'country_name' => 'Svalbarn and Jan Mayen Islands'),
			array('code' => 'SZ', 'country_name' => 'Swaziland'),
			array('code' => 'SE', 'country_name' => 'Sweden'),
			array('code' => 'CH', 'country_name' => 'Switzerland'),
			array('code' => 'SY', 'country_name' => 'Syrian Arab Republic'),
			array('code' => 'TW', 'country_name' => 'Taiwan'),
			array('code' => 'TJ', 'country_name' => 'Tajikistan'),
			array('code' => 'TZ', 'country_name' => 'Tanzania, United Republic of'),
			array('code' => 'TH', 'country_name' => 'Thailand'),
			array('code' => 'TG', 'country_name' => 'Togo'),
			array('code' => 'TK', 'country_name' => 'Tokelau'),
			array('code' => 'TO', 'country_name' => 'Tonga'),
			array('code' => 'TT', 'country_name' => 'Trinidad and Tobago'),
			array('code' => 'TN', 'country_name' => 'Tunisia'),
			array('code' => 'TR', 'country_name' => 'Turkey'),
			array('code' => 'TM', 'country_name' => 'Turkmenistan'),
			array('code' => 'TC', 'country_name' => 'Turks and Caicos Islands'),
			array('code' => 'TV', 'country_name' => 'Tuvalu'),
			array('code' => 'UG', 'country_name' => 'Uganda'),
			array('code' => 'UA', 'country_name' => 'Ukraine'),
			array('code' => 'AE', 'country_name' => 'United Arab Emirates'),
			array('code' => 'GB', 'country_name' => 'United Kingdom'),
			array('code' => 'UM', 'country_name' => 'United States minor outlying islands'),
			array('code' => 'UY', 'country_name' => 'Uruguay'),
			array('code' => 'UZ', 'country_name' => 'Uzbekistan'),
			array('code' => 'VU', 'country_name' => 'Vanuatu'),
			array('code' => 'VA', 'country_name' => 'Vatican City State'),
			array('code' => 'VE', 'country_name' => 'Venezuela'),
			array('code' => 'VN', 'country_name' => 'Vietnam'),
			array('code' => 'VG', 'country_name' => 'Virgin Islands (British)'),
			array('code' => 'VI', 'country_name' => 'Virgin Islands (U.S.)'),
			array('code' => 'WF', 'country_name' => 'Wallis and Futuna Islands'),
			array('code' => 'EH', 'country_name' => 'Western Sahara'),
			array('code' => 'YE', 'country_name' => 'Yemen'),
			array('code' => 'YU', 'country_name' => 'Yugoslavia'),
			array('code' => 'ZR', 'country_name' => 'Zaire'),
			array('code' => 'ZM', 'country_name' => 'Zambia'),
			array('code' => 'ZW', 'country_name' => 'Zimbabwe'),
		);


        $timestamp = now();

        $countries = array_map(function($country) use ($timestamp) {
            return array_merge($country, [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
                'created_by' => null,
                'updated_by' => null,
            ]);
        }, $countries);

        DB::table('countries')->insert($countries);
    }
}
