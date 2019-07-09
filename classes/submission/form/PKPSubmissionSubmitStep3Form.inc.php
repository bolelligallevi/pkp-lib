<?php

/**
 * @file classes/submission/form/PKPSubmissionSubmitStep3Form.inc.php
 *
 * Copyright (c) 2014-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PKPSubmissionSubmitStep3Form
 * @ingroup submission_form
 *
 * @brief Form for Step 3 of author submission: submission metadata
 */

import('lib.pkp.classes.submission.form.SubmissionSubmitForm');

// This class contains a static method to describe metadata field settings
import('lib.pkp.controllers.grid.settings.metadata.MetadataGridHandler');

class PKPSubmissionSubmitStep3Form extends SubmissionSubmitForm {

	/** @var SubmissionMetadataFormImplementation */
	var $_metadataFormImplem;

	/**
	 * Constructor.
	 * @param $context Context
	 * @param $submission Submission
	 * @param $metadataFormImplementation MetadataFormImplementation
	 */
	function __construct($context, $submission, $metadataFormImplementation) {
		parent::__construct($context, $submission, 3);

		$this->setDefaultFormLocale($submission->getLocale());
		$this->_metadataFormImplem = $metadataFormImplementation;
		$this->_metadataFormImplem->addChecks($submission);
	}

	/**
	 * @copydoc SubmissionSubmitForm::initData
	 */
	function initData() {
		$this->_metadataFormImplem->initData($this->submission);
		return parent::initData();
	}

	/**
	 * @copydoc SubmissionSubmitForm::fetch
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$context = $request->getContext();

		// Tell the form what fields are enabled (and which of those are required)
		foreach (array_keys(MetadataGridHandler::getNames()) as $field) {
			$templateMgr->assign(array(
				$field . 'Enabled' => $context->getSetting($field . 'EnabledSubmission'),
				$field . 'Required' => $context->getSetting($field . 'Required')
			));
		}

		// Get assigned categories
		// We need an array of IDs for the SelectListPanel, but we also need an
		// array of Category objects to use when the metadata form is viewed in
		// readOnly mode. This mode is invoked on the SubmissionMetadataHandler
		// is not available here
		$submissionDao = Application::getSubmissionDAO();
		$submission = $submissionDao->getById($this->submissionId);
		$categories = $submissionDao->getCategories($submission->getId(), $submission->getContextId());
		$assignedCategories = array();
		$selectedIds = array();
		while ($category = $categories->next()) {
			$assignedCategories[] = $category;
			$selectedIds[] = $category->getId();
		}

		// Get SelectCategoryListHandler data
		import('lib.pkp.controllers.list.SelectCategoryListHandler');
		$selectCategoryList = new SelectCategoryListHandler(array(
			'title' => 'submission.submit.placement.categories',
			'inputName' => 'categories[]',
			'selected' => $selectedIds,
			'getParams' => array(
				'contextId' => $submission->getContextId(),
			),
		));

		$selectCategoryListData = $selectCategoryList->getConfig();

		$templateMgr->assign(array(
			'hasCategories' => !empty($selectCategoryListData['items']),
			'selectCategoryListData' => json_encode($selectCategoryListData),
			'assignedCategories' => $assignedCategories,
		));
		return parent::fetch($request, $template, $display);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->_metadataFormImplem->readInputData();
	}

	/**
	 * Get the names of fields for which data should be localized
	 * @return array
	 */
	function getLocaleFieldNames() {
		return $this->_metadataFormImplem->getLocaleFieldNames();
	}

	/**
	 * Save changes to submission.
	 * @return int the submission ID
	 */
	function execute() {
		// Execute submission metadata related operations.
		$this->_metadataFormImplem->execute($this->submission, Application::getRequest());

		// Get an updated version of the submission.
		$submissionDao = Application::getSubmissionDAO();
		$submission = $submissionDao->getById($this->submissionId);

		// Set other submission data.
		if ($submission->getSubmissionProgress() <= $this->step) {
			$submission->setSubmissionProgress($this->step + 1);
			$submission->stampStatusModified();
		}

		parent::execute();

		// Save the submission.
		$submissionDao->updateObject($submission);

		return $this->submissionId;
	}
}


