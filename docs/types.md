---
currentMenu: types
---

# Type Information

phUML can extract type information from doc blocks

* It can extract return types via the `@return` tag
* It can extract scalar type hints via the `@param` tag
* It can extract types from attributes via the `@var` tag

The class below will show type information for all of its attributes and methods

```php
<?php
final class WithTypes
{
    // In this case type is taken from its declaration
    private string $name;

    /** @var DateTime */
    private $dob;

    // This one does not need a doc block since the type is extracted
    // directly from the method's signature
    public function __construct(DateTime $dob)
    {
        $this->dob = $dob;
    }

    /**
     * @param string $name
     * @return void
     */
    public function rename($name)
    {
        $this->name = $name;
    }
}
```

## Nullable and union types from DocBlocks

If you can't migrate to a recent PHP version, phUML can extract, nullable and union types from DocBlocks that follow [PSR-5](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md), as shown in the example below.

```php
final class FilteredAttributesBuilders
{
    /** @var ?VisibilityFilter */
    private $filter;
    
    /**
     * @param Stmt|Param $parsedAttribute
     * @param UseStatements $useStatements
     * @return ?Attribute
     */
    public function build($parsedAttribute, $useStatements)
    {
    }
}
```

## How phUML handles `array`

If `array` is found in a type declaration for an attribute or parameter, phUML will try to extract a more accurate type from the doc block, if present.

```php
<?php
final class WithTypeDeclarationsForArray
{
    /** @var ClassDefinition[] */
    private array $classes;
    
    /** @param ClassDefinition[] */
    public function __construct(array $classes)
    {
        $this->classes = $classes;
    }
    
    /** @return ClassDefinition[] */
    public function classes(): array
    {
        return $this->classes;
    }
}
```

In the 3 cases above, phUML will use `ClassDefinition[]` instead of `array`.
