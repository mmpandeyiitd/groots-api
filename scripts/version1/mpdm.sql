-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.20 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table mpdm.api_session
CREATE TABLE IF NOT EXISTS `api_session` (
  `store_front_id` int(10) unsigned NOT NULL,
  `logdate` timestamp NULL DEFAULT NULL,
  `session_id` varchar(45) DEFAULT NULL,
  KEY `IDX_API_STORE_FRONT_ID` (`store_front_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.attribute
CREATE TABLE IF NOT EXISTS `attribute` (
  `attribute_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_type` varchar(45) DEFAULT NULL,
  `attribute_code` varchar(45) DEFAULT NULL,
  `attribute_name` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `is_searchable` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attribute_id`),
  KEY `IDX_ATTRIBUTE_ID` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.attribute_option
CREATE TABLE IF NOT EXISTS `attribute_option` (
  `attribute_option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int(10) unsigned NOT NULL,
  `option_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attribute_option_id`),
  KEY `IDX_AO_ATTR_ID` (`attribute_id`),
  CONSTRAINT `FK_AO_ATTR_ID` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.base_product
CREATE TABLE IF NOT EXISTS `base_product` (
  `base_product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_base_product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '0',
  `small_description` text,
  `description` text,
  `color` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `product_weight` decimal(12,4) unsigned DEFAULT '0.0000',
  `brand` varchar(255) DEFAULT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `model_number` varchar(255) DEFAULT NULL,
  `manufacture` varchar(255) DEFAULT NULL,
  `manufacture_country` varchar(100) DEFAULT NULL,
  `manufacture_year` int(11) DEFAULT NULL,
  `specifications` text,
  `key_features` text,
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  `meta_description` varchar(150) DEFAULT NULL,
  `average_rating` int(11) NOT NULL DEFAULT '0',
  `other_website_rating` int(11) NOT NULL DEFAULT '0',
  `is_configurable` smallint(6) NOT NULL DEFAULT '0',
  `configurable_with` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `is_serial_required` tinyint(4) NOT NULL DEFAULT '0',
  `product_content_type` varchar(255) DEFAULT NULL,
  `ISBN` varchar(255) DEFAULT NULL,
  `product_shipping_charge` int(11) NOT NULL DEFAULT '0',
  `video_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`base_product_id`),
  KEY `IDX_BASE_PRODUCT_ID` (`base_product_id`),
  KEY `base_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.campaign
CREATE TABLE IF NOT EXISTS `campaign` (
  `campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(254) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `store_front_id` int(11) NOT NULL,
  `active` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.cart_value
CREATE TABLE IF NOT EXISTS `cart_value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subscribed_product_id` int(11) DEFAULT NULL,
  `store_price` decimal(10,2) DEFAULT NULL,
  `store_offer_price` decimal(10,2) NOT NULL,
  `product_qty` int(5) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.category
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `category_name` varchar(255) NOT NULL DEFAULT '0',
  `parent_category_id` int(10) unsigned NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `is_mega_category` smallint(6) NOT NULL DEFAULT '0',
  `category_shipping_charge` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  KEY `IDX_CATEGORY_ID` (`category_id`),
  KEY `category_shipping_charge` (`category_shipping_charge`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.category_shipping_charge
CREATE TABLE IF NOT EXISTS `category_shipping_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `shipping_charge` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.city_mapping
CREATE TABLE IF NOT EXISTS `city_mapping` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `city_label` varchar(80) NOT NULL,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.coupon
CREATE TABLE IF NOT EXISTS `coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(20) NOT NULL,
  `coupon_prefix` varchar(8) NOT NULL,
  `coupon_text` varchar(256) NOT NULL DEFAULT 'Coupon Offer' COMMENT 'coupon header line',
  `discount_rule_id` int(11) NOT NULL,
  `condition` enum('any','all') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_multiple_redemption` tinyint(1) NOT NULL,
  `max_redemption` int(11) NOT NULL,
  `coupons_left` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_distributed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`coupon_id`),
  UNIQUE KEY `coupon_code` (`coupon_code`),
  KEY `coupon_prefix` (`coupon_prefix`),
  KEY `is_multiple_redemption` (`is_multiple_redemption`),
  KEY `max_redemption` (`max_redemption`),
  KEY `coupons_left` (`coupons_left`),
  KEY `is_active` (`is_active`),
  KEY `is_distributed` (`is_distributed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.coupon_bak
CREATE TABLE IF NOT EXISTS `coupon_bak` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(20) NOT NULL,
  `coupon_prefix` varchar(5) NOT NULL,
  `discount_rule_id` int(11) NOT NULL,
  `condition` enum('any','all') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_multiple_redemption` tinyint(1) NOT NULL,
  `max_redemption` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `coupon_text` varchar(512) NOT NULL,
  PRIMARY KEY (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.coupon_validity_rel
CREATE TABLE IF NOT EXISTS `coupon_validity_rel` (
  `coupon_id` int(11) NOT NULL,
  `coupon_validity_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.coupon_validity_rule
CREATE TABLE IF NOT EXISTS `coupon_validity_rule` (
  `rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `condition` text NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.daily_deals
CREATE TABLE IF NOT EXISTS `daily_deals` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `deal_price` int(11) NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `bannerURL` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.dberror
CREATE TABLE IF NOT EXISTS `dberror` (
  `error_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `error_code` varchar(255) DEFAULT NULL,
  `error_message` varchar(255) DEFAULT NULL,
  `error_query` text,
  `error_time` datetime DEFAULT NULL,
  PRIMARY KEY (`error_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.device_details
CREATE TABLE IF NOT EXISTS `device_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `type` enum('gcm','apns','win') NOT NULL,
  `device_id` text NOT NULL,
  `services` text NOT NULL,
  `phone_no` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `is_deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.discount_rule
CREATE TABLE IF NOT EXISTS `discount_rule` (
  `discount_rule_id` int(11) NOT NULL AUTO_INCREMENT,
  `discount` int(11) NOT NULL,
  `type` enum('fix','percentage','item') NOT NULL,
  `cap` int(11) NOT NULL,
  `rule_details` text NOT NULL,
  PRIMARY KEY (`discount_rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.dzb_coupon_distribution
CREATE TABLE IF NOT EXISTS `dzb_coupon_distribution` (
  `dzb_coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(20) NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `is_distributed` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`dzb_coupon_id`),
  KEY `coupon_code` (`coupon_code`),
  KEY `mobile_number` (`mobile_number`),
  KEY `is_distributed` (`is_distributed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.error_codes
CREATE TABLE IF NOT EXISTS `error_codes` (
  `error_code` int(11) NOT NULL AUTO_INCREMENT,
  `error_message` varchar(255) NOT NULL,
  PRIMARY KEY (`error_code`),
  UNIQUE KEY `UNIQUE_ERROR_MESSAGE` (`error_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.final_table
CREATE TABLE IF NOT EXISTS `final_table` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `orderid` varchar(50) NOT NULL COMMENT 'orderid ',
  `subscribed_product_id` int(10) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `price` decimal(12,4) NOT NULL,
  `discount` int(4) NOT NULL,
  `coupon` varchar(50) NOT NULL,
  `checkout_price` decimal(12,4) NOT NULL,
  `storefront` varchar(255) NOT NULL,
  `store` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` int(12) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_pincode` int(12) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_discount` float NOT NULL,
  `total_payable_amount` float NOT NULL,
  `total_paid_amount` float NOT NULL,
  `payment_method` varchar(15) NOT NULL,
  `sub_total` float NOT NULL,
  `coupon_text` varchar(512) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.gcm_data
CREATE TABLE IF NOT EXISTS `gcm_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `android_id` varchar(100) NOT NULL,
  `package_name` varchar(30) NOT NULL,
  `gcm_id` varchar(512) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `android_id` (`android_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for view mpdm.getproductdetailview
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `getproductdetailview` (
	`subscribed_product_id` INT(10) UNSIGNED NOT NULL,
	`store_id` INT(10) UNSIGNED NOT NULL,
	`store_price` DECIMAL(12,4) NOT NULL,
	`store_offer_price` DECIMAL(12,4) NOT NULL,
	`weight` DECIMAL(10,4) UNSIGNED NULL,
	`length` DECIMAL(10,4) UNSIGNED NULL,
	`width` DECIMAL(10,4) UNSIGNED NULL,
	`height` DECIMAL(10,4) UNSIGNED NULL,
	`quantity` INT(11) NOT NULL,
	`subscribe_shipping_charge` INT(11) NOT NULL,
	`is_cod` TINYINT(2) NOT NULL,
	`sku` VARCHAR(128) NOT NULL COLLATE 'utf8_general_ci',
	`warranty` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`base_product_id` INT(10) UNSIGNED NOT NULL,
	`title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`size` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`product_weight` DECIMAL(12,4) UNSIGNED NULL,
	`brand` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`model_name` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`model_number` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`product_shipping_charge` INT(11) NOT NULL,
	`specifications` TEXT NULL COLLATE 'utf8_general_ci',
	`ISBN` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`store_name` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`seller_name` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`store_details` TEXT NULL COLLATE 'utf8_general_ci',
	`business_address` VARCHAR(300) NULL COLLATE 'utf8_general_ci',
	`business_address_country` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`business_address_state` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`business_address_pincode` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`store_email` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`store_contact_no` VARCHAR(100) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for table mpdm.giftproduct_campaign
CREATE TABLE IF NOT EXISTS `giftproduct_campaign` (
  `compain_id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_id` int(11) DEFAULT NULL,
  `merchant_order_id` varchar(255) DEFAULT NULL,
  `sub_order_id` int(11) DEFAULT NULL,
  `refid` int(11) DEFAULT NULL,
  `payment_method` varchar(225) DEFAULT NULL,
  `payment_status` varchar(225) DEFAULT NULL,
  `billing_name` varchar(225) DEFAULT NULL,
  `billing_telephone` varchar(20) DEFAULT NULL,
  `billing_email` varchar(225) DEFAULT NULL,
  `shipping_name` varchar(225) DEFAULT NULL,
  `shipping_telephone` varchar(20) DEFAULT NULL,
  `shipping_email` varchar(225) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_prefix` varchar(225) DEFAULT NULL,
  `coupon_code` varchar(225) DEFAULT NULL,
  `is_mail_sent` tinyint(1) NOT NULL DEFAULT '0',
  `is_sms_sent` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`compain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.greatbuyz_deals
CREATE TABLE IF NOT EXISTS `greatbuyz_deals` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `refId` int(11) NOT NULL,
  `mode` varchar(11) NOT NULL,
  `subscribed_product_id` int(10) NOT NULL,
  `deal_name` varchar(256) NOT NULL,
  `deal_description` varchar(256) NOT NULL,
  `deal_summary` varchar(256) NOT NULL,
  `last_updated` datetime NOT NULL,
  `listed` datetime NOT NULL,
  `expires` datetime NOT NULL,
  `availability` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `actual_price` int(11) NOT NULL,
  `voucher_price` int(11) NOT NULL,
  `offer_price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `redeem text` varchar(256) NOT NULL,
  `tag` varchar(256) NOT NULL,
  PRIMARY KEY (`autoId`),
  KEY `refId` (`refId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.greatbuyz_transactions
CREATE TABLE IF NOT EXISTS `greatbuyz_transactions` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `refId` int(11) NOT NULL,
  `correlationID` int(11) NOT NULL,
  `quantity_bought` int(11) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.lead
CREATE TABLE IF NOT EXISTS `lead` (
  `lead_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(100) NOT NULL,
  `seller_ids` varchar(255) NOT NULL COMMENT 'seller_ids send By GetitCRM',
  `base_product_id` int(11) NOT NULL,
  `colour` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_price` float NOT NULL,
  `total` float NOT NULL,
  `delivery_date` date NOT NULL,
  `lead_cost` int(11) NOT NULL,
  `buyer_name` varchar(100) NOT NULL,
  `buyer_phone_number` double NOT NULL,
  `buyer_pincode` int(11) NOT NULL,
  `buyer_address` text NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `balance_amount` float NOT NULL,
  `last_updated` datetime NOT NULL,
  `fullfilled_seller_id` int(11) NOT NULL,
  `status` enum('Open','Close','NA') NOT NULL,
  PRIMARY KEY (`lead_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.logging
CREATE TABLE IF NOT EXISTS `logging` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `entity` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `IDX_LOG_ID` (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.media
CREATE TABLE IF NOT EXISTS `media` (
  `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_url` varchar(255) DEFAULT NULL,
  `thumb_url` varchar(255) DEFAULT NULL,
  `media_type` varchar(45) DEFAULT NULL,
  `base_product_id` int(10) unsigned NOT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`),
  KEY `IDX_MEDIA_ID` (`media_id`),
  KEY `FK_MEDIA_BASE_PROD_ID` (`base_product_id`),
  CONSTRAINT `FK_MEDIA_BASE_PROD_ID` FOREIGN KEY (`base_product_id`) REFERENCES `base_product` (`base_product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.newsletter_sub_emailid
CREATE TABLE IF NOT EXISTS `newsletter_sub_emailid` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.now_floats
CREATE TABLE IF NOT EXISTS `now_floats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Seller_id` varchar(255) DEFAULT NULL,
  `Nowfloats_id` varchar(255) DEFAULT NULL,
  `CustomerEmail` varchar(225) DEFAULT NULL,
  `CustomerContact` bigint(20) DEFAULT NULL,
  `CustomerQuery` text,
  `CreatedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_Seller_id` (`Seller_id`),
  KEY `Nowfloats_id` (`Nowfloats_id`),
  KEY `CustomerEmail` (`CustomerEmail`),
  KEY `CustomerContact` (`CustomerContact`),
  KEY `CreatedDate` (`CreatedDate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.order
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_prefix` varchar(5) NOT NULL,
  `order_num` varchar(15) NOT NULL,
  `status` enum('pending','completed') NOT NULL,
  `payment_status` enum('pending','paid','cod','partial_paid') NOT NULL,
  `total_discount` float NOT NULL,
  `total_payable_amount` float NOT NULL,
  `total_paid_amount` float NOT NULL,
  `payment_method` enum('cod','paytm','prepay','tpsl','tbd') NOT NULL,
  `sub_total` float NOT NULL,
  `user_name` varchar(256) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_name` varchar(256) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city_id` varchar(100) NOT NULL,
  `shipping_state_id` varchar(100) NOT NULL,
  `shipping_pincode` int(11) NOT NULL,
  `billing_name` varchar(256) NOT NULL,
  `billing_address` text NOT NULL,
  `billing_city_id` varchar(100) NOT NULL,
  `billing_state_id` varchar(100) NOT NULL,
  `billing_pincode` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `coupon_code` varchar(20) NOT NULL,
  `coupon_text` varchar(512) NOT NULL,
  `is_processed` tinyint(1) DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `bank_transaction_id` varchar(50) DEFAULT NULL,
  `transaction_time` datetime DEFAULT NULL,
  `gateway_name` varchar(50) DEFAULT NULL,
  `bankname` varchar(50) DEFAULT NULL,
  `payment_mod` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.order_product
CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `subscribed_product_id` int(11) NOT NULL,
  `discount` float NOT NULL,
  `price` float NOT NULL,
  `discounted_price` float NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `product_name` varchar(256) NOT NULL,
  `discount_type` enum('price','item') NOT NULL,
  `discount_qty` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.otp_generate
CREATE TABLE IF NOT EXISTS `otp_generate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` bigint(20) DEFAULT NULL,
  `otp_pass` varchar(225) DEFAULT NULL,
  `counter` int(11) NOT NULL,
  `total_cunter` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `expire_date` datetime DEFAULT NULL,
  `is_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `MOBILE_NO` (`mobile`),
  KEY `status` (`status`),
  KEY `expire_date` (`expire_date`),
  KEY `counter` (`counter`),
  KEY `total_cunter` (`total_cunter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.product_attribute_mapping
CREATE TABLE IF NOT EXISTS `product_attribute_mapping` (
  `base_product_id` int(10) unsigned NOT NULL,
  `attribute_id` int(10) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  KEY `IDX_PAM_BASE_PROD_ID` (`base_product_id`),
  KEY `IDX_PAM_ATTR_ID` (`attribute_id`),
  CONSTRAINT `FK_PAM_ATTR_ID` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_PAM_BASE_PROD_ID` FOREIGN KEY (`base_product_id`) REFERENCES `base_product` (`base_product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.product_category_mapping
CREATE TABLE IF NOT EXISTS `product_category_mapping` (
  `base_product_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `UNQ_PCM` (`base_product_id`,`category_id`),
  KEY `IDX_PCM_BASE_PROD` (`base_product_id`),
  KEY `IDX_PCM_CAT` (`category_id`),
  CONSTRAINT `FK_PCM_BASE_PROD_ID` FOREIGN KEY (`base_product_id`) REFERENCES `base_product` (`base_product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_category_mapping_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.product_frontend_mapping
CREATE TABLE IF NOT EXISTS `product_frontend_mapping` (
  `subscribed_product_id` int(10) unsigned NOT NULL,
  `store_front_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `UNQ_PCM` (`subscribed_product_id`,`store_front_id`),
  KEY `IDX_PFM_SUB_PROD` (`subscribed_product_id`),
  KEY `IDX_PFM_STORE_FRONT` (`store_front_id`),
  CONSTRAINT `FK_PFM_STORE_FRONT_ID` FOREIGN KEY (`store_front_id`) REFERENCES `store_front` (`store_front_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_PFM_SUB_PROD_ID` FOREIGN KEY (`subscribed_product_id`) REFERENCES `subscribed_product` (`subscribed_product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.rating_review
CREATE TABLE IF NOT EXISTS `rating_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `order_id` varchar(32) NOT NULL,
  `base_product_id` int(11) NOT NULL,
  `rating` float DEFAULT NULL,
  `review` text,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `title_of_review` text,
  `review` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `ip_address` text,
  `submited_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.sgsy_logging
CREATE TABLE IF NOT EXISTS `sgsy_logging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) DEFAULT NULL,
  `page` varchar(500) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `useragent` varchar(200) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `method` varchar(150) DEFAULT NULL,
  `post_appId` varchar(100) DEFAULT NULL,
  `post_pubKey` varchar(250) DEFAULT NULL,
  `post_reqType` varchar(200) DEFAULT NULL,
  `post_data` text,
  `get` text,
  `sql_query` text,
  `execution_time` varchar(100) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.solr_back_log
CREATE TABLE IF NOT EXISTS `solr_back_log` (
  `subscribed_product_id` int(10) unsigned NOT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subscribed_product_id`),
  UNIQUE KEY `IDX_SOLR_SUB_PROD_ID` (`subscribed_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store
CREATE TABLE IF NOT EXISTS `store` (
  `store_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_code` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `store_details` text,
  `store_logo` varchar(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `business_address` varchar(300) DEFAULT NULL,
  `business_address_country` varchar(100) DEFAULT NULL,
  `business_address_state` varchar(100) DEFAULT NULL,
  `business_address_city` varchar(100) DEFAULT NULL,
  `business_address_pincode` varchar(100) DEFAULT NULL,
  `mobile_numbers` varchar(100) DEFAULT NULL,
  `telephone_numbers` varchar(100) DEFAULT NULL,
  `visible` smallint(6) NOT NULL DEFAULT '1',
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(150) DEFAULT NULL,
  `customer_value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `chat_id` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `vtiger_status` tinyint(1) NOT NULL DEFAULT '0',
  `vtiger_accountid` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `tagline` varchar(256) DEFAULT NULL,
  `is_tagline` tinyint(1) NOT NULL,
  `store_api_key` varchar(100) NOT NULL,
  `store_api_password` varchar(100) NOT NULL,
  `redirect_url` text,
  `seller_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `buyer_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `channel_name` varchar(255) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `order_prefix` varchar(11) NOT NULL,
  `is_active_valid` tinyint(1) NOT NULL DEFAULT '1',
  `store_shipping_charge` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`store_id`),
  KEY `IDX_STORE_ID` (`store_id`),
  KEY `store_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.storefront_fbpage_mapping
CREATE TABLE IF NOT EXISTS `storefront_fbpage_mapping` (
  `store_front_id` int(11) NOT NULL,
  `page_id` varchar(40) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `access_token` text,
  `created_on` datetime DEFAULT NULL,
  `modify_on` datetime DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store_2
CREATE TABLE IF NOT EXISTS `store_2` (
  `store_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_code` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `store_details` text,
  `store_logo` varchar(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `business_address` varchar(300) DEFAULT NULL,
  `business_address_country` varchar(100) DEFAULT NULL,
  `business_address_state` varchar(100) DEFAULT NULL,
  `business_address_city` varchar(100) DEFAULT NULL,
  `business_address_pincode` varchar(100) DEFAULT NULL,
  `mobile_numbers` varchar(100) DEFAULT NULL,
  `telephone_numbers` varchar(100) DEFAULT NULL,
  `visible` smallint(6) NOT NULL DEFAULT '1',
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(150) DEFAULT NULL,
  `customer_value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `chat_id` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `vtiger_status` tinyint(1) NOT NULL DEFAULT '0',
  `vtiger_accountid` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `tagline` varchar(256) DEFAULT NULL,
  `is_tagline` tinyint(1) NOT NULL,
  `store_api_key` varchar(100) NOT NULL,
  `store_api_password` varchar(100) NOT NULL,
  `redirect_url` text,
  `seller_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `buyer_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `channel_name` varchar(255) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `order_prefix` varchar(11) NOT NULL,
  `is_active_valid` tinyint(1) NOT NULL DEFAULT '1',
  `store_shipping_charge` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`store_id`),
  KEY `IDX_STORE_ID` (`store_id`),
  KEY `store_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store_backup
CREATE TABLE IF NOT EXISTS `store_backup` (
  `store_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_code` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `store_details` text,
  `store_logo` varchar(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `business_address` varchar(300) DEFAULT NULL,
  `business_address_country` varchar(100) DEFAULT NULL,
  `business_address_state` varchar(100) DEFAULT NULL,
  `business_address_city` varchar(100) DEFAULT NULL,
  `business_address_pincode` varchar(100) DEFAULT NULL,
  `mobile_numbers` varchar(100) DEFAULT NULL,
  `telephone_numbers` varchar(100) DEFAULT NULL,
  `visible` smallint(6) NOT NULL DEFAULT '1',
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(150) DEFAULT NULL,
  `customer_value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `chat_id` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `vtiger_status` tinyint(1) NOT NULL DEFAULT '0',
  `vtiger_accountid` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `tagline` varchar(256) DEFAULT NULL,
  `is_tagline` tinyint(1) NOT NULL,
  `store_api_key` varchar(100) NOT NULL,
  `store_api_password` varchar(100) NOT NULL,
  `redirect_url` text,
  `seller_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `buyer_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `channel_name` varchar(255) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `order_prefix` varchar(11) NOT NULL,
  `is_active_valid` tinyint(1) NOT NULL DEFAULT '1',
  `store_shipping_charge` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`store_id`),
  KEY `IDX_STORE_ID` (`store_id`),
  KEY `store_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store_fbpage_mapping
CREATE TABLE IF NOT EXISTS `store_fbpage_mapping` (
  `store_id` int(11) NOT NULL,
  `page_id` varchar(40) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `access_token` text,
  `created_on` datetime DEFAULT NULL,
  `modify_on` datetime DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  UNIQUE KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store_front
CREATE TABLE IF NOT EXISTS `store_front` (
  `store_front_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_front_name` varchar(255) DEFAULT NULL,
  `store_front_api_key` varchar(100) DEFAULT NULL,
  `store_front_api_password` varchar(100) DEFAULT NULL,
  `store_front_api_token` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `is_tagline` tinyint(1) NOT NULL,
  `redirect_url` text,
  `seller_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `buyer_mailer_flag` int(1) NOT NULL DEFAULT '1',
  `vendor_coupon_prefix` varchar(10) NOT NULL,
  `order_prefix` varchar(11) NOT NULL,
  PRIMARY KEY (`store_front_id`),
  UNIQUE KEY `UNQ_STORE_FRONT_NAME` (`store_front_name`),
  KEY `IDX_STORE_FRONT_ID` (`store_front_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.store_shipping_charge
CREATE TABLE IF NOT EXISTS `store_shipping_charge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `price` int(11) NOT NULL,
  `shipping_charge` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `price` (`price`),
  KEY `shipping_charge` (`shipping_charge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.subscribed_product
CREATE TABLE IF NOT EXISTS `subscribed_product` (
  `subscribed_product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `getit_subscribed_product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `base_product_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `store_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `store_offer_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `weight` decimal(10,4) unsigned DEFAULT '0.0000',
  `length` decimal(10,4) unsigned DEFAULT '0.0000',
  `width` decimal(10,4) unsigned DEFAULT '0.0000',
  `height` decimal(10,4) unsigned DEFAULT '0.0000',
  `warranty` varchar(100) DEFAULT NULL,
  `prompt` smallint(6) DEFAULT NULL,
  `prompt_key` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `checkout_url` varchar(2083) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  `sku` varchar(128) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '25',
  `is_cod` tinyint(2) NOT NULL DEFAULT '1',
  `subscribe_shipping_charge` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subscribed_product_id`),
  KEY `IDX_SUB_PROD_ID` (`subscribed_product_id`),
  KEY `IDX_SUB_PROD_STORE` (`store_id`),
  KEY `IDX_SUB_PROD_BASE_PROD` (`base_product_id`),
  KEY `subscribed_status` (`status`),
  KEY `weight` (`weight`),
  KEY `length` (`length`),
  KEY `width` (`width`),
  KEY `height` (`height`),
  KEY `subscribe_shipping_charge` (`subscribe_shipping_charge`),
  CONSTRAINT `FK_BASE_SUB_PROD_BASE_PROD_ID` FOREIGN KEY (`base_product_id`) REFERENCES `base_product` (`base_product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subscribed_product_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.sync_log
CREATE TABLE IF NOT EXISTS `sync_log` (
  `last_sync_date` timestamp NULL DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.tbl_pincode_new
CREATE TABLE IF NOT EXISTS `tbl_pincode_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_code` varchar(6) NOT NULL,
  `is_serviceable` int(11) NOT NULL,
  `ndd` int(11) NOT NULL,
  `prepaid` int(1) NOT NULL DEFAULT '1',
  `cod` varchar(3) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.tbl_pincode_new_bk
CREATE TABLE IF NOT EXISTS `tbl_pincode_new_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_code` varchar(6) NOT NULL,
  `is_serviceable` int(11) NOT NULL,
  `ndd` int(11) NOT NULL,
  `prepaid` int(1) NOT NULL DEFAULT '1',
  `cod` varchar(3) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.temp_product_list
CREATE TABLE IF NOT EXISTS `temp_product_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `pro_name` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for view mpdm.testview
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `testview` (
	`base_product_id` INT(10) UNSIGNED NOT NULL,
	`getit_base_product_id` INT(10) UNSIGNED NOT NULL,
	`title` VARCHAR(255) NOT NULL COLLATE 'utf8_general_ci',
	`small_description` TEXT NULL COLLATE 'utf8_general_ci',
	`description` TEXT NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`size` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`product_weight` DECIMAL(12,4) UNSIGNED NULL,
	`brand` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`model_name` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`model_number` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`manufacture` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`manufacture_country` VARCHAR(100) NULL COLLATE 'utf8_general_ci',
	`manufacture_year` INT(11) NULL,
	`specifications` TEXT NULL COLLATE 'utf8_general_ci',
	`key_features` TEXT NULL COLLATE 'utf8_general_ci',
	`meta_title` VARCHAR(150) NULL COLLATE 'utf8_general_ci',
	`meta_keyword` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`meta_description` VARCHAR(150) NULL COLLATE 'utf8_general_ci',
	`average_rating` INT(11) NOT NULL,
	`other_website_rating` INT(11) NOT NULL,
	`is_configurable` SMALLINT(6) NOT NULL,
	`configurable_with` TEXT NULL COLLATE 'utf8_general_ci',
	`status` TINYINT(1) NOT NULL,
	`created_date` TIMESTAMP NULL,
	`modified_date` TIMESTAMP NULL,
	`campaign_id` INT(11) NOT NULL,
	`is_deleted` SMALLINT(6) NOT NULL,
	`is_serial_required` TINYINT(4) NOT NULL,
	`product_content_type` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`ISBN` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`product_shipping_charge` INT(11) NOT NULL,
	`video_url` VARCHAR(255) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for table mpdm.unbxd_hit_count
CREATE TABLE IF NOT EXISTS `unbxd_hit_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.vendor_deals
CREATE TABLE IF NOT EXISTS `vendor_deals` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `refId` int(11) NOT NULL,
  `mode` varchar(11) NOT NULL,
  `subscribed_product_id` int(10) NOT NULL,
  `store_front_id` int(11) NOT NULL,
  `deal_name` varchar(256) NOT NULL,
  `deal_description` varchar(256) NOT NULL,
  `deal_summary` varchar(256) NOT NULL,
  `last_updated` datetime NOT NULL,
  `listed` datetime NOT NULL,
  `expires` datetime NOT NULL,
  `availability` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `actual_price` int(11) NOT NULL,
  `voucher_price` int(11) NOT NULL,
  `offer_price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `redeem text` varchar(256) NOT NULL,
  `tag` varchar(256) NOT NULL,
  PRIMARY KEY (`autoId`),
  KEY `refId` (`refId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.vendor_vouchers
CREATE TABLE IF NOT EXISTS `vendor_vouchers` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `refId` int(11) NOT NULL,
  `correlationID` int(11) NOT NULL,
  `quantity_bought` int(11) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `coupon_code` varchar(20) NOT NULL,
  `buyer_circle` varchar(20) NOT NULL,
  `pincode` varchar(8) NOT NULL,
  `store_front_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.withfloats_back_log
CREATE TABLE IF NOT EXISTS `withfloats_back_log` (
  `subscribed_product_id` int(10) unsigned NOT NULL,
  `is_deleted` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `IDX_SOLR_SUB_PROD_ID` (`subscribed_product_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.withfloats_products
CREATE TABLE IF NOT EXISTS `withfloats_products` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(11) NOT NULL,
  `store_fp_tag` varchar(80) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `fp` varchar(80) NOT NULL,
  `price` double NOT NULL,
  `discount` double NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isDeleted` int(1) NOT NULL,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.withfloats_stores
CREATE TABLE IF NOT EXISTS `withfloats_stores` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` varchar(11) DEFAULT NULL,
  `fp` varchar(80) DEFAULT NULL,
  `fp_tag` varchar(80) DEFAULT NULL,
  `mobile` bigint(10) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT '2015-02-06 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `response` text NOT NULL,
  PRIMARY KEY (`autoId`),
  KEY `store_id` (`store_id`),
  KEY `fp` (`fp`),
  KEY `fp_tag` (`fp_tag`),
  KEY `mobile` (`mobile`),
  KEY `created_date` (`created_date`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.withfloats_stores_backup
CREATE TABLE IF NOT EXISTS `withfloats_stores_backup` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` varchar(11) DEFAULT NULL,
  `fp` varchar(80) DEFAULT NULL,
  `fp_tag` varchar(80) DEFAULT NULL,
  `mobile` bigint(10) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT '2015-02-06 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `response` text NOT NULL,
  PRIMARY KEY (`autoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.withfloats_stores_old
CREATE TABLE IF NOT EXISTS `withfloats_stores_old` (
  `autoId` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` varchar(11) NOT NULL,
  `fp` varchar(80) NOT NULL,
  `fp_tag` varchar(80) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table mpdm.youtube_campaign_data
CREATE TABLE IF NOT EXISTS `youtube_campaign_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` varchar(100) NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `pincode` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for view mpdm.getproductdetailview
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `getproductdetailview`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `getproductdetailview` AS SELECT sp.subscribed_product_id,sp.store_id,sp.store_price,sp.store_offer_price,sp.weight,sp.length,sp.width,sp.height,sp.quantity,
sp.subscribe_shipping_charge,sp.is_cod,sp.sku,sp.warranty,bp.base_product_id,bp.title,bp.color,bp.size,bp.product_weight,
bp.brand,bp.model_name,bp.model_number,bp.product_shipping_charge,bp.specifications,bp.ISBN,s.store_name,s.seller_name,
s.store_details,s.business_address,s.business_address_country,s.business_address_state,s.business_address_pincode,
s.email AS store_email,s.mobile_numbers AS store_contact_no 
FROM subscribed_product sp INNER JOIN base_product bp on bp.base_product_id=sp.base_product_id
INNER JOIN store s on s.store_id=sp.store_id ;


-- Dumping structure for view mpdm.testview
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `testview`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `testview` AS SELECT * from base_product ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
