# Type hints

phUML can extract type information from doc blocks

* It can extract return types via the `@return` tag
* It can extract scalar type hints via the `@param` tag
* It can extract types from attributes via the `@var` tag

The class below will show type information for all of its attributes and methods

```php
<?php
class WithTypes
{
    /** @var string $name */
    private $name;

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
        $this->name = $name
    }
}
```
