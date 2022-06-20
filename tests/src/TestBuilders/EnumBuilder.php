<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\EnumDefinition;
use PhUml\Code\Name;
use PhUml\Code\Properties\Constant;
use PhUml\Code\Properties\EnumCase;

final class EnumBuilder
{
    use MembersBuilder;

    /** @var Name[] */
    private array $traits = [];

    /** @var Name[] */
    private array $interfaces = [];

    /** @var Constant[]  */
    private array $constants = [];

    /** @var EnumCase[]  */
    private array $cases = [];

    public function __construct(private readonly string $name)
    {
    }

    public function using(Name ...$traits): EnumBuilder
    {
        $this->traits = $traits;

        return $this;
    }

    public function implementing(Name ...$interfaces): EnumBuilder
    {
        $this->interfaces = array_merge($this->interfaces, $interfaces);

        return $this;
    }

    public function withConstants(Constant ...$constants): EnumBuilder
    {
        $this->constants = $constants;
        return $this;
    }

    public function withCases(string ...$cases): EnumBuilder
    {
        $this->cases = array_map(static fn ($case) => new EnumCase($case), $cases);
        return $this;
    }

    public function build(): EnumDefinition
    {
        return new EnumDefinition(
            new Name($this->name),
            $this->cases,
            $this->methods,
            $this->constants,
            $this->interfaces,
            $this->traits
        );
    }
}
