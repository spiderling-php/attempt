Attempt
=======

[![Build Status](https://travis-ci.org/spiderling-php/attempt.png?branch=master)](https://travis-ci.org/spiderling-php/attempt)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiderling-php/attempt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/spiderling-php/attempt/)
[![Code Coverage](https://scrutinizer-ci.com/g/spiderling-php/attempt/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/spiderling-php/attempt/)
[![Latest Stable Version](https://poser.pugx.org/spiderling-php/attempt/v/stable.png)](https://packagist.org/packages/spiderling-php/attempt)

Retry something until it works

Instalation
-----------

Install via composer

```
composer require spiderling-php/attempt
```

Usage
-----
``` php
$attempt = new Attempt(function () {
    return ... // Try to do something
});

$attempt->setTimeout(3000);

return $attempt->execute();
```

License
-------

Copyright (c) 2015, Clippings Ltd. Developed by Ivan Kerin

Under BSD-3-Clause license, read LICENSE file.
