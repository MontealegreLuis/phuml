<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Methods\Method;
use PhUml\Code\Properties\Constant;
use PhUml\Code\Properties\EnumCase;
use PhUml\Code\Properties\HasConstants;
use PhUml\Code\Properties\WithConstants;

final class EnumDefinition extends Definition implements HasConstants, UseTraits, ImplementsInterfaces
{
    use WithConstants;
    use WithTraits;
    use WithInterfaces;

    /**
     * @param Method[] $methods
     * @param EnumCase[] $cases
     * @param Constant[] $constants
     * @param Name[] $interfaces
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        private readonly array $cases,
        array $methods = [],
        array $constants = [],
        array $interfaces = [],
        array $traits = [],
    ) {
        parent::__construct($name, $methods);
        $this->constants = $constants;
        $this->traits = $traits;
        $this->interfaces = $interfaces;
    }

    public function hasProperties(): bool
    {
        return $this->cases !== [] || $this->constants !== [];
    }

    /** @return EnumCase[] */
    public function cases(): array
    {
        return $this->cases;
    }
}
