<?php
/**
 * @file classes/author/maps/Schema.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2000-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Schema
 *
 * @brief Map authors to the properties defined in the announcement schema
 */

namespace PKP\author\maps;

use APP\author\Author;
use Illuminate\Support\Enumerable;
use PKP\core\PKPRequest;
use PKP\services\PKPSchemaService;
use stdClass;

class Schema extends \PKP\core\maps\Schema
{
    public Enumerable $collection;

    public array $contributorRoleTerms;

    public string $schema = PKPSchemaService::SCHEMA_AUTHOR;

    public function __construct(PKPRequest $request, \PKP\context\Context $context, PKPSchemaService $schemaService)
    {
        parent::__construct($request, $context, $schemaService);
        $this->contributorRoleTerms = \PKP\author\Author::getContributorRoleTerms();
    }

    /**
     * Map an author
     *
     * Includes all properties in the announcement schema.
     */
    public function map(Author $item): array
    {
        return $this->mapByProperties($this->getProps(), $item);
    }

    /**
     * Summarize an author
     *
     * Includes properties with the apiSummary flag in the author schema.
     */
    public function summarize(Author $item): array
    {
        return $this->mapByProperties($this->getSummaryProps(), $item);
    }

    /**
     * Map a collection of Authors
     *
     * @see self::map
     */
    public function mapMany(Enumerable $collection): Enumerable
    {
        $this->collection = $collection;
        return $collection->map(function ($item) {
            return $this->map($item);
        });
    }

    /**
     * Summarize a collection of Authors
     *
     * @see self::summarize
     */
    public function summarizeMany(Enumerable $collection): Enumerable
    {
        $this->collection = $collection;
        return $collection->map(function ($item) {
            return $this->summarize($item);
        });
    }

    /**
     * Map schema properties of an Author to an assoc array
     */
    protected function mapByProperties(array $props, Author $item): array
    {
        $output = [];
        foreach ($props as $prop) {
            switch ($prop) {
                case 'fullName':
                    $output[$prop] = $item->getFullName();
                    break;
                case 'contributorRoles':
                    $auris = $item->getData($prop) ?? [];
                    $output[$prop] = array_filter($this->contributorRoleTerms, fn ($uri) => in_array($uri, $auris), ARRAY_FILTER_USE_KEY);
                    break;
                case 'creditRoles':
                    $output[$prop] = $item->getData($prop) ?? [];
                    break;
                default:
                    $output[$prop] = $item->getData($prop);
                    break;
            }
        }

        $output = $this->schemaService->addMissingMultilingualValues($this->schema, $output, $this->context->getSupportedSubmissionLocales());

        ksort($output);

        return $this->withExtensions($output, $item);
    }
}
