<?php
/**
 * Twigfield for Craft CMS
 *
 * Provides a twig editor field with Twig & Craft API autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\twigfield\base;

use Craft;
use craft\base\Model;
use craft\helpers\ArrayHelper;
use nystudio107\twigfield\models\CompleteItem;
use nystudio107\twigfield\Twigfield;
use nystudio107\twigfield\types\AutocompleteTypes;

/**
 * @author    nystudio107
 * @package   twigfield
 * @since     1.0.12
 */
abstract class Autocomplete extends Model implements AutocompleteInterface
{
    // Constants
    // =========================================================================

    const COMPLETION_KEY = '__completions';

    // Public Properties
    // =========================================================================

    /**
     * @var string The name of the autocomplete
     */
    public $name = 'BaseAutocomplete';

    /**
     * @var string The type of the autocomplete
     */
    public $type = AutocompleteTypes::TwigExpressionAutocomplete;

    /**
     * @var string Whether the autocomplete should be parsed with . -delimited nested sub-properties
     */
    public $hasSubProperties = false;

    /**
     * @var string The field type passed down from the template to the autocomplete
     */
    public $fieldType = TwigField::DEFAULT_FIELD_TYPE;

    /**
     * @var array The twigfield options object passed down from the template to the autocomplete
     */
    public $twigfieldOptions = [];

    // Protected Properties
    // =========================================================================

    /**
     * @var array The accumulated complete items
     */
    protected $completeItems = [];

    // Public Static Methods
    // =========================================================================

    /**
     * @inerhitDoc
     */
    public function generateCompleteItems(): void
    {
    }

    /**
     * @inerhitDoc
     */
    public function addCompleteItem(CompleteItem $item, string $path = ''): void
    {
        if (!$item->validate()) {
            Craft::debug(print_r($item->getErrors(), true), __METHOD__);
            return;
        }
        if (empty($path)) {
            $path = $item->label;
        }
        ArrayHelper::setValue($this->completeItems, $path, [
            self::COMPLETION_KEY => array_filter($item->toArray(), static function ($v) {
                return !is_null($v);
            })
        ]);
    }

    /**
     * @inerhitDoc
     */
    public function getCompleteItems(): array
    {
        return $this->completeItems;
    }
}
