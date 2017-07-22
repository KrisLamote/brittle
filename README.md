Brittle
=======
A small parser for fixed width (brittle) text files.

This could for instance be used to transform EDI files to CSV to allow
for easier processing.

Features
--------
This version contains only some basics. Feel free to make any suggestions or contributions.
Some ideas:

* Reader instantiation from resource and file path
* Increased test coverage
* Validators
* Cleansing, Transformations (cleanup rules)

Usage
-----

```php
Reader::fromString($aString)
            ->withFields([
                new OffsetField('foo', 1, 4),
                new FixedField('fix', 'bar')
            ])
            ->parse()
            ->toCsv($filePath);
```

See [ReaderTest](tests/ReaderTest.php) for further details.

License
-------

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.