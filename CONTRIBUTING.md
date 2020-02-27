Contributing
============

Thank you for contributing to this project!

Bug reports
-----------

If you find a bug, please submit an issue. Try to be as detailed as possible
in your problem description to help us fix the bug.

Feature requests
----------------

If you wish to propose a feature, please submit an issue. Try to explain your
use case as fully as possible to help us understand why you think the feature
should be added.

License
-------

By contributing your code, you agree to license your contribution under the terms of the APLv2: 
https://github.com/opentracing/opentracing-php/blob/master/LICENSE

All files are released with the Apache 2.0 license.

Creating a pull request (PR)
----------------------------

First [fork the repository](https://help.github.com/articles/fork-a-repo/) on
GitHub.

Then clone your fork:

```bash
$ git clone https://github.com/your-name/opentracing-php.git
$ git checkout -b bug-or-feature-description
```

And install the dependencies:

```bash
$ composer install
```

Write your code and add tests. Then run the static check and the tests:

```bash
$ composer run static-check
$ composer run test
```

Commit your changes and push them to GitHub:

```bash
$ git commit -m "Fix nasty bug"
$ git push -u origin bug-or-feature-description
```

Then [create a pull request](https://help.github.com/articles/creating-a-pull-request/)
on GitHub.

If you need to make some changes, commit and push them as you like. When asked
to squash your commits, do so as follows:

```bash
git rebase -i
git push origin bug-or-feature-description -f
```

Coding standard
---------------

This project follows the [PSR-2](http://www.php-fig.org/psr/psr-2/) coding style.
Please make sure your pull requests adhere to this standard.
