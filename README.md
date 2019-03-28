# Omnipay: Poli

**Poli driver for the Omnipay PHP payment processing library**

Website: http://www.polipay.co.nz
Developer Docs: http://www.polipaymentdeveloper.com/

[![Build Status](https://travis-ci.org/bbe-io/omnipay-poli.png?branch=master)](https://travis-ci.org/bbe-io/omnipay-poli)
[![Latest Stable Version](https://poser.pugx.org/bbe-io/omnipay-poli/version.png)](https://packagist.org/packages/bbe-io/omnipay-Poli)
[![Total Downloads](https://poser.pugx.org/bbe-io/omnipay-poli/d/total.png)](https://packagist.org/packages/bbe-io/omnipay-poli)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Poli support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `omnipay/poli` with Composer:

```
composer require league/omnipay omnipay/poli
```

## Basic Usage

The following gateways are provided by this package:

* Poli

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/bbe-io/omnipay-poli/issues), or better yet, fork the library and submit a pull request.
