<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Graphviz\Builders\NodeLabelBuilder;

class SimpleTableLabelBuilder extends NodeLabelBuilder
{
    public function __construct()
    {
    }

    public function forClass(ClassDefinition $class): string
    {
        return $this->classTemplate($class);
    }

    public function forInterface(InterfaceDefinition $interface): string
    {
        return $this->interfaceTemplate($interface);
    }

    /** @param InterfaceDefinition $interface */
    private function interfaceTemplate(InterfaceDefinition $interface): string
    {
        return sprintf(
            '<<table><tr><td>%s</td></tr>%s%s</table>>',
            $interface->name(),
            $this->members($interface->constants()),
            $this->members($interface->methods())
        );
    }

    /** @param ClassDefinition $class */
    private function classTemplate(ClassDefinition $class): string
    {
        return sprintf(
            '<<table><tr><td>%s</td></tr>%s%s%s</table>>',
            $class->name(),
            $this->members($class->constants()),
            $this->members($class->attributes()),
            $this->members($class->methods())
        );
    }

    private function members(array $members): string
    {
        if (empty($members)) {
            return '';
        }
        return '<tr><td>' . implode('<br/>', $members) . '</td></tr>';
    }
}
