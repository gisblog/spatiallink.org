-- 
-- Table structure for table `accounts_cases`
-- 

CREATE TABLE `accounts_cases` (
  `id` char(36) NOT NULL default '',
  `account_id` char(36) default NULL,
  `case_id` char(36) default NULL,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_acc_case_acc` (`account_id`),
  KEY `idx_acc_acc_case` (`case_id`)
) TYPE=MyISAM;

ALTER TABLE calls ADD reminder_time int(4) default '-1' AFTER deleted;

ALTER TABLE calls_contacts ADD required char(1) default '1' AFTER contact_id;

ALTER TABLE calls_contacts ADD accept_status char(25) default 'none' AFTER required;

ALTER TABLE calls_users ADD required char(1) default '1' AFTER user_id;

ALTER TABLE calls_users ADD accept_status char(25) default 'none' AFTER required;

-- 
-- Table structure for table `campaigns`
-- 
CREATE TABLE `campaigns` (
  `id` varchar(36) NOT NULL default '',
  `tracker_key` int(11) NOT NULL auto_increment,
  `tracker_count` int(11) default '0',
  `name` varchar(50) default NULL,
  `refer_url` varchar(255) default 'http://',
  `tracker_text` varchar(255) default NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `modified_user_id` varchar(36) default NULL,
  `assigned_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,



  `deleted` tinyint(1) NOT NULL default '0',
  `start_date` date default NULL,
  `end_date` date default NULL,
  `status` varchar(25) default NULL,
  `budget` double default NULL,
  `expected_cost` double default NULL,
  `actual_cost` double default NULL,
  `expected_revenue` double default NULL,
  `campaign_type` varchar(25) default NULL,
  `objective` text,
  `content` text,
  PRIMARY KEY  (`id`),
  KEY `auto_tracker_key` (`tracker_key`),
  KEY `idx_campaign_name` (`name`)
) TYPE=MyISAM AUTO_INCREMENT=1;

--
-- Table structure for `email_marketing`
--

CREATE TABLE `email_marketing` (
  `id` varchar(36) NOT NULL default '',
  `deleted` tinyint(1) NOT NULL default '0',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,
  `name` varchar(255) default NULL,
  `from_addr` varchar(100) default NULL,
  `from_name` varchar(100) default NULL,
  `date_start` date default NULL,
  `time_start` time default NULL,
  `template_id` varchar(36) NOT NULL default '',
  `campaign_id` varchar(36) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `idx_emmkt_name` (`name`),
  KEY `idx_emmkit_del` (`deleted`)
) TYPE=MyISAM;

ALTER TABLE contacts MODIFY first_name varchar(100) default NULL;
ALTER TABLE contacts MODIFY last_name varchar(100) default NULL;
ALTER TABLE currencies ADD created_by char(36) NOT NULL default '';


-- 
-- Table structure for table `document_revisions`
-- 

