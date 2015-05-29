

DROP TABLE IF EXISTS `#__hrm_reward_discipline`;


DROP TABLE IF EXISTS `#__hrm_wage`;

DROP TABLE IF EXISTS `#__hrm_payroll_autoup`;



/** delete from content_history **/

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.employee';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.employee');
DELETE FROM `#__user_usergroup_map` WHERE `#__user_usergroup_map`.`user_id` IN (SELECT `id` FROM `#__users` WHERE `id` IN (SELECT `user_id` FROM `#__hrm_employee`));
DELETE FROM `#__users` WHERE `id` IN (SELECT `user_id` FROM `#__hrm_employee`);
DROP TABLE IF EXISTS `#__hrm_employee`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.position';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.position');
DROP TABLE IF EXISTS `#__hrm_position`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.positionstype';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.positionstype');
DROP TABLE IF EXISTS `#__hrm_positionstype`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.department';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.department');
DROP TABLE IF EXISTS `#__hrm_departments`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.historyitself';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.historyitself');
DROP TABLE IF EXISTS `#__hrm_history_itself`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.payroll';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.payroll');
DROP TABLE IF EXISTS `#__hrm_payroll`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.coefficient';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.coefficient');
DROP TABLE IF EXISTS `#__hrm_coefficient`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.scale_type';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.scale_type');
DROP TABLE IF EXISTS `#__hrm_scale_type`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.scale_group';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_hrm.scale_group');
DROP TABLE IF EXISTS `#__hrm_scale_group`;