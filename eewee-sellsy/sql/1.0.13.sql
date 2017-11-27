ALTER TABLE `wp_eewee_sellsy_contact_form` ADD `contact_form_custom_fields_quantity` INT(11) NOT NULL AFTER `contact_form_status`;
ALTER TABLE `wp_eewee_sellsy_contact_form` ADD `contact_form_custom_fields_value` TEXT NOT NULL AFTER `contact_form_custom_fields_quantity`;