CREATE TABLE `document_revisions` (
  `id` varchar(36) NOT NULL default '',
  `change_log` varchar(255) default NULL,
  `document_id` varchar(36) NOT NULL default '',
  `date_entered` datetime default NULL,
  `created_by` varchar(36) default NULL,
  `filename` varchar(255) default NULL,
  `file_ext` varchar(25) default NULL,
  `file_mime_type` varchar(100) default NULL,
  `revision` varchar(25) default NULL,
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `documents`
-- 

CREATE TABLE `documents` (
  `id` varchar(36) NOT NULL default '',
  `document_name` varchar(255) NOT NULL default '',
  `active_date` date default NULL,
  `exp_date` date default NULL,
  `description` text,
  `category_id` varchar(25) default NULL,
  `subcategory_id` varchar(25) default NULL,
  `status_id` varchar(25) default NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `deleted` tinyint(1) default '0',
  `modified_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,



  `document_revision_id` varchar(36) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------


-- 
-- Table structure for table `emailman`
-- 

CREATE TABLE `emailman` (
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `user_id` varchar(36) default NULL,
  `template_id` varchar(36) default NULL,
  `from_email` varchar(255) default NULL,
  `from_name` varchar(255) default NULL,
  `id` varchar(36) NOT NULL default '',
  `module_id` varchar(36) default NULL,
  `campaign_id` varchar(36) default NULL,
  `marketing_id` varchar(36) default NULL,
  `list_id` varchar(36) default NULL,
  `module` varchar(100) default NULL,
  `send_date_time` datetime default NULL,
  `modified_user_id` varchar(36) default NULL,
  `invalid_email` tinyint(1) default '0',
  `in_queue` tinyint(1) default '0',
  `in_queue_date` datetime default NULL,
  `send_attempts` int(11) default '0',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_eman_list` (`list_id`,`user_id`,`deleted`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `emailman_sent`
-- 

CREATE TABLE `emailman_sent` (
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `user_id` varchar(36) default NULL,
  `template_id` varchar(36) default NULL,
  `from_email` varchar(255) default NULL,
  `from_name` varchar(255) default NULL,
  `id` varchar(36) NOT NULL default '',
  `module_id` varchar(36) default NULL,
  `campaign_id` varchar(36) default NULL,
  `marketing_id` varchar(36) default NULL,
  `list_id` varchar(36) default NULL,
  `module` varchar(100) default NULL,
  `send_date_time` datetime default NULL,
  `modified_user_id` varchar(36) default NULL,
  `invalid_email` tinyint(1) default '0',
  `in_queue` tinyint(1) default '0',
  `in_queue_date` datetime default NULL,
  `send_attempts` int(11) default '0',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_emanst_list` (`list_id`,`user_id`,`deleted`)
) TYPE=MyISAM;


CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` varchar(36) NOT NULL default '',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,
  `published` char(3) default NULL,
  `name` varchar(255) default NULL,
  `description` text,
  `subject` varchar(255) default NULL,
  `body` text,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_email_template_name` (`name`)
) TYPE=MyISAM;


ALTER TABLE feeds MODIFY description text;

-- --------------------------------------------------------

-- 
-- Table structure for table `fields_meta_data`
-- 

CREATE TABLE `fields_meta_data` (
  `id` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `custom_module` varchar(255) default '',
  `data_type` varchar(255) NOT NULL default '',
  `max_size` int(11) default '0',
  `required_option` varchar(255) default '',
  `default_value` varchar(255) default '',
  `deleted` tinyint(1) default '0',
  `ext1` varchar(255) default '0',
  `ext2` varchar(255) default '0',
  `ext3` varchar(255) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_meta_id_del` (`id`,`deleted`)
) TYPE=MyISAM;

-- --------------------------------------------------------





















-- --------------------------------------------------------





















-- --------------------------------------------------------

ALTER TABLE meetings ADD reminder_time int(11) default '-1';

ALTER TABLE meetings_contacts ADD required char(1) default '1' AFTER contact_id;

ALTER TABLE meetings_contacts ADD accept_status char(25) default 'none' AFTER required;

ALTER TABLE meetings_users ADD required char(1) default '1' AFTER user_id;

ALTER TABLE meetings_users ADD accept_status char(25) default 'none' AFTER required;

ALTER TABLE opportunities MODIFY lead_source varchar(50) default NULL;


-- 
-- Table structure for table `project`
-- 

CREATE TABLE `project` (
  `id` varchar(36) NOT NULL default '',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `assigned_user_id` varchar(36) default NULL,
  `modified_user_id` varchar(36) NOT NULL default '',
  `created_by` varchar(36) default NULL,



  `name` varchar(50) NOT NULL default '',
  `description` text,
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `project_relation`
-- 

CREATE TABLE `project_relation` (
  `id` varchar(36) NOT NULL default '',
  `project_id` varchar(36) NOT NULL default '',
  `relation_id` varchar(36) NOT NULL default '',
  `relation_type` varchar(255) NOT NULL default '',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `project_task`
-- 

CREATE TABLE `project_task` (
  `id` varchar(36) NOT NULL default '',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `assigned_user_id` varchar(36) default NULL,
  `modified_user_id` varchar(36) NOT NULL default '',
  `created_by` varchar(36) default NULL,



  `name` varchar(50) NOT NULL default '',
  `status` varchar(255) default NULL,
  `date_due` date default NULL,
  `time_due` time default NULL,
  `date_start` date default NULL,
  `time_start` time default NULL,
  `parent_id` varchar(36) NOT NULL default '',
  `priority` varchar(255) default NULL,
  `description` text,
  `order_number` int(11) default '1',
  `task_number` int(11) default NULL,
  `depends_on_id` varchar(36) default NULL,
  `milestone_flag` varchar(255) default NULL,
  `estimated_effort` int(11) default NULL,
  `actual_effort` int(11) default NULL,
  `utilization` int(11) default '100',
  `percent_complete` int(11) default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `prospect_list_campaigns`
-- 

CREATE TABLE `prospect_list_campaigns` (
  `id` char(36) NOT NULL default '',
  `prospect_list_id` char(36) default '',
  `campaign_id` char(36) default '',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_pro_id` (`prospect_list_id`),
  KEY `idx_cam_id` (`campaign_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `prospect_lists`
-- 

CREATE TABLE `prospect_lists` (
  `id` varchar(36) NOT NULL default '',
  `name` varchar(50) default NULL,
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `modified_user_id` varchar(36) default NULL,
  `assigned_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,



  `deleted` tinyint(1) NOT NULL default '0',
  `description` text,
  PRIMARY KEY  (`id`),
  KEY `idx_prospect_list_name` (`name`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `prospect_lists_prospects`
-- 

CREATE TABLE `prospect_lists_prospects` (
  `id` char(36) NOT NULL default '',
  `prospect_list_id` char(36) default '',
  `prospect_id` char(36) default '',
  `contact_id` char(36) default '',
  `lead_id` char(36) default '',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_pro_id` (`prospect_list_id`),
  KEY `idx_pros_id` (`prospect_id`),
  KEY `idx_cont_id` (`contact_id`),
  KEY `idx_lead_id` (`lead_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `prospects`
-- 

CREATE TABLE `prospects` (
  `id` varchar(36) NOT NULL default '',
  `tracker_key` int(11) NOT NULL auto_increment,
  `deleted` tinyint(1) default '0',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) NOT NULL default '',
  `assigned_user_id` varchar(36) default NULL,
  `created_by` varchar(36) default NULL,



  `salutation` varchar(5) default NULL,
  `first_name` varchar(100) default NULL,
  `last_name` varchar(100) default NULL,
  `title` varchar(25) default NULL,
  `department` varchar(255) default NULL,
  `birthdate` date default NULL,
  `do_not_call` char(3) default '0',
  `phone_home` varchar(25) default NULL,
  `phone_mobile` varchar(25) default NULL,
  `phone_work` varchar(25) default NULL,
  `phone_other` varchar(25) default NULL,
  `phone_fax` varchar(25) default NULL,
  `email1` varchar(100) default NULL,
  `email2` varchar(100) default NULL,
  `assistant` varchar(75) default NULL,
  `assistant_phone` varchar(25) default NULL,
  `email_opt_out` char(3) default '0',
  `primary_address_street` varchar(150) default NULL,
  `primary_address_city` varchar(100) default NULL,
  `primary_address_state` varchar(100) default NULL,
  `primary_address_postalcode` varchar(20) default NULL,
  `primary_address_country` varchar(100) default NULL,
  `alt_address_street` varchar(150) default NULL,
  `alt_address_city` varchar(100) default NULL,
  `alt_address_state` varchar(100) default NULL,
  `alt_address_postalcode` varchar(20) default NULL,
  `alt_address_country` varchar(100) default NULL,
  `description` text,
  `invalid_email` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `auto_tracker_key` (`tracker_key`),
  KEY `idx_prospects_last_first` (`last_name`,`first_name`,`deleted`),
  KEY `idx_prospecs_del_last` (`last_name`,`deleted`)



) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------



-- 
-- Table structure for table `roles`
-- 

CREATE TABLE `roles` (
  `id` varchar(36) NOT NULL default '',
  `date_entered` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_user_id` varchar(36) NOT NULL default '',
  `created_by` varchar(36) default NULL,
  `name` varchar(150) default NULL,
  `description` text,
  `modules` text,
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_role_id_del` (`id`,`deleted`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roles_modules`
-- 

CREATE TABLE `roles_modules` (
  `id` char(36) NOT NULL default '',
  `role_id` char(36) default '',
  `module_id` char(36) default '',
  `allow` tinyint(1) default '0',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_role_id` (`role_id`),
  KEY `idx_module_id` (`module_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `roles_users`
-- 

CREATE TABLE `roles_users` (
  `id` char(36) NOT NULL default '',
  `role_id` char(36) default '',
  `user_id` char(36) default '',
  `deleted` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_role_id` (`role_id`),
  KEY `idx_user_id` (`user_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- --------------------------------------------------------





















ALTER TABLE users ADD employee_status varchar(25) default NULL;
ALTER TABLE users ADD messenger_id varchar(25) default NULL;
ALTER TABLE users ADD messenger_type varchar(25) default NULL;


-- 
-- Table structure for table `vcals`
-- 

CREATE TABLE `vcals` (
  `id` varchar(36) NOT NULL default '',
  `deleted` tinyint(1) NOT NULL default '0',
  `date_entered` datetime default NULL,
  `date_modified` datetime default NULL,
  `user_id` varchar(36) NOT NULL default '',
  `type` varchar(25) default NULL,
  `source` varchar(25) default NULL,
  `content` text,
  PRIMARY KEY  (`id`),
  KEY `idx_vcal` (`type`,`user_id`)
) TYPE=MyISAM;

DROP INDEX `id` ON versions;
DROP INDEX `id` ON releases;











ALTER TABLE contacts ADD invalid_email tinyint(1) default '0';
ALTER TABLE leads ADD invalid_email tinyint(1) default '0';
ALTER TABLE feeds MODIFY url varchar(255) default NULL;
INSERT INTO config (category, name, value) VALUES ('info', 'sugar_version', '3.0');
INSERT INTO `config` VALUES ('license', 'users', '0');
INSERT INTO `config` VALUES ('license', 'expire_date', '');
INSERT INTO `config` VALUES ('license', 'key', '');





