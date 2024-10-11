<?php

/**
 * @file classes/submission/SubmissionKeyword.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SubmissionKeyword
 *
 * @ingroup submission
 *
 * @see SubmissionKeywordEntryDAO
 *
 * @brief Basic class describing a submission keyword
 */

namespace PKP\submission;

use PKP\controlledVocab\ControlledVocabEntry;
use SubmissionKeywordDAO;

class SubmissionKeyword extends ControlledVocabEntry
{
    //
    // Get/set methods
    //

    /**
     * Get the keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->getData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD);
    }

    /**
     * Get the keyword's label
     *
     * @return string|null
     */
    public function getLabel(): ?array
    {
        return $this->getData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_LABEL);
    }

    /**
     * Get the keyword's identifier
     *
     * @return string|null
     */
    public function getIdentifier(): ?string
    {
        return $this->getData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_ID);
    }

    /**
     * Set the keyword text
     *
     * @param string $keyword
     * @param string $locale
     */
    public function setKeyword($keyword, $locale)
    {
        $this->setData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD, $keyword, $locale);
    }

    /**
     * Set the keyword's label
     */
    public function setLabel(string $label, string $locale): void
    {
        $this->setData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_LABEL, $label, $locale);
    }

    /**
     * Set the keyword's identifier
     */
    public function setIdentifier(string $id): void
    {
        $this->setData(SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_ID, $id, '');
    }

    public function getLocaleMetadataFieldNames(): array
    {
        return [
            SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD,
            SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_LABEL,
            SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD_ID,
        ];
    }

    /**
     * Get entry related data
     */
    public function getEntryData(string $vocab = SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD): ?array
    {
        return parent::getEntryData($vocab);
    }

    /**
     * Set keyword related data
     */
    public function setEntryData(array $data, string $locale, string $vocab = SubmissionKeywordDAO::CONTROLLED_VOCAB_SUBMISSION_KEYWORD): void
    {
        parent::setEntryData($data, $locale, $vocab);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\submission\SubmissionKeyword', '\SubmissionKeyword');
}
