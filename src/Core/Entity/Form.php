<?php

/**
 * Ushahidi Form
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Platform
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Entity;

use Ushahidi\Core\StaticEntity;

class Form extends StaticEntity
{
    protected $id;
    protected $parent_id;
    protected $name;
    protected $description;
    protected $color;
    protected $type;
    protected $disabled;
    protected $created;
    protected $updated;
    protected $hide_author;
    protected $hide_time;
    protected $hide_location;
    protected $require_approval;
    protected $everyone_can_create;
    protected $targeted_survey;
    protected $can_create;
    protected $tags;

    // StatefulData
    protected function getDefaultData()
    {
        return [
            'type' => 'report',
            'require_approval' => true,
            'everyone_can_create' => true,
            'hide_author' => false,
            'hide_time' => false,
            'hide_location' => false,
            'targeted_survey' => false,
        ];
    }

    // DataTransformer
    protected function getDefinition()
    {
        $typeColor = function ($color) {
            if ($color) {
                return ltrim($color, '#');
            }
        };
        return [
            'id'          => 'int',
            'parent_id'   => 'int',
            'name'        => 'string',
            'description' => 'string',
            'color'       => $typeColor,
            'type'        => 'string',
            'disabled'    => 'bool',
            'created'     => 'int',
            'updated'     => 'int',
            'hide_author'           => 'bool',
            'hide_time'             => 'bool',
            'hide_location'         => 'bool',
            'require_approval'      => 'bool',
            'everyone_can_create'   => 'bool',
            'targeted_survey'       => 'bool',
            'can_create'            => 'array',
            'tags'        => 'array',
        ];
    }

    // Entity
    public function getResource()
    {
        return 'forms';
    }

    // StatefulData
    protected function getImmutable()
    {
        // Hack: Add computed properties to immutable list
        return array_merge(parent::getImmutable(), ['tags', 'can_create']);
    }

    public function __toString()
    {
        return json_encode([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description
        ]);
    }
}
