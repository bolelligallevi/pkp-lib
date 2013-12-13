<?php

/**
 * @file classes/security/UserGroup.inc.php
 *
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UserGroup
 * @ingroup security
 * @see UserGroupDAO
 *
 * @brief Describes user groups
 */

// Bring in role constants.
import('classes.security.Role');

class UserGroup extends DataObject {
	/**
	 * Constructor.
	 */
	function UserGroup() {
		parent::DataObject();
	}

	/**
	 * Get the role ID
	 * @return int ROLE_ID_...
	 */
	function getRoleId() {
		return $this->getData('roleId');
	}

	/**
	 * Set the role ID
	 * @param $roleId int ROLE_ID_...
	 */
	function setRoleId($roleId) {
		$this->setData('roleId', $roleId);
	}

	/**
	 * Get the role path
	 * @return string Role path
	 */
	function getPath() {
		return $this->getData('path');
	}

	/**
	 * Set the role path
	 * $param $path string
	 */
	function setPath($path) {
		$this->setData('path', $path);
	}

	/**
	 * Get the context ID
	 * @return int
	 */
	function getContextId() {
		return $this->getData('contextId');
	}

	/**
	 * Set the context ID
	 * @param $contextId int
	 */
	function setContextId($contextId) {
		$this->setData('contextId', $contextId);
	}

	/**
	 * Get the default flag
	 * @return boolean
	 */
	function getDefault() {
		return $this->getData('isDefault');
	}

	/**
	 * Set the default flag
	 * @param $isDefault boolean
	 */
	function setDefault($isDefault) {
		$this->setData('isDefault', $isDefault);
	}

	/**
	 * Get the "show title" flag (whether or not the title of the role
	 * should be included in the list of submission contributor names)
	 * @return boolean
	 */
	function getShowTitle() {
		return $this->getData('showTitle');
	}

	/**
	 * Set the "show title" flag
	 * @param $isDefault boolean
	 */
	function setShowTitle($showTitle) {
		$this->setData('showTitle', $showTitle);
	}

	/**
	 * Get the localized role name
	 * @return string
	 */
	function getLocalizedName() {
		return $this->getLocalizedData('name');
	}

	/**
	 * Get user group name
	 * @param $locale string
	 * @return string
	 */
	function getName($locale) {
		return $this->getData('name', $locale);
	}

	/**
	 * Set user group name
	 * @param $name string
	 * @param $locale string
	 */
	function setName($name, $locale) {
		return $this->setData('name', $name, $locale);
	}

	/**
	 * Get the localized abbreviation
	 * @return string
	 */
	function getLocalizedAbbrev() {
		return $this->getLocalizedData('abbrev');
	}

	/**
	 * Get user group abbrev
	 * @param $locale string
	 * @return string
	 */
	function getAbbrev($locale) {
		return $this->getData('abbrev', $locale);
	}

	/**
	 * Set user group abbrev
	 * @param $abbrev string
	 * @param $locale string
	 */
	function setAbbrev($abbrev, $locale) {
		return $this->setData('abbrev', $abbrev, $locale);
	}
}


?>
