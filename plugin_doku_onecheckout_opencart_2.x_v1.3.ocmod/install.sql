-- DOKU Onecheckout Transaction Table
--
-- Database: `opencart`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_doku_onecheckout`
--

DROP TABLE IF EXISTS `oc_dokuonecheckout`;
CREATE TABLE `oc_dokuonecheckout` (

		trx_id int( 11 ) NOT NULL AUTO_INCREMENT,
		ip_address VARCHAR( 16 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		process_type VARCHAR( 15 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		process_datetime DATETIME NULL, 
		doku_payment_datetime DATETIME NULL,   
		transidmerchant VARCHAR(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		amount DECIMAL( 20,2 ) NOT NULL DEFAULT '0',
		notify_type VARCHAR( 1 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		response_code VARCHAR( 4 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		status_code VARCHAR( 4 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		result_msg VARCHAR( 20 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		reversal INT( 1 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
		approval_code CHAR( 20 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		payment_channel VARCHAR( 2 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		payment_code VARCHAR( 20 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		bank_issuer VARCHAR( 100 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		creditcard VARCHAR( 16 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		words VARCHAR( 200 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',  
		session_id VARCHAR( 48 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		verify_id VARCHAR( 30 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		verify_score INT( 3 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
		verify_status VARCHAR( 10 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		check_status INT( 1 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
		count_check_status INT( 1 ) COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
		message TEXT COLLATE utf8_unicode_ci,  
		PRIMARY KEY (trx_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;